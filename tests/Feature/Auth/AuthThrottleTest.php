<?php

declare(strict_types=1);

use App\Models\User;

test('registration requests are throttled after too many attempts', function () {
    foreach (range(1, 6) as $attempt) {
        $this->post('/register', [])->assertStatus(302);
    }

    $this->post('/register', [])->assertStatus(429);
});

test('password reset link requests are throttled after too many attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 6) as $attempt) {
        $this->post('/forgot-password', ['email' => $user->email])->assertStatus(302);
    }

    $this->post('/forgot-password', ['email' => $user->email])->assertStatus(429);
});

test('password reset requests are throttled after too many attempts', function () {
    foreach (range(1, 6) as $attempt) {
        $this->post('/reset-password', [])->assertStatus(302);
    }

    $this->post('/reset-password', [])->assertStatus(429);
});
