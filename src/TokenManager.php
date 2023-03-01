<?php

declare(strict_types=1);

namespace DelOlmo\Token;

use DelOlmo\Token\Storage\Storage;

/**
 * @template T of Token
 * @extends Storage<T>
 */
interface TokenManager extends Storage
{
    public function isValid(string $id, string $input): bool;
}
