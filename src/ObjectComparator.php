<?php

declare(strict_types=1);

namespace BackEndTea\SameObject;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use function count;
use function get_class;
use function is_object;

final class ObjectComparator
{
    public function haveSameState(object $objectOne, object $objectTwo): bool
    {
        $rc = new ReflectionClass($objectOne);
        $otherRc = new ReflectionClass($objectTwo);

        if ($rc->isAnonymous() xor $otherRc->isAnonymous()
            || (
                ! $rc->isAnonymous()
                && get_class($objectOne) !== get_class($objectTwo)
            )
        ) {
            return false;
        }

        $properties = $rc->getProperties();
        $otherProperties = $otherRc->getProperties();
        if (count($properties) !== count($otherProperties)) {
            return false;
        }
        foreach ($properties as $property) {
            if (! $this->arePropertiesEqual($objectOne, $objectTwo, $property, $otherRc)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @psalm-param ReflectionClass<object> $otherRc
     */
    private function arePropertiesEqual(
        object $expected,
        object $other,
        ReflectionProperty $property,
        ReflectionClass $otherRc
    ): bool {
        $name = $property->getName();
        $property->setAccessible(true);
        try {
            $otherProp = $otherRc->getProperty($name);
            $otherProp->setAccessible(true);
        } catch (ReflectionException $e) {
            return false;
        }
        /** @var mixed $otherPropValue */
        $otherPropValue = $otherProp->getValue($other);
        /** @var mixed $thisPropValue */
        $thisPropValue = $property->getValue($expected);
        if (is_object($otherPropValue) && is_object($thisPropValue)) {
            return $this->haveSameState($otherPropValue, $thisPropValue);
        }

        return $otherPropValue === $thisPropValue;
    }
}
