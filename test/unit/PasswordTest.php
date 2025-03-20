<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\Attributes\Test;

final class PasswordTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function weak_password_should_throw_domain_exception(): void
    {
        $this->expectException(\DomainException::class);

        \Domain\Password::new("weakpassword");
    }

    #[Test]
    public function invalid_password_hash_should_throw_domain_exception(): void
    {
        $this->expectException(\DomainException::class);

        \Domain\Password::from("yNv4l1dh4\$h");
    }
}
