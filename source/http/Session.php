<?php

declare(strict_types=1);

namespace Http;

final class Session
{
  private static ?self $instance = null;

  private function __construct() {
    session_start();
  }

  public function __debugInfo(): array
  {
    $status = match(true){
        session_status() === PHP_SESSION_NONE => "none",
        session_status() === PHP_SESSION_ACTIVE => "active",
    };

    return [
      "id" => session_id(),
      "status" => $status,
      "data" => $_SESSION
    ];
  }

  public function __toString(): string
  {
    return json_encode($this->__debugInfo());
  }

  public function __set(string $key, mixed $value): void
  {
    $_SESSION[$key] = $value;
  }

  public function __get(string $key): mixed
  {
    if (key_exists($key, $_SESSION)) {
      return $_SESSION[$key];
    }

    throw new \InvalidArgumentException(sprintf("'%s' is not defined in current session", $key));
  }

  public function destroy(): void
  {
    if (session_status !== PHP_SESSION_ACTIVE){
      return;
    }

    session_unset();

    session_destroy();
  }

  public static function new(bool $create = false): self
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    if (session_status() === PHP_SESSION_ACTIVE && $create) {
      self::instance->destroy();

      self::instance = new self();
    }

    return self::$instance;
  }
}
