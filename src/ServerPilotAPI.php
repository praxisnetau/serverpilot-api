<?php

namespace ServerPilot;

use Curl\Curl;

use ServerPilot\Config\Config;

use ServerPilot\Base\BaseModel;
use ServerPilot\Base\BaseCommand;

use ServerPilot\Command\AppsCommand;
use ServerPilot\Command\ActionsCommand;
use ServerPilot\Command\ServersCommand;
use ServerPilot\Command\DatabasesCommand;
use ServerPilot\Command\SystemUsersCommand;

use ServerPilot\Factory\Factory;

use ServerPilot\Model\Action;

/**
 * A wrapper class for the ServerPilot REST API.
 */
class ServerPilotAPI
{
    /**
     * Define HTTP method constants.
     */
    const HTTP_METHOD_GET    = "get";
    const HTTP_METHOD_POST   = "post";
    const HTTP_METHOD_DELETE = "delete";
    
    /**
     * @var string
     */
    private static $endpoint = "https://api.serverpilot.io/v1";
    
    /**
     * @var ServerPilotAPI
     */
    private static $inst;
    
    /**
     * @var Curl
     */
    private $curl;
    
    /**
     * @var Config
     */
    private $config;
    
    /**
     * @var array
     */
    private $actions = array();
    
    /**
     * @var array
     */
    private $commands = array();
    
    /**
     * Answers the API instance.
     *
     * @param string $clientId
     * @param string $apiKey
     * @return ServerPilotAPI
     */
    public static function inst($clientId = null, $apiKey = null)
    {
        if (!self::$inst) {
            self::$inst = new ServerPilotAPI($clientId, $apiKey);
        }
        
        return self::$inst;
    }
    
    /**
     * Uses PHP magic to route undefined property names to API commands.
     *
     * @throws Exception
     *
     * @param string $name
     * @return BaseCommand
     */
    public function __get($name)
    {
        // Obtain Command Object:
        
        if ($command = $this->getCommand($name)) {
            return $command;
        }
        
        // Throw Exception (when not found):
        
        throw new \Exception(sprintf('invalid API command: "%s"', $name));
    }
    
    /**
     * Defines the value of the curl attribute.
     *
     * @param Curl $curl
     * @return ServerPilotAPI
     */
    public function setCurl(Curl $curl)
    {
        $this->curl = $curl;
        
        return $this;
    }
    
    /**
     * Answers the value of the curl attribute.
     *
     * @return Curl
     */
    public function getCurl()
    {
        return $this->curl;
    }
    
    /**
     * Defines the value of the config attribute.
     *
     * @param Config $config
     * @return ServerPilotAPI
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        
        return $this;
    }
    
    /**
     * Answers the value of the config attribute.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Defines the value of the actions attribute.
     *
     * @param array $actions
     * @return ServerPilotAPI
     */
    public function setActions($actions)
    {
        $this->actions = (array) $actions;
        
        return $this;
    }
    
    /**
     * Answers the value of the actions attribute.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    /**
     * Adds the given action object to the receiver.
     *
     * @param Action $action
     * @return ServerPilotAPI
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
        
        return $this;
    }
    
    /**
     * Answers a action object with the given ID.
     *
     * @param string $id
     * @return Action
     */
    public function getAction($id)
    {
        foreach ($this->actions as $action) {
            
            if ($action->getID() == $id) {
                return $action;
            }
            
        }
    }
    
    /**
     * Answers the last action object added.
     *
     * @return Action
     */
    public function getLastAction()
    {
        if (!empty($this->actions)) {
            return $this->actions[count($this->actions) - 1];
        }
    }
    
    /**
     * Answers the ID of the last action object added.
     *
     * @return string
     */
    public function getLastActionID()
    {
        if ($action = $this->getLastAction()) {
            return $action->getID();
        }
    }
    
    /**
     * Answers the action status object for the last action object added.
     *
     * @return ActionStatus
     */
    public function getLastActionStatus()
    {
        if ($action = $this->getLastAction()) {
            return $action->getStatus();
        }
    }
    
    /**
     * Defines the value of the commands attribute.
     *
     * @param array $commands
     * @return ServerPilotAPI
     */
    public function setCommands($commands)
    {
        $this->commands = (array) $commands;
        
        return $this;
    }
    
    /**
     * Answers the value of the commands attribute.
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }
    
    /**
     * Adds the given command object to the receiver.
     *
     * @param BaseCommand $command
     * @return ServerPilotAPI
     */
    public function addCommand(BaseCommand $command)
    {
        $this->commands[$command->getName()] = $command;
        
        return $this;
    }
    
