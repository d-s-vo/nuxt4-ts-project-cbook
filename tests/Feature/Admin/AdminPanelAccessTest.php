<?php

declare(strict_types=1);

use App\Models\User;

test('a verified administrator can open the admin panel', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertOk();
});

test('a verified non-admin is forbidden from the admin panel', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get('/admin')->assertForbidden();
});

test('a guest is redirected to the panel login', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('an unverified administrator is forbidden until the email is confirmed', function () {
    $admin = User::factory()->admin()->unverified()->create();

    $this->actingAs($admin)->get('/admin')->assertForbidden();
});
