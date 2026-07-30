<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Data\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();
        assert($user !== null);

        $this->users->setPassword($user, (string) $request->string('password'), false);

        return back();
    }
}
