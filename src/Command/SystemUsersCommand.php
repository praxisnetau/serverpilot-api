<?php

namespace ServerPilot\Command;

use ServerPilot\Base\BaseCommand;
use ServerPilot\Model\SystemUser;

/**
 * An extension of the base command object for system user operations.
 */
class SystemUsersCommand extends BaseCommand
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($name = 'sysusers', $method = 'sysusers')
    {
        // Construct Parent:
        
        parent::__construct($name, $method);
        
        // Construct Object:
        
        $this->setModel('SystemUser');
    }
    
    /**
     * Lists all system users available.
     *
     * @return array
     */
    public function listAll()
    {
        return $this->api()->get($this);
    }
    
    /**
     * Answers the details of the system user with the specified ID.
     *
     * @param string $id
     * @return SystemUser
     */
    public function get($id)
    {
        return $this->api()->get($this, (string) $id);
    }
    
    /**
     * Creates a new system user on the specified server with the given name and password.
     *
     * @param string $serverid
     * @param string $name
     * @param string $password
     * @return SystemUser
     */
    public function create($serverid, $name, $password)
    {
        return $this->api()->post(
            $this,
            array(),
            array(
                'serverid' => (string) $serverid,
                'name' => (string) $name,
                'password' => (string) $password
            )
        );
    }
    
    /**
     * Updates the system user with the specified ID.
     *
     * @param string $id
     * @param string $password
     * @return SystemUser
     */
    public function update($id, $password)
    {
        return $this->api()->post(
            $this,
            $id,
            array(
                'password' => (string) $password,
            )
        );
    }
    
    /**
     * Deletes the system user with the specified ID.
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->api()->delete($this, (string) $id);
    }
}
