<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DelOlmo\Token\Encoder\Encoder;
use DelOlmo\Token\Generator\Generator;
use DelOlmo\Token\Storage\Storage;

final class ExpirableTokenManager implements Manager
{
    public function __construct(
        private readonly Encoder $encoder,
        private readonly Generator $generator,
        private readonly Storage $storage,
    ) {
    }

    public function decode(string $value): string
    {
        return $this->encoder->decode($value);
    }

    public function encode(string $value): string
    {
        return $this->encoder->encode($value);
    }

    public function verify(string $input, string $value): bool
    {
        return $this->encoder->verify($input, $value);
    }

    public function generate(): string
    {
        return $this->generator->generate();
    }

    public function isValid(string $id, string $input): bool
    {
        if (! $this->has($id)) {
            return false;
        }

        $token = $this->find($id);

        if (! $token instanceof ExpirableToken) {
            return false;
        }

        return $this->verify($input, $token->getValue()) &&
            $token->isExpired() === false;
    }

    public function find(string $id): Token|null
    {
        return $this->storage->find($id);
    }

    public function has(string $id): bool
    {
        return $this->storage->has($id);
    }

    public function persist(Token $token): void
    {
        $this->storage->persist($token);
    }

    public function remove(string $id): void
    {
        $this->storage->remove($id);
    }
}
