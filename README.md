# ServerPilot API

A wrapper and data model for the [ServerPilot API](https://github.com/ServerPilot/API) implemented in PHP.

## Requires

* PHP 5.3.3 or newer
* [curl/curl](https://github.com/php-mod/curl) 1.4 or newer
* PHP JSON extension
* an account with [ServerPilot](https://serverpilot.io) (+ valid Client ID and API Key)

## Installation

You can install the API in your project either via Composer on the command-line:

```
composer require praxisnetau/serverpilot-api ~1.0
```

...or by adding it to your project composer.json file:

```json
require: {
    "praxisnetau/serverpilot-api": "~1.0"
}
```

...followed by this command:

```
composer install
```

## Usage

You will need to create a **Client ID** and **API Key** via your ServerPilot control panel. Once you have these,
you may create an instance of the API by passing in your credentials like so:

```php
use ServerPilot\ServerPilotAPI;

$api = ServerPilotAPI::inst('<client-id>', '<api-key>');
```

The API will throw an `Exception` if the credentials are invalid, or if it encounters any other problem, so it's
a good idea to wrap your API code in a try/catch block, e.g.:

```php
try {
    
    $api = ServerPilotAPI::inst('<client-id>', '<api-key>');
    
    $servers = $api->servers->listAll();
    
} catch (\Exception $e) {
    
    // Whoops, something went wrong!
    
}
```

You may also set the credentials via the config object if you like:

```php
$api = ServerPilotAPI::inst();

$api->config()->set('client-id', '<client-id>');
$api->config()->set('api-key', '<api-key>');
```

## Commands

The API works by routing property calls to a series of command objects. By default, these
property names and methods match those given by the [ServerPilot API documentation](https://github.com/ServerPilot/API),
i.e.:

```php
$api->servers;   // For server commands
$api->sysusers;  // For system user commands
$api->apps;      // For app commands
$api->dbs;       // For database commands
$api->actions;   // For action status commands
```

### Servers

Handles server-related operations.

```php
$api->servers->listAll();
```

```php
$api->servers->get('serverid');
```

```php
$api->servers->create('name');
```

```php
$api->servers->update('serverid', (boolean) $firewall, (boolean) $autoupdates);
```

```php
$api->servers->delete('serverid');
```

### System Users

Handles operations on system users.

```php
$api->sysusers->listAll();
```

```php
$api->sysusers->get('sysuserid');
```

```php
$api->sysusers->create('serverid', 'username', 'password');
```

```php
$api->sysusers->update('sysuserid', 'newpassword');
```

```php
$api->sysusers->delete('sysuserid');
```

### Apps

Handles operations on apps.

```php
$api->apps->listAll();
```

```php
$api->apps->get('appid');
```

```php
$api->apps->create('appname', 'sysuserid', 'runtime', ['domains'], ['wordpress']);
```

```php
$api->apps->update('appid', 'runtime', ['domains']);
```

```php
$api->apps->delete('appid');
```

### Databases

Handles database-related operations.

```php
$api->dbs->listAll();
```

```php
$api->dbs->get('databaseid');
```

```php
$api->dbs->create('appid', 'dbname', ['name' => 'username', 'password' => 'password']);
```

```php
$api->dbs->update('databaseid', ['id' => 'userid', 'password' => 'newpassword']);
```

```php
$api->dbs->delete('databaseid');
```

### Actions

Retrieve the status of an action.

```php
$api->actions->get('actionid');
```

## Data Model

While the API commands are powerful, it can sometimes be clumsy to issue a series
of commands directly via the API.  Enter the **data model**. Model
objects are provided for each of the API command areas, including:

* `Action`
* `ActionStatus`
* `App`
* `Database`
* `DatabaseUser`
* `Server`
* `SystemUser`

Rather than returning decoded JSON data, the API returns either individual instances of data
model classes, or arrays of data model classes (when multiple results are returned).

These model objects are *API-aware* and can be manipulated directly without making a separate call
to the API. All model objects have an `update()` and a `delete()` method. For example:

```php
if ($server = $api->servers->get('<server-id>')) {
    
    $server->setFirewall(true);
    $server->setAutoUpdates(true);
    
    $server->update();  // <-- server is updated via the API
    
}
```

Or, consider the following:

```php
if ($app = $api->apps->get('<app-id>')) {
    
    $app->setRuntime('php7.1');
    
    $app->addDomain('myapp.mydomain.com');
    $app->removeDomain('myapp.anotherdomain.com');
    
    $app->update();  // <-- app is updated via the API
    
}
```

Tired of that old app? Why not delete it!

```php
if ($app = $api->apps->get('<app-id>')) {
    
    $app->delete();  // <-- app has been deleted via the API, be careful!
    
}
```

### Retrieving Action Status

Every time the API performs an operation where something is changed, an `Action` is
recorded. It can be handy to see the last action that was performed, and
you can retrieve this object by calling `getLastAction()` on the API:

```php
if ($action = $api->getLastAction()) {
    
    $id     = $action->getID();      // ID of the action
    $data   = $action->getData();    // data returned by the API for the action
    $object = $action->getObject();  // data model object created for the action
    
}
```

If you just need the ID of the last action, you can use `getLastActionID()`:

```php
$id = $api->getLastActionID();  // ID of the last action
```

You can go a step further and check the status of the action using the `getStatus`
method, which returns an `ActionStatus` object:

```php
if ($action = $api->getLastAction()) {
    
    if ($status = $action->getStatus()) {
        
        $id          = $status->getID();           // ID of the action status
        $status      = $status->getStatus();       // status text ('open', 'error', or 'success')
        $serverId    = $status->getServerID();     // ID of the server
        $dateCreated = $status->getDateCreated();  // timestamp of the status
        
        if ($status->isOpen()) {
            // The action hasn't finished yet!
        }
        
        if ($status->isError()) {
            // Whoops, something went wrong!
        }
        
        if ($status->isSuccessful()) {
            // Huzzah, it worked!
        }
        
    }
    
}
```

Alternatively, you can retrieve the status of the last action directly from the API using `getLastActionStatus()`:

```php
$status = $api->getLastActionStatus();  // returns an ActionStatus object for last action
```

### DateTime Accessors

`DateTime` accessor methods are present for all properties returned as a timestamp via the API. For example:

```php
if ($server = $api->servers->get('<server-id>')) {
    
    $lastconn = $server->getLastConnected();        // returns integer timestamp
    $datetime = $server->getLastConnectedObject();  // returns DateTime object
    
}
```

## To Do

* implement paid plan SSL functionality for Apps
* expand data model to support additional functionality
