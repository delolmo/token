<?php

declare(strict_types=1);

namespace DelOlmo\Token\Manager;

use DateTime;
use DelOlmo\Token\Encoder\DummyEncoder;
use DelOlmo\Token\Encoder\Encoder;
use DelOlmo\Token\Exception\TokenAlreadyExists;
use DelOlmo\Token\Generator\Generator;
use DelOlmo\Token\Generator\UriSafeGenerator;
use DelOlmo\Token\Storage\ExpirableTokenStorageInterface as Storage;
use DelOlmo\Token\Storage\Session\SessionExpirableTokenStorage;

use function sprintf;

class ExpirableTokenManager extends AbstractTokenManager implements
    ExpirableTokenManagerInterface
{
    public const TOKEN_TIMEOUT = '+1 day';

    protected Encoder $encoder;

    protected Generator $generator;

    protected Storage $storage;

    /** @var DateTime The date on which ExpirableToken objects expire by default */
    protected DateTime $timeout;

    /** @param Storage $storage */
    public function __construct(
        Storage|null $storage = null,
        Encoder|null $encoder = null,
        Generator|null $generator = null,
        DateTime|null $timeout = null,
    ) {
        $this->encoder   = $encoder ?? new DummyEncoder();
        $this->generator = $generator ?? new UriSafeGenerator();
        $this->storage   = $storage ?? new SessionExpirableTokenStorage();
        $this->timeout   = $timeout ?? new DateTime(self::TOKEN_TIMEOUT);
    }

    public function generateToken(
        string $tokenId,
        DateTime|null $expiresAt = null,
    ): string {
        // Prevent overwriting an already existing token
        if ($this->hasToken($tokenId)) {
            $str     = 'A valid token already exists for the given id \'%s\'.';
            $message = sprintf($str, $tokenId);

            throw new TokenAlreadyExists($message);
        }

        // Return the value before hashing
        return $this->refreshToken($tokenId, $expiresAt);
    }

    public function refreshToken(
        string $tokenId,
        DateTime|null $expiresAt = null,
    ): string {
        // Value, before hashing, and timeout
        $value   = $this->generator->generateToken();
        $timeout = $expiresAt ?? $this->timeout;

        // Encode the value using the provided encoder
        $encoded = $this->encoder->encode($value);

        // Store the hashed value
        $this->storage->setToken($tokenId, $encoded, $timeout);

        // Return the value before hashing
        return $value;
    }
}
