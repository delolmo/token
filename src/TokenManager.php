<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DateTimeImmutable;
use DelOlmo\Token\Storage\Storage;

interface TokenManager extends Storage
{
    public function create(string $id, DateTimeImmutable $expiresAt): Token;

    public function isValid(string $id, string $input): bool;
}
