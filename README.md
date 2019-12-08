# Assert objects have same state

PHPUnit assertion helper to check if two objects have the same state.

Please note that same state only refers to the state of the properties. 
If you use the `static` keyword inside of functions, and they have their own state, then you are on your own.


## Install

Use [composer](https://getcomposer.org/):

```bash
$ composer require backendtea/assert-object-same-state --dev
```

### Usage

This package is compatible with phpunit 8.

```php
<?php

use BackEndTea\SameObject\ObjectHasSameStateAssertion;
use PHPUnit\Framework\TestCase;

class MyTest extends TestCase
{
    use ObjectHasSameStateAssertion;

    public function testObjects(): void
    {
        $one = new class{public $a=3;};
        $two = new class{public $a=3;};
        $this->assertObjectHasSameSate($one, $two);
    }
}
```

### Outside of PHPUnit

```php
<?php

$comp = new \BackEndTea\SameObject\ObjectComparator();

$comp->haveSameState($objectOne, $objectTwo);
```


## Why

`assertSame()` or `===`  checks if the two variables are refferencing the same object, 
which may not be always what you need.
But `assertEquals` or `==` is too loose a comparison, as it suffers from the same issues as other loose comparisons.

For example:

```php
<?php
class Thing
{
    public $prop;
    public function __construct($prop)
    {
        $this->prop = $prop;
    }
}
new Thing(10) == new Thing('10'); // true
new Thing(10) === new Thing('10'); // false
new Thing(10) === new Thing(10); // false
```

## Limitations

Same state only refers to the state of the properties. 
If you use the `static` keyword inside of methods, and they have their own state, 
then you are on your own.
