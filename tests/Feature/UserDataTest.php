<?php

declare(strict_types=1);

use App\Data\UserData;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('builds from a user model with only the whitelisted fields', function () {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $data = UserData::from($user);

    expect($data->id)->toBe($user->id)
        ->and($data->name)->toBe('Ada Lovelace')
        ->and($data->email)->toBe('ada@example.com')
        ->and($data->email_verified_at?->toIso8601String())
        ->toBe($user->email_verified_at?->toIso8601String());
});

it('keeps an unverified email as null', function () {
    $user = User::factory()->unverified()->create();

    expect(UserData::from($user)->email_verified_at)->toBeNull();
});

it('never leaks password or remember token', function () {
    $user = User::factory()->create();

    $array = UserData::from($user)->toArray();

    expect($array)->toHaveKeys(['id', 'name', 'email', 'email_verified_at'])
        ->and($array)->not->toHaveKey('password')
        ->and($array)->not->toHaveKey('remember_token');
});

it('shares a null user for guests on the welcome page', function () {
    $this->get('/')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->where('auth.user', null));
});

it('shares the user as a dto without sensitive fields when authenticated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('auth.user.id', $user->id)
            ->where('auth.user.name', $user->name)
            ->where('auth.user.email', $user->email)
            ->missing('auth.user.password')
            ->missing('auth.user.remember_token'));
});
