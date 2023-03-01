<?php

declare(strict_types=1);

namespace DelOlmo\Token\Serializer;

use DelOlmo\Token\Token;

interface Serializer
{
    public function serialize(Token $token): string;

    public function unserialize(string $serialize): Token;
}
