<?php

declare(strict_types=1);

namespace Controller;

final class AuthController extends Controller
{
    public function doSignup(\Http\Request $request): \Http\Response
    {
        $body = $request->getBody();

        $this->validateRequestBody($body, ["username", "email", "password"]);

        $query = "INSERT INTO Users (id,username,email,password) VALUES (?,?,?,?)";

        $connection = \Persistence\Connection::new();

        $connection->execute($query, [
          \Domain\UUID::new()->__toString(),
          \Domain\Username::from($body["username"])->__toString(),
          \Domain\Email::from($body["email"])->__toString(),
          \Domain\Password::new($body["password"])->__toString(),
        ]);

        return new \Http\Response([], \Http\StatusCode::CREATED);
    }

    public function doLogin(\Http\Request $request): \Http\Response
    {
        $body = $request->getBody();

        $this->validateRequestBody($body, ["email", "password"]);

        $query = "SELECT password FROM Users WHERE email = ?";

        $connection = \Persistence\Connection::new();

        $hash = $connection->execute($query, [
          \Domain\Email::from($body["email"])->__toString(),
        ])->fetch()[0]["password"];

        $password = \Domain\Password::from($hash);

        if (!$password->compare($body["password"])) {
            throw \Http\Exception\UnauthorizedException::new();
        }

        $token = \Http\Jwt::new(["email" => $body["email"]])->__toString();

        return new \Http\Response([
          "token" => $token,
        ], \Http\StatusCode::OK);
    }
}
