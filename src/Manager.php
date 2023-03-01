<?php

declare(strict_types=1);

namespace DelOlmo\Token;

interface Manager
{
    public function isValid(string $id, string $input): bool;
}
