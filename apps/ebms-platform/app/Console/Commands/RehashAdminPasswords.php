<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RehashAdminPasswords extends Command
{
    protected $signature   = 'admin:rehash-passwords {--dry-run : Show what would be changed without writing}';
    protected $description = 'Rehash plaintext admin passwords to bcrypt (one-time legacy migration)';

    public function handle(): int
    {
        $users = AdminUser::all(['id', 'login_id', 'password']);

        $toRehash = $users->filter(fn ($u) => ! str_starts_with($u->getRawOriginal('password'), '$2y$'));

        if ($toRehash->isEmpty()) {
            $this->info('All admin passwords are already bcrypt. Nothing to do.');
            return self::SUCCESS;
        }

        $this->table(['id', 'login_id', 'action'], $toRehash->map(fn ($u) => [
            $u->id, $u->login_id, 'rehash plaintext → bcrypt',
        ]));

        if ($this->option('dry-run')) {
            $this->warn('Dry run — no changes written.');
            return self::SUCCESS;
        }

        if (! $this->confirm("{$toRehash->count()} password(s) will be rehashed. Continue?", true)) {
            $this->line('Aborted.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($toRehash->count());
        $bar->start();

        foreach ($toRehash as $user) {
            $plain = $user->getRawOriginal('password');
            $user->forceFill(['password' => Hash::make($plain)])->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Done. {$toRehash->count()} password(s) rehashed.");

        return self::SUCCESS;
    }
}
