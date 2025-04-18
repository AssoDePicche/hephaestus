<?php

declare(strict_types=1);

namespace Http;

final class Response
{
    public function __construct(private array $payload, private StatusCode $statusCode, private ?\Exception $exception = null)
    {
    }

    public static function from(\Exception $exception): self
    {
        return new self([], StatusCode::fromException($exception), $exception);
    }

    public function send(): void
    {
        header(sprintf("Access-Control-Allow-Origin: %s", $_ENV["ALLOWED_ORIGIN"]));

        header("Access-Control-Allow-Credentials: true");

        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

        header("Access-Control-Allow-Headers: Accept, Accept-Language, Authorization, Content-Type");

        if ("OPTIONS" === $_SERVER["REQUEST_METHOD"]) {
            http_response_code(200);

            exit();
        };

        header("Content-Type: application/json;charset=UTF-8");

        http_response_code($this->statusCode->value);

        $response = [];

        $response["status"] = $this->statusCode->value;

        $response["message"] = $this->statusCode->getName();

        $now = new \DateTimeImmutable();

        $response["timestamp"] = $now->format(\DateTimeImmutable::RFC850);

        if (!empty($this->payload)) {
            $response["payload"] = $this->payload;
        }

        if (!is_null($this->exception)) {
            $response["error"] = $this->exception->getMessage();
        }

        echo json_encode($response) . PHP_EOL;
    }
}
