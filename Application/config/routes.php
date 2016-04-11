<?php
/**
 * Created by Trees.
 * User: router
 * Date: 2015/07/01
 */

$router = new \Phalcon\Mvc\Router();

$router->add("/loginv", array(
    'controller' => 'login',
    'action' => 'validate'
));
$router->add("/loginve", array(
    'controller' => 'login',
    'action' => 'end'
));

$router->add("/dolog", array(
    'controller' => 'sysuser',
    'action' => 'sysuserLog'
));

return $router;