    /**
     * Answers a command object with the given name.
     *
     * @param string $name
     * @return BaseCommand
     */
    public function getCommand($name)
    {
        if (isset($this->commands[$name])) {
            return $this->commands[$name];
        }
    }
    
    /**
     * Answers a command object for the specified model class.
     *
     * @param BaseModel|string $class
     * @return BaseCommand
     */
    public function getCommandForModel($class)
    {
        // Get Class of Object (if required):
        
        if ($class instanceof BaseModel) {
            $class = $class->getShortClassName();
        }
        
        // Iterate Command Objects:
        
        foreach ($this->commands as $command) {
            
            if ($command->getModel() == $class) {
                return $command;
            }
            
        }
        
        // Throw Exception (when not found):
        
        throw new \Exception(sprintf('invalid API model class: "%s"', $class));
    }
    
    /**
     * Initialises the receiver.
     *
     * @param string $mode
     */
    public function init($mode = null)
    {
        // Reset Curl Object:
        
        $this->curl->reset();
        
        // Define User Agent:
        
        $this->curl->setUserAgent($this->config->get('user-agent'));
        
        // Define API Credentials:
        
        $this->curl->setBasicAuthentication(
            $this->config->get('client-id'),
            $this->config->get('api-key')
        );
        
        // Define Request Timeout:
        
        $this->curl->setOpt(CURLOPT_TIMEOUT, $this->config->get('request-timeout'));
        
        // Define Standard Headers:
        
        $this->curl->setHeader('Accept', 'application/json');
        
        // Define POST Specific Settings:
        
        if ($mode == self::HTTP_METHOD_POST) {
            $this->curl->setHeader('Content-Type', 'application/json');
        }
    }
    
    /**
     * Issues a GET request to the ServerPilot API.
     *
     * @param BaseCommand $command
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function get(BaseCommand $command, $params = array(), $data = array())
    {
        // Initialise:
        
        $this->init(self::HTTP_METHOD_GET);
        
        // Issue GET Request:
        
        $this->curl->get($this->url($command, $params), $data);
        
        // Handle Response:
        
        return $this->handle($command);
    }
    
    /**
     * Issues a POST request to the ServerPilot API.
     *
     * @param BaseCommand $command
     * @param array $params
     * @param array $data
     * @return mixed
     */
    public function post(BaseCommand $command, $params = array(), $data = array())
    {
        // Initialise:
        
        $this->init(self::HTTP_METHOD_POST);
        
        // Remove Object from Cache:
        
        $this->decache($command, $params);
        
        // Issue POST Request:
        
        $this->curl->post($this->url($command, $params), $this->encode($data));
        
        // Handle Response:
        
        return $this->handle($command);
    }
    
    /**
     * Issues a DELETE request to the ServerPilot API.
     *
     * @param BaseCommand $command
     * @param array $params
     * @return mixed
     */
    public function delete(BaseCommand $command, $params = array())
    {
        // Initialise:
        
        $this->init(self::HTTP_METHOD_DELETE);
        
        // Remove Object from Cache:
        
        $this->decache($command, $params);
        
        // Issue DELETE Request:
        
        $this->curl->delete($this->url($command, $params));
        
        // Handle Response:
        
        return $this->handle($command, true);
    }
    
    /**
     * Answers the config instance (shortcut method).
     *
     * @return Config
     */
    public function config()
    {
        return $this->getConfig();
    }
    
    /**
     * Answers the factory instance (shortcut method).
     *
     * @return Factory
     */
    public function factory()
    {
        return Factory::inst();
    }
    
    /**
     * Handles the response received from the API.
     *
     * @throws Exception
     *
     * @param BaseCommand $command
     * @param boolean $boolean
     * @return mixed
     */
    private function handle(BaseCommand $command, $boolean = false)
    {
        // Initialise:
        
        $result = null;
        
        // Handle Errors:
        
        if ($this->curl->error) {
            
            // Throw Exception on Error:
            
            throw new \Exception($this->message(), $this->code());
            
        }
        
        // Handle JSON Data:
        
        if ($json = $this->json()) {
            
            // Define Result:
            
            if ($boolean) {
                
                // Boolean Result:
                
                $result = true;
                
            } else {
                
                // Array or Object Result:
                
                if (isset($json['data']) && !empty($json['data'])) {
                    $result = $this->build($command, $json['data']);
                }
                
            }
            
            // Create Action Object (if required):
            
            if (isset($json['actionid'])) {
                $this->addAction($this->action($json, $result));
            }
            
        }
        
        // Answer Result:
        
        return $result;
    }
    
