<?php

declare(strict_types=1);

namespace Http\Exception;

final class UnauthorizedException extends \RuntimeException
{
    public static function new(string $message = ""): self
    {
        return new self($message);
    }
}
