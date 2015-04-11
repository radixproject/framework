<?php

namespace Radix\Framework\Model;

class Type
{
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    private $className;
    
    public function getClassName()
    {
        return $this->className;
    }
    
    public function setClassName($className)
    {
        $this->className = $className;
    }
    
    private $description;
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
    
    private $fields = array();
    
    public function getField($name)
    {
        return $this->fields[$name];
    }
    
    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }
    
    public function getFields()
    {
        return $this->fields;
    }
}
