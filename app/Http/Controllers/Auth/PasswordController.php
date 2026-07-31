<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Data\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;

class PasswordController extends Controller
{
    public function __construct(private UserRepository $users)
    {
    }

    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();
        assert($user !== null);

        $this->users->setPassword($user, (string) $request->string('password'), false);

        return back();
    }
}
