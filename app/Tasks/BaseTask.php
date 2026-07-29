<?php

declare(strict_types=1);

namespace App\Tasks;

abstract class BaseTask
{
    abstract public function handle(): mixed;
}
