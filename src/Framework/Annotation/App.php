<?php

namespace Radix\Framework\Annotation;

/**
* @Annotation 
* @Target({"CLASS"})
*/
class App
{
    private $name;
    private $title;
    private $description;
    
    public function __construct(array $values)
    {
        $this->name = $values['name'];
        $this->title = $values['title'];
        $this->description = $values['description'];
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
