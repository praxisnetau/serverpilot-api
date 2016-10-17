<?php

namespace ServerPilot\Base;

use ServerPilot\ServerPilotAPI;
use ServerPilot\Factory\Factory;

/**
 * Abstract base class for command instances.
 */
abstract class BaseCommand
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $model;
    
    /**
     * @var string
     */
    protected $method;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $name
     * @param string $method
     */
    public function __construct($name = null, $method = null)
    {
        // Construct Object:
        
        $this->setName($name ? $name : $this->getDefaultName());
        $this->setMethod($method ? $method : $this->getDefaultMethod());
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
     * Defines the value of the name attribute.
     *
     * @param string $name
     * @return BaseCommand
     */
    public function setName($name)
    {
        $this->name = $name;
        
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
     * Defines the value of the model attribute.
     *
     * @param string $model
     * @return BaseCommand
     */
    public function setModel($model)
    {
        $this->model = (string) $model;
        
        return $this;
    }
    
    /**
     * Answers the value of the model attribute.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * Defines the value of the method attribute.
     *
     * @param string $method
     * @return BaseCommand
     */
    public function setMethod($method)
    {
        $this->method = (string) $method;
        
        return $this;
    }
    
    /**
     * Answers the value of the method attribute.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Answers the default name for the receiver.
     *
     * @return string
     */
    public function getDefaultName()
    {
        $reflect = new \ReflectionClass($this);
        
        return preg_replace('/command$/', '', strtolower($reflect->getShortName()));
    }
    
    /**
     * Answers the default method for the receiver.
     *
     * @return string
     */
    public function getDefaultMethod()
    {
        return $this->getDefaultName();
    }
}
