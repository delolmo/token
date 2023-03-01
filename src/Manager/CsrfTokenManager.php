<?php

declare(strict_types=1);

namespace DelOlmo\Token\Manager;

use DelOlmo\Token\Encoder\DummyEncoder;
use DelOlmo\Token\Generator\UriSafeGenerator;
use DelOlmo\Token\Storage\Session\SessionExpirableTokenStorage;

class CsrfTokenManager extends ExpirableTokenManager
{
    final public const TOKEN_TIMEOUT = '+24 hour';

    public function __construct()
    {
        $generator = new UriSafeGenerator(64);
        $encoder   = new DummyEncoder();
        $storage   = new SessionExpirableTokenStorage('_csrf');

        parent::__construct($storage, $encoder, $generator);
    }
}
