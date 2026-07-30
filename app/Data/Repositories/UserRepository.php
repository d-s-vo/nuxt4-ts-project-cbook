<?php

declare(strict_types=1);

namespace App\Data\Repositories;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserRepository extends BaseRepository
{
    public function create(string $name, string $email, string $plainPassword): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($plainPassword),
        ]);
    }

    public function setPassword(Authenticatable $user, string $plainPassword, bool $rotateRememberToken): void
    {
        assert($user instanceof User);

        $user->forceFill([
            'password' => Hash::make($plainPassword),
        ]);

        if ($rotateRememberToken) {
            $user->setRememberToken(Str::random(60));
        }

        $user->save();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateProfile(Authenticatable $user, array $attributes): void
    {
        assert($user instanceof User);

        $user->fill($attributes);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function delete(Authenticatable $user): void
    {
        assert($user instanceof User);

        $user->delete();
    }
}
