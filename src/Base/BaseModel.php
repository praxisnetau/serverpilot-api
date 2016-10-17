<?php

namespace ServerPilot\Base;

use ServerPilot\ServerPilotAPI;
use ServerPilot\Factory\Factory;

/**
 * Abstract base class for model instances.
 */
abstract class BaseModel
{
    /**
     * @var string
     */
    protected $id;
    
    /**
     * @var boolean
     */
    protected $cacheable;
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        $this->setCacheable(true);
    }
    
    /**
     * Answers the API instance (shortcut method).
     *
     * @return ServerPilotAPI
     */
    public function api()
    {
        return ServerPilotAPI::inst();
    }
    
    /**
     * Answers the factory instance (shortcut method).
     *
     * @return Factory
     */
    public function factory()
    {
        return Factory::inst();
    }
    
    /**
     * Answers the command object from the API which handles the receiver, or the specified model class.
     *
     * @param string $class
     * @return BaseCommand
     */
    public function command($class = null)
    {
        return $this->api()->getCommandForModel($class ? $class : $this);
    }
    
    /**
     * Defines the value of the id attribute.
     *
     * @param string $id
     * @return BaseModel
     */
    public function setID($id)
    {
        $this->id = (string) $id;
        
        return $this;
    }
    
    /**
     * Answers the value of the id attribute.
     *
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }
    
    /**
     * Defines the value of the cacheable attribute.
     *
     * @param boolean $cacheable
     * @return BaseModel
     */
    public function setCacheable($cacheable)
    {
        $this->cacheable = (boolean) $cacheable;
        
        return $this;
    }
    
    /**
     * Answers the value of the cacheable attribute.
     *
     * @return boolean
     */
    public function getCacheable()
    {
        return $this->cacheable;
    }
    
    /**
     * Answers true if the receiver is cacheable.
     *
     * @return boolean
     */
    public function isCacheable()
    {
        return $this->getCacheable();
    }
    
    /**
     * Saves changes made to the receiver via the API.
     *
     * @return boolean
     */
    public function update()
    {
        return false;
    }
    
    /**
     * Deletes the receiver via the API.
     *
     * @return boolean
     */
    public function delete()
    {
        return false;
    }
    
    /**
     * Converts the receiver to a data array.
     *
     * @return array
     */
    public function toData()
    {
        $data = array();
        
        $data['id'] = $this->getID();
        
        return $data;
    }
    
    /**
     * Defines the receiver from the given JSON data.
     *
     * @param array $data
     * @return BaseModel
     */
    public function fromData($data = array())
    {
        $this->setID($data['id']);
        
        return $this;
    }
    
    /**
     * Answers the short class name of the receiver (without namespace).
     *
     * @return string
     */
    public function getShortClassName()
    {
        $reflect = new \ReflectionClass($this);
        
        return $reflect->getShortName();
    }
}
