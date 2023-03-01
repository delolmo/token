<?php

declare(strict_types=1);

namespace DelOlmo\Token\Storage;

use DelOlmo\Token\Token;

/** @template T of Token */
interface Storage
{
    /** @return T|null */
    public function find(string $id): Token|null;

    public function has(string $id): bool;

    /** @param T $token */
    public function persist(Token $token): void;

    public function remove(string $id): void;
}
