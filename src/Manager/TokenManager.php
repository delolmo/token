<?php

declare(strict_types=1);

namespace DelOlmo\Token\Manager;

use DelOlmo\Token\Encoder\DummyEncoder;
use DelOlmo\Token\Encoder\Encoder;
use DelOlmo\Token\Exception\TokenAlreadyExists;
use DelOlmo\Token\Generator\Generator;
use DelOlmo\Token\Generator\UriSafeGenerator;
use DelOlmo\Token\Storage\Session\SessionStorage;
use DelOlmo\Token\Storage\Storage;

use function sprintf;

class TokenManager extends AbstractTokenManager implements
    ManagerInterface
{
    /** @param Storage $storage */
    public function __construct(
        Storage|null $storage = null,
        Encoder|null $encoder = null,
        Generator|null $generator = null,
    ) {
        $this->encoder   = $encoder ?? new DummyEncoder();
        $this->generator = $generator ?? new UriSafeGenerator();
        $this->storage   = $storage ?? new SessionStorage();
    }

    public function generateToken(string $tokenId): string
    {
        // Prevent overwriting an already existing valid token
        if ($this->hasToken($tokenId)) {
            $str     = 'A valid token already exists for the given id \'%s\'.';
            $message = sprintf($str, $tokenId);

            throw new TokenAlreadyExists($message);
        }

        // Return value, before hashing
        return $this->refreshToken($tokenId);
    }

    public function refreshToken(string $tokenId): string
    {
        // Value, before hashing
        $value = $this->generator->generateToken();

        // Hash the value using the provided encoder
        $encoded = $this->encoder->encode($value);

        // Store the hashed value
        $this->storage->setToken($tokenId, $encoded);

        // Return the value before hashing
        return $value;
    }
}
