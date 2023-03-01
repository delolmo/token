<?php

declare(strict_types=1);

namespace DelOlmo\Token\Storage;

use DelOlmo\Token\Serializer\Serializer;
use DelOlmo\Token\Token;

use function assert;
use function is_array;
use function is_string;
use function session_start;
use function session_status;

use const PHP_SESSION_NONE;

/**
 * @template T of Token
 * @implements Storage<T>
 */
class SessionStorage implements Storage
{
    /**
     * @param non-empty-string $namespace
     * @param Serializer<T>    $serializer
     */
    public function __construct(
        private readonly string $namespace,
        private readonly Serializer $serializer,
    ) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            isset($_SESSION[$this->namespace]) &&
            is_array($_SESSION[$this->namespace])
        ) {
            return;
        }

        $_SESSION[$this->namespace] = [];
    }

    public function find(string $id): Token|null
    {
        if (! $this->has($id)) {
            return null;
        }

        $serialized = $_SESSION[$this->namespace][$id] ?? null;

        if (! is_string($serialized)) {
            unset($_SESSION[$this->namespace]);

            return null;
        }

        return $this->serializer->unserialize($serialized);
    }

    public function persist(Token $token): void
    {
        $serialized = $this->serializer->serialize($token);

        $id = $token->getId();

        assert(is_array($_SESSION[$this->namespace]));

        $_SESSION[$this->namespace][$id] = $serialized;
    }

    public function has(string $id): bool
    {
        assert(is_array($_SESSION[$this->namespace]));

        return isset($_SESSION[$this->namespace][$id]);
    }

    public function remove(string $id): void
    {
        assert(is_array($_SESSION[$this->namespace]));

        unset($_SESSION[$this->namespace][$id]);
    }
}
