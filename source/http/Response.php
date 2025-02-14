<?php

declare(strict_types=1);

namespace Http {
    final class Response
    {
        public function __construct(private array $data, private int $statusCode)
        {
        }

        public function send(): void
        {
            header("Content-Type: application/json");

            http_response_code($this->statusCode);

            $timestamp = (new \DateTimeImmutable())->format(\DateTimeImmutable::RFC850);

            $this->data["timestamp"] = $timestamp;

            echo json_encode($this->data) . PHP_EOL;
        }
    }}
