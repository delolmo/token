<?php

declare(strict_types=1);

namespace DelOlmo\Token\Storage;

use DelOlmo\Token\Token;

interface Storage
{
    public function find(string $id): Token|null;

    public function has(string $id): bool;

    public function persist(Token $token): void;

    public function remove(string $id): void;
}
