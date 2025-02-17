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
            if (isset($_SERVER["HTTP_ORIGIN"])) {
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

                header("Access-Control-Allow-Credentials: true");
            }

            if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"])) {
                header("Access-Control-Allow-Methods: GET, POST");
            }

            if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            header("Content-Type: application/json");

            http_response_code($this->statusCode->value);

            $response = [];

            $response["message"] = $this->statusCode->getName();

            $now = new \DateTimeImmutable();

            $response["timestamp"] = $now->format(\DateTimeImmutable::RFC850);

            if (!empty($this->payload)) {
                $response["payload"] = $this->payload;
            }

            echo json_encode($response) . PHP_EOL;
        }
    }}
