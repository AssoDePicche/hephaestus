<?php

declare(strict_types=1);

namespace Domain;

final readonly class Password implements \Stringable
{
    private function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function new(string $value = ""): self
    {
        $pattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";

        $value = trim($value);

        if (empty($value)) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

            $digits = "0123456789";

            $symbols = "!@#$%&*()-+.;";

            for ($index = 0; $index < 8; ++$index) {
                $value .= $chars[random_int(0, strlen($chars)) - 1];

                $value .= $digits[random_int(0, strlen($digits)) - 1];

                $value .= $symbols[random_int(0, strlen($symbols)) - 1];
            }

            str_shuffle($value);
        }

        if (!preg_match($pattern, $value)) {
            throw new \DomainException(sprintf("%s is an invalid password", $value));
        }

        $hash = password_hash($value, PASSWORD_BCRYPT);


        return new self($hash);
    }

    public static function from(string $hash): self
    {
        if (60 !== strlen($hash)) {
            throw new \DomainException("Password hash must have 60 characters");
        }

        return new self($hash);
    }

    public function compare(string $password): bool
    {
        return password_verify($password, $this->value);
    }
}
