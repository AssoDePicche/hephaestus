<?php

declare(strict_types=1);

namespace Controller;

abstract class Controller
{
    final public function validateRequestBody(array $body, array $args): void
    {
        foreach ($args as $arg) {
            if (!key_exists($arg, $body)) {
                throw \Http\Exception\BadRequestException::new(sprintf("Missing '%s' in request body", $arg));
            }
        }
    }
}
