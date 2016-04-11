
<?php

class readAppName
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
    var $H_table;
    public function __construct() {
        $this->H_table = array ();
    }
    public  function GetAppInfo()
    {
        $this->setLogger("获得对应列表");
        try {
            $myfile = fopen(APP_PATH . "/app/config/AppName.config", "r") or die("Unable to open file!");
// 输出单行直到 end-of-file
            while (!feof($myfile)) {
                $row = fgets($myfile);
                $this->getLogger()->logInfo("获得对应列表-".$row);
                $stringlist = explode(",", $row);
                if (!array_key_exists($stringlist[0], $this->H_table)) {
                    $this->H_table[$stringlist[0]] = $stringlist[1];
                }

            }
            fclose($myfile);
        }catch (exception $e)
        {
            $this->getLogger()->logInfo("获得对应列表-异常".$e->getMessage());
        }
    }


}