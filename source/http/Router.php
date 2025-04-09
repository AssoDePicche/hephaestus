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


        try {
            if (!key_exists($request->getURI(), $this->routes)) {
                $message = sprintf("Route '%s' not found", $request->getURI());

                throw \Http\Exception\NotFoundException::new($message);
            }

            $http_method = $request->getMethod();

            $route = $this->routes[$request->getURI()];

            if (!key_exists($http_method, $route)) {
                $message = sprintf("Method '%s' not allowed for route '%s'", $http_method, $request->getURI());

                throw \Http\Exception\MethodNotAllowedException::new($message);
            }

            $class = "\\Controller\\" . explode("@", $route[$http_method])[0];

            $callback = explode("@", $route[$http_method])[1];

            if (!class_exists($class) || !method_exists($class, $callback)) {
                throw \Http\Exception\InternalServerErrorException::new("Request cannot be resolved");
            }

            $controller = new $class();

            return call_user_func([$controller, $callback], $request);
        } catch (\Exception $exception) {
            return Response::from($exception);
        }

    }
}
