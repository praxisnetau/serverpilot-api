<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;

/**
 * Represents a database user associated with a database record.
 */
class DatabaseUser extends BaseModel
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $password;
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        // Construct Parent:
        
        parent::__construct();
    }
    
    /**
     * Defines the value of the name attribute.
     *
     * @param string $name
     * @return DatabaseUser
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        
        return $this;
    }
    
    /**
     * Answers the value of the name attribute.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Defines the value of the password attribute.
     *
     * @param string $password
     * @return DatabaseUser
     */
    public function setPassword($password)
    {
        $this->password = (string) $password;
        
        return $this;
    }
    
    /**
     * Answers the value of the password attribute.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Converts the receiver to a data array.
     *
     * @return array
     */
    public function toData()
    {
        return array_merge(
            parent::toData(),
            array(
                'name' => $this->getName(),
                'password' => $this->getPassword()
            )
        );
    }
    
    /**
     * Defines the receiver from the given JSON data.
     *
     * @param array $data
     * @return DatabaseUser
     */
    public function fromData($data = array())
    {
        $this->setName($data['name']);
        
        return parent::fromData($data);
    }
}
