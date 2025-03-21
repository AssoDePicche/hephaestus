<?php

declare(strict_types=1);

namespace Domain;

final class User
{
    private UUID $id;
    private Username $username;
    private Email $email;
    private Password $password;

    private function __construct()
    {
    }

    public function __set(string $property, mixed $value): void
    {
        if (!property_exists(self::class, $property)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not a '%s' property", $property, self::class));
        }

        $type = (new \ReflectionProperty($this, $property))->getType()->getName();

        if ($type !== gettype($value)) {
            throw new \InvalidArgumentException(sprintf("Property '%s' is not of type %s", $property, gettype($value)));
        }

        $this->$property = $value;
    }

    public function __get(string $property): mixed
    {
        if (!property_exists(self::class, $property)) {
            throw new \InvalidArgumentException(sprintf("'%s' is not a '%s' class property", $property, self::class));
        }

        return $this->$property;
    }

    public function __debugInfo(): array
    {
        return [
          "id" => $this->id->__toString(),
          "username" => $this->username->__toString(),
          "email" => $this->email->__toString(),
          "password" => $this->password->__toString(),
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->__debugInfo());
    }

    public static function new(): self
    {
        $user = new self();

        $user->id = UUID::new();

        return $user;
    }

    public static function from(array $data): self
    {
        $user = new self();

        $user->id = UUID::from($data["id"]);

        $user->username = Username::from($data["username"]);

        $user->email = Email::from($data["email"]);

        $user->password = Password::from($data["password"]);

        return $user;
    }
}
