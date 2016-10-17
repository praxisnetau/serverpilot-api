<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;

use ServerPilot\Factory\Factory;

use ServerPilot\Model\App;
use ServerPilot\Model\Server;
use ServerPilot\Model\DatabaseUser;

/**
 * Represents a database accessed via the ServerPilot API.
 */
class Database extends BaseModel
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var DatabaseUser
     */
    protected $user;
    
    /**
     * @var string
     */
    protected $appId;
    
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
     * @return Database
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
     * Defines the value of the appId attribute.
     *
     * @param string $appId
     * @return Database
     */
    public function setAppID($appId)
    {
        $this->appId = (string) $appId;
        
        return $this;
    }
    
    /**
     * Answers the value of the appId attribute.
     *
     * @return string
     */
    public function getAppID()
    {
        return $this->appId;
    }
    
    /**
     * Answers the app associated with the receiver.
     *
     * @return App
     */
    public function getApp()
    {
        return $this->command('App')->get($this->appId);
    }
    
    /**
     * Defines the value of the user attribute.
     *
     * @param DatabaseUser $user
     * @return Database
     */
    public function setUser(DatabaseUser $user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * Answers the value of the user attribute.
     *
     * @return DatabaseUser
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Defines the value of the serverId attribute.
     *
     * @param string $serverId
     * @return Database
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
                $this->user->toData()
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
     * @return Database
     */
    public function fromData($data = array())
    {
        $this->setName($data['name']);
        $this->setAppID($data['appid']);
        $this->setServerID($data['serverid']);
        
        $this->setUser($this->factory()->create('DatabaseUser', $data['user']));
        
        return parent::fromData($data);
    }
}