    /**
     * Encodes the given data array as a JSON string.
     *
     * @param array $data
     * @return string
     */
    private function encode($data = array())
    {
        return json_encode($data);
    }
    
    /**
     * Encodes the given data array as a JSON string.
     *
     * @param string $data
     * @return array
     */
    private function decode($data)
    {
        return json_decode($data, true);
    }
    
    /**
     * Decodes the JSON response and answers the resulting array.
     *
     * @return array
     */
    private function json()
    {
        if ($this->curl->response) {
            return $this->decode($this->curl->response);
        }
    }
    
    /**
     * Answers the HTTP status code returned by the API, or the Curl object.
     *
     * @return integer
     */
    private function code()
    {
        if ($this->curl->http_status_code) {
            return $this->curl->http_status_code;
        } elseif ($this->curl->error_code) {
            return $this->curl->error_code;
        }
    }
    
    /**
     * Answers the error message from the JSON response, or the Curl object.
     *
     * @return string
     */
    private function error()
    {
        if (($json = $this->json()) && isset($json['error']['message'])) {
            return $json['error']['message'];
        } elseif ($this->curl->error) {
            return $this->curl->error_message;
        }
    }
    
    /**
     * Answers the error message for an exception.
     *
     * @return string
     */
    private function message()
    {
        $originator = $this->curl->response ? 'API' : 'cURL';
        
        return sprintf(
            '%s returned error %s: "%s"',
            $originator,
            $this->code(),
            $this->error()
        );
    }
    
    /**
     * Creates and answers either an instance of the command model class, or an array of instances.
     *
     * @param BaseCommand $command
     * @param array $data
     * @return mixed
     */
    private function build(BaseCommand $command, $data)
    {
        // Create Array of Objects:
        
        if (isset($data[0]) && is_array($data[0])) {
            
            $result = array();
            
            foreach ($data as $item) {
                $result[] = $this->create($command, $item);
            }
            
            return $result;
            
        }
        
        // Create Individual Object:
        
        return $this->create($command, $data);
    }
    
    /**
     * Creates and answers a new action instance with the specified data and model object.
     *
     * @param array $data
     * @param mixed $object
     * @return Action
     */
    private function action($data, $object)
    {
        // Map ID to Action ID:
        
        if (isset($data['actionid']) && !isset($data['id'])) {
            $data['id'] = $data['actionid'];
        }
        
        // Create Action Object:
        
        $action = $this->factory()->create('Action', $data);
        
        // Associate Model Object (if defined):
        
        if (is_object($object)) {
            $action->setObject($object);
        }
        
        // Answer Action Object:
        
        return $action;
    }
    
    /**
     * Creates and answers an instance of the command model class.
     *
     * @param BaseCommand $command
     * @param array $data
     * @return BaseModel
     */
    private function create(BaseCommand $command, $data)
    {
        return $this->factory()->create($command->getModel(), $data);
    }
    
    /**
     * Removes a model object with the specified details from the factory cache.
     *
     * @param BaseCommand $command
     * @param array $params
     */
    private function decache(BaseCommand $command, $params = array())
    {
        $params = (array) $params;
        
        if ($id = array_shift($params)) {
            return $this->factory()->decache($command->getModel(), $id);
        }
    }
    
    /**
     * Answers the complete request URL for the given parameters.
     *
     * @param string $method
     * @param array $params
     * @return string
     */
    private function url(BaseCommand $command, $params = array())
    {
        // Define URL Segments:
        
        $segments = array(
            rtrim(self::$endpoint, '/'),
            $command->getMethod(),
            implode('/', (array) $params)
        );
        
        // Answer URL String:
        
        return implode('/', $segments);
    }
    
    /**
     * Adds the default command objects to the receiver.
     */
    private function addDefaultCommands()
    {
        $this->addCommand(new ServersCommand());
        $this->addCommand(new SystemUsersCommand());
        $this->addCommand(new AppsCommand());
        $this->addCommand(new DatabasesCommand());
        $this->addCommand(new ActionsCommand());
    }

    /**
     * Constructs the object upon instantiation (private for singleton pattern).
     *
     * @param string $clientId
     * @param string $apiKey
     */
    private function __construct($clientId = null, $apiKey = null)
    {
        // Define Curl:
        
        $this->setCurl(new Curl());
        
        // Define Config:
        
        $this->setConfig(
            new Config(
                array(
                    'client-id' => $clientId,
                    'api-key' => $apiKey
                )
            )
        );
        
        // Add Default Commands:
        
        $this->addDefaultCommands();
    }
}
