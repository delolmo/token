<?php

declare(strict_types=1);

namespace DelOlmo\Token\Generator;

interface Generator
{
    public function generate(): string;
}
