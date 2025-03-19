<?php

declare(strict_types=1);

namespace Domain;

final readonly class Email
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
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \DomainException(sprintf("%s is an invalid email", $value));
        }

        return new self($value);
    }
}
