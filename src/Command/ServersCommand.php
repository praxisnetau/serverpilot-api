<?php

namespace ServerPilot\Command;

use ServerPilot\Base\BaseCommand;
use ServerPilot\Model\Server;

/**
 * An extension of the base command object for server-related operations.
 */
class ServersCommand extends BaseCommand
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($name = 'servers', $method = 'servers')
    {
        // Construct Parent:
        
        parent::__construct($name, $method);
        
        // Construct Object:
        
        $this->setModel('Server');
    }
    
    /**
     * Lists all servers available.
     *
     * @return array
     */
    public function listAll()
    {
        return $this->api()->get($this);
    }
    
    /**
     * Answers the details of the server with the specified ID.
     *
     * @param string $id
     * @return Server
     */
    public function get($id)
    {
        return $this->api()->get($this, (string) $id);
    }
    
    /**
     * Creates a new server with the specified name.
     *
     * @param string $name
     * @return Server
     */
    public function create($name)
    {
        return $this->api()->post($this, array(), array('name' => (string) $name));
    }
    
    /**
     * Updates the server with the specified ID.
     *
     * @param string $id
     * @param boolean $firewall
     * @param boolean $autoupdates
     * @return Server
     */
    public function update($id, $firewall, $autoupdates)
    {
        return $this->api()->post(
            $this,
            $id,
            array(
                'firewall' => (boolean) $firewall,
                'autoupdates' => (boolean) $autoupdates
            )
        );
    }
    
    /**
     * Deletes the server with the specified ID.
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->api()->delete($this, (string) $id);
    }
}
