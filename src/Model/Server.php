<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;

/**
 * Represents a server accessed via the ServerPilot API.
 */
class Server extends BaseModel
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $apiKey;
    
    /**
     * @var boolean
     */
    protected $firewall;
    
    /**
     * @var boolean
     */
    protected $autoUpdates;
    
    /**
     * @var string
     */
    protected $lastAddress;
    
    /**
     * @var integer
     */
    protected $dateCreated;
    
    /**
     * @var integer
     */
    protected $lastConnected;
    
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
     * @return Server
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
     * Defines the value of the apiKey attribute.
     *
     * @param string $apiKey
     * @return Server
     */
    public function setAPIKey($apiKey)
    {
        $this->apiKey = $apiKey;
        
        return $this;
    }
    
    /**
     * Answers the value of the apiKey attribute.
     *
     * @return string
     */
    public function getAPIKey()
    {
        return $this->apiKey;
    }
    
    /**
     * Defines the value of the firewall attribute.
     *
     * @param boolean $firewall
     * @return Server
     */
    public function setFirewall($firewall)
    {
        $this->firewall = (boolean) $firewall;
        
        return $this;
    }
    
    /**
     * Answers the value of the firewall attribute.
     *
     * @return boolean
     */
    public function getFirewall()
    {
        return $this->firewall;
    }
    
    /**
     * Defines the value of the autoUpdates attribute.
     *
     * @param boolean $autoUpdates
     * @return Server
     */
    public function setAutoUpdates($autoUpdates)
    {
        $this->autoUpdates = (boolean) $autoUpdates;
        
        return $this;
    }
    
    /**
     * Answers the value of the autoUpdates attribute.
     *
     * @return boolean
     */
    public function getAutoUpdates()
    {
        return $this->autoUpdates;
    }
    
    /**
     * Defines the value of the lastAddress attribute.
     *
     * @param string $lastAddress
     * @return Server
     */
    public function setLastAddress($lastAddress)
    {
        $this->lastAddress = (string) $lastAddress;
        
        return $this;
    }
    
    /**
     * Answers the value of the lastAddress attribute.
     *
     * @return string
     */
    public function getLastAddress()
    {
        return $this->lastAddress;
    }
    
    /**
     * Defines the value of the dateCreated attribute.
     *
     * @param integer $dateCreated
     * @return Server
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = (integer) $dateCreated;
        
        return $this;
    }
    
    /**
     * Answers the value of the dateCreated attribute.
     *
     * @return integer
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }
    
    /**
     * Answers the value of the dateCreated attribute as a DateTime object.
     *
     * @return DateTime
     */
    public function getDateCreatedObject()
    {
        return \DateTime::createFromFormat('U', $this->dateCreated);
    }
    
    /**
     * Defines the value of the lastConnected attribute.
     *
     * @param integer $lastConnected
     * @return Server
     */
    public function setLastConnected($lastConnected)
    {
        $this->lastConnected = (integer) $lastConnected;
        
        return $this;
    }
    
    /**
     * Answers the value of the lastConnected attribute.
     *
     * @return integer
     */
    public function getLastConnected()
    {
        return $this->lastConnected;
    }
    
    /**
     * Answers the value of the lastConnected attribute as a DateTime object.
     *
     * @return DateTime
     */
    public function getLastConnectedObject()
    {
        return \DateTime::createFromFormat('U', $this->lastConnected);
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
                $this->firewall,
                $this->autoUpdates
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
     * @return Server
     */
    public function fromData($data = array())
    {
        $this->setName($data['name']);
        $this->setFirewall($data['firewall']);
        $this->setAutoUpdates($data['autoupdates']);
        $this->setLastAddress($data['lastaddress']);
        $this->setDateCreated($data['datecreated']);
        $this->setLastConnected($data['lastconn']);
        
        if (isset($data['apikey'])) {
            $this->setAPIKey($data['apikey']);
        }
        
        return parent::fromData($data);
    }
}
