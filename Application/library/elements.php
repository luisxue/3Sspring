<?php

use Phalcon\Mvc\User\Component;

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
class elements extends Component
{
    protected  $_logger;
    private  function  setLogger($account)
    {
        if($this->_logger==false)
            $this->_logger=new Loggers($account,"Admin");
        return $this;
    }
    private  function  getLogger()
    {
        return $this->_logger;
    }
    private $_headerMenu = array(
        'navbar-left' => array(
            'index' => array(
                'caption' => '首页',
                'action' => 'home',
                'class' => 'icon-home'
            ),
            'sysuser' => array(
                'caption' => '系统用户管理',
                'action' => 'index',
                  'class' => 'icon-basket',
                'child'=>array(
                    'index'=>array(
                    'caption' => '系统用户列表',
                    'action' => 'index',
                    'class' => 'icon-tag'),
                    'new'=>array(
                        'caption' => '创建用户',
                        'action' => 'new',
                        'class' => 'icon-pencil'),
                    'sysuserLog'=>array(
                        'caption' => '操作日志',
                        'action' => 'sysuserLog',
                        'class' => 'icon-basket')
                )
            ),
            'roles' => array(
                'caption' => '角色管理',
                'action' => 'index',
                'class' => 'icon-rocket',
                'child'=>array(
                    'index'=>array(
                        'caption' => '角色列表',
                        'action' => 'index',
                        'class' => 'icon-tag'),
                    'new'=>array(
                        'caption' => '创建角色',
                        'action' => 'new',
                        'class' => 'icon-pencil')
                )
            ),
            'ad' => array(
                'caption' => '广告主管理',
                'action' => 'index',
                'class' => 'icon-star',
                'child'=>array(
                    'index'=>array(
                        'caption' => '广告主列表',
                        'action' => 'index',
                        'class' => 'icon-tag'),
                    'new'=>array(
                        'caption' => '添加广告主',
                        'action' => 'new',
                        'class' => 'icon-pencil'),
                   'prepayrecord'=>array(
                        'caption' => '预付记录',
                        'action' => 'prepayrecord',
                        'class' => 'icon-docs')
                )
            ),
            'app' => array(
                'caption' => '应用管理',
                'action' => 'index',
                'class' => 'icon-diamond',
                'child'=>array(
                    'index'=>array(
                        'caption' => '应用列表',
                        'action' => 'index',
                        'class' => 'icon-tag'),
                    'new'=>array(
                        'caption' => '添加应用',
                        'action' => 'new',
                        'class' => 'icon-pencil')
                )
            ),
            'taskinfo' => array(
                'caption' => '任务管理',
                'action' => 'index',
                'class' => 'icon-puzzle',
                'child'=>array(
                    'index'=>array(
                        'caption' => '任务列表',
                        'action' => 'index',
                        'class' => 'icon-tag'),
                    'new'=>array(
                        'caption' => '添加任务',
                        'action' => 'new',
                        'class' => 'icon-pencil'),
                    'reportforms'=>array(
                        'caption' => '任务报表',
                        'action' => 'reportforms',
                        'class' => 'icon-docs'),
                    'taskpush'=>array(
                        'caption' => '任务推送',
                        'action' => 'taskpush',
                        'class' => 'icon-docs'),
                    'selectIDFA'=>array(
                        'caption' => '查询IDFA',
                        'action' => 'selectIDFA',
                        'class' => 'icon-docs')
                )
            ),
            'user' => array(
                'caption' => '用户管理',
                'action' => 'index',
                'class' => 'icon-user',
                'child'=>array(
                    'index'=>array(
                        'caption' => '用户列表',
                        'action' => 'index',
                        'class' => 'icon-tag'),
                    'usermoney'=>array(
                        'caption' => '提现审核',
                        'action' => 'usermoney',
                        'class' => 'icon-tag')
                )
            ),
		   'interfaceinfo' => array(
                'caption' => '对接管理',
                'action' => 'index',
                'class' => 'icon-equalizer',
                'child'=>array(
                    'selectIDFA'=>array(
                        'caption' => '去重记录',
                        'action' => 'selectIDFA',
                        'class' => 'icon-tag'),
                    'clickinfo'=>array(
                        'caption' => '点击记录',
                        'action' => 'clickinfo',
                        'class' => 'icon-pencil'),
                    'callbackinfo'=>array(
                        'caption' => '回调记录',
                        'action' => 'callbackinfo',
                        'class' => 'icon-docs')
                    
                )
            ),
            'distribute' => array(
                'caption' => ' 分发管理',
                'action' => 'index',
                'class' => 'icon-folder',
                'child'=>array(
                    'channel'=>array(
                        'caption' => '分发渠道列表',
                        'action' => 'index',
                        'class' => 'icon-tag'),                   
                    'task'=>array(
                        'caption' => '分发任务列表',
                        'action' => 'task',
                        'class' => 'icon-pencil'),                  
                    'record'=>array(
                        'caption' => '分发任务记录',
                        'action' => 'record',
                        'class' => 'icon-docs')
                )
            ),
            'popularize' => array(
                'caption' => ' 推广管理',
                'action' => 'channnel',
                'class' => 'icon-wallet',
                'child'=>array(
                    'channnel'=>array(
                        'caption' => '推广渠道',
                        'action' => 'channnel',
                        'class' => 'icon-tag'),
                    'record'=>array(
                        'caption' => '推广记录',
                        'action' => 'record',
                        'class' => 'icon-pencil')
                )
                ),
                'thirdmark' => array(
                    'caption' => ' 公共号管理',
                    'action' => 'index',
                    'class' => 'icon-wallet',
                    'child'=>array(
                        'index'=>array(
                            'caption' => '公众号',
                            'action' => 'index',
                            'class' => 'icon-tag'),
                        'userinfo'=>array(
                            'caption' => '公众号用户',
                            'action' => 'userinfo',
                            'class' => 'icon-pencil'),
                        'trygame'=>array(
                            'caption' => '公众号用户试玩',
                            'action' => 'trygame',
                            'class' => 'icon-pencil')
                    )
            ),
        )
    );

