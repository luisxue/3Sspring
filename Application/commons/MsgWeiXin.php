<?php
/**
 * Created by PhpStorm.
 * User: fu
 * Date: 2015/4/13
 * Time: 11:22
 */
class MsgWeiXin {

    function __construct()
    {

    }

    private function  GetUserDeviceToken($userid)
    {
        $redis=CacheFactory::createCache("Redis");
        if($redis->IsExist("userdivertoken_" . $userid)) {
            return $userdivertoken = $redis->GetHValue("userdivertoken_" . $userid, "diverToken");
        }
        else
        {
            $userdivertoken = Userdivertoken::findFirstByUserId($userid);
            if ($userdivertoken)
                return $userdivertoken->getDiverToken();
            else
                return null;
        }

    }
    public function  sendMsg($disption,$content,$type,$userid)
    {
        $devicetoken=$this->GetUserDeviceToken($userid);
        $msgpush = new msgpush("551a4f4cfd98c537c5000891", "xc0sjxlclm0dtvx8s1wt41ov6esct9wn");
        $msgpush->sendIOSUnicast($disption,$content,$type,$devicetoken);
    }

}