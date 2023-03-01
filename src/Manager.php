<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DelOlmo\Token\Encoder\Encoder;
use DelOlmo\Token\Generator\Generator;
use DelOlmo\Token\Storage\Storage;

interface Manager extends Encoder, Generator, Storage
{
    public function isValid(string $id, string $input): bool;
}
