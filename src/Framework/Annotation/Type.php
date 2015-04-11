<?php

namespace Radix\Framework\Annotation;

/**
* @Annotation 
* @Target({"CLASS"})
*/
class Type
{
    private $name;
    private $description;
    
    public function __construct(array $values)
    {
        $this->name = $values['name'];
        if (isset($values['description'])) {
            $this->description = $values['description'];
        }
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
}
