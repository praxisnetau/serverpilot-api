<?php

namespace ServerPilot\Command;

use ServerPilot\Base\BaseCommand;
use ServerPilot\Model\Database;

/**
 * An extension of the base command object for database-related operations.
 */
class DatabasesCommand extends BaseCommand
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($name = 'dbs', $method = 'dbs')
    {
        // Construct Parent:
        
        parent::__construct($name, $method);
        
        // Construct Object:
        
        $this->setModel('Database');
    }
    
    /**
     * Lists all databases available.
     *
     * @return array
     */
    public function listAll()
    {
        return $this->api()->get($this);
    }
    
    /**
     * Answers the details of the database with the specified ID.
     *
     * @param string $id
     * @return Database
     */
    public function get($id)
    {
        return $this->api()->get($this, (string) $id);
    }
    
    /**
     * Creates a new database for the specified app with the given name and details.
     *
     * @param string $appid
     * @param string $name
     * @param array $user
     *   @param string $user['name']
     *   @param string $user['password']
     * @return Database
     */
    public function create($appid, $name, $user = array())
    {
        return $this->api()->post(
            $this,
            array(),
            array(
                'appid' => (string) $appid,
                'name' => (string) $name,
                'user' => (object) $user
            )
        );
    }
    
    /**
     * Updates the database with the specified ID.
     *
     * @param string $id
     * @param array $user
     *   @param string $user['id']
     *   @param string $user['password']
     * @return Database
     */
    public function update($id, $user = array())
    {
        return $this->api()->post(
            $this,
            $id,
            array(
                'user' => (object) $user
            )
        );
    }
    
    /**
     * Deletes the database with the specified ID.
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->api()->delete($this, (string) $id);
    }
}
