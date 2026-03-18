<?php

namespace App\Auth;

use App\Models\AdminUser;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;

/**
 * Custom provider for admin users.
 *
 * Legacy admin passwords were stored as plaintext in the old `admin` table.
 * When production data was migrated without running the bcrypt-rehash command,
 * those plaintext values landed in admin_users.password.
 *
 * On login, if the stored value is not a bcrypt hash, we fall back to a
 * constant-time plaintext comparison. On success we immediately rehash and
 * save the bcrypt value so the account is upgraded transparently.
 */
class AdminUserProvider extends EloquentUserProvider
{
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain  = $credentials['password'] ?? null;
        $stored = $user->getAuthPassword();

        if ($plain === null) {
            return false;
        }

        try {
            return $this->hasher->check($plain, $stored);
        } catch (RuntimeException $e) {
            // Stored value is not a bcrypt hash (legacy plaintext import).
            // Use hash_equals for constant-time comparison to prevent timing attacks.
            return hash_equals($stored, $plain);
        }
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        $plain  = $credentials['password'] ?? null;
        $stored = $user->getAuthPassword();

        if ($plain === null) {
            return;
        }

        $needsRehash = false;

        try {
            $needsRehash = $this->hasher->needsRehash($stored) || $force;
        } catch (RuntimeException) {
            // Stored value is not bcrypt — definitely needs rehash.
            $needsRehash = true;
        }

        if ($needsRehash) {
            /** @var AdminUser $user */
            $user->forceFill(['password' => $this->hasher->make($plain)])->save();
        }
    }
}
