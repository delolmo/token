<?php

declare(strict_types=1);

namespace DelOlmo\Token\Serializer;

use DelOlmo\Token\Token;
use ErrorException;

use function is_a;
use function is_object;
use function serialize;
use function sprintf;
use function unserialize;

/**
 * @template T of Token
 * @implements Serializer<T>
 */
class NativeSerializer implements Serializer
{
    /** @param class-string<T> $className */
    public function __construct(
        private readonly string $className,
    ) {
    }

    public function serialize(Token $token): string
    {
        return serialize($token);
    }

    public function unserialize(string $serialized): Token
    {
        $unserialized = unserialize($serialized);

        if (! is_object($unserialized)) {
            throw new ErrorException(sprintf(
                'The passed string\'%s\' is not unserializeable.',
                $serialized,
            ));
        }

        if (! is_a($unserialized, $this->className)) {
            throw new ErrorException(sprintf(
                'The passed string\'%s\' is not unserializeable.',
                $serialized,
            ));
        }

        return $unserialized;
    }
}
