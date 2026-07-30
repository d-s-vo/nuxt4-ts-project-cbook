<?php

declare(strict_types=1);

use App\Data\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->users = app(UserRepository::class);
});

it('creates a user with a hashed password', function () {
    $user = $this->users->create('Jane Doe', 'jane@example.com', 'secret-password');

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('Jane Doe')
        ->and($user->email)->toBe('jane@example.com');

    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'name' => 'Jane Doe',
    ]);

    $stored = User::query()->where('email', 'jane@example.com')->firstOrFail();

    expect($stored->password)->not->toBe('secret-password')
        ->and(Hash::check('secret-password', $stored->password))->toBeTrue();
});

it('sets a new password and rotates the remember token', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
        'remember_token' => 'original-token',
    ]);

    $this->users->setPassword($user, 'new-password', true);

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue()
        ->and(Hash::check('old-password', $user->password))->toBeFalse()
        ->and($user->remember_token)->not->toBe('original-token')
        ->and($user->remember_token)->not->toBeNull();
});

it('keeps the remember token when rotation is disabled', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
        'remember_token' => 'keep-me',
    ]);

    $this->users->setPassword($user, 'another-password', false);

    $user->refresh();

    expect(Hash::check('another-password', $user->password))->toBeTrue()
        ->and($user->remember_token)->toBe('keep-me');
});

it('resets email verification when the email changes', function () {
    $user = User::factory()->create([
        'email' => 'before@example.com',
        'email_verified_at' => now(),
    ]);

    $this->users->updateProfile($user, [
        'name' => $user->name,
        'email' => 'after@example.com',
    ]);

    $user->refresh();

    expect($user->email)->toBe('after@example.com')
        ->and($user->email_verified_at)->toBeNull();
});

it('keeps email verification when only the name changes', function () {
    $verifiedAt = now();

    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'stable@example.com',
        'email_verified_at' => $verifiedAt,
    ]);

    $this->users->updateProfile($user, [
        'name' => 'New Name',
        'email' => 'stable@example.com',
    ]);

    $user->refresh();

    expect($user->name)->toBe('New Name')
        ->and($user->email_verified_at)->not->toBeNull();
});

it('deletes a user', function () {
    $user = User::factory()->create();

    $this->users->delete($user);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
