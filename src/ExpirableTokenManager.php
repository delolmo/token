<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DelOlmo\Token\Encoder\Encoder;
use DelOlmo\Token\Generator\Generator;
use DelOlmo\Token\Storage\Storage;

/**
 * @template T of ExpirableToken
 * @implements TokenManager<T>
 */
final class ExpirableTokenManager implements TokenManager
{
    /** @param Storage<T> $storage */
    public function __construct(
        private readonly Encoder $encoder,
        private readonly Generator $generator,
        private readonly Storage $storage,
    ) {
    }

    public function find(string $id): Token|null
    {
        return $this->storage->find($id);
    }

    public function getGenerator(): Generator
    {
        return $this->generator;
    }

    public function has(string $id): bool
    {
        return $this->storage->has($id);
    }

    public function persist(Token $token): void
    {
        $token = $token->withValue(
            $this->encoder->encode($token->getValue()),
        );

        $this->storage->persist($token);
    }

    public function remove(string $id): void
    {
        $this->storage->remove($id);
    }

    public function isValid(string $id, string $input): bool
    {
        if (! $this->storage->has($id)) {
            return false;
        }

        $token = $this->storage->find($id);

        if (! $token instanceof ExpirableToken) {
            return false;
        }

        return $this->encoder->verify($input, $token->getValue()) &&
            $token->isExpired() === false;
    }
}
