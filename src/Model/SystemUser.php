<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;
use ServerPilot\Model\Server;

/**
 * Represents a system user accessed via the ServerPilot API.
 */
class SystemUser extends BaseModel
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $serverId;
    
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
     * @return SystemUser
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
     * Defines the value of the serverId attribute.
     *
     * @param string $serverId
     * @return SystemUser
     */
    public function setServerID($serverId)
    {
        $this->serverId = (string) $serverId;
        
        return $this;
    }
    
    /**
     * Answers the value of the serverId attribute.
     *
     * @return string
     */
    public function getServerID()
    {
        return $this->serverId;
    }
    
    /**
     * Answers the server associated with the receiver.
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->command('Server')->get($this->serverId);
    }
    
    /**
     * Saves changes made to the receiver via the API.
     *
     * @return boolean
     */
    public function update()
    {
        try {
            
            $this->command()->update(
                $this->id,
                $this->password
            );
            
            return true;
            
        } catch (\Exception $e) {
            
            throw $e;
            
        }
    }
    
    /**
     * Deletes the receiver via the API.
     *
     * @return boolean
     */
    public function delete()
    {
        try {
            
            $this->command()->delete($this->id);
            
            return true;
            
        } catch (\Exception $e) {
            
            throw $e;
            
        }
    }
    
    /**
     * Defines the receiver from the given JSON data.
     *
     * @param array $data
     * @return SystemUser
     */
    public function fromData($data = array())
    {
        $this->setName($data['name']);
        $this->setServerID($data['serverid']);
        
        return parent::fromData($data);
    }
}
