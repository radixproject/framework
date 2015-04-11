<?php

namespace Radix\Framework\Annotations;

/**
* @Annotation 
* @Target({"METHOD"})
*/
class Route
{
    private $foo;
    public function __construct(array $values)
    {
        $this->foo = $values['foo'];
    }
}
