---
name: ebms-deploy
description: Deploy the EBMS platform (apps/ebms-platform/) to the production cPanel server over SSH. Covers the passphrase-protected deploy key, asset rebuilds, upload ordering, cache clearing, and post-deploy verification against live data.
---

# EBMS Deploy

Deploy `apps/ebms-platform/` to the production cPanel server.

## When to use

When the user says "deploy", "push to production", "deploy to server", or similar for the EBMS
platform app. The legacy PHP portals are **not** deployed this way — they are separate hosts.

## Server details

- **SSH:** `ssh -i ~/.ssh/ebmsnova -p 21098 uascexams@198.54.114.171`
- **App path:** `/home/uascexams/ebmsnova.uasckuexams.in`
- **Public URL:** `https://ebmsnova.uasckuexams.in`
- **PHP on server:** 8.2 (local dev may be 8.3 — do not ship 8.3-only syntax)
- **Node.js is NOT available on the server** — build assets locally and upload `public/build/`
- Credentials live in `apps/ebms-platform/deploy.env`, which is untracked and may be absent
  from a fresh checkout.

## Step 0 — the deploy key is passphrase-protected

This blocks every non-interactive deploy, and it is the first thing to resolve. A plain
`ssh`/`scp` fails with `Permission denied (publickey)`, and `ssh-add` is no help because it
prompts on a terminal an agent shell does not have — it reads EOF and loads nothing:

```
Enter passphrase for /c/Users/girid/.ssh/ebmsnova:
The agent has no identities.
```

Two ways forward:

1. **Hand the commands to the user.** Have them run the whole deploy in their own session with
   the `!` prefix, as a single chained block — shell state does not persist between agent tool
   calls, so an agent loaded in one call is gone by the next.
2. **Strip the passphrase from a throwaway copy**, only when the user supplies the passphrase:

```bash
TMPK=<scratchpad>/dk
cp ~/.ssh/ebmsnova "$TMPK" && chmod 600 "$TMPK"
ssh-keygen -p -f "$TMPK" -P "<passphrase>" -N "" >/dev/null
# ... use -i "$TMPK" for every ssh/scp ...
shred -u "$TMPK" 2>/dev/null || rm -f "$TMPK"    # always, before finishing
```

**Never write the passphrase into a repo file, a commit, or this skill.** Get it from the repo
owner each time. Delete the stripped key when the deploy ends, including on failure.

Add `-o BatchMode=yes` to every `ssh`/`scp` so a missing key fails fast instead of hanging on a
prompt the agent cannot answer.

## Step 1 — find out what is actually already deployed

Do not assume the server matches your tree; earlier work may or may not have shipped. Check
before uploading, because a partial deploy is the dangerous state:

```bash
ssh -o BatchMode=yes -i "$TMPK" -p 21098 uascexams@198.54.114.171 \
  "cd /home/uascexams/ebmsnova.uasckuexams.in && ls app/Domain/Results 2>&1 | head && grep -c <new-route> routes/admin.php"
```

If `routes/admin.php` would register routes whose controller methods or namespaces are not on
the server, the whole admin portal fatals. Ship the dependencies together or not at all.

## Step 2 — rebuild assets when Blade changes introduce new utility classes

**Tailwind 4 scans Blade files at build time.** A Blade-only change is *not* automatically a
"just scp the view" deploy: any Tailwind utility that appears nowhere else in the codebase is
absent from the compiled CSS, and the element renders unstyled in production.

Confirm against the deployed CSS before deciding to skip the build:

```bash
ssh ... "cd $APP && CSS=\$(ls -t public/build/assets/app-*.css | head -1) && grep -c 'bg-red-700' \$CSS"
```

`0` means the class is missing and you must rebuild. Note that variant classes are escaped in
the output (`.hover\:bg-red-600:hover`), so grep for the escaped form or the bare colour, or
you will get a false negative from your own shell escaping.

```bash
cd apps/ebms-platform && npm run build
```

Rebuild is also required for any change under `resources/css/` or `resources/js/`.

## Step 3 — upload, in dependency order

Ordering is what makes a deploy safe. Two independent rules:

1. **Routes and PHP before views.** A layout referencing an unregistered route crashes the
   entire admin portal with `ERR_TOO_MANY_REDIRECTS`.
