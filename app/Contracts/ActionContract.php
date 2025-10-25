<?php

namespace App\Contracts;

interface ActionContract
{
    public function handle(array $data): mixed;
}
