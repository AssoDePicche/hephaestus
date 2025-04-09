<?php

declare(strict_types=1);

namespace Http\Exception;

final class InternalServerErrorException extends \RuntimeException
{
    public static function new(string $message = ""): self
    {
        return new self($message);
    }
}
