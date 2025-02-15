<?php

declare(strict_types=1);

namespace Persistence {
    final class Connection
    {
        private static ?Connection $instance = null;

        private \PDO $pdo;

        private \PDOStatement $statement;

        private function __construct(
            #[\SensitiveParameter]
            readonly string $url,
            #[\SensitiveParameter]
            readonly string $user,
            #[\SensitiveParameter]
            readonly string $password,
        ) {
            $this->pdo = new \PDO($url, $user, $password, [
              \PDO::ATTR_PERSISTENT => true,
              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        }

        public static function getInstance(): self
        {
            if (null === self::$instance) {
                self::$instance = new self(
                    $_ENV["DATABASE_URL"],
                    $_ENV["DATABASE_USER"],
                    $_ENV["DATABASE_PASSWORD"]
                );
            }

            return self::$instance;
        }

        public function execute(string $query, array $params = []): self
        {
          $this->statement = $this->pdo->prepare($query);

          foreach ($params as $key => $value) {
            $this->statement->bindValue($key + 1, $value);
          }

          $this->statement->execute();

          return $this;
        }

        public function fetch(): array
        {
          $rows = [];

          while ($row = $this->statement->fetch()) {
            $rows[] = $row;
          }

          return $rows;
        }
    }
}
