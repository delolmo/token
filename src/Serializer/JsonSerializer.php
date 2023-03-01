<?php

declare(strict_types=1);

namespace DelOlmo\Token\Serializer;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use DateTimeImmutable;
use DelOlmo\Token\ExpirableToken;
use DelOlmo\Token\Token;
use Exception;

use function json_encode;
use function sprintf;

use const DATE_ATOM;
use const JSON_THROW_ON_ERROR;

class JsonSerializer implements Serializer
{
    public function serialize(Token $token): string
    {
        $data = [
            'id' => $token->getId(),
            'value' => $token->getValue(),
            'expiresAt' => $token->expiresAt()?->format(DateTimeImmutable::ATOM),
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public function unserialize(string $serialized): Token
    {
        try {
            $token = (new MapperBuilder())
                ->supportDateFormats(DATE_ATOM)
                ->mapper()
                ->map(ExpirableToken::class, Source::json($serialized));
        } catch (MappingError $error) {
            throw new Exception(
                sprintf('Unable to unserialize string \'%s\'.', $serialized),
                $error->getCode(),
                $error,
            );
        }

        return $token;
    }
}
