<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;
use ServerPilot\Model\Server;

/**
 * Represents the status of an action accessed via the ServerPilot API.
 */
class ActionStatus extends BaseModel
{
    /**
     * Define status constants.
     */
    const STATUS_OPEN    = "open";
    const STATUS_ERROR   = "error";
    const STATUS_SUCCESS = "success";
    
    /**
     * @var string
     */
    protected $status;
    
    /**
     * @var string
     */
    protected $serverId;
    
    /**
     * @var integer
     */
    protected $dateCreated;
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setCacheable(false);
    }
    
    /**
     * Defines the value of the status attribute.
     *
     * @param string $status
     * @return Action
     */
    public function setStatus($status)
    {
        $this->status = (string) $status;
        
        return $this;
    }
    
    /**
     * Answers the value of the status attribute.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Defines the value of the serverId attribute.
     *
     * @param string $serverId
     * @return Action
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
     * Defines the value of the dateCreated attribute.
     *
     * @param integer $dateCreated
     * @return Action
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
     * Answers true if the status value is 'open'.
     *
     * @return boolean
     */
    public function isOpen()
    {
        return ($this->status == self::STATUS_OPEN);
    }
    
    /**
     * Answers true if the status value is 'error'.
     *
     * @return boolean
     */
    public function isError()
    {
        return ($this->status == self::STATUS_ERROR);
    }
    
    /**
     * Answers true if the status value is 'success'.
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return ($this->status == self::STATUS_SUCCESS);
    }
    
    /**
     * Alias for the isSuccess() method.
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->isSuccess();
    }
    
    /**
     * Defines the receiver from the given JSON data.
     *
     * @param array $data
     * @return ActionStatus
     */
    public function fromData($data = array())
    {
        $this->setStatus($data['status']);
        $this->setServerID($data['serverid']);
        $this->setDateCreated($data['datecreated']);
        
        return parent::fromData($data);
    }
}
