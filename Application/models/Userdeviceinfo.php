<?php

class Userdeviceinfo extends ModelBase
{

    /**
     *
     * @var integer
     */
    protected $ID;

    /**
     *
     * @var integer
     */
    protected $userid;

    /**
     *
     * @var integer
     */
    protected $channeltype;

    /**
     *
     * @var string
     */
    protected $channeluserid;

    /**
     *
     * @var string
     */
    protected $IDFA;

    /**
     *
     * @var string
     */
    protected $IDFV;

    /**
     *
     * @var string
     */
    protected $APPstoreaccount;

    /**
     *
     * @var string
     */
    protected $IMEI;

    /**
     *
     * @var string
     */
    protected $carrieroperator;

    /**
     *
     * @var string
     */
    protected $phonetype;

    /**
     *
     * @var string
     */
    protected $sysversion;

    /**
     *
     * @var string
     */
    protected $ip;

    /**
     *
     * @var integer
     */
    protected $addtime;
    /**
     *
     * @var string
     */
    protected $network;
    /**
     *
     * @var string
     */
    protected $nettype;

    /**
     *
     * @var string
     */
    protected $macaddress;
    /**
     * Method to set the value of field ID
     *
     * @param integer $ID
     * @return $this
     */
    public function setId($ID)
    {
        $this->ID = $ID;

        return $this;
    }

    /**
     * Method to set the value of field userid
     *
     * @param integer $userid
     * @return $this
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Method to set the value of field channeltype
     *
     * @param integer $channeltype
     * @return $this
     */
    public function setChanneltype($channeltype)
    {
        $this->channeltype = $channeltype;

        return $this;
    }

    /**
     * Method to set the value of field channeluserid
     *
     * @param string $channeluserid
     * @return $this
     */
    public function setChanneluserid($channeluserid)
    {
        $this->channeluserid = $channeluserid;

        return $this;
    }

    /**
     * Method to set the value of field IDFA
     *
     * @param string $IDFA
     * @return $this
     */
    public function setIdfa($IDFA)
    {
        $this->IDFA = $IDFA;

        return $this;
    }

    /**
     * Method to set the value of field nettype
     *
     * @param string $nettype
     * @return $this
     */
    public function setNettype($nettype)
    {
        $this->nettype = $nettype;

        return $this;
    }
    /**
     * Method to set the value of field macaddress
     *
     * @param string $macaddress
     * @return $this
     */
    public function setMacaddress($macaddress)
    {
        $this->macaddress = $macaddress;

        return $this;
    }

    /**
     * Method to set the value of field network
     *
     * @param string $network
     * @return $this
     */
    public function setNetwork($network)
    {
        $this->network = $network;

        return $this;
    }

    /**
     * Method to set the value of field IDFV
     *
     * @param string $IDFV
     * @return $this
     */
    public function setIdfv($IDFV)
    {
        $this->IDFV = $IDFV;

        return $this;
    }

    /**
     * Method to set the value of field APPstoreaccount
     *
     * @param string $APPstoreaccount
     * @return $this
     */
    public function setAppstoreaccount($APPstoreaccount)
    {
        $this->APPstoreaccount = $APPstoreaccount;

        return $this;
    }

    /**
     * Method to set the value of field IMEI
     *
     * @param string $IMEI
     * @return $this
     */
    public function setImei($IMEI)
    {
        $this->IMEI = $IMEI;

        return $this;
    }

    /**
     * Method to set the value of field carrieroperator
     *
     * @param string $carrieroperator
     * @return $this
     */
    public function setCarrieroperator($carrieroperator)
    {
        $this->carrieroperator = $carrieroperator;

        return $this;
    }

    /**
     * Method to set the value of field phonetype
     *
     * @param string $phonetype
     * @return $this
     */
    public function setPhonetype($phonetype)
    {
        $this->phonetype = $phonetype;

        return $this;
    }

    /**
     * Method to set the value of field sysversion
     *
     * @param string $sysversion
     * @return $this
     */
    public function setSysversion($sysversion)
    {
        $this->sysversion = $sysversion;

        return $this;
    }

    /**
     * Method to set the value of field addtime
     *
     * @param integer $addtime
     * @return $this
     */
    public function setAddtime($addtime)
    {
        $this->addtime = $addtime;

        return $this;
    }
    /**
     * Method to set the value of field ip
     *
     * @param string $ip
     * @return $this
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }
    /**
     * Returns the value of field ID
     *
     * @return integer
     */
    public function getId()
    {
        return $this->ID;
    }

    /**
     * Returns the value of field userid
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Returns the value of field channeltype
     *
     * @return integer
     */
    public function getChanneltype()
    {
        return $this->channeltype;
    }

    /**
     * Returns the value of field channeluserid
     *
     * @return string
     */
    public function getChanneluserid()
    {
        return $this->channeluserid;
    }

    /**
     * Returns the value of field IDFA
     *
     * @return string
     */
    public function getIdfa()
    {
        return $this->IDFA;
    }

    /**
     * Returns the value of field network
     *
     * @return string
     */
    public function getNetwork()
    {
        return $this->network;
    }
    /**
     * Returns the value of field macaddress
     *
     * @return string
     */
    public function getMacaddress()
    {
        return $this->macaddress;
    }

    /**
     * Returns the value of field nettype
     *
     * @return string
     */
    public function getNettype()
    {
        return $this->nettype;
    }

    /**
     * Returns the value of field IDFV
     *
     * @return string
     */
    public function getIdfv()
    {
        return $this->IDFV;
    }

    /**
     * Returns the value of field APPstoreaccount
     *
     * @return string
     */
    public function getAppstoreaccount()
    {
        return $this->APPstoreaccount;
    }

    /**
     * Returns the value of field IMEI
     *
     * @return string
     */
    public function getImei()
    {
        return $this->IMEI;
    }

    /**
     * Returns the value of field carrieroperator
     *
     * @return string
     */
    public function getCarrieroperator()
    {
        return $this->carrieroperator;
    }

    /**
     * Returns the value of field phonetype
     *
     * @return string
     */
    public function getPhonetype()
    {
        return $this->phonetype;
    }

    /**
     * Returns the value of field sysversion
     *
     * @return string
     */
    public function getSysversion()
    {
        return $this->sysversion;
    }

    /**
     * Returns the value of field addtime
     *
     * @return integer
     */
    public function getAddtime()
    {
        return $this->addtime;
    }

    /**
     * Returns the value of field ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'userid' => 'userid', 
            'channeltype' => 'channeltype', 
            'channeluserid' => 'channeluserid', 
            'IDFA' => 'IDFA', 
            'IDFV' => 'IDFV', 
            'APPstoreaccount' => 'APPstoreaccount', 
            'IMEI' => 'IMEI', 
            'carrieroperator' => 'carrieroperator', 
            'phonetype' => 'phonetype', 
            'sysversion' => 'sysversion', 
            'addtime' => 'addtime',
            'ip'=>'ip',
            'nettype'=>'nettype',
            'network'=>'network',
            'macaddress'=>'macaddress'
        );
    }

}
