<?php

declare(strict_types=1);

// 1. Модели Eloquent разрешено вызывать только в Репозиториях, Сидерах и Фабриках.
//    Ссылки между самими моделями (связи hasMany/belongsTo) — часть Eloquent и допустимы.
//    Политики авторизации получают уже загруженную модель для проверки прав и сами
//    запросов не выполняют (запросы к БД — по-прежнему только в репозиториях).
arch('Eloquent-модели — только в репозиториях (и слое данных Laravel)')
    ->expect('App\Models')
    ->toOnlyBeUsedIn([
        'App\Models',
        'App\Data\Repositories',
        'App\Policies',
        'Database\Factories',
        'Database\Seeders',
    ]);

// 2. Фасад DB разрешено вызывать исключительно в Репозиториях
arch('Фасад DB — только в репозиториях')
    ->expect('Illuminate\Support\Facades\DB')
    ->toOnlyBeUsedIn('App\Data\Repositories');
