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

    public function withExpiresAt(DateTimeImmutable|null $expiresAt): static;

    public function withId(string $id): static;

    public function withValue(string $value): static;
}
