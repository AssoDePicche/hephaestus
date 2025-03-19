<?php

declare(strict_types=1);

namespace Http;

final class Router
{
    private array $routes = [];

    public function __construct(
        private string $filename
    ) {
        $json = file_get_contents($this->filename);

        $this->routes = json_decode($json, true);
    }

    public function dispatch(Request $request): Response
    {
        $allowed_http_methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

        foreach ($this->routes as $route => $args) {
            if ($request->getURI() !== $route) {
                continue;
            }

            foreach ($allowed_http_methods as $http_method) {
                if ($request->getMethod() !== $http_method) {
                    continue;
                }

                $class = "\\Controller\\" . explode("@", $args[$http_method])[0];

                $callback = explode("@", $args[$http_method])[1];

                if (!class_exists($class) || !method_exists($class, $callback)) {
                    return new Response([], StatusCode::INTERNAL_SERVER_ERROR);
                }

                $controller = new $class();

                try {
                    return call_user_func([$controller, $callback], $request);
                } catch (\Exception $exception) {
                    return Response::from($exception);
                }
            }

            return new Response([], StatusCode::METHOD_NOT_ALLOWED);
        }

        return new Response([], StatusCode::NOT_FOUND);
    }
}
