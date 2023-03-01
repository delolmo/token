<?php

declare(strict_types=1);

namespace DelOlmo\Token\Serializer;

use DateTime;
use DelOlmo\Token\Token;

use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

class JsonSerializer implements Serializer
{
    public function serialize(Token $token): string
    {
        $data = [
            'id' => $token->getId(),
            'value' => $token->getValue(),
            'expiresAt' => $token->expiresAt()->format('Y-m-d H:i:s'),
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function unserialize(string $json): Token
    {
        $decoded = json_decode($json, true, JSON_THROW_ON_ERROR);

        return new ExpirableToken(
            $decoded['id'],
            $decoded['value'],
            DateTime::createFromFormat('Y-m-d H:i:s', $decoded['expiresAt']),
        );
    }
}
