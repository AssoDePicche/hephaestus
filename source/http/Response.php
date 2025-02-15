<?php

declare(strict_types=1);

namespace Http {
    final class Response
    {
        public function __construct(private array $payload, private StatusCode $statusCode)
        {
        }

        public function send(): void
        {
            header("Content-Type: application/json");

            http_response_code($this->statusCode->value);

            $data = [];

            $data["message"] = $this->statusCode->getName();

            $now = new \DateTimeImmutable();

            $data["timestamp"] = $now->format(\DateTimeImmutable::RFC850);

            $data["payload"] = $this->payload;

            echo json_encode($data) . PHP_EOL;
        }
    }}
