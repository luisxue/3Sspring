<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Micro;
use Phalcon\Http\Response;
class WeixinController extends WeiControllerBase
{
    protected  $_logger;

    var  $app_table;
    private  function  setLogger($account)
    {
        if($this->_logger==false)
            $this->_logger=new Loggers($account,"app");
        return $this;
    }
    private  function  getLogger()
    {
        return $this->_logger;
    }
    private function  DriverType()
    {
        $this->setLogger("weixin-android");
        $this->getLogger()->logInfo($_SERVER['HTTP_USER_AGENT']);
        if ( strstr($_SERVER['HTTP_USER_AGENT'], 'Android') == false ) {
            $this->getLogger()->logInfo("IOS手机");
            return true;
        }
        $this->getLogger()->logInfo("android手机");
        return $this->dispatcher->forward(array(
            "controller" => "weixin",
            "action" => "android"
        ));
    }

    private function  GetMaxID()
    {
        $maxid=0;
        try {
            $sqlcx = "select * from Userinfo order by  ID desc  limit 1";
            $query = $this->modelsManager->createQuery($sqlcx);
            $result = $query->execute();
            if(count($result)>0) {
                $this->getLogger()->logInfo("微信用户--最大ID：" . $result[0]->getId());
                $maxid=$result[0]->getId();
            }
        }catch (exception $e)
        {
            $this->getLogger()->logInfo("aaaaaaaa----:".$e->getMessage());
        }
       return $maxid;
    }
    private  function  SaveUserInfo($fromuser)
    {
        try
        {
        $this->getLogger()->logInfo("创建新用户----:");
        $weixinservice=weixinapi::getInstance();

        $userinfo=$weixinservice->getUserinfo($fromuser);
        if($userinfo)
        {
            $redis = CacheFactory::createCache("Redis");
           if ($redis->IsExist("user_MaxID") == false) {
                $maxid =(int) $this->GetMaxID();
                $maxid=$maxid+1;
             //   $redis->setValue("user_MaxID", ($maxid + 1));
            } else {
               // $maxid = (int)$redis->getValue("user_MaxID");
               $maxid = (int)$redis->getINCRValue("user_MaxID");
              ///  $redis->setValue("user_MaxID", ($maxid + 1));
            }
            $userid = $maxid == 0 ? "10001" : $maxid;
            $userinfo->setId($userid);

            if ($userinfo->save()) {
              //  $redis->setValue("user_MaxID", ($userid));
                $userbalance = Userbalance::findFirstByUserid($userid);
                if (!$userbalance) {
                    $userbalance = new  Userbalance();
                    $userbalance->setAddtime(strtotime(date("Y-m-d H:i:s")));
                    $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                    $userbalance->setUserid($userid);
                    $userbalance->setTotalincome(0);
                    $userbalance->setCurrentbalance(0);
                    $userbalance->save();
                }
                $this->SetNewUserToRedis($userinfo, $userbalance);
                $this->getLogger()->logInfo(" 用户保存成功----:");
            }
            else
            {
                if ($redis->IsExist("user_MaxID") == true)
                {
                    $redis->getDecrValue("user_MaxID");
                }
            }

        }
            else
            {
                $this->getLogger()->logInfo("没有获得用户信息fromuser:".$fromuser);
                return $this->dispatcher->forward(array(
                    "controller" => "weierror",
                    "action" => "show401"
                ));
            }
        }catch (exception $e)
        {
            $this->getLogger()->logInfo(" 保存用户异常:".$e->getMessage());
        }
        return $userid;
    }
    private  function  SaveUserInfoEx($fromuser,$friendId)
    {
        try {
            $this->setLogger("weixin");
            $this->getLogger()->logInfo("weixin:friendid--".$friendId);
            $this->getLogger()->logInfo("创建新用户----:");
            $weixinservice = weixinapi::getInstance();

            $userinfo = $weixinservice->getUserinfo($fromuser);
            if($userinfo) {
                $redis = CacheFactory::createCache("Redis");
                if ($redis->IsExist("user_MaxID") == false) {
                    $maxid =(int) $this->GetMaxID();
                    $maxid=$maxid+1;
                    //   $redis->setValue("user_MaxID", ($maxid + 1));
                } else {
                    // $maxid = (int)$redis->getValue("user_MaxID");
                    $maxid = (int)$redis->getINCRValue("user_MaxID");
                    ///  $redis->setValue("user_MaxID", ($maxid + 1));
                }
                $userid = $maxid == 0 ? "10001" : $maxid;
                $userinfo->setId($userid);
                $userinfo->setPresenter($friendId);

                if ($userinfo->save())
                {
                    //$redis->setValue("user_MaxID", ($userid));
                    $userinvite = new Userinvite();
                    $userinvite->setAddtime(strtotime(date("Y-m-d H:i:s")));
                    $userinvite->setFriendsId($friendId);
                    $userinvite->setUserId($userid);
                    $userinvite->setStatus(1);
                    $userinvite->save();

                    /*
                    $userfriends=new Userfriends();
                    $userfriends->setAddtime(strtotime(date("Y-m-d H:i:s")));
                    $userfriends->setPrice(0);
                    $userfriends->setFriendsId($friendId);
                    $userfriends->setUserId($userid);
                    $userfriends->setStatus(1);
                    $userfriends->setContent();
                    $userfriends->save();
                    */
                    $userbalance = Userbalance::findFirstByUserid($userid);
                    if (!$userbalance) {
                        $userbalance = new  Userbalance();
                        $userbalance->setAddtime(strtotime(date("Y-m-d H:i:s")));
                        $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                        $userbalance->setUserid($userid);
                        $userbalance->setTotalincome(0);
                        $userbalance->setCurrentbalance(0);
                        $userbalance->save();
                    }
                    $this->SetNewUserToRedis($userinfo, $userbalance);

                    $this->getLogger()->logInfo(" 用户保存成功----:");
                }
                else
                {
                    if ($redis->IsExist("user_MaxID") == true)
                    {
                       $redis->getDecrValue("user_MaxID");
                    }
                }
            }
            else
            {
                $this->getLogger()->logInfo("没有获得用户信息fromuser:".$fromuser);
                return $this->dispatcher->forward(array(
                    "controller" => "weierror",
                    "action" => "show401"
                ));
            }
        }catch (exception $e)
        {
            $this->getLogger()->logInfo(" 保存用户异常:".$e->getMessage());
        }
        return $userid;
    }
    /**
     * Register an authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($waccount)
    {
        $userinf=$this->VailUser($waccount);
        if($userinf!=null)
        {
            if($userinf->getNickname()=="")
            {
                $this->setLogger("weixin");
                $this->getLogger()->logInfo("用户存在----没有昵称:".$userinf->getId());
                $weixinservice = weixinapi::getInstance();
                $userinfotemp = $weixinservice->getUserinfo($userinf->getAccount());
                $userinf->setNickname($userinfotemp->getNickname());
                $userinf->save();
            }
            $this->session->set('auth_weixin', array(
                'wuserid' =>$userinf->getId(),
                'waccount' =>$waccount
            ));
            $this->persistent->wuserid=$userinf->getId();
        }
        else
        {
          $userid=$this-> SaveUserInfo($waccount);
            $this->session->set('auth_weixin', array(
                'wuserid' =>$userid,
                'waccount' =>$waccount
            ));
            $this->persistent->wuserid=$userid;
        }

        $this->persistent->waccount=$waccount;
    }
    private  function  SetNewUserToRedis($userinfo,$userbalance)
    {
        $redis=CacheFactory::createCache("Redis");
        $userinfobase= array();
        $userinfobase["city"] =$userinfo->getCity();
        $userinfobase["account"] =$userinfo->getAccount();
        $userinfobase["province"] =$userinfo->getProvince();
        $userinfobase["password"] =$userinfo->getPassword();
        $userinfobase["name"] =$userinfo->getName();
        $userinfobase["addtime"] =$userinfo->getAddtime();
        $userinfobase["status"] =$userinfo->getStatus();
        $userinfobase["birthday"] =$userinfo->getBirthday();
        $userinfobase["job"] =$userinfo->getJob();
        $userinfobase["phone"] =$userinfo-> getPhone();
        $userinfobase["sex"] =$userinfo->getSex();
        $userinfobase["nickname"] =$userinfo-> getNickname();
        $userinfobase["headerurl"] =$userinfo->getHeaderurl();
        $userinfobase["payaccount"] =$userinfo->getPayaccount();
        $userinfobase["payname"] =$userinfo->getPayname();
        $userinfobase["paytype"] =$userinfo->getPaytype();
        $userinfobase["Presenter"] =$userinfo->getPresenter();
        $redis->setHArrayValue($userinfo-> getId(),$userinfobase);
        $this->getLogger()->logInfo("设置用户基本信息成功:".$userinfo-> getId());

        $userinfobalance= array();
        $userinfobalance["currentbalance"] =$userbalance->getCurrentbalance();
        $userinfobalance["sendincome"] =$userbalance->getSendincome();
        $userinfobalance["taskincome"] =$userbalance->getTaskincome();
        $userinfobalance["paybalance"] =$userbalance->getPaybalance();
        $userinfobalance["totalincome"] =$userbalance->getTotalincome();
        $userinfobalance["modifiedtime"] =$userbalance->getModifiedtime();
        $userinfobalance["addtime"] =$userbalance->getAddtime();
        $redis->setHArrayValue("user_balance_".$userinfo-> getId(),$userinfobalance);
        $this->getLogger()->logInfo("设置用户余额信息成功:".$userinfo-> getId());
    }
    private function _registerSessionEx($waccount,$fiendId)
    {
        $userinf=$this->VailUser($waccount);
        $this->setLogger("weixin");
        if($userinf!=null)
        {

            $this->getLogger()->logInfo("没有收到徒弟一邀请者ID".$fiendId."----用户ID:".$userinf->getId());
            if($userinf->getNickname()=="")
            {
                $this->getLogger()->logInfo("用户存在----没有昵称:".$userinf->getId());
                $weixinservice = weixinapi::getInstance();
                $userinfotemp = $weixinservice->getUserinfo($userinf->getAccount());
                $userinf->setNickname($userinfotemp->getNickname());
                $userinf->save();
            }
            $this->session->set('auth_weixin', array(
                'wuserid' =>$userinf->getId(),
                'waccount' =>$waccount
            ));
            $this->persistent->wuserid=$userinf->getId();
        }
        else
        {

            $userid=$this-> SaveUserInfoEx($waccount,$fiendId);
            $this->getLogger()->logInfo("收到徒弟一邀请者ID".$fiendId."----用户ID:".$userid);
            $this->session->set('auth_weixin', array(
                'wuserid' =>$userid,
                'waccount' =>$waccount
            ));
            $this->persistent->wuserid=$userid;
        }

        $this->persistent->waccount=$waccount;
    }
    private  function  VailUser($account)
    {
        $userinfo = Userinfo::findFirstByAccount($account);
        if($userinfo)
            return $userinfo;
        return null;
    }
    private  function  VailSeesion()
    {
       // $this->session->remove('auth_weixin');

        $this->setLogger("weixin");
        $this->getLogger()->logInfo("验证用户----:");
        if ($this->session->has("auth_weixin")) {
            $this->getLogger()->logInfo("用户存在----:");
            $auth = $this->session->get('auth_weixin');
            if($auth['wuserid']==0&&$auth['waccount']==0)
            {
                $openID=$this->GetUserOpenId();
                $this->getLogger()->logInfo("邀请者ID--AAAA:".$auth['wfriend']);
                $this->_registerSessionEx($openID,$auth['wfriend']);
                $redis=CacheFactory::createCache("Redis");
                if($redis->IsExist("user_frinedcount_".$auth['wfriend'])==false)
                {
                    $friendcount=(int)$redis->getValue("user_frinedcount_".$auth['wfriend']);
                    $redis->setValue("user_frinedcount_".$auth['wfriend'],$friendcount +1);
                }
                else
                {
                    $redis->setValue("user_frinedcount_".$auth['wfriend'], 1);
                }
            }
            else
            {
                $redis=CacheFactory::createCache("Redis");
                if($redis->IsExist($auth['wuserid']))
                {
                    if ($redis->GetHValue($auth['wuserid'],"nickname") == "") {
                        $this->setLogger("weixin");
                        $this->getLogger()->logInfo("用户存在----没有昵称:" .$auth['wuserid']);
                        $weixinservice = weixinapi::getInstance();
                        $userinfotemp = $weixinservice->getUserinfo($redis->GetHValue($auth['wuserid'],"account"));
						if($userinfotemp)
								{
                        $userinfo = Userinfo::findFirstByID($auth['wuserid']);
                        if ($userinfo != null)
                        {
                            $userinfo->setNickname($userinfotemp->getNickname());
                            $userinfo->save();
                        }
								}
                    }
					 if($redis->GetHValue($auth['wuserid'],"headerurl")!= "")
                    {
                        try
                        {
                            $this->getLogger()->logInfo("redis用户存在----查看用户头像是否不显示:" . $auth['wuserid']);
                            if(!($this->GetUserHeaderImageSize($redis->GetHValue($auth['wuserid'],"headerurl"),$auth['wuserid'])))
                            {
                                $this->getLogger()->logInfo("redis用户存在----跟换头像:" .$auth['wuserid']);
                                $weixinservice = weixinapi::getInstance();
                                $userinfotemp = $weixinservice->getUserinfo($redis->GetHValue($auth['wuserid'],"account"));
								if($userinfotemp)
								{
									  $this->getLogger()->logInfo("redis用户存在----获得新头像:" .$auth['wuserid']);
                                $userinfo = Userinfo::findFirstByID($auth['wuserid']);
                                if ($userinfo != null)
                                {
                                    $userinfo->setHeaderurl($userinfotemp->getHeaderurl());
                                    $userinfo->save();
									$redis->setHValue($auth['wuserid'],"headerurl",$userinfotemp->getHeaderurl());
                                }
								}

                            }
                        }catch (exception $e)
                        {
                            $this->getLogger()->logInfo("redis用户存在----跟换头像异常:" . $userinfo->getId()."---".$e->getMessage());
                        }
                    }
                    $this->persistent->wuserid = $auth['wuserid'];
                    $this->persistent->waccount = $auth['waccount'];
                }
                else
                {
                    $userinfo = Userinfo::findFirstByID($auth['wuserid']);
                    if ($userinfo != null) {
                        if ($userinfo->getNickname() == "") {
                            $this->setLogger("weixin");
                            $this->getLogger()->logInfo("用户存在----没有昵称:" . $userinfo->getId());
                            $weixinservice = weixinapi::getInstance();
                            $userinfotemp = $weixinservice->getUserinfo($userinfo->getAccount());
							if($userinfotemp)
								{
                            $userinfo->setNickname($userinfotemp->getNickname());
                            $userinfo->save();
								}
                        }
						 $this->checkUserHeader($userinfo);
                        $this->persistent->wuserid = $auth['wuserid'];
                        $this->persistent->waccount = $auth['waccount'];
                    } else {
                        $openID = $this->GetUserOpenId();
                        $this->getLogger()->logInfo("邀请者ID--BBBB:" . $auth['wfriend']);
                        $this->_registerSessionEx($openID, $auth['wfriend']);
                        $redis = CacheFactory::createCache("Redis");
                        if ($redis->IsExist("user_frinedcount_" . $auth['wfriend']) == false) {
                            $friendcount = (int)$redis->getValue("user_frinedcount_" . $auth['wfriend']);
                            $redis->setValue("user_frinedcount_" . $auth['wfriend'], $friendcount + 1);
                        } else {
                            $redis->setValue("user_frinedcount_" . $auth['wfriend'], 1);
                        }
                    }
                }
            }
        }
        else
        {
            $openID=$this->GetUserOpenId();
            $this->getLogger()->logInfo("用户不存在---:");
            $this->_registerSession($openID);
       }

    }
	 private  function checkUserHeader($userinfo)
    {
        if($userinfo->getHeaderurl()!=""||$userinfo->getHeaderurl()!=null)
        {
            try
            {
                $this->getLogger()->logInfo("用户存在----查看用户头像是否不显示:" . $userinfo->getId());
                if(!($this->GetUserHeaderImageSize($userinfo->getHeaderurl(), $userinfo->getId())))
                {
                    $this->setLogger("weixin");
                    $this->getLogger()->logInfo("用户存在----跟换头像:" . $userinfo->getId());
                    $weixinservice = weixinapi::getInstance();
                    $userinfotemp = $weixinservice->getUserinfo($userinfo->getAccount());
					if($userinfotemp)
								{
                    $userinfo->setHeaderurl($userinfotemp->getHeaderurl());
                    $userinfo->save();
								}
                }
            }catch (exception $e)
            {
                $this->getLogger()->logInfo("用户存在----跟换头像异常:" . $userinfo->getId()."---".$e->getMessage());
            }
        }
    }
    private  function  GetUserHeaderImageSize($imagurl,$userid)
    {
        $img_info = getimagesize($imagurl);
        $this->getLogger()->logInfo("用户存在----".$userid."--跟换头像--大小:" .$img_info[0]);
        if($img_info[0]>120)
        {
            return true;
        }
        return false;
    }
    private  function  GetUserOpenId()
    {
        $this->setLogger("weixin");
        $code =$this->request->getQuery("code");
        $this->getLogger()->logInfo(" 微信code:".$code);
        $weixinservice=weixinapi::getInstance();

        $openid=$weixinservice->GetUserOpenId($code);
        $this->getLogger()->logInfo(" 微信openid:".$openid);
        return $openid;
    }
    /**
     * Index action
     */
    public function indexAction()
    {
       $this->DriverType();
        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;

    }
    public function applyAction()
    {

        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;
    }
    public function incomeAction()
    {
        $this->DriverType();
        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;
    }
    public function investAction()
    {

        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;
    }
    public function invitaAction()
    {
        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;

    }
    public  function  otherpriceAction()
    {
        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;
    }
    public function myInfoAction()
    {
        $this->DriverType();
        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;

    }
    public function rankingAction()
    {
        $this->DriverType();
       $this-> VailSeesion();
       $this->view->wuserid= $this->persistent->wuserid;

    }
    public function taskAction()
    {

       $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;
    }
	    public function beginTaskAction()
    {
        $this->setLogger("weixin");

        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("开始任务错误：不是post提交");
            //输出数据
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'开始任务失败',
                'code' => 200
            );
        }
        else
        {


            $redis=CacheFactory::createCache("Redis");
            $istaskchange=false;
            $isredis=false;
            $changnum=0;
            $this->getLogger()->logInfo("开始任务:".$this->persistent->wuserid."---".$this->request->getPost("DtaskID"));
            if($redis->IsExist("taskinfo_taskchange_".$this->request->getPost("DtaskID")))
            {
                $istaskchange=true;

                $isredis=true;
                $this->getLogger()->logInfo("redis 任务存在---:".$this->persistent->wuserid);
            }
            else
            {
                $this->getLogger()->logInfo("mysql 任务存在---:".$this->persistent->wuserid);
                $taskChange =TaskChange::findFirstByTaskId($this->request->getPost("DtaskID"));
                if($taskChange)
                {
                    $istaskchange=true;
                    $changnum  =$taskChange->getCurrentcount();
                }
            }
            if($istaskchange)
            {
                $isusertry=false;

                if($redis->IsExist("user_userzhanggui_".$this->persistent->wuserid))
                {

                    if($redis->IsExist("usrtrying_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
                    {
                        $isusertry=true;
                    }
                }
                else
                {
                    $sqlcx="select * from Usertrying where userid=".$this->persistent->wuserid." and status=1 and taskid=".$this->request->getPost("DtaskID");
                    $query = $this->modelsManager->createQuery($sqlcx);
                    $usertryingList = $query->execute();
                    if(count($usertryingList)>0)
                    {
                        $isusertry=true;
                    }
                }

                if(!$isusertry)
                {
                    if($isredis)
                    {
                        $changnum = $redis->getDecrValue("current_taskinfo_" . $this->request->getPost("DtaskID"));
                    }
                    else
                    {
                        $changnum=$changnum-1;
                    }
                    $this->getLogger()->logInfo("任务存在---:" . $this->persistent->wuserid);
                    if ($changnum+1 > 0)
                    {

                        $issucess=false;
                        if ($isredis)
                        {

                             $taskcount = (int)$redis->GetHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount");

                             $sqlcxupdate = "update Taskchange set currentcount=" . ($taskcount - 1) . ", modifiedtime=" . strtotime(date("Y-m-d H:i:s")) . " where taskId=" . $this->request->getPost("DtaskID");
                             $query = $this->modelsManager->createQuery($sqlcxupdate);
                             $result = $query->execute();
                            if ($result->success() == false)
                            {
                                $issucess=false;
                                $changnum=$redis->getINCRValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                            }
                            else
                            {
                                $taskappid = $redis->GetHValue("taskinfo_" . $this->request->getPost("DtaskID"), "taskappid");
                                $appEname = $redis->GetHValue("appinfo_" . $taskappid, "enname");
                                $appstoreId= $redis->GetHValue("appinfo_" . $taskappid, "appstoreid");
                                $adid= $redis->GetHValue("appinfo_" . $taskappid, "adid");
                                $issucess=true;
                              //  $changnum=$taskcount - 1;
                              //  $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $taskcount - 1);
                            }
                        }
                        else
                        {
                            if($taskChange&&$taskChange->getCurrentcount()>0) {
                                $taskChange->setCurrentcount($taskChange->getCurrentcount() - 1);
                                $taskChange->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                if ($taskChange->save())
                                {
                                    $this->getLogger()->logInfo("mysql 更新任务数" . $this->request->getPost("DtaskID"));
                                    $task = Taskinfo::findFirstByID($this->request->getPost("DtaskID"));
                                    $appinfos = Appinfos::findFirstByID($task->getAppid());
                                    if ($appinfos) {
                                        $appEname = $appinfos->getEnname();
                                        $appstoreId=  $appinfos->getAppid();
                                        $adid=$appinfos->getAdid();
                                    }
                                    $issucess=true;
                                   // $changnum=$taskChange->getCurrentcount();
                                }
                                else
                                {
                                    $changnum  =$changnum+1;
                                }
                            }


                        }
                        if($issucess)
                        {
                                $this->getLogger()->logInfo("redis 更新任务数" . $this->request->getPost("DtaskID"));
                                $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $changnum);
                                $usertrying = new  Usertrying();
                                $usertrying->setUserid($this->persistent->wuserid);
                                $usertrying->setBegintime(strtotime(date("Y-m-d H:i:s")));
                                $usertrying->setValidate(0);
                                $usertrying->setTaskid($this->request->getPost("DtaskID"));
                                $usertrying->setStatus(1);
                                if ($usertrying->save())
                                {
                                    $userinfotrying = array();
                                    $userinfotrying["usertryingtaskid"] = $this->request->getPost("DtaskID");
                                    $userinfotrying["begintime"] = $usertrying->getBegintime();
                                    $userinfotrying["endtime"] = $usertrying->getEndtime();
                                    $userinfotrying["status"] = $usertrying->getStatus();
                                    $userinfotrying["validate"] = $usertrying->getValidate();
                                    $userinfotrying["remark"] = $usertrying->getRemark();
                                    $userinfotryingList["usrtrying_"] = $usertrying->getTaskid();
                                    $this->getLogger()->logInfo("redis 用户开始试玩" . $this->persistent->wuserid . "--" . $this->request->getPost("DtaskID"));
                                    $redis->setHArrayValue("usrtrying_" . $this->persistent->wuserid . "_" . $usertrying->getTaskid(), $userinfotrying);
                                    $redis->setHValue("usrtrying_online", $this->persistent->wuserid . "_" . $usertrying->getTaskid(), $usertrying->getTaskid());
                                    $redis->setHValue("usrtrying_online_time", $this->persistent->wuserid . "_" . $usertrying->getTaskid(), $usertrying->getBegintime());



                                    /*
                                    $sqlcx="select * from Userappinfo where userId=".$this->persistent->wuserid." and appname='".$appinfos->getEnname()."'";
                                    $query = $this->modelsManager->createQuery($sqlcx);
                                    $userappinfoList = $query->execute();
                                    if(count($userappinfoList)>0)
                                    {
                                        $userappinfo = $userappinfoList[0];
                                        $userappinfo->setOpentime(strtotime(date("Y-m-d H:i:s")));
                                        $userappinfo->save();
                                    }
                                    */
                                    try {
                                        $sqlcx = "delete  from Userappinfo where userId=" . $this->persistent->wuserid . " and appname='" . $appEname . "'";
                                        $query = $this->modelsManager->createQuery($sqlcx);
                                        $userappinfodelete = $query->execute();

                                        $redis->DeleteValue("user_appinfo_" . $this->persistent->wuserid . "_" . $appEname);


                                    } catch (exception $e) {
                                        $this->getLogger()->logInfo("删除用户应用异常" . $e->getMessage());
                                    }
                                    try
                                    {

                                        if((int)$redis->GetHValue("taskinfo_".$this->request->getPost("DtaskID"),"isinterface")==1)
                                        {
                                            $this->getLogger()->logInfo("对接任务接口--");
                                            $taskinterfaceController = new TaskinterfaceController();
                                            $taskinterfaceController->tocallbackAction($this->persistent->wuserid,$this->request->getPost("DtaskID"),$appstoreId,$adid);
                                        }

                                    }catch (exception $e)
                                    {

                                    }
                                    //输出数据
                                    $output = array(
                                        'data' => array(
                                            'success' => true,
                                            'msg' => ''
                                        ),
                                        'success' => true,
                                        'msg' => '开始任务成功',
                                        'code' => 200
                                    );
                                }
                                else
                                {

                                    if ($isredis)
                                    {

                                        $taskcount = (int)$redis->GetHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount");

                                        $sqlcxupdate = "update Taskchange set currentcount=" . ($taskcount +1) . ", modifiedtime=" . strtotime(date("Y-m-d H:i:s")) . " where taskId=" . $this->request->getPost("DtaskID");
                                        $query = $this->modelsManager->createQuery($sqlcxupdate);
                                        $result = $query->execute();
                                        if ($result->success() == false)
                                        {
                                            $issucess=false;
                                        }
                                        else
                                        {

                                            $issucess=true;
                                            $changnum=$taskcount+1;
                                            //  $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $taskcount - 1);
                                        }
                                    }
                                    else
                                    {
                                        if($taskChange&&$taskChange->getCurrentcount()>0) {
                                            $taskChange->setCurrentcount($taskChange->getCurrentcount() + 1);
                                            $taskChange->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                            if ($taskChange->save())
                                            {
                                                $this->getLogger()->logInfo("mysql 更新任务数" . $this->request->getPost("DtaskID"));
                                                $issucess=true;
                                                $changnum=$taskChange->getCurrentcount();
                                            }
                                        }


                                    }
                                    if($issucess)
                                    {
                                        $changnum=$redis->getINCRValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                        $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $changnum);
                                    }
                                    //输出数据
                                    $output = array(
                                        'data' => array(
                                            'success' => true,
                                            'msg' => ''
                                        ),
                                        'success' => false,
                                        'msg' => '开始任务失败',
                                        'code' => 200
                                    );
                                }

                        }
                        else
                        {
                            
                            //输出数据
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'msg' => ''
                                ),
                                'success' => false,
                                'msg' => '开始任务失败',
                                'code' => 200
                            );
                        }
                    } else {

                        if($isredis)
                        {
                            $changnum=$redis->getINCRValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                        }
                        //输出数据
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '亲,你来晚一步,份额被抢光了',
                            'code' => 200
                        );
                    }
                }
                else
                {
                    //输出数据
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg'=>'任务正在进行中',
                        'code' => 200
                    );
                }
            }
            else
            {


                //输出数据
                $output = array(
                    'data' => array(
                        'success' => true,
                        'msg' => ''
                    ),
                    'success' => false,
                    'msg'=>'开始任务失败',
                    'code' => 200
                );
            }

        }
        $this->getLogger()->logInfo( $this->request->getPost("DtaskID"));
        $this->getLogger()->logInfo(json_encode($output));
        //exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    private  function  setCallBackUserinfoToRedis($redis,$userid,$taskid)
    {
        $userinfValidate= array();
        $userinfValidate["taskIDFA"] ="IDFA";
        $userinfValidate["usestatus"] = 1;
        $userinfValidate["createtime"] = strtotime(date("Y-m-d H:i:s"));
        $userinfValidate["taskId"] = $taskid;
        $userinfValidate["callbackstatus"] = 1;
        $redis->setHArrayValue("validatetask_" . "IDFA", $userinfValidate);
    }
    private  function  GetTrying($taskid)
    {
        $usertryingList=Usertrying::query()
            ->where("taskid = :taskid:")
            ->andWhere(" status=1")
            ->andWhere(" userid=:userid:")
            ->bind(array("taskid" =>$taskid,"userid"=>$this->persistent->wuserid))
            ->execute();
       return $usertryingList;
    }
    private  function  GetTryed($taskid)
    {
        $usertrygamesList=Usertrygames::query()
            ->where("taskId = :taskid:")
            ->andWhere(" status=1")
            ->andWhere(" userId=:userid:")
            ->bind(array("taskid" =>$taskid,"userid"=>$this->persistent->wuserid))
            ->execute();
        return $usertrygamesList;
    }
    //次数限制
    private  function  GetFanXianCount($childuserid,$friendsId)
    {
        $userfriendsList=Userfriends::query()
            ->Where(" status=1")
            ->andWhere(" userId= :userid:")
            ->andWhere(" friendsId= :friendsId:")
            ->bind(array("userid"=>$childuserid,"friendsId"=>$friendsId))
            ->execute();
        return count($userfriendsList);
    }
    //完成任务给师傅，师爷的贡献(三级，20%，0.3%)
    private  function  discipleCount($taskID,$price,$appname,$redis)
    {
        $userinviteList = Userinvite::query()
            ->Where(" status=1")
            ->andWhere(" userId= :userid:")
            ->bind(array("userid"=>$this->persistent->wuserid))
            ->execute();
        foreach($userinviteList as $userinvite)
        {
            $this->getLogger()->logInfo("获得师傅：".$userinvite->getFriendsId());

            if($this->GetFanXianCount($userinvite->getFriendsId(),$this->persistent->wuserid)<10)
            {
                $userfriends = new Userfriends();
                $userfriends->setAddtime(strtotime(date("Y-m-d H:i:s")));
                $userfriends->setPrice($price * 0.2);
                $userfriends->setFriendsId($this->persistent->wuserid);
                $userfriends->setUserId($userinvite->getFriendsId());
                $userfriends->setStatus(1);
                $userfriends->setContent("徒弟完成任务收入");
                $userfriends->setTaskId($taskID);
                if($userfriends->save())
                {

                    $userbalance = Userbalance::findFirstByUserid($userinvite->getFriendsId());
                    if($userbalance) {

                        $userbalance->setCurrentbalance($userbalance->getCurrentbalance() + $price * 0.2);
                        $userbalance->setTotalincome($userbalance->getTotalincome() + $price * 0.2);
                        $userbalance->setSendincome($userbalance->getSendincome() + $price * 0.2);
                        $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                        if ($userbalance->save())
                        {
                            $redis->setHValue("user_balance_" . $userinvite->getFriendsId(), "currentbalance", $userbalance->getCurrentbalance());
                            if ($redis->IsExist("user_todaymoney_" . $userinvite->getFriendsId()) == false) {
                                $todaymoney = (double)($redis->getValue("user_todaymoney_" . $userinvite->getFriendsId()));
                                $redis->setValue("user_todaymoney_" . $userinvite->getFriendsId(), $todaymoney + $price * 0.2);
                            } else {
                                $redis->setValue("user_todaymoney_" . $userinvite->getFriendsId(), $price * 0.2);
                            }
                            $userdivertoken = $redis->GetHValue("userdivertoken_" . $userinvite->getFriendsId(), "diverToken");
                            $userinfo = Userinfo::findFirstByID($this->persistent->wuserid);
                            if ($userinfo) {
                                $this->sendMsg("奖励", "恭喜您获得徒弟 " . $userinfo->getNickname() . " 任务 \"" . $appname . "\" 贡献" . $price * 0.2 . "元", "2", $userdivertoken);
                                $this->getLogger()->logInfo("奖励--恭喜" . $this->persistent->wuserid . "获得徒弟 " . $userinfo->getNickname() . " 任务 \"" . $appname . "\" 贡献" . $price * 0.2 . "元");
                            }
                        }
                        else {
                            foreach ($userbalance->getMessages() as $message) {
                                $this->getLogger()->Error("用户:" . $userinvite->getFriendsId() . "--获得徒弟:" . $this->persistent->wuserid  . "奖励- " . $price * 0.2 . " 添加到余额失败 taskid:" . $taskID . "---" . $message->getMessage());

                            }
                        }
                    }
                    else
                    {
                        $this->getLogger()->Error("用户:" . $userinvite->getFriendsId() . "--获得徒弟:" . $this->persistent->wuserid . "奖励- " . $price * 0.2 . " 添加到余额失败 taskid:" . $taskID);
                    }
                }
                else
                {
                    foreach ($userfriends->getMessages() as $message)
                    {
                        $this->getLogger()->Error("用户:".$userinvite->getFriendsId()."--获得徒弟:".$this->persistent->wuserid."奖励- ".$price * 0.2." 失败 taskid:".$taskID."---".$message->getMessage());

                    }
                }
                /*
                $userinviteChildList = Userinvite::query()
                    ->Where(" status=1")
                    ->andWhere(" userId= :userid:")
                    ->bind(array("userid" => $userinvite->getFriendsId()))
                    ->execute();
                foreach ($userinviteChildList as $userinviteChild) {
                    $this->getLogger()->logInfo("获得师傅师傅：" . $userinviteChild->getFriendsId());
                    if($this->GetFanXianCount($userinviteChild->getFriendsId(),$userinvite->getFriendsId())<=10) {
                        $userfriends = new Userfriends();
                        $userfriends->setAddtime(strtotime(date("Y-m-d H:i:s")));
                        $userfriends->setPrice($price * 0.003);
                        $userfriends->setFriendsId($userinvite->getFriendsId());
                        $userfriends->setUserId($userinviteChild->getFriendsId());
                        $userfriends->setStatus(1);
                        $userfriends->setContent("徒孙完成任务收入");
                        $userfriends->setTaskId($taskID);
                        $userfriends->save();
                        $userbalancechild = Userbalance::findFirstByUserid($userinviteChild->getFriendsId());
                        $userbalancechild->setCurrentbalance($userbalancechild->getCurrentbalance() + $price * 0.003);
                        $userbalancechild->setTotalincome($userbalancechild->getTotalincome() + $price * 0.003);
                        $userbalancechild->setSendincome($userbalancechild->getSendincome() + $price * 0.003);
                        $userbalancechild->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                        $userbalancechild->save();
                    }
                }
                */
            }
        }
    }
    private  function  setUserTryGame($taskid,$userId,$price,$appid,$appname,$status,$validate,$redis,$isredis,$usertrying)
    {
        $usertrygames = new Usertrygames();
        $usertrygames->setStatus($status);
        $usertrygames->setValidate($validate);
        $usertrygames->setTaskid($taskid);
        $usertrygames->setUserid($userId);

        $usertrygames->setPrice($price);
        $usertrygames->setGametime(strtotime(date("Y-m-d H:i:s")));
        $usertrygames->setAppId($appid);
        if ($usertrygames->save())
        {
            $issucess = false;
            if ($isredis) {
                $sqlcxtrying = "update Usertrying set status=2, endtime=" . strtotime(date("Y-m-d H:i:s")) . " where userid=" . $userId. " and status=1 and taskid=" . $this->request->getPost("DtaskID");
                $query = $this->modelsManager->createQuery($sqlcxtrying);
                $resulttrying = $query->execute();
                if ($resulttrying->success() == false) {
                    foreach ($resulttrying->getMessages() as $message) {
                        $this->getLogger()->Error("用户:" .$userId . "--更新正在试玩列表status=2失败 taskid:" . $taskid . "---" . $message->getMessage());

                    }
                } else {
                    $issucess = true;
                    $redis->DeleteValue("usrtrying_" . $userId . "_" . $taskid);
                }

            } else {

                //输出数据
                $usertrying->setStatus(2);
                $usertrying->setEndtime(strtotime(date("Y-m-d H:i:s")));
                if ($usertrying->save()) {
                    $issucess = true;
                    $redis->DeleteValue("usrtrying_" . $userId . "_" . $taskid);
                } else {
                    foreach ($usertrying->getMessages() as $message) {
                        $this->getLogger()->Error("用户:" . $userId . "--更新正在试玩列表status=2失败 taskid:" .$taskid . "---" . $message->getMessage());

                    }
                }

            }
            if ($issucess) {
                $redis->Hdel("usrtrying_online_time", $userId . "_" .$taskid);
                $redis->Hdel("usrtrying_online", $userId. "_" .$taskid);

                $this->getLogger()->logInfo("添加到已完成列表:" . $userId . "---" . $taskid);
                $userinfotrygame = array();
                $userinfotrygame["trygametaskid"] =$taskid;
                $userinfotrygame["price"] = $price;
                $userinfotrygame["gametime"] = strtotime(date("Y-m-d H:i:s"));
                $userinfotrygame["status"] = $status;
                $userinfotrygame["trygameappId"] = $appid;
                $redis->setHArrayValue("usertrygame_" . $userId. "_" .$taskid, $userinfotrygame);
                if($status==1)
                {
                    $userbalance = Userbalance::findFirstByUserid($userId);
                    if ($userbalance) {

                        $this->getLogger()->logInfo("设置用户余额start:" . $userId . "---" . $taskid);
                        $userbalance->setCurrentbalance($userbalance->getCurrentbalance() + $price);
                        $userbalance->setTotalincome($userbalance->getTotalincome() + $price);
                        $userbalance->setTaskincome($userbalance->getTaskincome() + $price);
                        $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                        if ($userbalance->save()) {
                            $redis->setHValue("user_balance_" . $userId, "currentbalance", $userbalance->getCurrentbalance());
                            if ($redis->IsExist("user_todaymoney_" . $userId) == false) {
                                $todaymoney = (double)($redis->getValue("user_todaymoney_" . $userId));
                                $redis->setValue("user_todaymoney_" . $userId, $todaymoney + $price);
                            } else {
                                $redis->setValue("user_todaymoney_" . $userId, $price);
                            }
                            $this->getLogger()->logInfo("设置用户余额end:" . $userId . "---" . $taskid);

                            $output = array(
                                'data' => array(
                                    'success' => false,
                                    'msg' => ''
                                ),
                                'success' => true,
                                'msg' => '恭喜获得任务奖励',
                                'code' => 202
                            );
                            $msgCommon = new MsgCommon();
                            $msgCommon->sendMsg("奖励", "恭喜你获得任务 " . $appname . " 完成奖励" . $price . "元", "2", $userId);
                            $this->getLogger()->logInfo("奖励", "恭喜你获得" . $appname . "任务奖励" . $price . "元---" . $userId);
                            $this->discipleCount($taskid, $price, $appname, $redis);
                        } else {
                            foreach ($userbalance->getMessages() as $message) {
                                $this->getLogger()->Error("用户:" . $userId . "--获得奖励任务- " . (double)$price . " 添加到余额失败 taskid:" . $taskid . "---" . $message->getMessage());
                            }
                        }
                    } else {
                        $this->getLogger()->Error("用户:" . $userId . "--获得奖励任务- " . (double)$price . " 添加到余额失败 taskid:" . $taskid);
                    }
                }

            } else {
                //输出数据
                $output = array(
                    'data' => array(
                        'success' => false,
                        'msg' => ''
                    ),
                    'success' => false,
                    'msg' => '请按要求完成任务',
                    'code' => 202
                );
            }
        } else {
            //输出数据
            $output = array(
                'data' => array(
                    'success' => false,
                    'msg' => ''
                ),
                'success' => false,
                'msg' => '请按要求完成任务',
                'code' => 202
            );
        }
        return $output;
    }
    public  function  endTaskAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("完成任务错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'完成任务失败',
                'code' => 202
            );
        }
        else
        {
            $redis=CacheFactory::createCache("Redis");
            $this->getLogger()->logInfo( $this->request->getPost("DtaskID"));
            $isredistrying=false;
            $isredis=false;
            if($redis->IsExist("usrtrying_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
            {
                $this->getLogger()->logInfo("redis 获得用户正在玩的列表--".$this->persistent->wuserid);
                $isredistrying=true;
                $isredis=true;
                $taskappid=$redis->GetHValue("taskinfo_".$this->request->getPost("DtaskID"),"taskappid");
                $appEname=trim($redis->GetHValue("appinfo_".$taskappid,"enname"));
                $appname=$redis->GetHValue("appinfo_".$taskappid,"name");
                $taskprice=(int)$redis->GetHValue("taskinfo_".$this->request->getPost("DtaskID"),"price");
                $taksgetTime=(int)$redis->GetHValue("taskinfo_".$this->request->getPost("DtaskID"),"time");
                $taskbegintime=(int)$redis->GetHValue("usrtrying_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID"),"begintime");
                $taskmaxtime=(int)$redis->GetHValue("taskinfo_".$this->request->getPost("DtaskID"),"maxtime");
                $appnameIdentifier=trim($redis->GetHValue("appinfo_".$taskappid,"identifier"));
            }
            else
            {
                $this->getLogger()->logInfo("mysql 获得用户正在玩的列表--".$this->persistent->wuserid);
                $usertryingList=$this->GetTrying( $this->request->getPost("DtaskID"));
                if(count($usertryingList)>0)
                {
                    $isredistrying = true;
                    $taskinfo = Taskinfo::findFirstByID($this->request->getPost("DtaskID"));
                    if($taskinfo)
                    {
                        $taksgetTime=$taskinfo->getTime();
                        $taskprice=$taskinfo->getPrice() ;
                        $taskmaxtime=$taskinfo->getMaxtime();
                        $taskappid=$taskinfo->getAppId();
                    }
                    $appinfos =Appinfos::findFirstByID( $taskinfo->getAppid());
                    if($appinfos) {

                        $appEname = trim($appinfos->getEnname());
                        $appname=$appinfos->getName();
                        $appnameIdentifier=trim($appinfos->getIdentifier());
                    }
                    $taskbegintime= $usertryingList[0]->getBegintime();
                }

            }

            if($isredistrying)
            {

                $this->getLogger()->logInfo("开始完成任务流程");


                if($redis->IsExist("user_appinfo_".$this->persistent->wuserid."_".$appEname))
                {
                    $istryed = true;
                    $appopentime=(int)$redis->GetHValue("user_appinfo_".$this->persistent->wuserid."_".$appEname,"opentime");
                }
                     else
                     {
                       $sqlcx = "select * from Userappinfo where userId=" . $this->persistent->wuserid . " and appname='" . $appEname . "'";
                       $query = $this->modelsManager->createQuery($sqlcx);
                       $userappinfoList = $query->execute();
                       if (count($userappinfoList) > 0) {
                           $istryed = true;
                           $appopentime=$userappinfoList[0]->getOpentime();
                       }
                     }
                      if($istryed)
                      {
                          if ((strtotime(date("Y-m-d H:i:s")) -$appopentime) >= ($taksgetTime * 60))
                          {
                              if ((strtotime(date("Y-m-d H:i:s")) - $appopentime) >=$taskmaxtime * 60)
                              {
                                  $issucess=false;
                                  if($isredis)
                                  {


                                      $sqlcxtrying="update Usertrying set status=0, endtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$this->persistent->wuserid." and taskid=". $this->request->getPost("DtaskID");
                                      $query = $this->modelsManager->createQuery($sqlcxtrying);
                                      $resulttrying = $query->execute();
                                      if ($resulttrying->success() == false)
                                      {
                                          foreach ($resulttrying->getMessages() as $message)
                                          {
                                              $this->getLogger()->Error("用户:".$this->persistent->wuserid."--更新正在试玩列表status=0失败 taskid:".$this->request->getPost("DtaskID")."---".$message->getMessage());

                                          }
                                      }
                                      else
                                      {
                                          $this->getLogger()->logInfo("redis 更新任务数".$this->request->getPost("DtaskID"));
                                         // $taskcount=(int)$redis->GetHValue("taskinfo_taskchange_".$this->request->getPost("DtaskID"),"currentcount");
                                          $changenum= $redis->getINCRValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                          $sqlcxupdate="update Taskchange set currentcount=".($changenum).", modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where taskId=".$this->request->getPost("DtaskID");
                                          $query = $this->modelsManager->createQuery($sqlcxupdate);
                                          $result = $query->execute();
                                          if ($result->success() == false)
                                          {
                                              foreach ($result->getMessages() as $message)
                                              {
                                                  $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--更新任务数失败! taskid:" .$this->request->getPost("DtaskID") . "---" . $message->getMessage());

                                              }
                                              $changenum= $redis->getDecrValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                          }
                                          else
                                          {
                                              $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount",$changenum);

                                          }
                                          $issucess=true;
                                          $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                          $redis->DeleteValue("user_installed_" . $this->persistent->wuserid . "_" .$appnameIdentifier);
                                          $redis->Hdel("usrtrying_online_time",$this->persistent->wuserid."_".$this->request->getPost("DtaskID"));
                                          $redis->Hdel("usrtrying_online",$this->persistent->wuserid."_".$this->request->getPost("DtaskID"));
                                      }

                                  }
                                  else
                                  {
                                      $this->getLogger()->logInfo("mysql 更新任务数".$this->request->getPost("DtaskID"));
                                      $usertryingList[0]->setStatus(0);
                                      $usertryingList[0]->setEndtime(strtotime(date("Y-m-d H:i:s")));
                                      if($usertryingList[0]->save())
                                      {
                                          $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                          $redis->DeleteValue("user_installed_" . $this->persistent->wuserid . "_" . $appnameIdentifier);
                                          $taskChange = TaskChange::findFirstByTaskId($this->request->getPost("DtaskID"));

                                          $taskChange->setCurrentcount($taskChange->getCurrentcount() + 1);
                                          $taskChange->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                          if($taskChange->save())
                                          {
                                              $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $taskChange->getCurrentcount());

                                          }
                                          else
                                          {
                                              foreach ($taskChange->getMessages() as $message) {
                                                  $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--更新任务数失败! taskid:" .$this->request->getPost("DtaskID") . "---" . $message->getMessage());

                                              }
                                          }

                                          $redis->Hdel("usrtrying_online_time", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                          $redis->Hdel("usrtrying_online", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                      }
                                      else
                                      {
                                          foreach ($usertryingList[0]->getMessages() as $message)
                                          {
                                              $this->getLogger()->Error("用户:".$this->persistent->wuserid."--更新正在试玩列表status=0失败 taskid:".$this->request->getPost("DtaskID")."---".$message->getMessage());

                                          }
                                      }
                                  }
                                  try {
                                      $sqlcx = "delete  from Userinstalledapp where userId=" .$this->persistent->wuserid . " and identifier='" .$appnameIdentifier. "'";
                                      $query = $this->modelsManager->createQuery($sqlcx);
                                      $userappinfodelete = $query->execute();
                                  }catch (exception $e)
                                  {
                                      $this->getLogger()->logInfo("删除已经完成任务异常". $e->getMessage());
                                  }
                                  //输出数据
                                  $output = array(
                                      'data' => array(
                                          'success' => true,
                                          'msg' => ''
                                      ),
                                      'success' => false,
                                      'msg' => '任务超时',
                                      'code' => 402
                                  );

                              } else {
                                  $this->getLogger()->logInfo("完成任务:" . $this->request->getPost("DtaskID"));
                                  try {

                                          $this->getLogger()->logInfo("更新正在试玩列表:" . $this->persistent->wuserid . "---" . $this->request->getPost("DtaskID"));
                                         if(!$redis->IsExist("usertrygame_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
                                         {

                                             if((int)$redis->GetHValue("taskinfo_".$this->request->getPost("DtaskID"),"isinterface")==1)
                                             {

                                                 if($redis->IsExist("validatetask_".$this->persistent->wuserid))
                                                 {
                                                     if ((int)$redis->GetHValue("validatetask_" . $this->persistent->wuserid, "validatet") == 1)
                                                         $output   =  $this->setUserTryGame($this->request->getPost("DtaskID"), $this->persistent->wuserid,$taskprice, $taskappid, $appname, 1, 1,$redis,$isredis,$isredis==true?null:$usertryingList[0]);
                                                     else
                                                         $output   =   $this->setUserTryGame($this->request->getPost("DtaskID"), $this->persistent->wuserid, $taskprice, $taskappid, $appname, 0, 0,$redis,$isredis,$isredis==true?null: $usertryingList[0]);
                                                 }
                                             }
                                             else
                                             {
                                                 $output   = $this->setUserTryGame($this->request->getPost("DtaskID"),$this->persistent->wuserid,$taskprice,$taskappid,$appname,1,0,$redis,$isredis, $isredis==true?null:$usertryingList[0]);
                                             }
                                             /*
                                             $usertrygames = new Usertrygames();
                                             $usertrygames->setStatus(1);
                                             $usertrygames->setValidate(0);
                                             $usertrygames->setTaskid($this->request->getPost("DtaskID"));
                                             $usertrygames->setUserid($this->persistent->wuserid);

                                             $usertrygames->setPrice($taskprice);
                                             $usertrygames->setGametime(strtotime(date("Y-m-d H:i:s")));
                                             $usertrygames->setAppId($taskappid);
                                             if ($usertrygames->save())
                                             {
                                                 $issucess = false;
                                                 if ($isredis) {
                                                     $sqlcxtrying = "update Usertrying set status=2, endtime=" . strtotime(date("Y-m-d H:i:s")) . " where userid=" . $this->persistent->wuserid . " and status=1 and taskid=" . $this->request->getPost("DtaskID");
                                                     $query = $this->modelsManager->createQuery($sqlcxtrying);
                                                     $resulttrying = $query->execute();
                                                     if ($resulttrying->success() == false) {
                                                         foreach ($resulttrying->getMessages() as $message) {
                                                             $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--更新正在试玩列表status=2失败 taskid:" . $this->request->getPost("DtaskID") . "---" . $message->getMessage());

                                                         }
                                                     } else {
                                                         $issucess = true;
                                                         $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                                     }

                                                 } else {

                                                     //输出数据
                                                     $usertryingList[0]->setStatus(2);
                                                     $usertryingList[0]->setEndtime(strtotime(date("Y-m-d H:i:s")));
                                                     if ($usertryingList[0]->save()) {
                                                         $issucess = true;
                                                         $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                                     } else {
                                                         foreach ($usertryingList[0]->getMessages() as $message) {
                                                             $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--更新正在试玩列表status=2失败 taskid:" . $this->request->getPost("DtaskID") . "---" . $message->getMessage());

                                                         }
                                                     }

                                                 }
                                                 if ($issucess) {
                                                     $redis->Hdel("usrtrying_online_time", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                                     $redis->Hdel("usrtrying_online", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));

                                                     $this->getLogger()->logInfo("添加到已完成列表:" . $this->persistent->wuserid . "---" . $this->request->getPost("DtaskID"));
                                                     $userinfotrygame = array();
                                                     $userinfotrygame["trygametaskid"] = $this->request->getPost("DtaskID");
                                                     $userinfotrygame["price"] = $taskprice;
                                                     $userinfotrygame["gametime"] = strtotime(date("Y-m-d H:i:s"));
                                                     $userinfotrygame["status"] = 1;
                                                     $userinfotrygame["trygameappId"] = $taskappid;
                                                     $redis->setHArrayValue("usertrygame_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"), $userinfotrygame);
                                                     $userbalance = Userbalance::findFirstByUserid($this->persistent->wuserid);
                                                     if ($userbalance) {

                                                         $this->getLogger()->logInfo("设置用户余额start:" . $this->persistent->wuserid . "---" . $this->request->getPost("DtaskID"));
                                                         $userbalance->setCurrentbalance($userbalance->getCurrentbalance() + $taskprice);
                                                         $userbalance->setTotalincome($userbalance->getTotalincome() + $taskprice);
                                                         $userbalance->setTaskincome($userbalance->getTaskincome() + $taskprice);
                                                         $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                                         if ($userbalance->save()) {
                                                             $redis->setHValue("user_balance_" . $this->persistent->wuserid, "currentbalance", $userbalance->getCurrentbalance());
                                                             if ($redis->IsExist("user_todaymoney_" . $this->persistent->wuserid) == false) {
                                                                 $todaymoney = (double)($this->redis->getValue("user_todaymoney_" . $this->persistent->wuserid));
                                                                 $redis->setValue("user_todaymoney_" . $this->persistent->wuserid, $todaymoney + $taskprice);
                                                             } else {
                                                                 $redis->setValue("user_todaymoney_" . $this->persistent->wuserid, $taskprice);
                                                             }
                                                             $this->getLogger()->logInfo("设置用户余额end:" . $this->persistent->wuserid . "---" . $this->request->getPost("DtaskID"));

                                                             $output = array(
                                                                 'data' => array(
                                                                     'success' => false,
                                                                     'msg' => ''
                                                                 ),
                                                                 'success' => true,
                                                                 'msg' => '恭喜获得任务奖励',
                                                                 'code' => 202
                                                             );
                                                             $msgCommon = new MsgCommon();
                                                             $msgCommon->sendMsg("奖励", "恭喜你获得任务 " . $appname . " 完成奖励" . $taskprice . "元", "2", $this->persistent->wuserid);
                                                             $this->getLogger()->logInfo("奖励", "恭喜你获得" . $appname . "任务奖励" . $taskprice . "元---" . $this->persistent->wuserid);
                                                             $this->discipleCount($this->request->getPost("DtaskID"), $taskprice, $appname, $redis);
                                                         } else {
                                                             foreach ($userbalance->getMessages() as $message) {
                                                                 $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--获得奖励任务- " . (double)$taskprice . " 添加到余额失败 taskid:" . $this->request->getPost("DtaskID") . "---" . $message->getMessage());
                                                             }
                                                         }
                                                     } else {
                                                         $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--获得奖励任务- " . (double)$taskprice . " 添加到余额失败 taskid:" . $this->request->getPost("DtaskID"));
                                                     }

                                                 } else {
                                                     //输出数据
                                                     $output = array(
                                                         'data' => array(
                                                             'success' => false,
                                                             'msg' => ''
                                                         ),
                                                         'success' => false,
                                                         'msg' => '请按要求完成任务',
                                                         'code' => 202
                                                     );
                                                 }
                                             } else {
                                                 //输出数据
                                                 $output = array(
                                                     'data' => array(
                                                         'success' => false,
                                                         'msg' => ''
                                                     ),
                                                     'success' => false,
                                                     'msg' => '请按要求完成任务',
                                                     'code' => 202
                                                 );
                                             }

                                             /*
                                             try {
                                                 $sqlcx = "delete  from Userappinfo where userId=" .  $this->persistent->wuserid . " and appname='" . $appname . "'";
                                                 $query = $this->modelsManager->createQuery($sqlcx);
                                                 $userappinfodelete = $query->execute();
                                             } catch (exception $e) {
                                                 $this->getLogger()->logInfo("删除已经完成任务异常" . $e->getMessage());
                                             }*/
                                         }
                                      else
                                      {
                                          //输出数据
                                          $output = array(
                                              'data' => array(
                                                  'success' => false,
                                                  'msg' => ''
                                              ),
                                              'success' => false,
                                              'msg' => '任务已经完成',
                                              'code' => 202
                                          );
                                      }
                                  } catch (exception $e) {
                                      $output = array(
                                          'data' => array(
                                              'success' => false,
                                              'msg' => ''
                                          ),
                                          'success' => true,
                                          'msg' => '更新完成信息出错',
                                          'code' => 202
                                      );
                                      $this->getLogger()->logInfo("完成任务:" . $this->request->getPost("DtaskID") . " 错误信息：" . $e->getMessage());
                                  }
                              }
                          } else {
                              $this->getLogger()->logInfo("没有按时间要求完成:" . $this->request->getPost("DtaskID"));
                              //输出数据
                              $output = array(
                                  'data' => array(
                                      'success' => false,
                                      'msg' => ''
                                  ),
                                  'success' => false,
                                  'msg' => '请按要求完成任务',
                                  'code' => 202
                              );
                          }
                      }
                      else
                      {
                          if ((strtotime(date("Y-m-d H:i:s")) -$taskbegintime ) >= $taskmaxtime * 60)
                          {

                              $issucess=false;
                              if($isredis)
                              {
                                  $this->getLogger()->logInfo("redis 更新任务数".$this->request->getPost("DtaskID"));
                                 // $taskcount=(int)$redis->GetHValue("taskinfo_taskchange_".$this->request->getPost("DtaskID"),"currentcount");

                                  $changenum= $redis->getINCRValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                  $sqlcxtrying="update Usertrying set status=0, endtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$this->persistent->wuserid." and taskid=". $this->request->getPost("DtaskID");
                                  $query = $this->modelsManager->createQuery($sqlcxtrying);
                                  $resulttrying = $query->execute();
                                  if ($resulttrying->success() == false)
                                  {
                                      foreach ($resulttrying->getMessages() as $message)
                                      {
                                          $this->getLogger()->Error("用户:".$this->persistent->wuserid."--更新正在试玩列表status=0失败 taskid:".$this->request->getPost("DtaskID")."---".$message->getMessage());

                                      }
                                      $changenum= $redis->getDecrValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                  }
                                  else
                                  {

                                      $sqlcxupdate="update Taskchange set currentcount=".($changenum).", modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where taskId=".$this->request->getPost("DtaskID");
                                      $query = $this->modelsManager->createQuery($sqlcxupdate);
                                      $result = $query->execute();
                                      if ($result->success() == false)
                                      {
                                          foreach ($result->getMessages() as $message)
                                          {
                                              $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--更新任务数失败! taskid:" .$this->request->getPost("DtaskID") . "---" . $message->getMessage());

                                          }
                                      }
                                      else
                                      {
                                          $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $changenum);

                                      }
                                      $issucess=true;
                                      $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                      $redis->DeleteValue("user_installed_" . $this->persistent->wuserid . "_" .$appnameIdentifier);
                                  }

                              }
                              else
                              {
                                  $usertryingList[0]->setStatus(0);
                                  $usertryingList[0]->setEndtime(strtotime(date("Y-m-d H:i:s")));
                                 if($usertryingList[0]->save()) {
                                     $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));

                                     $redis->DeleteValue("user_installed_" . $this->persistent->wuserid . "_" . $appnameIdentifier);
                                     $taskChange = TaskChange::findFirstByTaskId($this->request->getPost("DtaskID"));
                                    if($taskChange)
                                    {
                                        $taskChange->setCurrentcount($taskChange->getCurrentcount() + 1);
                                        $taskChange->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                        if ($taskChange->save()) {

                                            $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $taskChange->getCurrentcount());

                                        }
                                        else
                                        {
                                            foreach ($taskChange->getMessages() as $message) {
                                                $this->getLogger()->Error("用户:" . $this->persistent->wuserid . "--更新任务数失败! taskid:" .$this->request->getPost("DtaskID") . "---" . $message->getMessage());

                                            }
                                        }
                                    }
                                     $issucess=true;
                                 }
                                 else
                                 {
                                     foreach ($usertryingList[0]->getMessages() as $message)
                                     {
                                         $this->getLogger()->Error("用户:".$this->persistent->wuserid."--更新正在试玩列表status=0失败 taskid:".$this->request->getPost("DtaskID")."---".$message->getMessage());

                                     }
                                 }
                              }
                              try {
                                  $sqlcx = "delete  from Userinstalledapp where userId=" .$this->persistent->wuserid . " and identifier='" .$appnameIdentifier. "'";
                                  $query = $this->modelsManager->createQuery($sqlcx);
                                  $userappinfodelete = $query->execute();
                              }catch (exception $e)
                              {
                                  $this->getLogger()->logInfo("删除已经完成任务异常". $e->getMessage());
                              }
                              if($issucess)
                              {
                                  $redis->Hdel("usrtrying_online_time", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                  $redis->Hdel("usrtrying_online", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                              }
                              //输出数据
                              $output = array(
                                  'data' => array(
                                      'success' => true,
                                      'msg' => ''
                                  ),
                                  'success' => false,
                                  'msg' => '任务超时',
                                  'code' => 402
                              );
                          } else
                          {
                              $this->getLogger()->logInfo("没有按时间要求完成:" . $this->request->getPost("DtaskID"));
                              //输出数据
                              $output = array(
                                  'data' => array(
                                      'success' => false,
                                      'msg' => ''
                                  ),
                                  'success' => false,
                                  'msg' => '请按要求完成任务',
                                  'code' => 202
                              );
                          }
                      }
            }
            else
            {
                if($redis->IsExist("usertrygame_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
                {

                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg' => '已经完成此任务',
                        'code' => 205
                    );
                }
                else
                {
                    $usertrygamesList = $this->GetTryed($this->request->getPost("DtaskID"));
                    if (count($usertrygamesList) == 0) {


                        $this->getLogger()->logInfo("任务没有进行中:" . $this->request->getPost("DtaskID"));
                        //输出数据
                        $output = array(
                            'data' => array(
                                'success' => false,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '请按要求完成任务',
                            'code' => 202
                        );
                    }
                    else
                    {
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '已经完成此任务',
                            'code' => 205
                        );
                    }
                }
            }
        }
        $this->getLogger()->logInfo( $this->request->getPost("DtaskID"));
        $this->getLogger()->logInfo(json_encode($output));
      //  exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public function  giveupTaskAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("放弃任务错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'放弃任务错误',
                'code' => 404
            );
        }
        else
        {
            $redis=CacheFactory::createCache("Redis");
            $this->getLogger()->logInfo( $this->request->getPost("DtaskID"));
            $isredistrying=false;
            $isredis=false;
            if($redis->IsExist("usrtrying_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
            {
                $isredis=true;
                $isredistrying=true;
            }
            else
            {
                $usertryingList=$this->GetTrying( $this->request->getPost("DtaskID"));
                if(count($usertryingList)>0)
                    $isredistrying=true;
            }
            if($isredistrying)
            {


                    $this->getLogger()->logInfo("开始放弃任务流程");
                $issucess=false;
                if($isredis)
                {
                                  $this->getLogger()->logInfo("redis 更新任务数".$this->request->getPost("DtaskID"));
                                    $changenum= $redis->getINCRValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                //  $taskcount=(int)$redis->GetHValue("taskinfo_taskchange_".$this->request->getPost("DtaskID"),"currentcount");
                                  $sqlcxupdate="update Taskchange set currentcount=".($changenum).", modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where taskId=".$this->request->getPost("DtaskID");
                                  $query = $this->modelsManager->createQuery($sqlcxupdate);
                                  $result = $query->execute();
                                if ($result->success() == false)
                                  {
                                     $issucess=false;
                                      $changenum= $redis->getDecrValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                  }
                                else
                                {


                                    $sqlcxtrying="update Usertrying set status=3, endtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$this->persistent->wuserid."  and status=1 and taskid=". $this->request->getPost("DtaskID");
                                    $query = $this->modelsManager->createQuery($sqlcxtrying);
                                    $resulttrying = $query->execute();
                                    if ($resulttrying->success() == false)
                                    {
                                        $issucess=false;
                                        $changenum= $redis->getDecrValue("current_taskinfo_".$this->request->getPost("DtaskID"));
                                        $sqlcxupdate="update Taskchange set currentcount=".($changenum).", modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where taskId=".$this->request->getPost("DtaskID");
                                        $query = $this->modelsManager->createQuery($sqlcxupdate);
                                        $result = $query->execute();
                                    }
                                    else
                                    {
                                        $issucess=true;

                                        $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $changenum);
                                        $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                    }


                                }

                }
                    else
                    {

                        $this->getLogger()->logInfo("放弃任务 mysql".$this->request->getPost("DtaskID"));
                        $taskChange = TaskChange::findFirstByTaskId($this->request->getPost("DtaskID"));
                        if($taskChange)
                        {
                            $taskChange->setCurrentcount($taskChange->getCurrentcount() + 1);
                            $taskChange->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                            if ($taskChange->save())
                            {


                                $usertryingList[0]->setStatus(3);
                                $usertryingList[0]->setEndtime(strtotime(date("Y-m-d H:i:s")));
                                if($usertryingList[0]->save())
                                {$issucess=true;
                                    $redis->DeleteValue("usrtrying_" . $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                                    $redis->setHValue("taskinfo_taskchange_" . $this->request->getPost("DtaskID"), "currentcount", $taskChange->getCurrentcount());

                                }
                                else
                                {
                                    $taskChange->setCurrentcount($taskChange->getCurrentcount() -1);
                                    $taskChange->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                    $taskChange->save();
                                }
                            }
                        }

                    }
                if( $issucess)
                {
                    $redis->Hdel("usrtrying_online_time", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));
                    $redis->Hdel("usrtrying_online", $this->persistent->wuserid . "_" . $this->request->getPost("DtaskID"));

                    //输出数据
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => true,
                        'msg' => '放弃任务',
                        'code' => 402
                    );
                }
                else
                {
                    //输出数据
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg' => '操作失败',
                        'code' => 404
                    );
                }

                } else {
                    $this->getLogger()->logInfo("任务没有进行中:" . $this->request->getPost("DtaskID"));
                    //输出数据
                    $output = array(
                        'data' => array(
                            'success' => false,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg' => '任务没有进行中',
                        'code' => 404
                    );
                }

        }
        $this->getLogger()->logInfo( $this->request->getPost("DtaskID"));
        $this->getLogger()->logInfo(json_encode($output));
       // exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public  function  tryingAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("检查用户任务错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'检查任务失败',
                'code' => 205
            );
        }
        else
        {
            $redis=CacheFactory::createCache("Redis");
            $isdazhanggui=false;
            if($redis->IsExist("user_userzhanggui_".$this->persistent->wuserid))
            {
                if((int)$redis->getValue("user_userzhanggui_".$this->persistent->wuserid)==1)
                {
                    $isdazhanggui=true;
                }
            }
            else
            {
                $userzhanggui = Userzhanggui::findFirstByUserid($this->persistent->wuserid);
                if($userzhanggui&&$userzhanggui->getStatus()==1)
                {
                    $isdazhanggui=true;
                }
            }
            if($isdazhanggui)
            {
                if($redis->IsExist("usertrygame_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
                {

                         $output = array(
                              'data' => array(
                                 'success' => true,
                                  'msg' => ''
                              ),
                                 'success' => false,
                               'msg' => '已经完成此任务',
                             'code' => 205
                         );
                }
                else
                {
                    $usertrygamesList = $this->GetTryed($this->request->getPost("DtaskID"));
                    if (count($usertrygamesList) == 0) {
                        if($redis->IsExist("usrtrying_".$this->persistent->wuserid."_".$this->request->getPost("DtaskID")))
                        {
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'msg' => ''
                                ),
                                'success' => true,
                                'msg' => '此任务在进行中',
                                'code' => 203
                            );
                        }
                        else
                        {
                            $usertryingList = $this->GetTrying($this->request->getPost("DtaskID"));
                            if (count($usertryingList) > 0) {
                                $output = array(
                                    'data' => array(
                                        'success' => true,
                                        'msg' => ''
                                    ),
                                    'success' => true,
                                    'msg' => '此任务在进行中',
                                    'code' => 203
                                );
                            } else {
                                $output = array(
                                    'data' => array(
                                        'success' => true,
                                        'msg' => ''
                                    ),
                                    'success' => false,
                                    'msg' => '此任务没有进行中',
                                    'code' => 203
                                );
                            }
                        }
                    } else {
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '已经完成此任务',
                            'code' => 205
                        );
                    }
                }
            }
            else
            {
                $output = array(
                    'data' => array(
                        'success' => true,
                        'msg' => ''
                    ),
                    'success' => false,
                    'msg' => '大掌柜不在线',
                    'code' => 205
                );
                $this->getLogger()->logInfo("用户:".$this->persistent->wuserid."大掌柜不在线");
            }
        }
        $this->getLogger()->logInfo( $this->request->getPost("DtaskID"));
        $this->getLogger()->logInfo(json_encode($output));
      //  exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public  function  PayAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("申请提现错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'申请提现错误',
                'code' => 300
            );
        }
        else
        {
            $userinfo = Userinfo::findFirstByID($this->persistent->wuserid);
            if($userinfo) {

                $price=$this->request->getPost("Price");
                $name=$this->request->getPost("Name");
                $account=$this->request->getPost("Account");
                $userbalance =Userbalance::findFirstByUserid($this->persistent->wuserid);
                if($userbalance->getCurrentbalance()>=$price)
                {
                    $userbusiness=new Userbusiness();
                    $userbusiness->setStatus(2);
                    $userbusiness->setAddtime(strtotime(date("Y-m-d H:i:s")));
                    $userbusiness->setPrice($price);
                    $userbusiness->setUserid($this->persistent->wuserid);
                    if($userinfo->getPayaccount()=="") {
                        $this->getLogger()->logInfo("用户提现--保存账户：".$userinfo->getid());
                        $userinfo->setPayaccount($account);
                        $userinfo->setPayname($name);
                        $userinfo->setPaytype(1);
                        $userinfo->save();
                        $userbusiness->setPayaccount($account);
                        $userbusiness->setPayname($name);
                    }
                    else {
                        $userinfo->setPayaccount($account);
                        $userinfo->setPayname($name);
                        $userinfo->setPaytype(1);
                        $userinfo->save();
                        $userbusiness->setPayaccount($account);
                        $userbusiness->setPayname($name);
                    }
                    $userbusiness->setType(1);
                    if ($userbusiness->save()) {

                        $this->getLogger()->logInfo("用户提现--申请提现成功,等待审核：".$userinfo->getid());
                        $userbalance =Userbalance::findFirstByUserid($this->persistent->wuserid);
                        $redis=CacheFactory::createCache("Redis");
                        $redis->setHValue("user_balance_".$userinfo->getid(),"currentbalance",($userbalance->getCurrentbalance() - $price));
                        $userbalance->setCurrentbalance($userbalance->getCurrentbalance() - $price);
                        $userbalance->setPayingincome($userbalance->getPayingincome()+$price);
                        $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));

                        $userbalance->save();


                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => true,
                            'msg' => '申请提现成功,等待审核!',
                            'code' => 300
                        );
                    } else {
                        $this->getLogger()->logInfo("用户提现--申请提现失败：".$userinfo->getid());
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '申请提现失败',
                            'code' => 300
                        );
                    }
                }
                else
                {
                    $this->getLogger()->logInfo("用户提现--大于最大金额：".$userinfo->getid());
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg' => '最大提现金额:'.sprintf("%.2f",$userbalance->getCurrentbalance())."元",
                        'code' => 300
                    );
                }
            }
            else
            {
                $this->getLogger()->logInfo("用户提现--账户不存在");
                $output = array(
                    'data' => array(
                        'success' => true,
                        'msg' => ''
                    ),
                    'success' => false,
                    'msg' => ' 用户不存在',
                    'code' => 300
                );
            }
        }

        $this->getLogger()->logInfo(json_encode($output));
        //exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public  function  myInfoSaveAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("用户提交信息错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'用户提交信息错误',
                'code' => 500
            );

        }
        else
        {
            $phone=$this->request->getPost("Phone");
            $sex=$this->request->getPost("Sex");
            $job=$this->request->getPost("Job");
            $birthady=$this->request->getPost("Birthday");
            $userinfo = Userinfo::findFirstByID($this->persistent->wuserid);
            if($userinfo) {


                $userinfo->setSex($sex);
                $userinfo->setPhone($phone);
                $userinfo->setJob($job);
                $userinfo->setBirthday($birthady);
                if(!$userinfo->save())
                {
                    $this->getLogger()->logInfo("用户更新信息失败:".$userinfo->getId());
                    foreach ($userinfo->getMessages() as $message) {
                        $this->getLogger()->logInfo("更新系统用户失败:" . $message);
                    }
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg'=>'提交用户失败',
                        'code' => 500
                    );
                }
                $redis=CacheFactory::createCache("Redis");
                $userinfoarray=$redis->GetHmValue($userinfo->getId());
                if(count($userinfoarray)>0)
                {
                    $userinfoarray["phone"]=$phone;
                    $userinfoarray["sex"]=$sex;
                    $userinfoarray["job"]=$job;
                    $userinfoarray["birthday"]=$birthady;
                    $redis->DeleteValue($userinfo->getId());
                    $redis->setHArrayValue($userinfo->getId(),$userinfoarray);
                    $this->getLogger()->logInfo("更新用户基本信息redis:".$userinfo->getId());
                }

                $this->getLogger()->logInfo("用户更新信息成功:".$userinfo->getId());
                $output = array(
                    'data' => array(
                        'success' => true,
                        'msg' => ''
                    ),
                    'success' => true,
                    'msg'=>'提交信息成功',
                    'code' => 500
                );
            }
            else
            {
                $this->getLogger()->logInfo("用户不存在!,请刷新");
                $output = array(
                    'data' => array(
                        'success' => true,
                        'msg' => ''
                    ),
                    'success' => false,
                    'msg'=>'用户不存在!,请刷新',
                    'code' => 500
                );
            }
        }
        $this->getLogger()->logInfo(json_encode($output));
      //  exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public  function  daZhangGuiStatusAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("检查大掌柜状态错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'检查大掌柜状态错误',
                'code' => 205
            );
        }
        else
        {

            $redis=CacheFactory::createCache("Redis");
            if($redis->IsExist("user_userzhanggui_".$this->persistent->wuserid))
            {
                $this->getLogger()->logInfo("redis--检查大掌柜状态");

                if($redis->getValue("user_userzhanggui_version".$this->persistent->wuserid)=="1.0.2")
                {


                    if ((int)$redis->getValue("user_userzhanggui_" . $this->persistent->wuserid) == 1) {
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => true,
                            'msg' => '大掌柜在线',
                            'code' => 208
                        );
                        $this->getLogger()->logInfo("用户:" . $this->persistent->wuserid . "大掌柜在线");
                    } else {
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '大掌柜不在线',
                            'code' => 205
                        );
                        $this->getLogger()->logInfo("用户:" . $this->persistent->wuserid . "大掌柜不在线");
                    }
                }
                else
                {
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg' => '下载新版大掌柜',
                        'code' => 400
                    );
                }
            }
            else
            {
                $this->getLogger()->logInfo("mysql--检查大掌柜状态");
                $userzhanggui=Userzhanggui::findFirstByUserid($this->persistent->wuserid);
                if($userzhanggui)
                {
                    if($redis->getValue("user_userzhanggui_version".$this->persistent->wuserid)=="1.0.2")
                    {
                        if ($userzhanggui->getStatus() == 1) {
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'msg' => ''
                                ),
                                'success' => true,
                                'msg' => '大掌柜在线',
                                'code' => 208
                            );
                            $this->getLogger()->logInfo("用户:" . $this->persistent->wuserid . "大掌柜在线");
                        } else {
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'msg' => ''
                                ),
                                'success' => false,
                                'msg' => '大掌柜不在线',
                                'code' => 205
                            );
                            $this->getLogger()->logInfo("用户:" . $this->persistent->wuserid . "大掌柜不在线");
                        }
                    }
                    else
                    {
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'msg' => ''
                            ),
                            'success' => false,
                            'msg' => '下载新版大掌柜',
                            'code' => 400
                        );
                    }
                }
                else
                {
                    $output = array(
                        'data' => array(
                            'success' => true,
                            'msg' => ''
                        ),
                        'success' => false,
                        'msg' => '未激活',
                        'code' => 206
                    );
                    $this->getLogger()->logInfo("用户:".$this->persistent->wuserid."大掌柜未激活");
                }
            }

        }

        $this->getLogger()->logInfo(json_encode($output));
       // exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    private  function  GetRankingToWeek($day)
    {
        $htmlpage = "";
        $sqltaskprice = "select  a.ID,sum(b.price) as p ,b.gametime ,a.nickname,a.headerurl from userinfo a
                 left join usertrygames b on a.id=b.userId
                 where  b.gametime>" . strtotime("-".$day." day") . "  group by b.userId order by p desc limit 20";

        $this->getLogger()->logInfo("获得土豪榜sql--".$sqltaskprice);
        $mySqlHelper=new MySqlHelper();
        $this->getLogger()->logInfo("执行sql--");
        $result=$mySqlHelper->Select($sqltaskprice);
        $H_table = array();
        while($row = mysqli_fetch_array($result))
        {
            $this->getLogger()->logInfo("有试玩记录--");
            if (!array_key_exists($row["ID"], $H_table)) {
                $H_table[$row["ID"]] = $row["p"];
            }

            $sqlfriendprice1 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userfriends  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-".$day." day") . " and b.userId=" . $row["ID"] . "  group by b.userId order by p desc limit 20";
            $resultfriendpriceList1=$mySqlHelper->Select($sqlfriendprice1);
            while($row1 = mysqli_fetch_array($resultfriendpriceList1))
            {
                if (array_key_exists($row1["ID"], $H_table)) {
                    $H_table[$row1["ID"]] = (double)$H_table[$row1["ID"]] + (double)$row1["p"];
                }

            }

            $sqlotherprice1 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userotherprice  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-".$day." day") . " and a.ID=" .  $row["ID"]. "  group by b.userId order by p desc limit 20";
            $resultotherprice=$mySqlHelper->Select($sqlotherprice1);
            $this->getLogger()->logInfo("获得土豪榜sql--".$sqlotherprice1);
            while($row2 = mysqli_fetch_array($resultotherprice))
            {
                if (array_key_exists($row2["ID"], $H_table)) {
                    $H_table[$row2["ID"]] = (double)$H_table[$row2["ID"]] + (double)$row2["p"];
                }
            }




        }

        $sqlfriendprice = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                 left join userfriends  b on a.id=b.userId
                 where  b.Addtime>" . strtotime("-".$day." day") . "  group by b.userId order by p desc limit 20";

        $result2=$mySqlHelper->Select($sqlfriendprice);
        while($row = mysqli_fetch_array($result2))
        {
            if (!array_key_exists($row["ID"], $H_table)) {
                $H_table[$row["ID"]] =$row["p"];


                $sqltaskprice1 = "select  a.ID,sum(b.price) as p ,b.gametime ,a.nickname,a.headerurl from userinfo a
                        left join usertrygames b on a.id=b.userId
                        where  b.gametime>" . strtotime("-".$day." day") . " and a.ID=" .$row["ID"] . "  group by b.userId order by p desc limit 20";
                $resulttaskpriceList1 =$mySqlHelper->Select($sqltaskprice1);
                while($row1 = mysqli_fetch_array($resulttaskpriceList1))
                {
                    if (array_key_exists($row1["ID"], $H_table)) {
                        $H_table[$row1["ID"]] = (double)$H_table[$row1["ID"]] + (double)$row1["p"];
                    }

                }

                $sqlotherprice2 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userotherprice  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-".$day." day") . "  and a.ID=" .$row["ID"] . "  group by b.userId order by p desc limit 20";
                $resultotherpriceList2 =$mySqlHelper->Select($sqlotherprice2);
                while($row2= mysqli_fetch_array($resultotherpriceList2))
                {
                    if (array_key_exists($row2["ID"], $H_table)) {
                        $H_table[$row2["ID"]] = (double)$H_table[$row2["ID"]] + (double)$row2["p"];
                    }

                }
            }


        }

        $sqlotherprice = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                 left join userotherprice  b on a.id=b.userId
                 where  b.Addtime>" . strtotime("-".$day." day") . "  group by b.userId order by p desc limit 20";
        $result3=$mySqlHelper->Select($sqlotherprice);
        while($row = mysqli_fetch_array($result3))
        {
            if (!array_key_exists($row["ID"], $H_table)) {
                $H_table[$row["ID"]] = $row["p"];
            }
            $sqlfriendprice2 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userfriends  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-".$day." day") . " and b.userId=" .$row["ID"] . "  group by b.userId order by p desc limit 20";
            $resultfriendpriceList2 =$mySqlHelper->Select($sqlfriendprice2);
            while($row1 = mysqli_fetch_array($resultfriendpriceList2))
            {
                if (array_key_exists($row1["ID"], $H_table)) {
                    $H_table[$row1["ID"]] = (double)$H_table[$row1["ID"]] + (double)$row1["p"];
                }

            }

            $sqltaskprice2 = "select  a.ID,sum(b.price) as p ,b.gametime ,a.nickname,a.headerurl from userinfo a
                        left join usertrygames b on a.id=b.userId
                        where  b.gametime>" . strtotime("-".$day." day") . " and a.ID=" . $row["ID"] . "  group by b.userId order by p desc limit 20";
            $resulttaskpriceList2 =$mySqlHelper->Select($sqltaskprice2);
            while($row2 = mysqli_fetch_array($resulttaskpriceList2))
            {
                if (array_key_exists($row2["ID"], $H_table)) {
                    $H_table[$row2["ID"]] = (double)$H_table[$row2["ID"]] + (double)$row2["p"];
                }

            }

        }

        arsort($H_table);
        $numprice = 1;
        foreach (array_keys($H_table) as $keytotalpice) {

            if ($numprice <= 20) {
                $htmlpage .= "<div class=\"ranking-list\"><p class=\"num\">";

                if ($numprice == 1)
                    $htmlpage .= " <img src=\"../img/one.jpg\"/>";
                else if ($numprice == 2)
                    $htmlpage .= " <img src=\"../img/two.jpg\"/>";
                else if ($numprice == 3)
                    $htmlpage .= " <img src=\"../img/thr.jpg\"/>";
                else
                    $htmlpage .= $numprice;
                $htmlpage .= "</p><img class=\"touxiang\" src=\"";
                $userinfo = Userinfo::findFirstByID($keytotalpice);

                $nickname = str_replace("??", "", $userinfo->getNickname());
                if ($nickname > 20)
                    $temp = substr($nickname, 0, 20) . "...";
                else
                    $temp = $nickname;
                //假数据
                $totalnum = $H_table[$keytotalpice];

                $htmlpage .= $userinfo->getHeaderurl() . "\  width=\"150px\" height=\"150px\"/><p class=\"name\">" . $temp . "</p>
              <p class=\"red\">" . sprintf("%.2f", $totalnum) . " </p></div>";//暂时假数据
            }
            $numprice++;
        }
        return $htmlpage;
    }
    public  function  rankinfoAction()
    {
        $this->setLogger("weixin");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("获得土豪排行榜出错：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'获得土豪排行榜出错',
                'code' => 400
            );
        }
        else
        {
                $type=$this->request->getPost("type");
            if($type!="")
            {
                $sqlcx = "";
                $htmlpage = "";
                if ($type == 0)
                {

                    $htmlpage= $this->GetRankingToWeek(6);
                  //  $sqlcx = "select * from Userbalance where modifiedtime between " . strtotime("-6 day") . " and " . strtotime(date("Y-m-d") . ' 24:59:59') . " order by totalincome desc limit 20";

                }
            else if($type==1)
            {
                $htmlpage= $this->GetRankingToWeek(30);
               // $sqlcx = "select * from Userbalance where modifiedtime between " . strtotime("-29 day") . " and " . strtotime(date("Y-m-d") . ' 24:59:59') . " order by totalincome desc limit 20";
            }
              else
              {
                  $sqlcx = "select * from Userbalance  order by totalincome desc limit 20";
                  $query = $this->modelsManager->createQuery($sqlcx);
                  $userbalanceList = $query->execute();
                  $num = 1;

                  foreach ($userbalanceList as $userbalance)
                  {
                      $htmlpage .= "<div class=\"ranking-list\"><p class=\"num\">";

                      if ($num == 1)
                          $htmlpage .= " <img src=\"../img/one.jpg\"/>";
                      else if ($num == 2)
                          $htmlpage .= " <img src=\"../img/two.jpg\"/>";
                      else if ($num == 3)
                          $htmlpage .= " <img src=\"../img/thr.jpg\"/>";
                      else
                          $htmlpage .= $num;
                      $htmlpage .= "</p><img class=\"touxiang\" src=\"";
                      $userinfo = Userinfo::findFirstByID($userbalance->getUserid());

                      $nickname = str_replace("??", "", $userinfo->getNickname());
                      if ($nickname > 20)
                          $temp = substr($nickname, 0, 20) . "...";
                      else
                          $temp = $nickname;
                      //假数据
                      $totalnum = $userbalance->getTotalincome();

                      $htmlpage .= $userinfo->getHeaderurl() . "\  width=\"150px\" height=\"150px\"/><p class=\"name\">" . $temp . "</p>
              <p class=\"red\">" . sprintf("%.2f", $totalnum) . " </p></div>";//暂时假数据

                      $num++;
                  }
              }
                $output = array(
                    'data' => array(
                        'success' => true,
                        'content' =>$htmlpage
                    ),
                    'success' => true,
                    'msg'=>'获得土豪排行榜',
                    'code' => 400
                );
            }
        }

        $this->getLogger()->logInfo(json_encode($output));
     //   exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public  function  invitepageAction()
    {
        $this->DriverType();
        $this->setLogger("weixin");
        $ID =$this->request->getQuery('wuserid');
        $this->view->isself=false;
       if($ID)
       {
           $this->getLogger()->logInfo("邀请者ID--:".$ID);
           if($ID!=$this->persistent->wuserid)
           {
               $this->session->set('auth_weixin', array(
                   'wuserid' =>0,
                   'waccount' =>0,
                   'wfriend'=>$ID
               ));
                $this->view->isself=true;
           }
       }
        else
        {
            $this-> VailSeesion();
            $this->view->wuserid= $this->persistent->wuserid;
        }

    }
    public  function  businessCooperateAction()
    {
        $this->DriverType();
    }
    public  function  customServiceAction()
    {
        $this->DriverType();
    }
    public  function  downdzgAction()
    {
        $this->DriverType();
    }
    public function  apprenticeAction()
    {
        $this->DriverType();
        $this-> VailSeesion();
        $this->view->wuserid= $this->persistent->wuserid;
    }
    public  function  androidAction()
    {

    }

    //本地方法
    /*
     *
     */
    private  function  setDiverToken($userid,$dtoken,$redis)
    {
        $isexistdivertoken=false;
        $this->getLogger()->logInfo("用户diverToken:".$userid);
        if($redis->IsExist("userdivertoken_".$userid))
        {

            $isexistdivertoken=true;
            $divertoken=$redis->GetHValue("userdivertoken_".$userid,"diverToken");
            $this->getLogger()->logInfo("redis 用户:".$userid."--diverToken：".$divertoken);
        }
        else
        {
            $userdivertoken=Userdivertoken::findFirstByUserId($userid);
            if($userdivertoken)
            {
                $isexistdivertoken=true;
                $divertoken=$userdivertoken->getDiverToken();
                $this->getLogger()->logInfo("mysql 用户:".$userid."--diverToken：".$divertoken);
            }
        }

        if($isexistdivertoken)
        {
            $this->getLogger()->logInfo("存在用户diverToken:".$userid."--diverToken：".$divertoken);
            $dtoken= str_replace("<", "", $dtoken);
            $dtoken= str_replace(">", "", $dtoken);
            $dtoken= str_replace(" ", "", $dtoken);
            $this->getLogger()->logInfo("存在用户返回diverToken:".$userid."--diverToken：".$dtoken);
            if($divertoken!=$dtoken&&!empty($dtoken)&&$dtoken!="(null)")
            {
                $this->getLogger()->logInfo("用户:".$userid."--更新diverToken");
                if($redis->IsExist("userdivertoken_".$userid))
                {
                    $sqlcxuserdevice="update Userdivertoken set diverToken='".$dtoken."',modieytime=".strtotime(date("Y-m-d H:i:s"))." where userId=".$userid;
                    $query = $this->modelsManager->createQuery($sqlcxuserdevice);
                    $result = $query->execute();
                }
                else
                {
                    $userdivertoken->setDiverToken($dtoken);
                    $userdivertoken->setModieytime(strtotime(date("Y-m-d H:i:s")));
                    $userdivertoken->save();
                }
                $redis->setHValue("userdivertoken_".$userid,"diverToken",$dtoken);
                $redis->setHValue("userdivertoken_".$userid,"modieytime",strtotime(date("Y-m-d H:i:s")));
            }
        }
        else
        {
            try {
                $dtoken = str_replace("<", "", $dtoken);
                $dtoken = str_replace(">", "", $dtoken);
                $dtoken = str_replace(" ", "", $dtoken);
                $this->getLogger()->logInfo("用户:" . $userid . "--新建diverToken");
                $userdivertoken = new Userdivertoken();
                $userdivertoken->setUserId($userid);
                $userdivertoken->setCreatetime(strtotime(date("Y-m-d H:i:s")));
                $userdivertoken->setDiverToken($dtoken);
                $userdivertoken->save();
                $userdivertokenarray= array();
                $userdivertokenarray["modieytime"] =$userdivertoken->getModieytime();
                $userdivertokenarray["createtime"] =$userdivertoken->getCreatetime();
                $userdivertokenarray["diverToken"] =$userdivertoken->getDiverToken();
                $redis->setHArrayValue("userdivertoken_".$userid,$userdivertokenarray);

            }catch (exception $e)
            {
                $this->getLogger()->logInfo("用户:" . $userid . "--新建diverToken异常".$e->getMessage());
            }
        }
    }
    private   function getIP()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        return $cip;
    }
    private  function  setuserinstalledapp($userid,$appname)
    {
        $sqlcx="select * from Userinstalledapp where userId=".$userid." and identifier='".$appname."'";
        $query = $this->modelsManager->createQuery($sqlcx);
        $userinstalledappList = $query->execute();
        if (count($userinstalledappList) > 0)
        {
            $userinstalledapp=$userinstalledappList[0];
            if($userinstalledapp->getIdentifier()!=$appname)
            {
                $this->getLogger()->logInfo("用户:".$userid."--更新installedapp.".$appname);
                $userinstalledapp->setIdentifier($appname);
                $userinstalledapp->save();
            }
            $this->getLogger()->logInfo("用户:".$userid."--已经存在installedapp.".$appname);
        }
        else
        {
            try {
                $this->getLogger()->logInfo("用户:" . $userid . "--新增installedapp".$appname);
                $userinstalledapp=new Userinstalledapp();
                $userinstalledapp->setUserId($userid);
                $userinstalledapp->setIdentifier($appname);
                $userinstalledapp->save();
            }catch (exception $e)
            {
                $this->getLogger()->logInfo("用户:" . $userid . "--更新installedapp".$e->getMessage());
            }
        }
    }
    private  function  deleteAppInfoForRedis($userid,$redis)
    {
        $userappList=$this-> redis->GetHmValue("userapp_info");
        foreach($userappList as $userappinfo)
        {
            $redis->DeleteValue("user_appinfo_" . $userid . "_" . $userappinfo);
        }
        $redis->DeleteValue("userapp_info" . $userid );
    }

    /**
     * 新用户奖励
     * @param $userId
     *
     */
    private  function  NewJiangLi($userId,$redis)
    {

        $sqlOtherPrice="select * from Userotherprice where type in (1,0)  and userId=".$userId;
        $query = $this->modelsManager->createQuery($sqlOtherPrice);
        $result = $query->execute();
        if(count($result)==0)
        {
            $userbalance = Userbalance::findFirstByUserid($userId);

            $userbalance->setCurrentbalance($userbalance->getCurrentbalance() + 2);
            $userbalance->setTotalincome($userbalance->getTotalincome() + 2);
            $userbalance->setOtherincome($userbalance->getOtherincome() + 2);
            $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
            if($userbalance->save())
            {

                $userotherprice = new Userotherprice();
                $userotherprice->setType(1);
                $userotherprice->setPrice(2);
                $userotherprice->setAddtime(strtotime(date("Y-m-d H:i:s")));
                $userotherprice->setStatus(1);
                $userotherprice->setUserid($userId);
                if($userotherprice->save())
                {
                    $redis->setHValue("user_balance_" . $userId, "currentbalance", $userbalance->getCurrentbalance());
                    if ($redis->IsExist("user_todaymoney_" . $userId) == false) {
                        $todaymoney = (double)($this->redis->getValue("user_todaymoney_" . $userId));
                        $redis->setValue("user_todaymoney_" . $userId, $todaymoney + 2);
                    } else {
                        $redis->setValue("user_todaymoney_" . $userId, 2);
                    }
                    $this->getLogger()->logInfo("新用户下载大掌柜奖励-设置用户余额start:" . $userId . "---");
                    $this->getLogger()->logInfo("新用户下载大掌柜奖励-设置用户余额end:" . $userId);
                    $msgCommon = new MsgCommon();
                    $msgCommon->sendMsg("奖励", "恭喜你获得 大掌柜 奖励 2 元", "2", $userId);
                    $this->getLogger()->logInfo("奖励", "恭喜你获得 大掌柜 奖励 2 元---" . $userId);
                }
                else
                {
                    $userbalance->setCurrentbalance($userbalance->getCurrentbalance() - 2);
                    $userbalance->setTotalincome($userbalance->getTotalincome() - 2);
                    $userbalance->setOtherincome($userbalance->getOtherincome() - 2);
                    $userbalance->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                    if($userbalance->save())
                    {
                        foreach ($userbalance->getMessages() as $message) {
                            $this->getLogger()->Error("用户:" . $userId . "--获得新用户下载大掌柜奖励- " . 2 . " 减余额失败---" . $message->getMessage());
                        }
                    }
                }
            }
            else {
                foreach ($userbalance->getMessages() as $message) {
                    $this->getLogger()->Error("用户:" . $userId . "--获得新用户下载大掌柜奖励- " . 2 . " 添加到余额失败---" . $message->getMessage());
                }
            }
        }
    }
    //本地方法
    //


    public  function  vaildzgAction()
    {
        $this->setLogger("大掌柜");
        $this->getLogger()->logInfo("验证大掌柜");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("验证大掌柜错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'验证大掌柜错误',
                'code' => 400
            );
        }
        else
        {


            $this->getLogger()->logInfo("HTTP-DATA:".$GLOBALS['HTTP_RAW_POST_DATA']);
            $userappjson=$GLOBALS['HTTP_RAW_POST_DATA'];
            $userappjson=str_replace(" ", "",$userappjson);
            $userappjson=str_replace("\r", "",$userappjson);
            $userappjson=str_replace("\n", "",$userappjson);
            $this->getLogger()->logInfo("HTTP-DATA--json-end:".$userappjson);
            $restul=json_decode($userappjson,true);
            $redis=CacheFactory::createCache("Redis");

            $this->getLogger()->logInfo("userid:".$restul[0]["userid"]);
            $isexistuser=false;
            if($redis->IsExist($restul[0]["userid"]))
            {
                $isexistuser=true;
                $channeluserid=$redis->GetHValue($restul[0]["userid"],"account");
                $userheader= $redis->GetHValue($restul[0]["userid"],"headerurl");
                $redis->setValue("user_userzhanggui_version" .$restul[0]["userid"],$restul[0]["version"]);
                $this->getLogger()->logInfo("用户存在userid-version:".$restul[0]["version"]);
              $this->getLogger()->logInfo("用户存在userid-ip:".$restul[0]["userid"]."---".$_SERVER["REMOTE_ADDR"]);
            }
            else
            {
                $userinfo = Userinfo::findFirstByID( $restul[0]["userid"]);
                if($userinfo)
                {
                    $isexistuser=true;
                    $channeluserid=$userinfo->getAccount();
                    $userheader=  $userinfo->getHeaderurl();
                    $redis->setValue("user_userzhanggui_version" .$restul[0]["userid"],$restul[0]["version"]);
                    $this->getLogger()->logInfo("用户存在userid-ip:".$restul[0]["userid"]."---".$_SERVER["REMOTE_ADDR"]);
                }

            }

            if($isexistuser)
            {
               $redis->setHValue("userinstallapp_MQ","app_".$restul[0]["userid"],$restul[0]["userid"]."$".$userappjson);
                /*
                foreach($restul[0]["appinfo"]  as $item)
                {
                    $this->setuserinstalledapp( $userinfo->getId(),$item["name"]);
                }
                */
                $this->getLogger()->logInfo("用户存在userid:".$restul[0]["userid"]);
                $isexistuserDevicer=false;
                if($redis->IsExist("userdiverinfo_".$restul[0]["userid"]))
                {
                    $isexistuserDevicer=true;
                    $IDFA=$redis->GetHValue("userdiverinfo_".$restul[0]["userid"],"idfa");
                    $IDFV=$redis->GetHValue("userdiverinfo_".$restul[0]["userid"],"idfv");
                }
                else
                {
                    $userdeviceinfo = Userdeviceinfo::findFirstByUserid($restul[0]["userid"]);
                    if($userdeviceinfo)
                    {
                        $isexistuserDevicer=true;
                        $IDFA=$userdeviceinfo->getIdfa();
                        $IDFV=$userdeviceinfo->getIdfv();
                    }
                }
                if($isexistuserDevicer)
                {
                    if($IDFA=="")
                    {
                        if($redis->IsExist("userdiverinfo_".$restul[0]["userid"]))
                        {
                            $sqlcxuserdevice="update Userdeviceinfo set IDFA='".$restul[0]["IDFA"]."', IDFV='".$restul[0]["IDFV"]."',APPstoreaccount='".$restul[0]["APPstoreaccount"]."',IMEI='".$restul[0]["IMEI"]."',carrieroperator='".$restul[0]["carrieroperator"]."'
                            ,phonetype='".$restul[0]["phonetype"]."',macaddress='".$restul[0]["macAddress"]."',ip='".$_SERVER["HTTP_X_FORWARDED_FOR"]."',network='".$restul[0]["network"]."',
                            sysversion='".$restul[0]["sysversion"]."',addtime=".strtotime(date("Y-m-d H:i:s")).",channeluserid='".$channeluserid."',channeltype=1,nettype='".$restul[0]["nettype"]."'  where userid=".$restul[0]["userid"];
                            $query = $this->modelsManager->createQuery($sqlcxuserdevice);
                            $result = $query->execute();

                        }
                        else
                        {
                            $userdeviceinfo->setUserid($restul[0]["userid"]);
                            $userdeviceinfo->setAddtime(strtotime(date("Y-m-d H:i:s")));
                            $userdeviceinfo->setAppstoreaccount($restul[0]["APPstoreaccount"]);
                            $userdeviceinfo->setCarrieroperator($restul[0]["carrieroperator"]);
                            $userdeviceinfo->setChanneltype(1);
                            $userdeviceinfo->setChanneluserid($channeluserid);
                            $userdeviceinfo->setImei($restul[0]["IMEI"]);
                            $userdeviceinfo->setIp($_SERVER["HTTP_X_FORWARDED_FOR"]);

                            //$this->getLogger()->Error("userid:" . $restul[0]["userid"] . "-ip:" . $_SERVER["HTTP_X_FORWARDED_FOR"]);
                            $this->getLogger()->logInfo("userid:" . $restul[0]["userid"] . "-ip:" . $_SERVER["REMOTE_ADDR"]);
                            $userdeviceinfo->setNettype($restul[0]["nettype"]);
                            $userdeviceinfo->setNetwork($restul[0]["network"]);
                            $userdeviceinfo->setIdfa($restul[0]["IDFA"]);
                            $userdeviceinfo->setMacaddress($restul[0]["macAddress"]);
                            $userdeviceinfo->setIdfv($restul[0]["IDFV"]);
                            $userdeviceinfo->setPhonetype($restul[0]["phonetype"]);
                            $userdeviceinfo->setSysversion($restul[0]["sysversion"]);
                            $userdeviceinfo->save();

                        }
                        $redis->DeleteValue("userdiverinfo_". $restul[0]["userid"]);
                        $redis->DeleteValue("userdiverinfo_IDFA_".$restul[0]["IDFA"]);
                        $redis->DeleteValue("userdiverinfo_IDFV_". $restul[0]["IDFV"]);
                        $userdeviceinfoarray= array();
                        $userdeviceinfoarray["channeltype"] =1;
                        $userdeviceinfoarray["idfa"] =$restul[0]["IDFA"];
                        $userdeviceinfoarray["macaddress"] =$restul[0]["macAddress"];
                        $userdeviceinfoarray["idfv"] =$restul[0]["IDFV"];
                        $userdeviceinfoarray["appstoreaccount"] =$restul[0]["APPstoreaccount"];
                        $userdeviceinfoarray["imei"] =$restul[0]["IMEI"];
                        $userdeviceinfoarray["addtime"] =strtotime(date("Y-m-d H:i:s"));
                        $userdeviceinfoarray["userid"] =$restul[0]["userid"];
                        $userdeviceinfoarray["ip"] =$_SERVER["HTTP_X_FORWARDED_FOR"];
                        $redis->setHArrayValue("userdiverinfo_".$restul[0]["userid"],$userdeviceinfoarray);
                        $redis->setHArrayValue("userdiverinfo_IDFA_".$restul[0]["IDFA"],$userdeviceinfoarray);
                        $redis->setHArrayValue("userdiverinfo_IDFV_".$restul[0]["IDFV"],$userdeviceinfoarray);

                        $this->setDiverToken($restul[0]["userid"],$restul[0]["deviceToken"],$redis);
                        if(!$redis->IsExist("user_userzhanggui_".$restul[0]["userid"])) {
                            $userzhanggui = new Userzhanggui();
                            $userzhanggui->setOpentime(strtotime(date("Y-m-d H:i:s")));
                            $userzhanggui->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                            $userzhanggui->setStatus(1);
                            $userzhanggui->setVersion($restul[0]["version"]);
                            $userzhanggui->setUserid($restul[0]["userid"]);
                            $userzhanggui->save();
                            $redis->setValue("user_userzhanggui_" .$restul[0]["userid"],1);
                            $redis->setHValue("user_userzhanggui_online",$restul[0]["userid"],$userzhanggui->getStatus());
                            $redis->setHValue("user_userzhanggui_online_opentime",$restul[0]["userid"],$userzhanggui->getOpentime());
                            $redis->setHValue("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"],$userzhanggui->getModifiedtime());
                        }
                        else
                        {
                            $sqlcxupdate="update Userzhanggui set status=1,version='".$restul[0]["version"]."', modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$restul[0]["userid"];
                            $query = $this->modelsManager->createQuery($sqlcxupdate);
                            $result = $query->execute();
                            $redis->setValue("user_userzhanggui_".$restul[0]["userid"],1);
                            $redis->setHValue("user_userzhanggui_online",$restul[0]["userid"],1);
                            $redis->setHValue("user_userzhanggui_online_opentime",$restul[0]["userid"],strtotime(date("Y-m-d H:i:s")));
                            $redis->setHValue("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"],strtotime(date("Y-m-d H:i:s")));
                        }
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'content' => ""
                            ),
                            'success' => true,
                            'msg' => '验证通过',
                            'userid' => $restul[0]["userid"],
                            'headerurl' => $userheader,
                            'code' => 500
                        );
                        $this->getLogger()->logInfo("用户存在userid:".$restul[0]["userid"]."--验证通过");
                    }
                    else
                    {
                        $this->getLogger()->logInfo("用户diverinfo存在userid:".$restul[0]["userid"]);
                        //&&$userdeviceinfo->getIdfv()== $restul[0]["IDFV"]
                       if($IDFA==$restul[0]["IDFA"]||$IDFV== $restul[0]["IDFV"])
                       {

                           if($redis->IsExist("userdiverinfo_".$restul[0]["userid"]))
                           {

                               $sqlcxuserdevice="update Userdeviceinfo set phonetype='".$restul[0]["phonetype"]."',ip='".$_SERVER["HTTP_X_FORWARDED_FOR"]."' where userid=".$restul[0]["userid"];
                               $query = $this->modelsManager->createQuery($sqlcxuserdevice);
                               $result = $query->execute();

                           }
                           else
                           {
                               $userdeviceinfo->setPhonetype($restul[0]["phonetype"]);
                               $userdeviceinfo->setIp( $_SERVER["HTTP_X_FORWARDED_FOR"]);
                               $userdeviceinfo->save();

                           }
                           $this->getLogger()->logInfo("用户aaaa存在userid:".$restul[0]["userid"]);
                           $sqlcxupdate="update Userzhanggui set status=1, version='".$restul[0]["version"]."',modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$restul[0]["userid"];
                           $query = $this->modelsManager->createQuery($sqlcxupdate);
                           $result = $query->execute();
                           $redis->setValue("user_userzhanggui_".$restul[0]["userid"],1);
                           $redis->setHValue("user_userzhanggui_online",$restul[0]["userid"],1);
                           $redis->setHValue("user_userzhanggui_online_opentime",$restul[0]["userid"],strtotime(date("Y-m-d H:i:s")));
                           $redis->setHValue("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"],strtotime(date("Y-m-d H:i:s")));
                           $this->getLogger()->logInfo("用户acccccca存在userid:".$restul[0]["userid"]);
                           $this->setDiverToken($restul[0]["userid"],$restul[0]["deviceToken"],$redis);
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => true,
                                'msg' => '验证通过',
                                'userid' => $restul[0]["userid"],
                                'headerurl' => $userheader,
                                'code' => 500
                            );
                           $this->getLogger()->logInfo("用户存在userid:".$restul[0]["userid"]."--验证通过");
                       }
                        else
                        {
                            $sqlcxupdate="update Userzhanggui set status=0,version='".$restul[0]["version"]."', modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$restul[0]["userid"];
                            $query = $this->modelsManager->createQuery($sqlcxupdate);
                            $result = $query->execute();
                            $this->getLogger()->logInfo("验证失败:".$restul[0]["userid"]);
                            $redis->setValue("user_userzhanggui_".$restul[0]["userid"],0);
                            try {
                                $redis->Hdel("user_userzhanggui_online_opentime", $restul[0]["userid"]);
                                $redis->Hdel("user_userzhanggui_online_Modifiedtime", $restul[0]["userid"]);
                                $redis->Hdel("user_userzhanggui_online", $restul[0]["userid"]);
                            }catch (exception $e)
                            {

                            }
                            $this->getLogger()->logInfo("大掌柜验证失败，此微信号已经绑定了设备:".$restul[0]["userid"]);
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => false,
                                'msg' => '大掌柜验证失败，此微信号已经绑定了设备',
                                'userid' => $restul[0]["userid"],
                                'code' => 400
                            );
                        }
                    }
                }
                else
                {
                    $userdeviceinfoIDFA=Userdeviceinfo::findFirstByIDFA( $restul[0]["IDFA"]);
                    if($userdeviceinfoIDFA)
                    {

                        if($redis->IsExist("user_userzhanggui_".$restul[0]["userid"])) {

                            $sqlcxupdate="update Userzhanggui set status=0,version='".$restul[0]["version"]."', modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$restul[0]["userid"];
                            $query = $this->modelsManager->createQuery($sqlcxupdate);
                            $result = $query->execute();
                            $redis->setValue("user_userzhanggui_".$restul[0]["userid"],0);
                            try
                            {
                            $redis->Hdel("user_userzhanggui_online_opentime",$restul[0]["userid"]);
                            $redis->Hdel("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"]);
                            $redis->Hdel("user_userzhanggui_online",$restul[0]["userid"]);
                            }catch (exception $e)
                            {

                            }
                        }
                        else
                        {
                            $userzhanggui = Userzhanggui::findFirstByUserid($restul[0]["userid"]);
                            if ($userzhanggui) {
                                $userzhanggui->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                $userzhanggui->setStatus(0);
                                $userzhanggui->setVersion($restul[0]["version"]);
                                $userzhanggui->save();
                                $redis->setValue("user_userzhanggui_".$restul[0]["userid"],0);
                                try
                                {
                                $redis->Hdel("user_userzhanggui_online_opentime",$restul[0]["userid"]);
                                $redis->Hdel("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"]);
                                $redis->Hdel("user_userzhanggui_online",$restul[0]["userid"]);
                            }catch (exception $e)
                            {

                            }
                            }
                        }
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'content' => ""
                            ),
                            'success' => false,
                            'msg' => '大掌柜验证失败，此设备已经绑定了',
                            'userid' => $restul[0]["userid"],
                            'code' => 400
                        );
                    }
                    else
                    {

                        $userdeviceinfoIDFV=Userdeviceinfo::findFirstByIDFA( $restul[0]["IDFV"]);
                        if($userdeviceinfoIDFV)
                        {
                            if($redis->IsExist("user_userzhanggui_".$restul[0]["userid"])) {

                                $sqlcxupdate="update Userzhanggui set status=0,version='".$restul[0]["version"]."', modifiedtime=".strtotime(date("Y-m-d H:i:s"))." where userid=".$restul[0]["userid"];
                                $query = $this->modelsManager->createQuery($sqlcxupdate);
                                $result = $query->execute();
                                $redis->setValue("user_userzhanggui_".$restul[0]["userid"],0);
                                try {
                                    $redis->Hdel("user_userzhanggui_online_opentime", $restul[0]["userid"]);
                                    $redis->Hdel("user_userzhanggui_online_Modifiedtime", $restul[0]["userid"]);
                                    $redis->Hdel("user_userzhanggui_online", $restul[0]["userid"]);
                                }catch (exception $e)
                                {

                                }
                            }
                            else
                            {
                                $userzhanggui = Userzhanggui::findFirstByUserid($restul[0]["userid"]);
                                if ($userzhanggui) {
                                    $userzhanggui->getModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                    $userzhanggui->setStatus(0);
                                    $userzhanggui->setVersion($restul[0]["version"]);
                                    $userzhanggui->save();
                                    $redis->setValue("user_userzhanggui_".$restul[0]["userid"],0);
                                    try {
                                        $redis->Hdel("user_userzhanggui_online_opentime", $restul[0]["userid"]);
                                        $redis->Hdel("user_userzhanggui_online_Modifiedtime", $restul[0]["userid"]);
                                        $redis->Hdel("user_userzhanggui_online", $restul[0]["userid"]);
                                    }catch (exception $e)
                                      {

                                      }

                                }
                            }
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => true,
                                'msg' => '大掌柜验证失败，此设备已经绑定了',
                                'userid' => $restul[0]["userid"],
                                'code' => 400
                            );
                        }
                        else
                        {
                            $this->getLogger()->logInfo("新用户验证userid:".$restul[0]["userid"]);
                            $userdeviceinfo = new Userdeviceinfo();
                            $userdeviceinfo->setUserid($restul[0]["userid"]);
                            $userdeviceinfo->setAddtime(strtotime(date("Y-m-d H:i:s")));
                            $userdeviceinfo->setAppstoreaccount($restul[0]["APPstoreaccount"]);
                            $userdeviceinfo->setCarrieroperator($restul[0]["carrieroperator"]);
                            $userdeviceinfo->setChanneltype(1);
                            $userdeviceinfo->setChanneluserid($channeluserid);
                            $userdeviceinfo->setImei($restul[0]["IMEI"]);
                            $userdeviceinfo->setIp( $_SERVER["HTTP_X_FORWARDED_FOR"]);
                            $this->getLogger()->logInfo("userid:".$restul[0]["userid"]."-ip:". $_SERVER["REMOTE_ADDR"]);
                           // $this->getLogger()->Error("userid:" . $restul[0]["userid"] . "-ip:" . $_SERVER["HTTP_X_FORWARDED_FOR"]);
                            $userdeviceinfo->setNettype($restul[0]["nettype"]);
                            $userdeviceinfo->setNetwork($restul[0]["network"]);
                            $userdeviceinfo->setIdfa($restul[0]["IDFA"]);
                            $userdeviceinfo->setIdfv($restul[0]["IDFV"]);
                            $userdeviceinfo->setMacaddress($restul[0]["macAddress"]);
                            $userdeviceinfo->setPhonetype($restul[0]["phonetype"]);
                            $userdeviceinfo->setSysversion($restul[0]["sysversion"]);
                            $userdeviceinfo->save();

                            $userdeviceinfoarray= array();
                            $userdeviceinfoarray["channeltype"] =1;
                            $userdeviceinfoarray["idfa"] =$restul[0]["IDFA"];
                            $userdeviceinfoarray["macaddress"] =$restul[0]["macAddress"];
                            $userdeviceinfoarray["idfv"] =$restul[0]["IDFV"];
                            $userdeviceinfoarray["appstoreaccount"] =$restul[0]["APPstoreaccount"];
                            $userdeviceinfoarray["imei"] =$restul[0]["IMEI"];
                            $userdeviceinfoarray["addtime"] =strtotime(date("Y-m-d H:i:s"));
                            $userdeviceinfoarray["userid"] =$restul[0]["userid"];
                            $userdeviceinfoarray["ip"] =$_SERVER["REMOTE_ADDR"];
                            $redis->setHArrayValue("userdiverinfo_".$restul[0]["userid"],$userdeviceinfoarray);
                            $redis->setHArrayValue("userdiverinfo_IDFA_".$restul[0]["IDFA"],$userdeviceinfoarray);
                            $redis->setHArrayValue("userdiverinfo_IDFV_".$restul[0]["IDFV"],$userdeviceinfoarray);

                            $this->setDiverToken($restul[0]["userid"],$restul[0]["deviceToken"],$redis);

                            $userzhanggui = new Userzhanggui();
                            $userzhanggui->setOpentime(strtotime(date("Y-m-d H:i:s")));
                            $userzhanggui->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                            $userzhanggui->setStatus(1);
                            $userzhanggui->setVersion($restul[0]["version"]);
                            $userzhanggui->setUserid($restul[0]["userid"]);
                            $userzhanggui->save();
                            $redis->setValue("user_userzhanggui_" .$restul[0]["userid"],1);
                            $redis->setHValue("user_userzhanggui_online",$restul[0]["userid"],1);
                            $redis->setHValue("user_userzhanggui_online_opentime",$restul[0]["userid"],$userzhanggui);
                            $redis->setHValue("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"],$userzhanggui);

                            $this->getLogger()->logInfo("用户验证通过userid:".$restul[0]["userid"]);

                            $this->NewJiangLi($restul[0]["userid"],$redis);
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => true,
                                'msg' => '验证通过',
                                'userid' => $restul[0]["userid"],
                                'headerurl' => $userheader,
                                'code' => 500
                            );
                            $this->getLogger()->logInfo("用户存在userid:".$restul[0]["userid"]."--验证通过");
                       }
                    }
                }


            }
            else
            {
                $this->getLogger()->logInfo("用户不存在IDFA:".$restul[0]["IDFA"]);
                $isexistIDFA=false;
                if($redis->IsExist("userdiverinfo_IDFA_".$restul[0]["IDFA"]))
                {
                    $this->getLogger()->logInfo("redis 用户存在IDFA:".$restul[0]["IDFA"]);
                    $isexistIDFA=true;
                    $userid=$redis->GetHValue("userdiverinfo_IDFA_".$restul[0]["IDFA"],"userid");
                }
                else
                {
                    $this->getLogger()->logInfo("mysql 用户存在IDFA:".$restul[0]["IDFA"]);
                    $userdeviceinfoIDFA = Userdeviceinfo::findFirstByIDFA($restul[0]["IDFA"]);
                    if($userdeviceinfoIDFA)
                    {
                        $isexistIDFA = true;
                        $userid=$userdeviceinfoIDFA->getUserid();
                    }
                }
                if($isexistIDFA)
                {
                    try {
                        $redis->setValue("user_userzhanggui_version" .$userid,$restul[0]["version"]);
                        if ($redis->IsExist($userid)) {
                            $this->getLogger()->logInfo("REDIS 用户存在" . $userid);
                            $isexistuser = true;
                            $userheader= $redis->GetHValue($userid,"headerurl");

                        } else {
                            $this->getLogger()->logInfo("mysql 用户存在" . $userid);
                            $userinfo = Userinfo::findFirstByID($userid);
                            if ($userinfo) {
                                $isexistuser = true;
                                $userheader= $userinfo->getHeaderurl();
                            }
                        }

                        if ($isexistuser)
                        {

                            $redis->setHValue("userinstallapp_MQ","app_".$userid,$userid."$".$userappjson);
                            /*
                            foreach($restul[0]["appinfo"]  as $item)
                            {
                                $this->setuserinstalledapp( $userinfo->getId(),$item["name"]);
                            }
                   */
                            if ($redis->IsExist("user_userzhanggui_" . $userid)) {

                                $this->getLogger()->logInfo("REDIS 设置用户大掌柜" . $userid);
                                $sqlcxupdate = "update Userzhanggui set status=1,version='".$restul[0]["version"]."', modifiedtime=" . strtotime(date("Y-m-d H:i:s")) . " where userid=" . $userid;
                                $query = $this->modelsManager->createQuery($sqlcxupdate);
                                $result = $query->execute();
                                $redis->setValue("user_userzhanggui_" . $userid, 1);
                                $redis->setHValue("user_userzhanggui_online",$userid,1);
                                $redis->setHValue("user_userzhanggui_online_opentime",$userid,strtotime(date("Y-m-d H:i:s")));
                                $redis->setHValue("user_userzhanggui_online_Modifiedtime",$userid,strtotime(date("Y-m-d H:i:s")));

                            } else {
                                $this->getLogger()->logInfo("mysql 设置用户大掌柜" . $userid);
                                $userzhanggui = Userzhanggui::findFirstByUserid($userid);
                                if ($userzhanggui) {
                                    $userzhanggui->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                    $userzhanggui->setStatus(1);
                                    $userzhanggui->setVersion($restul[0]["version"]);
                                    $userzhanggui->save();
                                    $redis->setValue("user_userzhanggui_" . $userid, 1);
                                    $redis->setHValue("user_userzhanggui_online",$userid,1);
                                    $redis->setHValue("user_userzhanggui_online_opentime",$userid, $userzhanggui->getOpentime());
                                    $redis->setHValue("user_userzhanggui_online_Modifiedtime",$userid, $userzhanggui->getModifiedtime());

                                }

                            }
                            if ($redis->IsExist("userdiverinfo_IDFA_" . $restul[0]["IDFA"])) {
                                $sqlcxuserdevice = "update Userdeviceinfo set phonetype='" . $restul[0]["phonetype"] . "',ip='".$_SERVER["HTTP_X_FORWARDED_FOR"]."' where userid=" . $restul[0]["userid"];
                                $query = $this->modelsManager->createQuery($sqlcxuserdevice);
                                $result = $query->execute();

                            } else {
                                $userdeviceinfoIDFA->setPhonetype($restul[0]["phonetype"]);
                                $userdeviceinfoIDFA->setIp( $_SERVER["HTTP_X_FORWARDED_FOR"]);
                                $userdeviceinfoIDFA->save();
                            }
                            $this->getLogger()->logInfo("设置用户deviceToken" . $userid);
                            $this->setDiverToken($userid, $restul[0]["deviceToken"], $redis);
                            $this->getLogger()->logInfo("用户存在userid:" .$userid . "--验证通过");
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => true,
                                'msg' => '验证通过',
                                'userid' => $userid,
                                'headerurl' =>$userheader,
                                'code' => 500
                            );

                        } else {
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => false,
                                'msg' => '验证未通过,请返回任务列表激活(或点击任务列表大掌柜)',
                                'code' => 400
                            );
                        }
                    }catch (exception $e)
                    {
                        $this->getLogger()->logInfo("验证用户异常:" .$userid . "--".$e->getMessage().":".$e->getTrace());
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'content' => ""
                            ),
                            'success' => false,
                            'msg' => '验证失败,请重试',
                            'code' => 400
                        );
                    }
                }
                else
                {

                    $this->getLogger()->logInfo("用户不存在--IDFV:".$restul[0]["IDFV"]);
                    $isexistIDFV=false;
                    if($redis->IsExist("userdiverinfo_IDFV_".$restul[0]["IDFV"]))
                    {
                        $this->getLogger()->logInfo("redis 用户存在IDFV:".$restul[0]["IDFV"]);
                        $isexistIDFV=true;
                        $userid=$redis->GetHValue("userdiverinfo_IDFV_".$restul[0]["IDFV"],"userid");

                    }
                    else
                    {
                        $this->getLogger()->logInfo("mysql 用户存在IDFV:".$restul[0]["IDFV"]);
                        $userdeviceinfoIDFV=Userdeviceinfo::findFirstByIDFV( $restul[0]["IDFV"]);
                        if($userdeviceinfoIDFV)
                        {
                            $isexistIDFV = true;
                            $userid=$userdeviceinfoIDFV->getUserid();

                        }
                    }

                    if($isexistIDFV)
                    {
                        try {
                            $redis->setValue("user_userzhanggui_version" .$userid,$restul[0]["version"]);
                            if ($redis->IsExist($userid)) {
                                $isexistuser = true;
                                $userheader= $redis->GetHValue($userid,"headerurl");

                            } else {
                                $userinfo = Userinfo::findFirstByID($userid);
                                if ($userinfo) {
                                    $isexistuser = true;
                                    $userheader= $userinfo->getHeaderurl();
                                }
                            }
                            if ($isexistuser) {
                                $redis->setHValue("userinstallapp_MQ","app_".$userid,$userid."$".$userappjson);
                                /*
                                foreach($restul[0]["appinfo"]  as $item)
                                {
                                    $this->setuserinstalledapp( $userinfo->getId(),$item["name"]);
                                }
                                */
                                if ($redis->IsExist("user_userzhanggui_" . $userid)) {

                                    $sqlcxupdate = "update Userzhanggui set status=1,version='".$restul[0]["version"]."', modifiedtime=" . strtotime(date("Y-m-d H:i:s")) . " where userid=" . $userid;
                                    $query = $this->modelsManager->createQuery($sqlcxupdate);
                                    $result = $query->execute();
                                    $redis->setValue("user_userzhanggui_" . $userid, 1);
                                    $redis->setHValue("user_userzhanggui_online",$userid,1);
                                    $redis->setHValue("user_userzhanggui_online_opentime",$userid,strtotime(date("Y-m-d H:i:s")));
                                    $redis->setHValue("user_userzhanggui_online_Modifiedtime",$userid,strtotime(date("Y-m-d H:i:s")));

                                } else {
                                    $userzhanggui = Userzhanggui::findFirstByUserid($userid);
                                    if ($userzhanggui) {
                                        $userzhanggui->setModifiedtime(strtotime(date("Y-m-d H:i:s")));
                                        $userzhanggui->setVersion($restul[0]["version"]);
                                        $userzhanggui->setStatus(1);
                                        $userzhanggui->save();
                                        $redis->setValue("user_userzhanggui_" . $userid, 1);
                                        $redis->setHValue("user_userzhanggui_online",$userid,1);
                                        $redis->setHValue("user_userzhanggui_online_opentime",$userid, $userzhanggui->getOpentime());
                                        $redis->setHValue("user_userzhanggui_online_Modifiedtime",$userid, $userzhanggui->getModifiedtime());

                                    }

                                }
                                if ($redis->IsExist("userdiverinfo_IDFV_" . $restul[0]["IDFV"])) {
                                    $sqlcxuserdevice = "update Userdeviceinfo set phonetype='" . $restul[0]["phonetype"] . "',ip='".$_SERVER["HTTP_X_FORWARDED_FOR"]."' where userid=" . $restul[0]["userid"];
                                    $query = $this->modelsManager->createQuery($sqlcxuserdevice);
                                    $result = $query->execute();

                                } else {
                                    $userdeviceinfoIDFV->setPhonetype($restul[0]["phonetype"]);
                                    $userdeviceinfoIDFV->setIp( $_SERVER["HTTP_X_FORWARDED_FOR"]);
                                    $userdeviceinfoIDFV->save();
                                }
                                $this->setDiverToken($userid, $restul[0]["deviceToken"], $redis);
                                $this->getLogger()->logInfo("用户存在userid:" . $userid . "--验证通过");
                                $output = array(
                                    'data' => array(
                                        'success' => true,
                                        'content' => ""
                                    ),
                                    'success' => true,
                                    'msg' => '验证通过',
                                    'userid' => $userid,
                                    'headerurl' => $userheader,
                                    'code' => 500
                                );

                            } else {
                                $output = array(
                                    'data' => array(
                                        'success' => true,
                                        'content' => ""
                                    ),
                                    'success' => false,
                                    'msg' => '验证未通过,请返回任务列表激活(或点击任务列表大掌柜)',
                                    'code' => 400
                                );
                            }
                        }catch (exception $e)
                        {
                            $this->getLogger()->logInfo("验证用户异常:" .$userid . "--".$e->getMessage().":".$e->getTrace());
                            $output = array(
                                'data' => array(
                                    'success' => true,
                                    'content' => ""
                                ),
                                'success' => false,
                                'msg' => '验证失败,请重试',
                                'code' => 400
                            );
                        }
                    }
                    else
                    {
                        $output = array(
                            'data' => array(
                                'success' => true,
                                'content' => ""
                            ),
                            'success' => false,
                            'msg' => '验证未通过,请返回任务列表激活(或点击任务列表大掌柜)',
                            'code' => 400
                        );
                    }
                }
            }
        }
        $this->getLogger()->logInfo(json_encode($output));
      //  exit(json_encode($output));
        $this->response->setContent(json_encode($output));

        return $this->response;

    }
    public function  dzgstatusAction()
    {
        $this->setLogger("大掌柜");
        $this->getLogger()->logInfo("大掌柜状态");
        if (!$this->request->isPost()) {

            $this->getLogger()->logInfo("大掌柜发生状态错误：不是post提交");
            $output = array(
                'data' => array(
                    'success' => true,
                    'msg' => ''
                ),
                'success' => false,
                'msg'=>'大掌柜发生状态错误',
                'code' => 408
            );
        }
        else
        {
            $this->getLogger()->logInfo("HTTP-DATA:".$GLOBALS['HTTP_RAW_POST_DATA']);
            $userappjson=$GLOBALS['HTTP_RAW_POST_DATA'];
            $userappjson=str_replace(" ", "",$userappjson);
            $userappjson=str_replace("\r", "",$userappjson);
            $userappjson=str_replace("\n", "",$userappjson);
            $restul=json_decode($userappjson,true);
            $this->getLogger()->logInfo("userid:".$restul[0]["userid"]);
            $redis=CacheFactory::createCache("Redis");
            $isexistuser=false;
            if($redis->IsExist($restul[0]["userid"]))
            {
                $isexistuser=true;
                $channeluserid=$redis->GetHValue($restul[0]["userid"],"account");
                $userheader= $redis->GetHValue($restul[0]["userid"],"headerurl");
            }
            else
            {
                $userinfo = Userinfo::findFirstByID( $restul[0]["userid"]);
                if($userinfo)
                {
                    $isexistuser=true;
                    $channeluserid=$userinfo->getAccount();
                    $userheader= $userinfo->getHeaderurl();
                }
            }

           if($isexistuser)
           {

               $this->setDiverToken($restul[0]["userid"],$restul[0]["deviceToken"],$redis);
               $this->getLogger()->logInfo("大掌柜状态-".$restul[0]["userid"]);

               $redis->setValue("user_userzhanggui_".$restul[0]["userid"],1);
               $redis->setHValue("user_userzhanggui_online",$restul[0]["userid"],1);
               $redis->setHValue("user_userzhanggui_online_opentime",$restul[0]["userid"], strtotime(date("Y-m-d H:i:s")));
               $redis->setHValue("user_userzhanggui_online_Modifiedtime",$restul[0]["userid"], strtotime(date("Y-m-d H:i:s")));

               $redis->setHValue("userappinfo_MQ","app_".$restul[0]["userid"],$restul[0]["userid"]."$".$userappjson);

               $output = array(
                   'data' => array(
                       'success' => true,
                       'content' => ""
                   ),
                   'success' => true,
                   'msg' => '大掌柜发送数据成功',
                   'userid' => $restul[0]["userid"],
                   'headerurl' =>$userheader,
                   'code' => 408
               );
           }
            else
            {
                $output = array(
                    'data' => array(
                        'success' => true,
                        'content' => ""
                    ),
                    'success' => false,
                    'msg' => '用户不存在',
                    'code' => 408
                );
            }
        }

       $this->getLogger()->logInfo(json_encode($output));
     //   exit(json_encode($output));
        $this->response->setContent(json_encode($output));
        return $this->response;
    }
    public  function  openAppAction()
    {
        $this->setLogger("weixin");
        $gametype =$this->request->getQuery('gametype');
        $appid =urldecode($this->request->getQuery('click'));

        if($gametype==1)
        {
            $this->getLogger()->logInfo("开始应用下载:".$appid);
             //  echo "<script> window.location.href(\"http://itunes.apple.com/us/app/id\"".$appid.")</script>";
        }
        else
        {
            $this->getLogger()->logInfo("开始应用搜索:".$appid);
            echo "<meta. http-equiv=refresh content='0; url=www.baidu.com'>";

        }
    }
   //微信接口
    public  function  webChatAction()
    {

    }


    /*
    /*
     * 二维码

    public  function  qrCodeAction()
    {
        include "phpqrcode/phpqrcode.php";
        $value='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx89f2077a35b48a40&redirect_uri=http://jyq.ihmedia.com.cn/weixin/invitepage?wuserid='.$this->persistent->wuserid.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect';
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 6;
        QRcode::png($value, 'img/xiangyang.png', $errorCorrectionLevel, $matrixPointSize, 2);
        echo "QR code generated"."<br />";
        $logo = 'img/logo.png';
        $QR = 'img/xiangyang.png';

        if($logo !== FALSE)
        {

            $QR = imagecreatefromstring(file_get_contents($QR));
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        imagepng($QR,'img/xiangyanglog.png');
        exit;
    }
     */

}
