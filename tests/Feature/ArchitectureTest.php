<?php

declare(strict_types=1);

// 1. Модели Eloquent разрешено вызывать только в Репозиториях, Сидерах и Фабриках.
//    Ссылки между самими моделями (связи hasMany/belongsTo) — часть Eloquent и допустимы.
arch('Eloquent-модели — только в репозиториях (и слое данных Laravel)')
    ->expect('App\Models')
    ->toOnlyBeUsedIn([
        'App\Models',
        'App\Data\Repositories',
        'Database\Factories',
        'Database\Seeders',
    ]);

// 2. Фасад DB разрешено вызывать исключительно в Репозиториях
arch('Фасад DB — только в репозиториях')
    ->expect('Illuminate\Support\Facades\DB')
    ->toOnlyBeUsedIn('App\Data\Repositories');
