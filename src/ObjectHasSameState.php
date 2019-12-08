<?php

declare(strict_types=1);

namespace BackEndTea\SameObject;

use PHPUnit\Framework\Constraint\Constraint;
use function is_object;
use function sprintf;

final class ObjectHasSameState extends Constraint
{
    /** @var object */
    private $expectedObject;

    public function __construct(object $expectedObject)
    {
        $this->expectedObject = $expectedObject;
    }

    /**
     * @param object|mixed $other
     */
    public function matches($other): bool
    {
        return is_object($other) && (new ObjectComparator())->haveSameState($this->expectedObject, $other);
    }

    /**
     * Returns a string representation of the object.
     */
    public function toString(): string
    {
        return sprintf('has the same state as %s', $this->exporter()->export($this->expectedObject));
    }
}
