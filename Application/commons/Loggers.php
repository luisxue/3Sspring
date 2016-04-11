
<?php

use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;
class Loggers {

    protected  $logger;
    protected  $useraccount;
    protected  $errorlogger;
    private  $type;
    public function setLogger($ID)
    {
        $this->logger = $ID;

        return $this;
    }
    public function getLogger()
    {
        return $this->logger;
    }
    public function setErrorLogger($ID)
    {
        $this->errorlogger = $ID;

        return $this;
    }
    public function getErrorLogger()
    {
        return $this->errorlogger;
    }
    private  function  setUseraccount($account)
    {
        $this->useraccount = $account;

        return $this;
    }
    private function getUseraccount()
    {
        return $this->useraccount;
    }
    function __construct($accountc='',$type)
    {
        $this->type=$type;
        $this->setUseraccount($accountc);
        if (!file_exists(APP_PATH . "logs/".$type))
        {
            mkdir (APP_PATH . "logs/".$type);
        }
        if (!file_exists(APP_PATH . "logs/".$type."/".date("Y")))
        {
            mkdir (APP_PATH . "logs/".$type."/".date("Y"));
        }
        if (!file_exists(APP_PATH . "logs/".$type."/".date("Y")."/".date("m")))
        {
            mkdir (APP_PATH . "logs/".$type."/".date("Y")."/".date("m"));
        }
        if (!file_exists(APP_PATH . "logs/".$type."/".date("Y")."/".date("m")."/".date("Y-m-d")))
        {
            mkdir (APP_PATH . "logs/".$type."/".date("Y")."/".date("m")."/".date("Y-m-d"));
        }

        $this->setLogger(new FileAdapter(APP_PATH . "logs/".$type."/".date("Y")."/".date("m")."/".date("Y-m-d")."/".date("Y-m-d H").".log"));
       $formatter = new LineFormatter("%message%");
        $this->getLogger()->setFormatter($formatter);
    }
    public  function logInfo($msg)
    {
        if (!file_exists(APP_PATH . "logs/".$this->type."/".date("Y")))
        {
            mkdir (APP_PATH . "logs/".$this->type."/".date("Y"));
        }
        if (!file_exists(APP_PATH . "logs/".$this->type."/".date("Y")."/".date("m")))
        {
            mkdir (APP_PATH . "logs/".$this->type."/".date("Y")."/".date("m"));
        }
        if (!file_exists(APP_PATH . "logs/".$this->type."/".date("Y")."/".date("m")."/".date("Y-m-d")))
        {
            mkdir (APP_PATH . "logs/".$this->type."/".date("Y")."/".date("m")."/".date("Y-m-d"));
        }
        if(!file_exists(APP_PATH . "logs/".$this->type."/".date("Y")."/".date("m")."/".date("Y-m-d")."/".date("Y-m-d H").".log"))
        {
            $this->setLogger(new FileAdapter(APP_PATH . "logs/".$this->type."/".date("Y")."/".date("m")."/".date("Y-m-d")."/".date("Y-m-d H").".log"));
            $formatter = new LineFormatter("%message%");
            $this->getLogger()->setFormatter($formatter);
        }
      $this->getLogger()->log( date('Y-m-d H:i:s')."-".$this->getUseraccount()."-".$msg);
    }
    public  function Error($msg)
    {
        if (!file_exists(APP_PATH . "logs/".$this->type."/error"))
        {
            mkdir (APP_PATH . "logs/".$this->type."/error");
        }
        if (!file_exists(APP_PATH . "logs/".$this->type."/error/".date("Y")))
        {
            mkdir (APP_PATH . "logs/".$this->type."/error/".date("Y"));
        }
        if (!file_exists(APP_PATH . "logs/".$this->type."/error/".date("Y")."/".date("m")))
        {
            mkdir (APP_PATH . "logs/".$this->type."/error/".date("Y")."/".date("m"));
        }
        if (!file_exists(APP_PATH . "logs/".$this->type."/error/".date("Y")."/".date("m")."/".date("Y-m-d")))
        {
            mkdir (APP_PATH . "logs/".$this->type."/error/".date("Y")."/".date("m")."/".date("Y-m-d"));
        }
        if(!file_exists(APP_PATH . "logs/".$this->type."/error/".date("Y")."/".date("m")."/".date("Y-m-d")."/".date("Y-m-d H").".log"))
        {
            $this->setErrorLogger(new FileAdapter(APP_PATH . "logs/".$this->type."/error/".date("Y")."/".date("m")."/".date("Y-m-d")."/".date("Y-m-d H").".log"));
            $formatter = new LineFormatter("%message%");
            $this->getErrorLogger()->setFormatter($formatter);
        }
        $this->getErrorLogger()->log(date('Y-m-d H:i:s')."-".$this->getUseraccount()."-".$msg);
    }
}