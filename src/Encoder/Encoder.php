<?php

declare(strict_types=1);

namespace DelOlmo\Token\Encoder;

interface Encoder
{
    public function decode(string $value): string;

    public function encode(string $value): string;

    public function verify(string $input, string $value): bool;
}
