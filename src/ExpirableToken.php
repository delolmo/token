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

    public function isExpired(DateTimeImmutable|null $when = null): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }

        $when ??= new DateTimeImmutable();

        return $when > $this->expiresAt;
    }

    public function withExpiresAt(DateTimeImmutable|null $expiresAt): static
    {
        return new self($this->id, $this->value, $expiresAt);
    }

    public function withId(string $id): static
    {
        return new self($id, $this->value, $this->expiresAt);
    }

    public function withValue(string $value): static
    {
        return new self($this->id, $value, $this->expiresAt);
    }
}
