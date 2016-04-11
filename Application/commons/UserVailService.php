<?php

use Phalcon\Mvc\Controller;

class UserVailService extends Controller
{
    protected  $_logger;
    public  function  VailSeesion()
    {
            $this->_logger=new Loggers("验证","Admin");
            if ($this->session->has("auth_polyRich")) {
            $auth = $this->session->get('auth_polyRich');
            $user = SysUser::findFirst($auth['user_id']);
            $this->persistent->useraccount=$auth['account'];
            if ($user == true) {
                return  true;
            }
        }
        else {

            return $this->dispatcher->forward(array(
                "controller" => "index",
                "action" => "index"
            ));

        }
    }
}
