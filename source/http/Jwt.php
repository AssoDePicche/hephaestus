<?php

declare(strict_types=1);

namespace Http;

final readonly class Jwt
{
    private function __construct(public array $data, public string $issuedAt)
    {
    }

    public static function new(array $data): self
    {
        $now = new \DateTimeImmutable();

        $data["issuedAt"] = $now->format(\DateTimeImmutable::RFC850);

        return new self($data, $data["issuedAt"]);
    }

    public static function from(string $token): self
    {
        $key = new \Firebase\JWT\Key($_ENV["AUTH_KEY"], $_ENV["AUTH_ALGORITHM"]);

        $headers = new \stdClass();

        $data = (array)\Firebase\JWT\JWT::decode($token, $key, $headers);

        return new self($data, $data["issuedAt"]);
    }

    public function isExpired(): bool
    {
        $now = new \DateTimeImmutable();

        $duration = \DateInterval::createFromDateString($_ENV["AUTH_DURATION"]);

        $issuedAt = \DateTimeImmutable::createFromFormat(\DateTimeImmutable::RFC850, $this->issuedAt);

        return ($now <=> $issuedAt->add($duration)) === 1;
    }

    public function __toString(): string
    {
        $key = $_ENV["AUTH_KEY"];

        $algorithm = $_ENV["AUTH_ALGORITHM"];

        return \Firebase\JWT\JWT::encode($this->data, $key, $algorithm);
    }
}
