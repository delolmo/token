<?php

declare(strict_types=1);

namespace DelOlmo\Token\Manager;

use DelOlmo\Token\Encoder\Encoder;
use DelOlmo\Token\Exception\TokenNotFound;
use DelOlmo\Token\Generator\Generator;
use DelOlmo\Token\Storage\Storage;

use function sprintf;

abstract class AbstractTokenManager
{
    protected Encoder $encoder;

    protected Generator $generator;

    protected Storage $storage;

    public function getToken(string $tokenId): string
    {
        // If the given $tokenId does not exist
        if (! $this->hasToken($tokenId)) {
            $str     = 'No valid token exists for the given id \'%s\'.';
            $message = sprintf($str, $tokenId);

            throw new TokenNotFound($message);
        }

        $encoded = $this->storage->getToken($tokenId);

        return $this->encoder->decode($encoded);
    }

    public function hasToken(string $tokenId): bool
    {
        return $this->storage->hasToken($tokenId);
    }

    public function isTokenValid(string $tokenId, string $value): bool
    {
        // Return false if no token exists with the given token id
        if (! $this->hasToken($tokenId)) {
            return false;
        }

        // Get the decoded value of the stored token
        $decoded = $this->storage->getToken($tokenId);

        // Whether or not the hashed/encoded value and the given value match
        return $this->encoder->verify($value, $decoded);
    }

    public function removeToken(string $tokenId): string|null
    {
        return $this->storage->removeToken($tokenId);
    }
}
