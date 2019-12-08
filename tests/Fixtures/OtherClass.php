<?php

declare(strict_types=1);

namespace BackEndTea\SameObject\Test\Fixtures;

/**
 * @internal
 */
final class OtherClass
{
    /** @var mixed $foo */
    private $foo;

    /**
     * @param mixed $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }
}
