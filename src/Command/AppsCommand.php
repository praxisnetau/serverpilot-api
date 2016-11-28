<?php

namespace ServerPilot\Command;

use ServerPilot\Base\BaseCommand;
use ServerPilot\Model\App;

/**
 * An extension of the base command object for app-related operations.
 */
class AppsCommand extends BaseCommand
{
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct($name = 'apps', $method = 'apps')
    {
        // Construct Parent:
        
        parent::__construct($name, $method);
        
        // Construct Object:
        
        $this->setModel('App');
    }
    
    /**
     * Lists all apps available.
     *
     * @return array
     */
    public function listAll()
    {
        return $this->api()->get($this);
    }
    
    /**
     * Answers the details of the app with the specified ID.
     *
     * @param string $id
     * @return App
     */
    public function get($id)
    {
        return $this->api()->get($this, (string) $id);
    }
    
    /**
     * Creates a new app with the specified name and details.
     *
     * @param string $name
     * @param string $sysuserid
     * @param string $runtime
     * @param array $domains
     * @param array $wordpress
     * @return App
     */
    public function create($name, $sysuserid, $runtime, $domains = array(), $wordpress = array())
    {
        return $this->api()->post(
            $this,
            array(),
            array(
                'name' => (string) $name,
                'sysuserid' => (string) $sysuserid,
                'runtime' => (string) $runtime,
                'domains' => (array) $domains,
                'wordpress' => (object) $wordpress
            )
        );
    }
    
    /**
     * Updates the app with the specified ID.
     *
     * @param string $id
     * @return App
     */
    public function update($id, $runtime, $domains = array())
    {
        return $this->api()->post(
            $this,
            $id,
            array(
                'runtime' => (string) $runtime,
                'domains' => (array) $domains
            )
        );
    }
    
    /**
     * Deletes the app with the specified ID.
     *
     * @param string $id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->api()->delete($this, (string) $id);
    }
}
