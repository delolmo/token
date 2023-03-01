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

    public function getEncoder(): Encoder
    {
        return $this->encoder;
    }

    public function getGenerator(): Generator
    {
        return $this->generator;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function isValid(string $id, string $input): bool
    {
        if (! $this->getStorage()->has($id)) {
            return false;
        }

        $token = $this->getStorage()->find($id);

        if (! $token instanceof ExpirableToken) {
            return false;
        }

        return $this->getEncoder()->verify($input, $token->getValue()) &&
            $token->isExpired() === false;
    }
}
