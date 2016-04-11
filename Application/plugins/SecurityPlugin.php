<?php
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;
/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin {

	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
    private  $_userVail;
	protected  $logger;
	private  function  setLogger()
	{
		if($this->logger==false)
		 $this->logger=new Loggers("","Admin");
		return $this;
	}
	private  function  getLogger()
	{
		return $this->logger;
	}
	public function getAcl()
	{
		$this->setLogger();
		//throw new \Exception("something");
		//$this->getLogger()->logInfo("ACl创建测试11");
	    //if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			$this->getLogger()->logInfo("ACl创建用户!");
			//Register roles
			$roles = array(
				'users'  => new Role('Users'),
				'guests' => new Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
			$this->getLogger()->logInfo("ACl创建controler!");
			//Private area resources
			$privateResources = array(
				'ad'    => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete','prepayrecord','prepay','prepayAdd'),
                'taskinfo' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete','reportforms','reportformtest','lines','taskpush','taskpushSend','finsh','doneing','selectIDFA',"doneIDFA",'noIDFA','exceptExcel'),
                'user'    => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete','usermoney','userpass','detailed','trygame','usernopass','unwrap','exceptExcel','openapp','intalled','friendrecord','friendprice','otherprice'),
				'app'     => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete','upload','lines','appimg'),
				'roles' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
                'popularize' => array('channnel', 'record','new', 'edit','create','save','delete','qrcode'),
                'thirdmark'=>array('trygame','index','new', 'edit','create','save','delete','userinfo'),
				'sysuser' => array('index', 'search','edit','new','save','create','delete','sysuserLog',"updatepassword","newpassword",'noLock'),
				'interfaceinfo' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete', 'selectIDFA', 'clickinfo', 'callbackinfo'),
				'distribute' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete', 'channel', 'record', 'task', 'statistics'),
			);
			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Public area resource,
			$publicResources = array(
				'index'      => array('index','home'),
				'about'      => array('index'),
				'register'   => array('index'),
				'errors'     => array('show404', 'show500','show401'),
				'login'    => array('index','validate','end'),
				'contact'    => array('index', 'send'),
                'thirdweixin'=>array('beginMoney','myinfo','apply','index','ranking','income'),
                'weixin'     => array('index','apply','income','invest','invita','ranking','task','myInfo','beginTask','endTask',
                    'trying','pay', 'myInfoSave','rankinfo','invitepage','webChat', 'businessCooperate','customService','downdzg',
                    'daZhangGuiStatus','openApp','android','dzgstatus','giveupTask','vaildzg','apprentice','otherprice','qrCode','qrRedirect','friends','beginMoney'),
                'weierror' =>array('show500','show401','show404'),
                'taskinterface' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete','ihinterface','tocallback','test','TestClick'),
				'channelinterface' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete','channeltoclick','channelincallback','info','test','filterIdfa'),
                'weigo'    =>array('index')
			);
			foreach ($publicResources as $resource => $actions) {
				$acl->addResource(new Resource($resource), $actions);
			}

			//Grant access to public areas to both users and guests
			foreach ($roles as $role) {
				foreach ($publicResources as $resource => $actions) {
					foreach ($actions as $action){
						$acl->allow($role->getName(), $resource, $action);
					}
				}
			}

			//Grant acess to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('Users', $resource, $action);
				}
			}

			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
	//}
		return $this->persistent->acl;
	}
   private  function  ToPage($controller, $action,$dispatcher)
   {
       $auth = $this->session->get('auth_polyRich');
       if (!$auth) {
           $this->getLogger()->logInfo("ACl创建用户!");
           $role = 'Guests';
       } else {
           $role = 'Users';
       }


       $acl = $this->getAcl();

       $allowed = $acl->isAllowed($role, $controller, $action);
       if ($allowed != Acl::ALLOW) {
           if($controller=="weixin"||$controller=="weigo")
           {

               $dispatcher->forward(array(
                   'controller' => 'weierror',
                   'action' => 'show401'
               ));
           }
           else if($controller=="app"&&$action=="appimg")
           {

           }
           else {
               $dispatcher->forward(array(
                   'controller' => 'errors',
                   'action' => 'show401'
               ));
           }
           return false;
       }
   }
	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {

        $this->setLogger();

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $this->getLogger()->logInfo("controller:".$controller."---action:".$action);
        if($controller=="weixin"||$controller=="weierror"||$controller=="weigo"||($controller=="app"&&$action=="appimg")||$controller=="thirdweixin") {
            $this->ToPage($controller, $action,$dispatcher);
        } else {
            $this->_userVail = new UserVailService();
            if ($controller == "login"||$controller=="taskinterface") {
                $this->ToPage($controller, $action, $dispatcher);
            } elseif ($controller == "index" && $action == "index") {
                $this->ToPage($controller, $action, $dispatcher);
            } else {
                if ($this->_userVail->VailSeesion()) {
                    $this->ToPage($controller, $action, $dispatcher);
                }
            }
        }
    }
}
