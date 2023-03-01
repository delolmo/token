<?php

declare(strict_types=1);

namespace DelOlmo\Token\Encoder;

class DummyEncoder implements Encoder
{
    public function decode(string $value): string
    {
        return $value;
    }

    public function encode(string $value): string
    {
        return $value;
    }

    public function verify(string $input, string $value): bool
    {
        return $input === $value;
    }
}
