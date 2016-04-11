<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use Phalcon\Mvc\Model\Manager as modelsManager;
use Phalcon\DiInterface;
/*
 * Created by PhpStorm.
 * User: fu
 * Date: 2015/4/16
 * Time: 15:04
 */
class ReidsService
{

    protected  $_logger;
   // protected  $modelsManager;
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
    protected $redis;
    public function __construct() {
       $this-> redis=CacheFactory::createCache("Redis");
     //   $this->modelsManager=new modelsManager();
    }
   private function array_sort($array,$keys,$type='asc'){
//$array为要排序的数组,$keys为要用来排序的键名,$type默认为升序排序
        $keysvalue = $new_array = array();
        foreach ($array as $k=>$v){
            $keysvalue[$k] = $v[$keys];
        }
        if($type == 'asc'){
            asort($keysvalue);
        }else{
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k=>$v){
            $new_array[$k] = $array[$k];
        }
        return $new_array;
    }
//SORT_DESC    //SORT_ASC
   private function multi_array_sort($multi_array,$sort_key,$sort=SORT_DESC){
        if(is_array($multi_array)){
            foreach ($multi_array as $row_array){
                if(is_array($row_array)){
                    $key_array[] = $row_array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_array,$sort,$multi_array);
        return $multi_array;
    }
    /*
     * 获得用户任务列表
     */
    public  function  GetUserTaskList($userid)
    {
        $this->setLogger("微信页面数据");
        $taskarray=$this-> redis->GetHmValue("taskinfo");
        $tasklisthtml="";
        $num=0;
        if(count($taskarray)>0)
        {

            arsort($taskarray);
            $this->getLogger()->logInfo("获得任务列表redis:".$userid);
            $this->getLogger()->logInfo($userid."-任务数:".count($taskarray));
        //    $usertrygamearray=$redis->GetHmValue("user_usertrygames_".$userid);
         //   $usertrygameAppidarray=$redis->GetHmValue("user_usertrygames_appid_".$userid);
            $tasknum=1;
          //  foreach($taskarray as $taskinfo)
            foreach(array_keys($taskarray) as $taskinfo)
            {
                $this->getLogger()->logInfo($userid."-任务:".$taskinfo);
                if($this->redis->IsExist("taskinfo_".$taskinfo))
                {
                    $task = $this->redis->GetHmValue("taskinfo_" . $taskinfo);
                    $this->getLogger()->logInfo($userid . "-任务:" . $task["taskId"]);
                    $appinfos = $this->redis->GetHmValue("appinfo_" . $task["taskappid"]);
                    $isinterface=false;
                    $isdisply=false;
                    if((int)$task["isinterface"]==1)
                    {

                        if($this->redis->IsExist("filter_".$task["taskappid"]."_".$userid))
                        {
                            $isdisply=true;

                        }
                        $isinterface=true;
                    }
                    if ((($isinterface&&$isdisply)==true||!$isinterface)&&($this->redis->IsExist("usertrygame_" . $userid . "_" . $task["taskId"]) == false && $this->redis->IsExist("user_usertrygames_appid_" . $userid . "_" . $task["taskappid"]) == false && $this->redis->IsExist("user_installed_" . $userid . "_" . trim($appinfos["identifier"])) == false)||($this->redis->IsExist("user_installed_" . $userid . "_" .trim( $appinfos["identifier"])) == true&&$this->redis->IsExist("usrtrying_" . $userid . "_" . $task["taskId"]) ==true)||$this->redis->IsExist("usrtrying_" . $userid . "_" . $task["taskId"]) ==true) {

                        $num++;

                        $taskChange = $this->redis->GetHmValue("taskinfo_taskchange_" . $task["taskId"]);
                        $tasklisthtml .= "<div class=\"";

                        if ((int)$taskChange["currentcount"] == 0&&$this->redis->IsExist("usrtrying_" . $userid . "_" . $task["taskId"]) ==false)
                            $tasklisthtml .= "tast-list2" . "\"";
                        else
                            $tasklisthtml .= "tast-list" . "\"";

                        $showname="";
                        $searchkey="";
                        if(empty($task["searchkey"])||$task["searchkey"]==null)
                        {
                            $showname= $appinfos["name"];
                        }
                        else
                        {
                            if(strstr($task["searchkey"],"&"))
                            {

                                $searchkey=substr($task["searchkey"],0,strlen($task["searchkey"])-1);
                                $showname = $searchkey;
                            }
                            else
                            {
                                $searchkey=$task["searchkey"];
                                $showname= $appinfos["name"];
                            }
                        }
                        $tasklisthtml .= " gametype=\"" . $task["gametypeid"] . "\"  data=\"" . $appinfos["appstoreid"] . "\" value=\"" . $task["taskId"] . "\" search=\"" .$searchkey . "\" maxtime=\"" . $task["maxtime"] . "\">";


                        $tasklisthtml .= " <img class=\"touxiang\" src=\"".AliUrl . $appinfos["ico"] . "\"/>
                            <dl class=\"task-info\">
                                <dt>" . $showname . "</dt>
                                <dd>剩余 <span>" . $taskChange["currentcount"]
                            . "</span> 份 已完成
                                    <span>" . (((int)$taskChange["totaltcount"] - (int)$taskChange["currentcount"]) + $task["jiacount"]) . "</span>
                                    份
                                </dd>
                                <dd class=\"red\"
                                    style=\"width: 350px; word-break:break-all\">" . $task["gamemethod"] . " </dd>
                            </dl>";
                        if ((int)$taskChange["currentcount"] == 0) {

                            $tasklisthtml .= " <button class=\"complete\"><span>" . $task["price"] . "</span>元</button>";

                        } else {

                            $tasklisthtml .= "<button class=\"add\" value=\"" . $task["taskId"] . "\" data=\"" . $appinfos["appstoreid"]
                                . "\" gametype=\"" . $task["gametypeid"] . "\" search=\"" .$searchkey . "\">
                                    <span>" . $task["price"] . "</span>元
                                </button>";

                        }

                        $tasklisthtml .= "</div>";

                    }
                    $tasknum++;
                }
            }
        }
        else
        {


            $this->getLogger()->logInfo("获得任务列表mysql:".$userid);
            /*
            $sqlcx="select * from Taskinfo where status=1 order by addtime desc";
            $query =$this->modelsManager->createQuery($sqlcx);
            $tasklist = $query->execute();
            */
            $tasklist = Taskinfo::query()
                ->Where(" status=1")
                ->orderBy('addtime desc')
                ->execute();

            $userinstalledapplist = Userinstalledapp::query()
                ->Where(" userId= :userid:")
                ->bind(array("userid"=>$userid))
                ->execute();
            /*
            $sqlcx2="select * from Userinstalledapp where userId=".$userid;
            $query2 =$this->modelsManager->createQuery($sqlcx2);
            $userinstalledapplist = $query2->execute();
            */
            foreach($tasklist as $task)
            {
                $appinfos =Appinfos::findFirstByID( $task->getAppid());
                $usertrygames = Usertrygames::query()
                    ->where("taskId = :taskid:")
                    ->andWhere(" status=1")
                    ->andWhere(" userId= :userid:")
                    ->bind(array("taskid" =>$task->getId(),"userid"=>$userid))
                    ->execute();
                $usertrygamesApp = Usertrygames::query()
                    ->where("appId= :appId:")
                    ->andWhere(" status=1")
                    ->andWhere(" userId= :userid:")
                    ->bind(array("appId"=>$task->getAppid(),"userid"=>$userid))
                    ->execute();
                $isdisply=false;
                $isinterface=false;
                if((int)$task->getIsinterface()==1)
                {
                    $filteruser = Filteruser::query()
                        ->Where(" userId= :userid:")
                        ->bind(array("userid"=>$userid))
                        ->execute();
                    if(count($filteruser)>0)
                    {
                        $isdisply=true;

                    }
                    $isinterface=true;
                }
                if(count($usertrygames)==0&&count($usertrygamesApp)==0&&(($isinterface&&$isdisply)==true||!$isinterface))
                {
                    $isInstalled = false;
                    foreach ($userinstalledapplist as $userinstalled) {
                        if (trim($userinstalled->getIdentifier())==trim($appinfos->getIdentifier())) {
                            $isInstalled = true;
                            break;
                        }
                    }
                    $usertrying = Usertrying::query()
                        ->where("taskid = :taskid:")
                        ->andWhere(" status=1")
                        ->andWhere(" userid= :userid:")
                        ->bind(array("taskid" =>$task->getId(),"userid"=>$userid))
                        ->execute();
                    if (!$isInstalled||($isInstalled&&count($usertrying)>0)||count($usertrying)>0)
                    {
                        $num++;

                        $taskChange = Taskchange::findFirstByTaskId($task->getId());
                        $tasklisthtml.="<div class=\"";

                        if( $taskChange->getCurrentcount()==0&&count($usertrying)==0)
                            $tasklisthtml.= "tast-list2"."\"";
                        else
                            $tasklisthtml.= "tast-list"."\"";

                        $showname="";
                        $searchkey="";
                        if($task->getSearchkey()!=""||$task->getSearchkey()!=null)//如果把关键字显示为应用名称，关键字后面加&
                        {
                            if(strstr($task->getSearchkey(),"&"))
                            {
                                $searchkey=substr($task->getSearchkey(),0,strlen($task->getSearchkey())-1);
                                $showname =$searchkey;
                            }
                            else
                            {
                                $searchkey=$task->getSearchkey();
                                $showname= $appinfos->getName();
                            }
                        }
                        else
                        {
                            $showname= $appinfos->getName();
                        }
                        $tasklisthtml.=" gametype=\"". $task->getGametypeId()."\"  data=\"". $appinfos->getAppid()."\" value=\"". $task->getId() ."\" search=\"".$searchkey."\" maxtime=\"".$task->getMaxtime()."\">";


                        $tasklisthtml.=" <img class=\"touxiang\" src=\"".AliUrl. $appinfos->getIco()."\"/>
                            <dl class=\"task-info\">
                                <dt>".$showname."</dt>
                                <dd>剩余 <span>". $taskChange->getCurrentcount()
                                        ."</span> 份 已完成
                                    <span>".(($taskChange->getTotaltcount() - $taskChange->getCurrentcount()) + $task->getJiacount())."</span>
                                    份
                                </dd>
                                <dd class=\"red\"
                                    style=\"width: 350px; word-break:break-all\">". $task->getGamemethod() ." </dd>
                            </dl>";
                            if($taskChange->getCurrentcount()==0)
                            {

                            $tasklisthtml.=" <button class=\"complete\"><span>". $task->getPrice() ."</span>元</button>";

                            }
                            else
                            {

                               $tasklisthtml.="<button class=\"add\" value=\"". $task->getId()."\"data=\"". $appinfos->getAppid()
                                ."\" gametype=\"". $task->getGametypeId() ."\" search=\"". $searchkey ."\">
                                    <span>". $task->getPrice() ."</span>元
                                </button>";

                            }

                        $tasklisthtml.="</div>";

                    }

                }
            }
        }
        if($num>1)
        {
            $tasklisthtml.="<div class=\"tast-list2\"><dl class=\"task-info\"><dt></dt><dd> </dd><dd></dd></dl></div>";


        }
        if($num==0)
        {
            $tasklisthtml="  <img  style='left: 40%; top: 400px; position: absolute' src=\"img/noerror.png\" width=\"142px\" height=\"169px\"/>
            <p style='text-align: center; font-size: 28px; color: #ed3723;position: absolute; top: 600px;left: 34%;'>任务被小伙伴们抢光了</p>
            <p style='text-align: center ;font-size: 28px; color: #ed3723;position: absolute;top: 640px;left: 38%;'>快去邀请好友吧！</p>
  ";
        }
        $this->getLogger()->logInfo("任务列表html--".$userid."----".$tasklisthtml);
        return $tasklisthtml;
    }

    public  function  GetUserTodayMoney($userid)
    {
        $this->setLogger("微信页面数据");
        if($this->redis->getValue("user_todaymoney_data_".$userid)!=date("Y-m-d"))
        {
            $usertrygamesList = Usertrygames::query()
                ->Where(" status=1")
                ->andWhere(" userId= :userid:")
                ->andWhere(" gametime > :gametime:")
                ->bind(array("gametime" =>strtotime(date("Y-m-d")),"userid"=>$userid))
                ->execute();
            $priceuser=0;
            if(count($usertrygamesList)>0)
            {
                foreach($usertrygamesList as $usertrygames)
                    $priceuser+=$usertrygames->getPrice();
            }
            $userfriendsList=Userfriends::query()
                ->Where(" status=1")
                ->andWhere(" userId= :userid:")
                ->andWhere(" Addtime > :Addtime:")
                ->bind(array("userid"=>$userid,"Addtime"=>strtotime(date("Y-m-d"))))
                ->execute();
            if(count($userfriendsList)>0)
            {
                foreach($userfriendsList as $userfriends)
                    $priceuser+=$userfriends->getPrice();
            }
            $userotherpriceList=Userotherprice::query()
                ->Where(" status=1")
                ->andWhere(" userId= :userid:")
                ->andWhere(" addtime > :Addtime:")
                ->bind(array("userid"=>$userid,"Addtime"=>strtotime(date("Y-m-d"))))
                ->execute();
            if(count($userotherpriceList)>0)
            {
                foreach($userotherpriceList as $userotherprice)
                    $priceuser+=$userotherprice->getPrice();
            }
            $this-> redis->setValue("user_todaymoney_".$userid,$priceuser);
            $this-> redis->setValue("user_todaymoney_data_".$userid,date("Y-m-d"));
        }
       if($this-> redis->IsExist("user_todaymoney_".$userid)==true)
       {
           $money=(double)($this->redis->getValue("user_todaymoney_".$userid));
           $this->getLogger()->logInfo("redis获得用户今天收入--".$userid."----".$money);
       }
       else
        {
            $usertrygamesList = Usertrygames::query()
                ->Where(" status=1")
                ->andWhere(" userId= :userid:")
                ->andWhere(" gametime > :gametime:")
                ->bind(array("gametime" =>strtotime(date("Y-m-d")),"userid"=>$userid))
                ->execute();
            $priceuser=0;
            if(count($usertrygamesList)>0)
            {
                foreach($usertrygamesList as $usertrygames)
                    $priceuser+=$usertrygames->getPrice();
            }
            $userfriendsList=Userfriends::query()
                ->Where(" status=1")
                ->andWhere(" userId= :userid:")
                ->andWhere(" Addtime > :Addtime:")
                ->bind(array("userid"=>$userid,"Addtime"=>strtotime(date("Y-m-d"))))
                ->execute();
            if(count($userfriendsList)>0)
            {
                foreach($userfriendsList as $userfriends)
                    $priceuser+=$userfriends->getPrice();
            }
            $userotherpriceList=Userotherprice::query()
                ->Where(" status=1")
                ->andWhere(" userId= :userid:")
                ->andWhere(" addtime > :Addtime:")
                ->bind(array("userid"=>$userid,"Addtime"=>strtotime(date("Y-m-d"))))
                ->execute();
            if(count($userotherpriceList)>0)
            {
                foreach($userotherpriceList as $userotherprice)
                    $priceuser+=$userotherprice->getPrice();
            }
            $money=$priceuser;
            $this->getLogger()->logInfo("mysql获得用户今天收入--".$userid."----".$money);
        }
        return $money;
    }
    public function  GetTodayFriend($userid)
    {
        $this->setLogger("微信页面数据");
        if($this->redis->getValue("user_frinedcount_data_".$userid)!=date("Y-m-d"))
        {
            $userinviteList = Userinvite::query()
                ->Where(" status=1")
                ->andWhere(" friendsId= :userid:")
                ->andWhere(" Addtime > :Addtime:")
                ->bind(array("Addtime" =>strtotime(date("Y-m-d")),"userid"=>$userid))
                ->execute();
            $num=0;
            if(count($userinviteList)>0)
            {
                $num=count($userinviteList);
            }
            $this->  redis->setValue("user_frinedcount_".$userid,$num);
           $this->  redis->setValue("user_frinedcount_data_".$userid,date("Y-m-d"));
        }
        if($this-> redis->IsExist("user_frinedcount_".$userid)==false)
        {
            $countf=(int)$this->redis->getValue("user_frinedcount_".$userid);
            $this->getLogger()->logInfo("redis获得用户今天收徒--".$userid."----".$countf);
        }
        else
        {
            $userinviteList = Userinvite::query()
                ->Where(" status=1")
                ->andWhere(" friendsId= :userid:")
                ->andWhere(" Addtime > :Addtime:")
                ->bind(array("Addtime" =>strtotime(date("Y-m-d")),"userid"=>$userid))
                ->execute();
            $countf=count($userinviteList);
            $this->getLogger()->logInfo("mysql获得用户今天收徒--".$userid."----".$countf);
        }
        return $countf;
    }
    public  function  GetRanking()
    {

/*
        $userbalanceList = Userbalance::query()
            ->where("modifiedtime between :startime: and :endtime:")
            ->orderBy("totalincome desc  limit 20")
            ->bind(array("startime" =>strtotime("-6 day"),"endtime"=>strtotime(date("Y-m-d"))))
            ->execute();
        $num=1;
        $htmlpage = "";
        foreach ($userbalanceList as $userbalance) {
            $htmlpage .= "<div class=\"ranking-list\"><p class=\"num\">";

            if ($num == 1)
                $htmlpage .= " <img src=\"../img/one.jpg\"/>";
            else  if ($num == 2)
                $htmlpage .= " <img src=\"../img/two.jpg\"/>";
            else  if ($num == 3)
                $htmlpage .= " <img src=\"../img/thr.jpg\"/>";
            else
                $htmlpage .=$num;
            $htmlpage .= "</p><img class=\"touxiang\" src=\"";
            $userinfo = Userinfo::findFirstByID($userbalance->getUserid());

            $nickname=str_replace("??", "", $userinfo->getNickname());
            if($nickname>20)
                $temp=substr($nickname,0,20)."...";
            else
                $temp= $nickname ;
            //假数据
            $totalnum=$userbalance->getTotalincome();

            $totalnum=$totalnum/100;
            $htmlpage .= $userinfo->getHeaderurl() . "\  width=\"150px\" height=\"150px\"/><p class=\"name\">" .$temp . "</p>
              <p class=\"red\">" . sprintf("%.2f",$totalnum ) . " </p></div>";//暂时假数据

            $num++;
        }
       // return $htmlpage;
*/
        $this->setLogger("获得土豪榜sql--");
  //  $this->getLogger()->logInfo("获得土豪榜--".$htmlpage);



        $sqltaskprice = "select  a.ID,sum(b.price) as p ,b.gametime ,a.nickname,a.headerurl from userinfo a
                 left join usertrygames b on a.id=b.userId
                 where  b.gametime>" . strtotime("-6 day") . "  group by b.userId order by p desc limit 20";

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
                         where  b.Addtime>" . strtotime("-6 day") . " and a.ID=" . $row["ID"] . "  group by b.userId order by p desc limit 20";
            $resultfriendpriceList1=$mySqlHelper->Select($sqlfriendprice1);
            while($row1 = mysqli_fetch_array($resultfriendpriceList1))
            {
                if (array_key_exists($row1["ID"], $H_table)) {
                    $H_table[$row1["ID"]] = (double)$H_table[$row1["ID"]] + (double)$row1["p"];
                }

            }

            $sqlotherprice1 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userotherprice  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-6 day") . " and b.userId=" .  $row["ID"]. "  group by b.userId order by p desc limit 20";
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
                 where  b.Addtime>" . strtotime("-6 day") . "  group by b.userId order by p desc limit 20";

        $result2=$mySqlHelper->Select($sqlfriendprice);
        while($row = mysqli_fetch_array($result2))
        {
            if (!array_key_exists($row["ID"], $H_table)) {
                $H_table[$row["ID"]] =$row["p"];


                $sqltaskprice1 = "select  a.ID,sum(b.price) as p ,b.gametime ,a.nickname,a.headerurl from userinfo a
                        left join usertrygames b on a.id=b.userId
                        where  b.gametime>" . strtotime("-6 day") . " and a.ID=" .$row["ID"] . "  group by b.userId order by p desc limit 20";
                $resulttaskpriceList1 =$mySqlHelper->Select($sqltaskprice1);
                while($row1 = mysqli_fetch_array($resulttaskpriceList1))
                {
                    if (array_key_exists($row1["ID"], $H_table)) {
                        $H_table[$row1["ID"]] = (double)$H_table[$row1["ID"]] + (double)$row1["p"];
                    }

                }

                $sqlotherprice2 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userotherprice  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-6 day") . "  and a.ID=" .$row["ID"] . "  group by b.userId order by p desc limit 20";
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
                 where  b.Addtime>" . strtotime("-6 day") . "  group by b.userId order by p desc limit 20";
        $result3=$mySqlHelper->Select($sqlotherprice);
        while($row = mysqli_fetch_array($result3))
        {
            if (!array_key_exists($row["ID"], $H_table)) {
                $H_table[$row["ID"]] = $row["p"];
            }
            $sqlfriendprice2 = "select  a.ID,sum(b.price) as p ,b.Addtime ,a.nickname,a.headerurl from userinfo a
                         left join userfriends  b on a.id=b.userId
                         where  b.Addtime>" . strtotime("-6 day") . " and a.ID=" .$row["ID"] . "  group by b.userId order by p desc limit 20";
            $resultfriendpriceList2 =$mySqlHelper->Select($sqlfriendprice2);
            while($row1 = mysqli_fetch_array($resultfriendpriceList2))
            {
                if (array_key_exists($row1["ID"], $H_table)) {
                    $H_table[$row1["ID"]] = (double)$H_table[$row1["ID"]] + (double)$row1["p"];
                }

            }

            $sqltaskprice2 = "select  a.ID,sum(b.price) as p ,b.gametime ,a.nickname,a.headerurl from userinfo a
                        left join usertrygames b on a.id=b.userId
                        where  b.gametime>" . strtotime("-6 day") . " and a.ID=" . $row["ID"] . "  group by b.userId order by p desc limit 20";
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
        $htmlpage = "";
        foreach (array_keys($H_table) as $keytotalpice) {

            if ($numprice <= 20) {
                $htmlpage .= "<div class=\"ranking-list\"><p class=\"num\">";

                if ($numprice == 1)
                    $htmlpage .= " <img src=\"../img/one.jpg\"/>";
                else  if ($numprice == 2)
                    $htmlpage .= " <img src=\"../img/two.jpg\"/>";
                else  if ($numprice == 3)
                    $htmlpage .= " <img src=\"../img/thr.jpg\"/>";
                else
                    $htmlpage .=$numprice;
                $htmlpage .= "</p><img class=\"touxiang\" src=\"";
                $userinfo = Userinfo::findFirstByID($keytotalpice);

                $nickname=str_replace("??", "", $userinfo->getNickname());
                if($nickname>20)
                    $temp=substr($nickname,0,20)."...";
                else
                    $temp= $nickname ;
                //假数据
                $totalnum=(double)$H_table[$keytotalpice];


                $htmlpage .= $userinfo->getHeaderurl() . "\  width=\"150px\" height=\"150px\"/><p class=\"name\">" .$temp . "</p>
              <p class=\"red\">" . sprintf("%.2f",$totalnum ) . " </p></div>";//暂时假数据

            }
            $numprice++;
        }
        $this->getLogger()->logInfo("获得土豪榜--".$htmlpage);
        return $htmlpage;

    }
}