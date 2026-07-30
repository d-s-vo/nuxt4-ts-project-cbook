<?php

declare(strict_types=1);

namespace App\Tasks;

use App\Data\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;

final class RegisterUserTask extends BaseTask
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function run(string $name, string $email, string $plainPassword): Authenticatable
    {
        return $this->users->create($name, $email, $plainPassword);
    }
}
