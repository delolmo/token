<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DateTimeImmutable;

interface Token
{
    public function expiresAt(): DateTimeImmutable|null;

    public function getId(): string;

    public function getValue(): string;

    public function isExpired(): bool;
}
