<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->admin()->create());
});

test('an administrator can list and view users', function () {
    $user = User::factory()->create();

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$user]);

    Livewire::test(ViewUser::class, ['record' => $user->getKey()])
        ->assertOk();
});

test('the user resource never exposes password or remember token', function () {
    $user = User::factory()->create();

    Livewire::test(ListUsers::class)
        ->assertSuccessful()
        ->assertDontSee($user->password)
        ->assertDontSee($user->getRememberToken());

    Livewire::test(ViewUser::class, ['record' => $user->getKey()])
        ->assertSuccessful()
        ->assertDontSee($user->password)
        ->assertDontSee($user->getRememberToken());
});

test('the user resource is read-only', function () {
    $user = User::factory()->create();

    expect(UserResource::canCreate())->toBeFalse();
    expect(UserResource::canEdit($user))->toBeFalse();
    expect(UserResource::canDelete($user))->toBeFalse();

    // Панель отдаёт только список и просмотр — маршрутов create/edit нет.
    expect(array_keys(UserResource::getPages()))->toBe(['index', 'view']);
});
