# Admin Authentication

**Controller:** `app/Http/Controllers/Admin/AuthController.php`

---

## Routes

```
GET  /admin/login        → showLogin
POST /admin/login        → login  (throttle: 5/min per IP:username)
POST /admin/logout       → logout
```

---

## Guard

```php
Auth::guard('admin')
```

Provider: `App\Auth\AdminUserProvider` (registered as `ebms-admin`)

Session cookie: `ADMIN_SESS`

---

## Roles

| Role | Access |
|------|--------|
| `admin` | Standard admin access |
| `superadmin` | Full access including grade sheet generation and student edits |

The `role` middleware (alias for `RequireRole`) is used on sensitive routes:

```php
Route::put('/students/{id}', ...)->middleware('role:admin,superadmin');
Route::post('/gradesheets/{student}/generate', ...)->middleware('role:superadmin');
```
