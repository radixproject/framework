<?php

namespace Radix\Framework\Annotation;

/**
* @Annotation 
* @Target({"PROPERTY"})
*/
class Field
{
    private $name;
    private $description;
    private $type;
    
    public function __construct(array $values)
    {
        $this->name = $values['name'];
        if (isset($values['type'])) {
            $this->type = $values['type'];
        }
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

    public function getType()
    {
        return $this->type;
    }
}
