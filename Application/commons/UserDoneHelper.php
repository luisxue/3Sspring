
<?php

class UserDoneHelper {


   public  function SetUserDone($doneuser,$done,$content)
    {
        $sysUserDone = new SysUserDone();
        $sysUserDone->setContent($content);
        $sysUserDone->setDone($done);
        $sysUserDone->setDonetime(strtotime(date("Y-m-d H:i:s")));
        $sysUserDone->setUserid($doneuser);
        if (!$sysUserDone->save()) {

        }

    }


}