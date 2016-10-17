<?php

namespace ServerPilot\Factory;

use ServerPilot\Model;

/**
 * Factory class used to create instances of model classes for the API.
 */
class Factory
{
    /**
     * @var Factory
     */
    private static $inst;
    
    /**
     * @var array
     */
    private $cache = array();
    
    /**
     * Answers the factory instance.
     *
     * @return Factory
     */
    public static function inst()
    {
        if (!self::$inst) {
            self::$inst = new Factory();
        }
        
        return self::$inst;
    }
    
    /**
     * Creates and answers an instance of a model object.
     *
     * @param string $class
     * @param array $data
     * @return BaseModel
     */
    public function create($class, $data = array())
    {
        // Answer Cached Object (if it exists):
        
        if (isset($data['id'])) {
            
            if (isset($this->cache[$class][$data['id']])) {
                return $this->cache[$class][$data['id']];
            }
            
        }
        
        // Create Cache Array:
        
        if (!isset($this->cache[$class])) {
            $this->cache[$class] = array();
        }
        
        // Define Model:
        
        $model = "ServerPilot\\Model\\{$class}";
        
        // Create Object:
        
        $object = new $model();
        
        // Define Object:
        
        $object->fromData($data);
        
        // Record Object (if cacheable):
        
        if ($object->getID() && $object->isCacheable()) {
            $this->cache[$class][$object->getID()] = $object;
        }
        
        // Answer Object:
        
        return $object;
    }
    
    /**
     * Removes a model object with the specified details from the cache.
     *
     * @param string $class
     * @param string $id
     */
    public function decache($class, $id)
    {
        if (isset($this->cache[$class][$id])) {
            unset($this->cache[$class][$id]);
        }
    }
    
    /**
     * Constructs the object upon instantiation (private for singleton pattern).
     */
    private function __construct()
    {
        
    }
}
