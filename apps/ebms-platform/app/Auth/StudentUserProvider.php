<?php

namespace App\Auth;

use App\Models\Student;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class StudentUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Authenticatable
    {
        return Student::find($identifier);
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return null; // Remember-me tokens not supported for students
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        // Not implemented
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials['hall_ticket'])) {
            return null;
        }

        return Student::active()
            ->byHallTicket($credentials['hall_ticket'])
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        /** @var Student $user */
        if (empty($credentials['dob'])) {
            return false;
        }

        $dob = $credentials['dob'];

        // Primary: match by DOB
        if ($user->dob && $user->dob->format('Y-m-d') === $dob) {
            return true;
        }

        // Fallback: match by DOST ID (2023 batch compatibility)
        if (! empty($credentials['dost_id']) && $user->dost_id === $credentials['dost_id']) {
            return true;
        }

        return false;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // Not applicable — students auth by DOB, not password
    }
}
