<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;
use ServerPilot\Model\ActionStatus;

/**
 * Represents an action accessed via the ServerPilot API.
 */
class Action extends BaseModel
{
    /**
     * @var array
     */
    protected $data;
    
    /**
     * @var BaseModel
     */
    protected $object;
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        // Construct Parent:
        
        parent::__construct();
    }
    
    /**
     * Defines the value of the data attribute.
     *
     * @param array $data
     * @return Action
     */
    public function setData($data)
    {
        $this->data = (array) $data;
        
        return $this;
    }
    
    /**
     * Answers the value of the data attribute.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * Defines the value of the object attribute.
     *
     * @param BaseModel $object
     * @return Action
     */
    public function setObject(BaseModel $object)
    {
        $this->object = $object;
        
        return $this;
    }
    
    /**
     * Answers the value of the object attribute.
     *
     * @return BaseModel
     */
    public function getObject()
    {
        return $this->object;
    }
    
    /**
     * Answers the action status object for the receiver.
     *
     * @return ActionStatus
     */
    public function getStatus()
    {
        return $this->command('ActionStatus')->get($this->id);
    }
    
    /**
     * Defines the receiver from the given JSON data.
     *
     * @param array $data
     * @return Action
     */
    public function fromData($data = array())
    {
        $this->setData($data['data']);
        
        return parent::fromData($data);
    }
}
