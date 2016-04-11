<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Flash\Session as FlashSession;
/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * We register the events manager
 */
$di->set('dispatcher', function() use ($di) {

    $eventsManager = new EventsManager;

    /**
     * Check if the user is allowed to access certain action using the SecurityPlugin
     */

     $eventsManager->attach('dispatch:beforeDispatch', new SecurityPlugin());

    /**
     * Handle exceptions and not-found exceptions using NotFoundPlugin
     */
    $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin());

    $dispatcher = new Dispatcher;
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});

/**
 * Router
 */
$di->set(
    'router',
    function () {
        return include "routes.php";
    },
    true
);
/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);
/**
 * Setting up volt
 */
$di->set('volt', function($view, $di) {

    $volt = new VoltEngine($view, $di);

    $volt->setOptions(array(
        "compiledPath" => APP_PATH . "cache/volt/"
    ));

    $compiler = $volt->getCompiler();
    $compiler->addFunction('is_a', 'is_a');

    return $volt;
}, true);

/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function(){
    return new FlashSession(array(
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
    ));
});

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.leaf' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('dbMaster', function () use ($config) {
    $eventsManager = new EventsManager();
    $logger=new Loggers("数据库操作","Admin");
    $logger->logInfo("dbMaster--");
    //-Listen all the database eventss
    $eventsManager->attach('dbMaster', function($event, $connection) use ($logger) {
        if ($event->getType() == 'beforeQuery') {
          //  $logger->logInfo($connection->getSQLStatement());
        }
    });

    $connection =  new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        "charset" => $config->database->charset
    ));

    //Assign the eventsManager to the db adapter instance
    $connection->setEventsManager($eventsManager);
    return $connection;
});
$di->set('dbSlave', function () use ($config) {
    $eventsManager1 = new EventsManager();
    $logger=new Loggers("数据库操作","Admin");
    //-Listen all the database events
    $eventsManager1->attach('dbSlave', function($event, $connection) use ($logger) {
        if ($event->getType() == 'beforeQuery') {
              $logger->logInfo("dbSlave--".$connection->getSQLStatement());
        }
    });
    $slaveName = 'mysql_slave' . (String)rand(1, $config->mysql_slave_count->count);
    $logger->logInfo("dbSlave--");
    $connectionslave = new DbAdapter(array(
        'host' => $config->mysqlSlaves[$slaveName]['host'],
        'username' => $config->mysqlSlaves[$slaveName]['username'],
        'password' => $config->mysqlSlaves[$slaveName]['password'],
        'dbname' => $config->mysqlSlaves[$slaveName]['dbname'],
        "charset" => $config->mysqlSlaves[$slaveName]['charset']
    ));

    //Assign the eventsManager to the db adapter instance
    $connectionslave->setEventsManager($eventsManager1);
    return $connectionslave;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->set('logger',function() use ($config)
{
 return new FileAdapter($config->application->logsDir.date("Y-m-d").".log");
});
/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
/**
 * Register a user component
 */
$di->set('elements', function(){
    return new elements();
});

$di->set('security', function(){

    $security = new Phalcon\Security();

    //Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);

    return $security;
}, true);