2. **Assets before `manifest.json`.** The manifest names hashed files; upload it first and every
   page 404s on assets until the rest lands.

Full order:

1. `routes/*.php`
2. PHP classes — controllers, models, services, new namespaces under `app/`
3. Blade views
4. `public/build/assets/*`, then `public/build/manifest.json` last

Use `scp -o BatchMode=yes -i "$TMPK" -P 21098` (capital `-P` for scp, lowercase `-p` for ssh).
Remote base: `uascexams@198.54.114.171:/home/uascexams/ebmsnova.uasckuexams.in/`

Create new directories first — `scp` will not create them:

```bash
ssh ... "mkdir -p $APP/resources/views/admin/<newdir>"
```

Back up anything you overwrite, so a rollback is one `cp` away:

```bash
ssh ... "cd $APP && cp routes/admin.php routes/admin.php.bak-<feature>"
```

`public/build/` is gitignored, so built assets exist only on the machine that built them —
they are never part of a commit.

## Step 4 — migrations

Only when there are new migration files:

```bash
ssh ... "cd $APP && php artisan migrate --force"
```

`--force` is for non-interactive SSH only. Prefer confirming interactively when you can.

## Step 5 — clear caches

After **every** deploy, without exception:

```bash
ssh ... "cd $APP && php artisan optimize:clear"
```

## Step 6 — verify, and mean it

`route:list` proves registration, not that the page works. Layer the checks:

```bash
# route registered
ssh ... "cd $APP && php artisan route:list | grep -i <feature>"

# page responds — 302 to /admin/login is healthy for an admin page; 500 is not.
# Compare against a known-good admin page as a control.
curl -s -o /dev/null -w "%{http_code} %{redirect_url}\n" https://ebmsnova.uasckuexams.in/admin/<page>
curl -s -o /dev/null -w "%{http_code}\n" https://ebmsnova.uasckuexams.in/admin/revaluations

# a class you just introduced is really in the live CSS
curl -s https://ebmsnova.uasckuexams.in/build/assets/app-<hash>.css | grep -c '<class>'

# fresh errors
ssh ... "tail -c 3000 $APP/storage/logs/laravel.log | grep -iE 'ERROR|exception' | tail -5"
```

Note that errors from your own failed probe attempts land in that log too — read the timestamps
before blaming the deploy.

### Exercising a query against live data

For a report or any non-trivial query, the strongest check is running it against production
data. **Do not** try `php artisan tinker --execute="..."` over SSH — the nested quoting mangles
namespaces into `Syntax error, unexpected T_NS_SEPARATOR`. Upload a throwaway script instead,
run it, and delete it in the same command:

```php
<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
// ... exercise the code, print counts and a sample row ...
```

```bash
scp ... probe.php $SRV:$APP/_probe.php
ssh ... "cd $APP && php _probe.php; rm -f _probe.php"
```

Keep it outside `public/` and always delete it, even if the run fails.

## Common pitfalls

- **`ERR_TOO_MANY_REDIRECTS`** — a Blade layout referencing an unregistered route. Upload routes
  first, then views, then clear caches.
- **Unstyled elements after a Blade-only deploy** — new Tailwind classes missing from the built
  CSS. Rebuild and upload assets (see step 2).
- **DataTables search/export missing** — the `js-datatable` class alone does nothing. The view
  must also `@push('scripts') @vite(['resources/js/admin-datatable.js']) @endpush`. This is an
  app convention, but it surfaces as "the feature didn't deploy".
- **`Route [login] not defined`** — pre-existing: the `Authenticate` middleware redirects to
  `route('login')` but under the admin prefix the name is `admin.login`. Not caused by deploys.
- **Missing columns** — add an additive migration rather than editing a create migration that
  has already run on the server.
- **`release` branch conflicts** — several people push to it. `git pull --rebase origin release`
  before pushing.

## Finish

- Delete the stripped key copy.
- Tell the user exactly what shipped, what you verified, and what you did **not** verify —
  unrun tests and unrendered pages are worth naming, not glossing.
- If the work is not committed, say so: production running code that exists nowhere in git is a
  hazard, since the next person to deploy `release` will silently revert it.
