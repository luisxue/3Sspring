<?php
use phalcon\Mvc\Controller;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;
class LoginController extends Controller
{


	public function indexAction()
    {
    
    }
	/**
	 * Register an authenticated user into session data
	 *
	 * @param Users $user
	 */
	private function _registerSession(SysUser $user)
	{
		//$this->session->remove('auth_polyRich');
		$this->session->set('auth_polyRich', array(
			'user_id' => $user->getId(),
			'account' => $user->getAccount()
		));
	}
	/**
	 * Finishes the active session redirecting to the index
	 *
	 * @return unknown
	 */
	public function endAction()
	{
        $auth = $this->session->get('auth_polyRich');
        (new  UserDoneHelper())->SetUserDone( $this->persistent->userid,"退出","退出系统");
		$this->session->remove('auth_polyRich');
		return $this->dispatcher->forward(array(
			"controller" => "index",
			"action" => "index"
		));
	}
	public function validateAction()
	{
		$logger=new Loggers("登陆","Admin");
        $logger->logInfo("页面认证B--");
		$numberPage = 1;
		if ($this->request->isPost()) {
			$logger->logInfo("页面认证");
			//if ($this->security->checkToken())
         //{

          //  if($_SERVER["REMOTE_ADDR"]=="223.20.62.254"||$_SERVER["REMOTE_ADDR"]=="223.20.64.247"||$_SERVER["REMOTE_ADDR"]=="111.194.208.148"||$_SERVER["REMOTE_ADDR"]=="58.135.80.161"||$_SERVER["REMOTE_ADDR"]=="121.42.166.250"||$_SERVER["REMOTE_ADDR"]=="111.192.255.149"||$_SERVER["REMOTE_ADDR"]=="222.128.128.77")
           // {
				$login = $this->request->getPost('Account');
                $logger->logInfo("获得账号--".$login);
                if(empty($login))
                {
                    $this->flash->error('账号不能为空!');
                    return $this->dispatcher->forward(array(
                        "controller" => "index",
                        "action" => "index"
                    ));
                }
                $password = $this->request->getPost('Password');
                if(empty($password))
                {
                    $this->flash->error('密码不能为空!');
                    return $this->dispatcher->forward(array(
                        "controller" => "index",
                        "action" => "index"
                    ));
                }
                $logger->logInfo("begion获得用户--".$login);
				$sysuser = SysUser::findFirstByAccount($login);
                $logger->logInfo("end获得用户--".$login);
				if ($sysuser) {

                    $redis=CacheFactory::createCache("Redis");
                    $num=0;
                    if($redis->IsExist("sysuser_passworderror_".$login))
                    {
                        $num = (int)$redis->getValue("sysuser_passworderror_" . $login);
                    }
                    if($num!=5)
                    {
                        $logger->logInfo("获得用户--" . $login);
                        if ($this->security->checkHash($password, $sysuser->getPassword())) {
                            if ($sysuser->getStatus() == 1) {
                                $logger->logInfo("设置session获得用户--" . $login);
                                $this->_registerSession($sysuser);
                                $logger->logInfo("设置session获得用户完成--" . $login);
                                $this->persistent->userid = $sysuser->getId();
                                (new  UserDoneHelper())->SetUserDone($this->persistent->userid, "登录", "登录系统:" . $login);
                                return $this->dispatcher->forward(array(
                                    "controller" => "index",
                                    "action" => "home"
                                ));
                            } else {
                                $logger->logInfo("账户冻结请联系管理员!---" . $sysuser->getAccount());
                                $this->flash->error('账户冻结请联系管理员!');
                            }
                        } else {

                            if (!$redis->IsExist("sysuser_passworderror_" . $login)) {
                                $logger->logInfo("密码错误,还有 4 次,将锁定" . $login);
                                $this->flash->error("密码错误,还有 4 次,将锁定");
                                $redis->setValue("sysuser_passworderror_" . $login, 1);
                            } else {

                                if ($num == 5) {
                                    $logger->logInfo("密码错误,已达到5次，已经锁定，请联系管理员!" . $login);
                                    $this->flash->error('密码错误,已达到5次，已经锁定，请联系管理员!');
                                } else {
                                    $logger->logInfo("密码错误,还有" . (5 - $num - 1) . " 次,将锁定" . $login);
                                    $this->flash->error("密码错误,还有" . (5 - $num - 1) . " 次,将锁定");
                                    $redis->setValue("sysuser_passworderror_" . $login, $num + 1);
                                }
                            }
                        }
                    }
                    else
                    {
                        $logger->logInfo("密码错误,已达到5次，已经锁定，请联系管理员!" . $login);
                        $this->flash->error('密码错误,已达到5次，已经锁定，请联系管理员!');
                    }

				} else {
					//$logger->logInfo("用户名不存在!".$login);
					$this->flash->error('用户名不存在!');
				}
				return $this->dispatcher->forward(array(
					"controller" => "index",
					"action" => "index"
				));


/*
        //}
           else
           {
                $logger->logInfo("页面验证失败!");
                return $this->dispatcher->forward(array(
                   "controller" => "index",
                   "action" => "index"
               ));
            }
*/
		} else {
			return $this->dispatcher->forward(array(
				"controller" => "index",
				"action" => "index"
			));
		}

	}
}

