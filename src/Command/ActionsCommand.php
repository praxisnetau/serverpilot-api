<?php

namespace ServerPilot\Command;

use ServerPilot\Base\BaseCommand;
use ServerPilot\Model\ActionStatus;

/**
 * An extension of the base command object for action status operations.
 */
class ActionsCommand extends BaseCommand
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($name = 'actions', $method = 'actions')
    {
        // Construct Parent:
        
        parent::__construct($name, $method);
        
        // Construct Object:
        
        $this->setModel('ActionStatus');
    }
    
    /**
     * Answers the details of the action with the specified ID.
     *
     * @param string $id
     * @return ActionStatus
     */
    public function get($id)
    {
        return $this->api()->get($this, (string) $id);
    }
}
