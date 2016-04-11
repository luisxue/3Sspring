<?php
/**
 * Created by PhpStorm.
 * User: fu
 * Date: 2015/4/13
 * Time: 11:22
 */
class FilterIdfaHelper {

    protected  $_logger;
    protected  $redis;
    protected $mySqlHelper;
    protected  $con;
    public $numno;
    protected  $isChannel=false;
    private  function  setLogger($account)
    {
        if($this->_logger==false)
            $this->_logger=new Loggers($account,"IDFA");
        return $this;
    }
    private  function  getLogger()
    {
        return $this->_logger;
    }

    function __construct($isChannel)
    {
        $this->setLogger("IDFA");
        $this->redis=CacheFactory::createCache("Redis");
        $this->isChannel=$isChannel;
    }
    public  function setCon($con)
    {
        $this->con=$con;
    }
    public  function setMySql($mysql)
    {
        $this->mySqlHelper=$mysql;
    }
    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    function send_post($url, $post_data) {

        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }
    private function  goToduibiQianDuo($appId,$list,$url,$appIdentifier)
    {
        $this->getLogger()->logInfo("查询地址:".$appId."--url".$url);
        $data = array();
        $urllist=explode("&",$url);
        $url=$urllist[0];
        $this->getLogger()->logInfo("查询地址:".$appId."--url".$url);
        // $data["appid"] = $appstroeId;
        //juyouqian
        $data[$urllist[1]] ="02:00:00:00:00:00";
        $data[$urllist[2]] =$list;
        $this->getLogger()->logInfo(json_encode($data));
        //$taskinfo->getUrl()
        //  $resultjson = $this->httpService($url, $data);
        $resultjson = $this->send_post($url, $data);
        $jsoninfo = json_decode($resultjson, true);
        $this->getLogger()->logInfo("应用返回:".$appId."--".$resultjson);
        if($this->isChannel)
        {

            if ($jsoninfo["message"] == "false")
            {
                $output = array(
                    $list => 0
                );
            }
            else
            {
                $output = array(
                    $list => 1
                );
            }
            return json_encode($output);
        }
        else
        {
            //  if((int)$jsoninfo[$key]==0) //0是没有安装过的
            if ($jsoninfo["message"] == "false") {
                $sqluser = "select * from userdeviceinfo where IDFA='" . $list . "'";
                $resul = $this->mySqlHelper->GetResult($sqluser, $this->con);
                if ($resul != null && $resul != "") {
                    $row = mysqli_fetch_array($resul);
                    $filteruser = new Filteruser();
                    $filteruser->setIdfa($list);
                    $filteruser->setUserId($row["userid"]);
                    $filteruser->setAppId($appId);
                    $filteruser->save();
                    $filteruserarray = array();
                    $filteruserarray["idfa"] = $list;
                    $filteruserarray["userId"] = $row["userid"];
                    $filteruserarray["appId"] = $appId;
                    $this->redis->setHArrayValue("filter_" . $appId . "_" . $row["userid"], $filteruserarray);
                    $this->numno++;
                    //   unset($filteruserarray);
                    //  unset($filteruser);
                    //  unset($row);
                }
                //  unset($resul);
                // unset($sqluser);
            } else {
                $sqluser = "select * from userdeviceinfo where IDFA='" . $list . "'";
                $resultidfa = $this->mySqlHelper->GetResult($sqluser, $this->con);
                //   $this->getLogger()->logInfo("已安装任务-- statu=1---result:".$resultidfa);
                $rowIDFA = mysqli_fetch_array($resultidfa);
                if (count($rowIDFA) > 0) {

                    $this->getLogger()->logInfo("已安装任务---appId:" . $appId . "--userId" . $rowIDFA["userid"]);
                    $userinstalledapp = new Userinstalledapp();
                    $userinstalledapp->setUserId($rowIDFA["userid"]);
                    $userinstalledapp->setIdentifier($appIdentifier);//$appinfos->getIdentifier()
                    $userinstalledapp->setAddtime(strtotime(date("Y-m-d H:i:s")));
                    $userinstalledapp->save();
                    $userinstalledappchild = array();
                    $userinstalledappchild["id"] = $userinstalledapp->getId();
                    $userinstalledappchild["userId"] = $userinstalledapp->getUserId();
                    $userinstalledappchild["urlschemes"] = $userinstalledapp->getUrlschemes();
                    $userinstalledappchild["identifier"] = $userinstalledapp->getIdentifier();
                    $userinstalledappchild["remark"] = $userinstalledapp->getRemark();
                    $this->redis->setHArrayValue("user_installed_" . $rowIDFA["userid"] . "_" . $userinstalledapp->getIdentifier(), $userinstalledappchild);
                    // unset($userinstalledappchild);
                    // unset($userinstalledapp);
                }
                // unset($rowIDFA);
                // unset($resultidfa);
                // unset($sqluser);

            }
        }
        //unset($jsoninfo);
        //unset($resultjson);
        //unset($data);
    }
    private function  goToduibi($appId,$list,$url,$appIdentifier)
    {
        $this->getLogger()->logInfo("查询地址:".$appId."--url".$url);
        $data = array();
        $urllist=explode("&",$url);
        $url=$urllist[0];
        $this->getLogger()->logInfo("查询地址:".$appId."--url".$url);
        // $data["appid"] = $appstroeId;
        //juyouqian
        $data[$urllist[1]] =$urllist[4];//appid
        $data[$urllist[2]] =$urllist[5];//channel
        // $data["Idfas"] = $list;
        $data[$urllist[3]] = $list; //IDFA
        $this->getLogger()->logInfo(json_encode($data));
        //$taskinfo->getUrl()
        //  $resultjson = $this->httpService($url, $data);
        $resultjson = $this->send_post($url, $data);
        $jsoninfo = json_decode($resultjson, true);
        $this->getLogger()->logInfo("应用返回:".$appId."--".$resultjson);
        if($this->isChannel)
        {

            return $resultjson;
        }
        else
        {
            foreach (array_keys($jsoninfo) as $key) {
                //  if((int)$jsoninfo[$key]==0) //0是没有安装过的
                if ((int)$jsoninfo[$key] == 0) {
                    $sqluser = "select * from userdeviceinfo where IDFA='" . $key . "'";
                    $resul = $this->mySqlHelper->GetResult($sqluser, $this->con);
                    if ($resul != null && $resul != "") {
                        $row = mysqli_fetch_array($resul);
                        $filteruser = new Filteruser();
                        $filteruser->setIdfa($key);
                        $filteruser->setUserId($row["userid"]);
                        $filteruser->setAppId($appId);
                        $filteruser->save();
                        $filteruserarray = array();
                        $filteruserarray["idfa"] = $key;
                        $filteruserarray["userId"] = $row["userid"];
                        $filteruserarray["appId"] = $appId;
                        $this->redis->setHArrayValue("filter_" . $appId . "_" . $row["userid"], $filteruserarray);
                        $this->numno++;
                    }
                } else {
                    $sqluser = "select * from userdeviceinfo where IDFA='" . $key . "'";
                    $resultidfa = $this->mySqlHelper->GetResult($sqluser, $this->con);
                    //   $this->getLogger()->logInfo("已安装任务-- statu=1---result:".$resultidfa);
                    $rowIDFA = mysqli_fetch_array($resultidfa);
                    if (count($rowIDFA) > 0) {

                        $this->getLogger()->logInfo("已安装任务---appId:" . $appId . "--userId" . $rowIDFA["userid"]);
                        $userinstalledapp = new Userinstalledapp();
                        $userinstalledapp->setUserId($rowIDFA["userid"]);
                        $userinstalledapp->setIdentifier($appIdentifier);//$appinfos->getIdentifier()
                        $userinstalledapp->setAddtime(strtotime(date("Y-m-d H:i:s")));
                        $userinstalledapp->save();
                        $userinstalledappchild = array();
                        $userinstalledappchild["id"] = $userinstalledapp->getId();
                        $userinstalledappchild["userId"] = $userinstalledapp->getUserId();
                        $userinstalledappchild["urlschemes"] = $userinstalledapp->getUrlschemes();
                        $userinstalledappchild["identifier"] = $userinstalledapp->getIdentifier();
                        $userinstalledappchild["remark"] = $userinstalledapp->getRemark();
                        $this->redis->setHArrayValue("user_installed_" . $rowIDFA["userid"] . "_" . $userinstalledapp->getIdentifier(), $userinstalledappchild);
                    }
                }
            }
        }
    }


    public function  selectIDFA($appId,$list,$url,$appIdentifier)
    {
        if($this->isChannel)
        {
            if($appIdentifier=="921511287")
            {

              return  $this->goToduibiQianDuo( $appId, $list, $url, $appIdentifier);
            }
            else
            {
              return    $this->goToduibi( $appId, $list, $url, $appIdentifier);
            }
        }
        else
        {
            if($appIdentifier=="com.xingduoduo.xdd")
            {

                $this->goToduibiQianDuo( $appId, $list, $url, $appIdentifier);
            }
            else
            {
                $this->goToduibi( $appId, $list, $url, $appIdentifier);
            }
        }

    }

}