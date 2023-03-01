<?php

declare(strict_types=1);

namespace DelOlmo\Token;

interface TokenManager
{
    public function isValid(string $id, string $input): bool;
}
