<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DateTimeImmutable;

final class ExpirableToken implements Token
{
    public function __construct(
        private readonly string $id,
        private readonly string $value,
        private readonly DateTimeImmutable|null $expiresAt = null,
    ) {
    }

    public function expiresAt(): DateTimeImmutable|null
    {
        return $this->expiresAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isExpired(DateTimeImmutable $when): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        $when ??= new DateTimeImmutable();

        return $when > $this->expiresAt;
    }
}
