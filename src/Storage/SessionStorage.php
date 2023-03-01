<?php

declare(strict_types=1);

namespace DelOlmo\Token\Storage;

use DelOlmo\Token\Serializer\Serializer;
use DelOlmo\Token\Token;

use function session_start;
use function session_status;

use const PHP_SESSION_NONE;

class SessionStorage implements Storage
{
    private bool $sessionHasStarted = false;

    public function __construct(
        private readonly string $namespace,
        private readonly Serializer $serializer,
    ) {
    }

    public function find(string $id): Token|null
    {
        if ($this->sessionHasStarted === false) {
            $this->startSession();
        }

        if (! $this->has($id)) {
            return null;
        }

        $serialized = $_SESSION[$this->namespace][$id];

        return $this->getSerializer()->unserialize($serialized);
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    public function persist(Token $token): void
    {
        if ($this->sessionHasStarted === false) {
            $this->startSession();
        }

        $serialized = $this->getSerializer()->serialize($token);

        $_SESSION[$this->namespace][$token->getId()] = $serialized;
    }

    public function has(string $id): bool
    {
        if ($this->sessionHasStarted === false) {
            $this->startSession();
        }

        return isset($_SESSION[$this->namespace][$id]);
    }

    public function remove(string $id): void
    {
        if ($this->sessionHasStarted === false) {
            $this->startSession();
        }

        unset($_SESSION[$this->namespace][$id]);
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->sessionHasStarted = true;
    }
}
