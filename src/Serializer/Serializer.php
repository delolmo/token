<?php

declare(strict_types=1);

namespace DelOlmo\Token\Serializer;

use DelOlmo\Token\Token;

/** @template T of Token */
interface Serializer
{
    /** @param T $token */
    public function serialize(Token $token): string;

    /** @return T */
    public function unserialize(string $serialized): Token;
}
