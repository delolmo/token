<?php

declare(strict_types=1);

namespace DelOlmo\Token\Encoder;

use ErrorException;

use function is_string;
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
        $hash = password_hash($value, PASSWORD_DEFAULT);

        if (! is_string($hash)) {
            throw new ErrorException();
        }

        return $hash;
    }

    public function verify(string $input, string $value): bool
    {
        return password_verify($input, $value);
    }
}
