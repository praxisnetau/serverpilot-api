<?php

namespace ServerPilot\Model;

use ServerPilot\Base\BaseModel;

use ServerPilot\Model\Server;
use ServerPilot\Model\SystemUser;

/**
 * Represents an app accessed via the ServerPilot API.
 *
 * @todo Add SSL and AutoSSL stuff.
 */
class App extends BaseModel
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $domains;
    
    /**
     * @var string
     */
    protected $runtime;
    
    /**
     * @var string
     */
    protected $serverId;
    
    /**
     * @var integer
     */
    protected $dateCreated;
    
    /**
     * @var string
     */
    protected $systemUserId;
    
    /**
     * Constructs the object upon instantiation.
     */
    public function __construct()
    {
        // Construct Parent:
        
        parent::__construct();
        
        // Construct Object:
        
        $this->setDomains(array());
    }
    
    /**
     * Defines the value of the name attribute.
     *
     * @param string $name
     * @return App
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
     * Defines the value of the domains attribute.
     *
     * @param array $domains
     * @return App
     */
    public function setDomains($domains)
    {
        $this->domains = (array) $domains;
        
        return $this;
    }
    
    /**
     * Answers the value of the domains attribute.
     *
     * @return array
     */
    public function getDomains()
    {
        return $this->domains;
    }
    
    /**
     * Answers true if the receiver has the specified domain.
     *
     * @param string $domain
     * @return boolean
     */
    public function hasDomain($domain)
    {
        return in_array($domain, $this->domains);
    }
    
    /**
     * Adds the specified domain to the receiver.
     *
     * @param string $domain
     * @return App
     */
    public function addDomain($domain)
    {
        if (!$this->hasDomain($domain)) {
            $this->domains[] = $domain;
        }
        
        return $this;
    }
    
    /**
     * Removes the specified domain from the receiver.
     *
     * @param string $domain
     * @return App
     */
    public function removeDomain($domain)
    {
        if ($this->hasDomain($domain)) {
            
            if (($key = array_search($domain, $this->domains)) !== false) {
                unset($this->domains[$key]);
            }
            
        }
        
        return $this;
    }
    
    /**
     * Defines the value of the runtime attribute.
     *
     * @param string $runtime
     * @return App
     */
    public function setRuntime($runtime)
    {
        $this->runtime = (string) $runtime;
        
        return $this;
    }
    
    /**
     * Answers the value of the runtime attribute.
     *
     * @return string
     */
    public function getRuntime()
    {
        return $this->runtime;
    }
    
    /**
     * Defines the value of the serverId attribute.
     *
     * @param string $serverId
     * @return App
     */
    public function setServerId($serverId)
    {
        $this->serverId = (string) $serverId;
        
        return $this;
    }
    
    /**
     * Answers the value of the serverId attribute.
     *
     * @return string
     */
    public function getServerId()
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
     * @return App
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
     * Defines the value of the systemUserId attribute.
     *
     * @param string $systemUserId
     * @return App
     */
    public function setSystemUserID($systemUserId)
    {
        $this->systemUserId = (string) $systemUserId;
        
        return $this;
    }
    
    /**
     * Answers the value of the systemUserId attribute.
     *
     * @return string
     */
    public function getSystemUserID()
    {
        return $this->systemUserId;
    }
    
    /**
     * Answers the system user associated with the receiver.
     *
     * @return SystemUser
     */
    public function getSystemUser()
    {
        return $this->command('SystemUser')->get($this->systemUserId);
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
                $this->runtime,
                $this->domains
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
     * @return App
     */
    public function fromData($data = array())
    {
        $this->setName($data['name']);
        $this->setDomains($data['domains']);
        $this->setRuntime($data['runtime']);
        $this->setServerID($data['serverid']);
        $this->setDateCreated($data['datecreated']);
        $this->setSystemUserID($data['sysuserid']);
        
        return parent::fromData($data);
    }
}
