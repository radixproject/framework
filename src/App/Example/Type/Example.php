<?php

namespace Radix\App\Example\Type;

use Radix\Framework\Annotation\Type;
use Radix\Framework\Annotation\Field;

/**
 * @Type(name="example",
 * title="Example model",
 * description="This is an example type")
 */
class Example
{
    /**
     * @Field(name="id", type="integer", description="The primary key")
     */
    private $id;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    

    /**
     * @Field(name="name", type="varchar(32)", description="The example name")
     */
    private $name;
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @Field(name="created_at", type="integer", description="When was this example created")
     */
    private $created_at;
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    public function setCreatedAt($name)
    {
        $this->name = $created_at;
    }

}