    private $_tabs = array(
        'Invoices' => array(
            'controller' => 'invoices',
            'action' => 'index',
            'any' => false
        ),
        'Companies' => array(
            'controller' => 'companies',
            'action' => 'index',
            'any' => true
        ),
        'Products' => array(
            'controller' => 'products',
            'action' => 'index',
            'any' => true
        ),
        'Product Types' => array(
            'controller' => 'producttypes',
            'action' => 'index',
            'any' => true
        ),
        'Your Profile' => array(
            'controller' => 'invoices',
            'action' => 'profile',
            'any' => false
        )
    );


    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {

        $auth = $this->session->get('auth_polyRich');
        $this->persistent->currentuser= Sysuser::findFirst($auth['user_id']);
        if ($auth) {
            /*
            $this->_headerMenu['navbar-right']['login'] = array(
                'caption' => 'Log Out',
                'action' => 'end'
            );
            */

        $query = $this->modelsManager->createQuery("SELECT SysRight.rightinfo  FROM  SysRight , SysRoleRight  where SysRight.ID=SysRoleRight.rightId and SysRoleRight.roleId = :roleid:");
        $userrights = $query->execute(array(
            'roleid' => $this->persistent->currentuser->getRoleid()
        ));
            $sysrights=SysRight::find();
            $roles =array();
            foreach($userrights as $row)
            {
                array_push($roles, $row->rightinfo);

            }

            foreach($sysrights as $menu1)
            {

                if(!in_array($menu1->getRightinfo(),$roles))
               {
                   if($menu1->getRightinfo()!="index")
                  unset($this->_headerMenu['navbar-left'][$menu1->getRightinfo()]);
               }

            }


        } else {

            //unset($this->_headerMenu['navbar-left']['login']);
        }

        $controllerName = $this->view->getControllerName();
        $action = $this->view->getActionName();
        foreach ($this->_headerMenu as $position => $menu) {
          //  echo '<li>';
           // echo '<ul class="nav navbar-nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if($controller!="index")
                {
                    if ($controllerName == $controller) {
                        echo '<li class="active open">';
                        echo  '<a href="javascript:;"><i class="' . $option['class'] . '"></i>
                          <span class="title"> ' . $option['caption'] . '</span> <span class="selected"></span><span class="arrow "></span></a>';
                    } else {
                        echo '<li>';
                        echo '<a href="javascript:;"><i class="' . $option['class'] . '"></i>
                          <span class="title"> ' . $option['caption'] . '</span><span class="arrow "></span></a>';
                    }
                    echo  '<ul class="sub-menu">';
                    foreach ($option['child'] as $child => $coption)
                    {

                             if($action==$child) {
                                 echo '<li class="active">';

                             }
                        else
                        {
                            echo '<li>';

                        }
                        echo $this->tag->linkTo($controller . '/' . $child, '<i class="' . $coption['class'] . '"></i>'. $coption['caption']);
                                    echo '</li>';
                    }
                    echo   '</ul>';
                    echo '</li>';
                }
                else {
                    if ($controllerName == $controller) {
                        echo '<li class="active open">';
                        echo $this->tag->linkTo($controller . '/' . $option['action'], '<i class="' . $option['class'] . '"></i>
                          <span class="title"> ' . $option['caption'] . '</span> <span class="selected"></span><span class="arrow "></span>');
                    } else {
                        echo '<li>';
                        echo $this->tag->linkTo($controller . '/' . $option['action'], '<i class="' . $option['class'] . '"></i>
                          <span class="title"> ' . $option['caption'] . '</span><span class="arrow "></span>');
                    }
                    echo '</li>';
                }
            }
          //  echo '</ul>';
          //  echo '</li>';
        }
    }
    public  function  CloseWinXinPage()
    {
        $controllerName = $this->view->getControllerName();
        $action = $this->view->getActionName();
        if($controllerName=="weixin"&&$action=="downdzg")
        {
            echo ' setTimeout(function () {
            wx.closeWindow();
        },1800)';
        }
    }
    private  function  LoadRanking()
    {
        /*
        $controllerName = $this->view->getControllerName();
        $action = $this->view->getActionName();
        if($controllerName=="weixin"&&$action=="ranking")
        {
            echo 'window.onload = function(){
　　$.post(\'/weixin/rankinfo\',{type:0},function(data){

              eval("data="+data);
              if(data[\'success\']){
                  $(".ranking-task").html(data[\'data\'][\'content\']);

              }else{
                  alert(data[\'msg\']);
              }
          });
　　}); ';
        }
        */
    }
    public  function  GetHomeJs()
    {
        $controllerName = $this->view->getControllerName();
        $action = $this->view->getActionName();
        if($controllerName=="weixin"&&$action=="index")
        {
            echo ' <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
               ';
        }
    }
    public  function  GetCheckedJs()
    {
        $action = $this->view->getActionName();
        if(!($action=="usermoney"))
        {
            echo '  Metronic.init();';
        }


    }
    public  function  GetJs()
    {
        $controllerName = $this->view->getControllerName();
        $action = $this->view->getActionName();
       if(($controllerName=="sysuser"&&$action=="sysuserLog")||($controllerName=="ad"&&$action=="index")||
           ($controllerName=="app"&&$action=="index")||($controllerName=="user"&&$action=="usermoney")||($controllerName=="user"&&$action=="openapp")||($controllerName=="user"&&$action=="friendprice")||($controllerName=="user"&&$action=="otherprice")||($controllerName=="user"&&$action=="friendrecord")||
           ($controllerName=="user"&&$action=="index")||($controllerName=="user"&&$action=="trygame")||
           ($controllerName=="taskinfo"&&$action=="index")||
           ($controllerName=="taskinfo"&&$action=="reportforms")||($controllerName=="taskinfo"&&$action=="finsh")||($controllerName=="taskinfo"&&$action=="doneing"))
        {
               echo ' TableAjax.init();

               ';
        }
        else
        {
            echo ' Index.init();
                  Index.initDashboardDaterange();
                  Index.initJQVMAP();
                  Index.initCalendar();
                  Index.initCharts();
                  Index.initChat();
                  Index.initMiniCharts();
                  Index.initIntro();
                  Tasks.initDashboardWidget()';
        }
    }
	//设置定时刷新
    public  function SetWeiXinIndex()
    {
        $controllerName = $this->view->getControllerName();
        $action1 = $this->view->getActionName();
        if($controllerName=="weixin"&&$action1=="index")
        {
            echo 'setTimeout(function () {
            DaZhangGuiStatu();
        },
        200);

    setInterval(function () {


            DaZhangGuiStatu();

        },
        5000);
    function DaZhangGuiStatu() {

        var configPathR="";
       // var configPathR="/polyrichweb";
        $.ajax({
            type: "POST",
            url: configPathR + "/weixin/daZhangGuiStatus",
            data: {
                t: 3
            },
            beforeSend: function () {
            },
            success: function (data) {
                eval("data=" + data);
                if (data[\'success\']) {
                 $(".mask").attr("style","display:none");
                   $("#Newdazhanggui").attr("style","display:none");
				    $("#Actiondzg").attr("style","display:none");
                    $("#dazhanggui1").attr("class", "manager display");
                    $("#dazhanggui2").attr("class", "manager01");

                }
                else
            {
                if(data[\'code\']==206)
                {
                   $(".mask").attr("style","display:block");
                   $("#Actiondzg").attr("style","display:block");
                      $(".mask").css("height",$(document).height());
                      $(".mask").css("width",$(document).width());
                  $("#dazhanggui2").attr("class", "manager01 display");
                    $("#dazhanggui1").attr("class", "manager");
                }
                else if(data[\'code\']==400)
                {
                 $(".mask").attr("style","display:block");
                   $("#Newdazhanggui").attr("style","display:block");
                       $("#Newdazhanggui").css("height","100");
                      $(".mask").css("height",$(document).height());
                      $(".mask").css("width",$(document).width());
                  $("#dazhanggui2").attr("class", "manager01 display");
                    $("#dazhanggui1").attr("class", "manager");
                }
                else
                {
                $(".mask").attr("style","display:none");
                   $("#Newdazhanggui").attr("style","display:none");
				    $("#Actiondzg").attr("style","display:none");
                    $("#dazhanggui2").attr("class", "manager01 display");
                    $("#dazhanggui1").attr("class", "manager");
                }
              }
            }
        });
    }';
        }
    }
    public  function  SetRightMenu()
    {
        $controllerName = $this->view->getControllerName();
        $action1 = $this->view->getActionName();
        if(($controllerName=="weixin"&&$action1!="invitepage"&&$action1!="downdzg"&&$action1!="index"&&$action1!="qrRedirect"&&$action1!="qrCode")||$controllerName=="weigo")
        {

                      echo'wx.hideAllNonBaseMenuItem({
             success: function () {

             }
           });';
        }
        else
        {
            $this->setLogger("weixin");
            if ($this->session->has("auth_weixin")) {
                $auth = $this->session->get('auth_weixin');
                $userinfo = Userinfo::findFirstByID($auth['wuserid']);
                if($userinfo)
                {
                    $wusernickname=$userinfo->getNickname();
                  //  $wuserheaderurl= $userinfo->getHeaderurl();
                    $wuserheaderurl="https://mmbiz.qlogo.cn/mmbiz/YnfDoNY49nQic3HoBniako8hcKc3QAM4ma2WJ4teqMwd6JXKuib48PGfPfm8gArBqIlSS0uWtdTbbYibeNsNMiaFMWA/0";
                    $wuserid=$userinfo->getId();
                }

                $this->getLogger()->logInfo('邀请者地址:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe3f13ae65402c4c8&redirect_uri=http://jyq.ihmedia.com.cn/weixin/invitepage?wuserid='.$wuserid.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect');
                echo ' wx.onMenuShareAppMessage({
      title: \''. str_replace("??", "", $wusernickname).':关注聚有钱下载应用就赚钱\',
      desc: \' 聚有钱\',
      link: \'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx89f2077a35b48a40&redirect_uri=http://jyq.ihmedia.com.cn/weixin/invitepage?wuserid='.$wuserid.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect\',
      imgUrl:\''.$wuserheaderurl.'\',
      trigger: function (res) {
            // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
           // alert(JSON.stringify(res));
        },
      success: function (res) {
          // alert(JSON.stringify(res));
        },
      cancel: function (res) {
           // alert(JSON.stringify(res));
        },
      fail: function (res) {
            //alert(JSON.stringify(res));
        }
    });  wx.showAllNonBaseMenuItem();

    wx.onMenuShareTimeline({
    title: \''. str_replace("??", "", $wusernickname).':关注聚有钱下载应用就赚钱\',
    link: \'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx89f2077a35b48a40&redirect_uri=http://jyq.ihmedia.com.cn/weixin/invitepage?wuserid='.$wuserid.'&response_type=code&scope=snsapi_base&state=1#wechat_redirect\',
    imgUrl: \''.$wuserheaderurl.'\',
    success: function () {
        // 用户确认分享后执行的回调函数
    },
    cancel: function () {
        // 用户取消分享后执行的回调函数
    }
});
    ';

            }
        }
    }

    
    /**
     * Returns menu tabs
     */
    public function getTabs()
    {
        $controllerName = $this->view->getControllerName();
        $actionName = $this->view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach ($this->_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo $this->tag->linkTo($option['controller'] . '/' . $option['action'], $caption), '<li>';
        }
        echo '</ul>';
    }
    public  function  getLogoutHtml()
    {
        $auth = $this->session->get('auth_polyRich');
        if ($auth)
        {
            $this->persistent->currentuser= Sysuser::findFirst($auth['user_id']);

        }
        echo $this->persistent->currentuser->getName();
    }
    public  function  getCheckFiledForRigth($ID,$new)
    {

        $auth = $this->session->get('
}auth_polyRich');
        //Query the active user
        $sysrights=SysRight::find();
        /*
        $userrights=SysRoleRight::find(array(
        "(roleId Like :roleId:)",
        'bind' => array('roleId' => '%' .   . '%')
    ));
        */

        $query = $this->modelsManager->createQuery("SELECT rightId FROM SysRoleRight WHERE  roleId = :roleid:");
        $userrights = $query->execute(array(
            'roleid' => $ID
        ));

        $rights =array();
        foreach($userrights as $row)
        {
            array_push($rights, $row->rightId);
        }



      
        foreach ($sysrights as $sysright) {

            if(in_array($sysright->getId(),$rights)&&$new)
            {
                echo '<input type="checkbox" name="hobby[]" value="' . $sysright->getId() . '" checked="checked" />' . $sysright->getDescription() . '<br>';
            }
            else
            echo '<input type="checkbox" name="hobby[]" value="' . $sysright->getId() . '" />' . $sysright->getDescription() . '<br>';
            /*
            echo  '&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp<input type="checkbox" name="hobby[]" value="1"/>查询
            &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="hobby[]" value="2"/>创建
            &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="hobby[]" value="3"/>编辑
            &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="hobby[]" value="3"/>删除
            <br><br>';
             */
        }

    }
}
