<?php

declare(strict_types=1);

namespace DelOlmo\Token\Encoder;

use function password_hash;
use function password_verify;

use const PASSWORD_DEFAULT;

class NativePasswordEncoder implements Encoder
{
    public function decode(string $value): string
    {
        return $value;
    }

    public function encode(string $value): string
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    public function verify(string $input, string $value): bool
    {
        return password_verify($input, $value);
    }
}
