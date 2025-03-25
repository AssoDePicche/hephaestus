<?php

declare(strict_types=1);

namespace Http;

final class Jwt
{
    public static function encode(array $data): string
    {
        $key = $_ENV["AUTH_KEY"];

        $algorithm = $_ENV["AUTH_ALGORITHM"];

        return \Firebase\JWT\JWT::encode($data, $key, $algorithm);
    }

    public static function decode(string $token): array
    {
        $key = new \Firebase\JWT\Key($_ENV["AUTH_KEY"], $_ENV["AUTH_ALGORITHM"]);

        $headers = new \stdClass();

        return (array)\Firebase\JWT\JWT::decode($token, $key, $headers);
    }
}
