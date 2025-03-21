<?php

declare(strict_types=1);

namespace Http;

final class Request
{
    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"] ?? "";
    }

    public function getURI(): string
    {
        return $_SERVER["REQUEST_URI"] ?? "";
    }

    public function getQueryString(): array
    {
        return $_GET ?? [];
    }

    public function getParts(): array
    {
        return $_FILES ?? [];
    }

    public function getCookies(): array
    {
        return $_COOKIE ?? [];
    }

    public function getSession(bool $create = false): Session
    {
        return Session::new($create);
    }

    public function getBody(): array
    {
        return json_decode(file_get_contents("php://input", true), true) ?? [];
    }

    public function getHeaders(): array
    {
        return getallheaders() ?? [];
    }
}
