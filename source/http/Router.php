<?php

declare(strict_types=1);

namespace Http;

final class Router
{
    private function __construct(
        private array $routes = []
    ) {
    }

    public function dispatch(Request $request): Response
    {
        try {
            $route = $this->getRoute($request);

            $class = "\\Controller\\" . explode("@", $route["callback"])[0];

            $callback = explode("@", $route["callback"])[1];

            if (!class_exists($class) || !method_exists($class, $callback)) {
                throw \Http\Exception\InternalServerErrorException::new("Request cannot be resolved");
            }

            $controller = new $class();

            return call_user_func([$controller, $callback], $request);
        } catch (\Exception $exception) {
            return Response::from($exception);
        }
    }

    private function getRoute(Request $request): array
    {
        $http_method = $request->getMethod();

        foreach ($this->routes as $allowed_http_method => $routes) {
            foreach ($routes as $route) {
                $pattern = array_keys($route)[0];

                if (!preg_match($pattern, $request->getURI(), $matches)) {
                    continue;
                }

                if ($allowed_http_method !== $request->getMethod()) {
                    $message = sprintf("'%s' method not allowed for '%s' route", $request->getMethod(), $request->getURI());

                    throw \Http\Exception\MethodNotAllowedException::new($message);
                }

                unset($matches[0]);

                $route[$pattern]["variables"] = array_combine($route[$pattern]["variables"], $matches);

                return $route[$pattern];
            }
        }

        $message = sprintf("Route '%s' not found", $request->getURI());

        throw \Http\Exception\NotFoundException::new($message);
    }

    public static function from(string $filename): self
    {
        if (!file_exists($filename)) {
            throw new \RuntimeException(sprintf("'%s' not found", $filename));
        }

        $json = json_decode(file_get_contents($filename), true);

        $routes = [
          "GET" => [],
          "POST" => [],
          "PUT" => [],
          "PATCH" => [],
          "DELETE" => [],
        ];

        foreach (array_keys($json) as $route) {
            foreach (array_keys($json[$route]) as $http_method) {
                $pattern = "/{(.*?)}/";

                $replacement = "(.*?)";

                $index = $route;

                if (preg_match_all($pattern, $route, $matches)) {
                    $index = preg_replace($pattern, $replacement, $route);
                }

                $index = "/^" .  str_replace("/", "\/", rtrim($index, "/")) . "$/";

                $routes[$http_method][] = [
                  $index => [
                    "callback" => $json[$route][$http_method]["callback"],
                    "middlewares" => $json[$route][$http_method]["middlewares"] ?? [],
                    "variables" => $matches[1] ?? [],
                  ]
                ];
            }
        }

        return new self($routes);
    }
}
