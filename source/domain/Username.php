<?php

declare(strict_types=1);

namespace Domain;

final readonly class Username implements \Stringable
{
    private function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        $value = trim($value);

        $length = strlen($value);

        if ($length < 3 || $length > 255) {
            throw new \DomainException("Username must have between 3 and 255 characters");
        }

        return new self($value);
    }
}
