<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;

/**
 * NotFoundPlugin
 *
 * Handles not-found controller/actions
 */
class NotFoundPlugin extends Plugin
{

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception)
	{
        $controller = $dispatcher->getControllerName();
		if ($exception instanceof DispatcherException) {
			switch ($exception->getCode()) {
				case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
				case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                if($controller=="weixin"||$controller=="weigo")
                {
                    $dispatcher->forward(array(
                        'controller' => 'weierror',
                        'action' => 'show404'
                    ));
                }
                else {
                    $dispatcher->forward(array(
                        'controller' => 'errors',
                        'action' => 'show404'
                    ));
                }
					return false;
			}
		}

        if($controller=="weixin"||$controller=="weigo")
        {
            $dispatcher->forward(array(
                'controller' => 'weierror',
                'action' => 'show500'
            ));
        }
        else {
            $dispatcher->forward(array(
                'controller' => 'errors',
                'action' => 'show500'
            ));
        }
		return false;
	}
}
