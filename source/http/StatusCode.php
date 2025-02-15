<?php

declare(strict_types=1);

namespace Http {
    enum StatusCode: int
    {
        case BAD_REQUEST = 400;
        case CREATED = 201;
        case INTERNAL_SERVER_ERROR = 500;
        case METHOD_NOT_ALLOWED = 405;
        case NOT_FOUND = 404;
        case OK = 200;
        case SERVICE_UNAVAILABLE = 503;
        case UNAUTHORIZED = 401;
        case UNSUPPORTED_MEDIA_TYPE = 415;

        public function getName(): string
        {
            return match($this) {
                self::BAD_REQUEST => "Bad Request",
                self::CREATED => "Created",
                self::INTERNAL_SERVER_ERROR => "Internal Server Error",
                self::METHOD_NOT_ALLOWED => "Method Not Allowed",
                self::NOT_FOUND => "Not Found",
                self::OK => "OK",
                self::SERVICE_UNAVAILABLE => "Service Unavailable",
                self::UNAUTHORIZED => "Unauthorized",
                self::UNSUPPORTED_MEDIA_TYPE => "Unsupported Media Type",
            };
        }
    }
}
