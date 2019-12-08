<?php

declare(strict_types=1);

namespace BackEndTea\SameObject\Test;

use BackEndTea\SameObject\ObjectHasSameState;
use BackEndTea\SameObject\ObjectHasSameStateAssertion;
use BackEndTea\SameObject\Test\Fixtures\OtherClass;
use BackEndTea\SameObject\Test\Fixtures\SomeClass;
use Generator;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class ObjectHasSameStateTest extends TestCase
{
    use ObjectHasSameStateAssertion;

    /**
     * @dataProvider provideSameStateObjects
     */
    public function testObjectsHaveSameState(object $one, object $two): void
    {
        self::assertTrue((new ObjectHasSameState($one))->matches($two));
        self::assertObjectHasSameSate($two, $one);
    }

    /**
     * @psalm-return Generator<array<object>>
     */
    public function provideSameStateObjects(): Generator
    {
        yield 'Same scalar props' => [new SomeClass(10), new SomeClass(10)];
        yield 'Same object props' => [new SomeClass(new SomeClass(10)), new SomeClass(new SomeClass(10))];
        yield 'anonyous classes' => [
            new class {
                /** @var mixed */
                public $a = 3;
            }, new class {
                /** @var mixed */
                public $a =3;
            },
        ];
        yield 'anoymous functions' => [
            static function (): void {
            }, static function (): void {
            },
        ];
        $a = new stdClass();
        $a->foo =10;
        $b = new stdClass();
        $b->foo = 10;
        yield 'stdClasses' => [$a, $b];
    }

    /**
     * @param object|mixed $two
     *
     * @dataProvider provideNonSameStateObjects
     */
    public function testObjectsHaveDifferentState(object $one, $two): void
    {
        self::assertFalse((new ObjectHasSameState($one))->matches($two));
    }

    /**
     * @psalm-return Generator<array<object|mixed>>
     */
    public function provideNonSameStateObjects(): Generator
    {
        yield 'Different values same type' => [new SomeClass(10), new SomeClass(20)];
        yield 'Different type same value' => [new SomeClass(10), new SomeClass('10')];
        yield 'Different strings' =>[new SomeClass('foo'), new SomeClass('10')];
        yield 'Different classes' =>[new SomeClass(10), new OtherClass(10)];
        yield 'Property objects are different classes' => [
            new SomeClass(new OtherClass(10)),
            new SomeClass(new OtherClass('10')),
        ];
        yield 'One Property is a class' => [
            new SomeClass(new OtherClass(10)),
            new SomeClass(10),
        ];
        yield 'anonyous classes' => [
            new class {
                /** @var mixed */
                public $a = 3;
            }, new class {
                /** @var mixed */
                public $a =4;
            },
        ];
        yield 'anonyous classes with more props' => [
            new class {
                /** @var mixed */
                public $a = 3;
            }, new class {
                /** @var mixed */
                public $a =3;
                /** @var mixed */
                public $b =4;
            },
        ];
        yield 'anonyous classes with diffent name' => [
            new class {
                /** @var mixed */
                public $a = 3;
            }, new class {
                /** @var mixed */
                public $b =4;
            },
        ];

        yield 'other is not an object' => [
            new SomeClass(12),
            12,
        ];
    }

    public function testItFailsWithErrorMessage(): void
    {
        $one = new SomeClass(10);
        $two = new SomeClass(20);
        try {
            self::assertObjectHasSameSate($one, $two);
        } catch (AssertionFailedError $e) {
            self::assertStringContainsString('has the same state as', $e->getMessage());

            return;
        }

        self::fail('Assertion failure was not thrown');
    }
}
