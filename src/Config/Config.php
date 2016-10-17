<?php

namespace ServerPilot\Config;

/**
 * Configuration class for the ServerPilot REST API.
 */
class Config
{
    /**
     * @var array
     */
    public static $defaultConfig = array(
        'api-key' => null,
        'client-id' => null,
        'user-agent' => 'ServerPilotAPI-PHP/1.0',
        'request-timeout' => 30
    );
    
    /**
     * @var array
     */
    protected $config;
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param array $config
     */
    public function __construct($config = array())
    {
        // Define Config:
        
        $this->config = array_merge(self::$defaultConfig, $config);
    }
    
    /**
     * Define the value of the config setting with the given key.
     *
     * @param string $key
     * @param mixed $value
     * @return Config
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
        
        return $this;
    }
    
    /**
     * Answers the value of the config setting with the given key.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        switch ($key) {
            
            case "credentials":
                
                return sprintf(
                    '%s:%s',
                    $this->get('client-id'),
                    $this->get('api-key')
                );
                
            default:
                
                if (!isset($this->config[$key])) {
                    return null;
                }
                
                return $this->config[$key];
            
        }
    }
}
