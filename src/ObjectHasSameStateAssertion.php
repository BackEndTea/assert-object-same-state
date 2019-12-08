<?php

declare(strict_types=1);

namespace BackEndTea\SameObject;

use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait ObjectHasSameStateAssertion
{
    public static function assertObjectHasSameSate(object $expected, object $actual, string $message = ''): void
    {
        self::assertThat($actual, new ObjectHasSameState($expected), $message);
    }
}
