<?php

declare(strict_types=1);

namespace DelOlmo\Token\Generator;

use function base64_encode;
use function random_bytes;
use function rtrim;
use function strtr;

class UriSafeGenerator implements Generator
{
    /** @param int<1, max> $entropy */
    public function __construct(
        private readonly int $entropy = 64,
    ) {
    }

    public function generate(): string
    {
        $bytes = random_bytes($this->entropy);

        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}
