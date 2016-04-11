<?php
/**
 * Created by PhpStorm.
 * User: fu
 * Date: 2015/4/27
 * Time: 15:50
 */
/**
 * Read the configuration
 */

class MySqlHelper {


    const HOST = 'localhost';
    const username = 'root';
    const pwd = '123456';
    const db = 'polyrich';

    const HOSTW = 'localhost';
    const usernameW = 'root';
    const pwdW = '123456';
    function __construct()
    {
     //  $this->config= include __DIR__ . "/../app/config/config.php";
    }


    public function  Select($sql)
    {

    //    $this->getLogger()->logInfo("host--");
    //    $this->getLogger()->logInfo("host--".$this->config->database->host);

        $con = mysqli_connect(self::HOST,self::username,self::pwd,self::db);
        if (!$con)
        {
            return "";
        }
      //  mysql_select_db(, $con);
        mysqli_query($con,"SET NAMES utf8");
        $result = mysqli_query($con,$sql);
        mysqli_close($con);
        return $result;
    }

    public  function  Connect()
    {
     return  mysqli_connect(self::HOST,self::username,self::pwd,self::db);
    }
    public function  GetResult($sql,$con)
    {
        if (!$con)
        {
            return "";
        }
        mysqli_query($con,"SET NAMES utf8");
        $result = mysqli_query($con,$sql);
        return $result;
    }
    public  function  Close($con)
    {
        mysqli_close($con);
    }

    public function  GetResultW($sql,$con)
    {
        if (!$con)
        {
            return "";
        }
        mysqli_query($con,"SET NAMES utf8");
        $result = mysqli_query($con,$sql);
        return $result;
    }
    public  function  CloseW($con)
    {
        mysqli_close($con);
    }
    public  function  ConnectW()
    {
        return  mysqli_connect(self::HOSTW,self::usernameW,self::pwdW,self::db);
    }
    public function  SelectW($sql)
    {

        //    $this->getLogger()->logInfo("host--");
        //    $this->getLogger()->logInfo("host--".$this->config->database->host);

        $con = mysqli_connect(self::HOSTW,self::usernameW,self::pwdW,self::db);
        if (!$con)
        {
            return "";
        }
        //  mysql_select_db(, $con);
        mysqli_query($con,"SET NAMES utf8");
        $result = mysqli_query($con,$sql);
        mysqli_close($con);
        return $result;
    }
}