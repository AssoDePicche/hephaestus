<?php

declare(strict_types=1);

namespace Unit;

use PHPUnit\Framework\Attributes\Test;

final class UUIDTest extends \PHPUnit\Framework\TestCase
{
    #[Test]
    public function uuid_should_be_36_characters_long(): void
    {
        $this->assertTrue(36 === strlen(\Domain\UUID::new()->__toString()));
    }

    #[Test]
    public function empty_uuid_should_throw_domain_exception(): void
    {
        $this->expectException(\DomainException::class);

        \Domain\UUID::from("");
    }
}
