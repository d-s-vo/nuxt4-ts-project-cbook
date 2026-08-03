<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Data\Repositories\UserRepository;
use Illuminate\Console\Command;

final class GrantAdminCommand extends Command
{
    protected $signature = 'app:grant-admin {email : Почта пользователя, которому выдаём доступ к админ-панели}';

    protected $description = 'Выдать существующему пользователю права администратора';

    public function handle(UserRepository $users): int
    {
        $email = (string) $this->argument('email');

        if (! $users->grantAdminByEmail($email)) {
            $this->error("Пользователь с почтой {$email} не найден.");

            return self::FAILURE;
        }

        $this->info("Пользователю {$email} выданы права администратора.");

        return self::SUCCESS;
    }
}
