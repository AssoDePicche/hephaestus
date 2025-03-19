<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\Attributes\Test;

final class EmailTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function invalid_email_should_throw_domain_exception(): void
    {
        $this->expectException(\DomainException::class);

        \Domain\Email::from("");
    }
}
