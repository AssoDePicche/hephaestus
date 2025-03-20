<?php

declare(strict_types=1);

namespace Domain;

final readonly class UUID implements \Stringable
{
    private function __construct(
        private string $value
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function new(): self
    {
        $bytes = random_bytes(16);

        assert(strlen($bytes) === 16);

        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);

        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);

        $value = vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($bytes), 4));

        return new self($value);
    }

    public static function from(string $value): self
    {
        $pattern = "/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/";

        if (!preg_match($pattern, $value)) {
            throw new \DomainException(sprintf("%s is an invalid UUID", $value));
        }

        return new self($value);
    }
}
