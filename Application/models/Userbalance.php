<?php

class Userbalance extends ModelBase
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
     * @var double
     */
    protected $currentbalance;

    /**
     *
     * @var double
     */
    protected $taskincome;

    /**
     *
     * @var double
     */
    protected $otherincome;

    /**
     *
     * @var double
     */
    protected $sendincome;

    /**
     *
     * @var double
     */
    protected $paybalance;

    /*
     * *
     * @var double
     */
    protected  $payingincome;
    /**
     *
     * @var double
     */
    protected $totalincome;

    /**
     *
     * @var integer
     */
    protected $modifiedtime;

    /**
     *
     * @var integer
     */
    protected $addtime;

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
     * Method to set the value of field currentbalance
     *
     * @param double $currentbalance
     * @return $this
     */
    public function setCurrentbalance($currentbalance)
    {
        $this->currentbalance = $currentbalance;

        return $this;
    }
    /**
     * Method to set the value of field otherincome
     *
     * @param double $otherincome
     * @return $this
     */
    public function setOtherincome($otherincome)
    {
        $this->otherincome = $otherincome;

        return $this;
    }
    /**
     * Method to set the value of field sendincome
     *
     * @param double $sendincome
     * @return $this
     */
    public function setSendincome($sendincome)
    {
        $this->sendincome = $sendincome;

        return $this;
    }
    /**
     * Method to set the value of field taskincome
     *
     * @param double $taskincome
     * @return $this
     */
    public function setTaskincome($taskincome)
    {
        $this->taskincome = $taskincome;

        return $this;
    }
    /**
     * Method to set the value of field paybalance
     *
     * @param double $paybalance
     * @return $this
     */
    public function setPaybalance($paybalance)
    {
        $this->paybalance = $paybalance;

        return $this;
    }
    /**
     * Method to set the value of field payingincome
     *
     * @param double $payingincome
     * @return $this
     */
    public function setPayingincome($payingincome)
    {
        $this->payingincome = $payingincome;

        return $this;
    }

    /**
     * Method to set the value of field totalincome
     *
     * @param double $totalincome
     * @return $this
     */
    public function setTotalincome($totalincome)
    {
        $this->totalincome = $totalincome;

        return $this;
    }

    /**
     * Method to set the value of field modifiedtime
     *
     * @param integer $modifiedtime
     * @return $this
     */
    public function setModifiedtime($modifiedtime)
    {
        $this->modifiedtime = $modifiedtime;

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
     * Returns the value of field currentbalance
     *
     * @return double
     */
    public function getCurrentbalance()
    {
        return $this->currentbalance;
    }

    /**
     * Returns the value of field otherincome
     *
     * @return double
     */
    public function getOtherincome()
    {
        return $this->otherincome;
    }
    /**
     * Returns the value of field sendincome
     *
     * @return double
     */
    public function getSendincome()
    {
        return $this->sendincome;
    }
    /**
     * Returns the value of field taskincome
     *
     * @return double
     */
    public function getTaskincome()
    {
        return $this->taskincome;
    }

    /**
     * Returns the value of field paybalance
     *
     * @return double
     */
    public function getPaybalance()
    {
        return $this->paybalance;
    }
    /**
     * Returns the value of field payingincome
     *
     * @return double
     */
    public function getPayingincome()
    {
        return $this->payingincome;
    }

    /**
     * Returns the value of field totalincome
     *
     * @return double
     */
    public function getTotalincome()
    {
        return $this->totalincome;
    }

    /**
     * Returns the value of field modifiedtime
     *
     * @return integer
     */
    public function getModifiedtime()
    {
        return $this->modifiedtime;
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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'userid' => 'userid', 
            'currentbalance' => 'currentbalance', 
            'paybalance' => 'paybalance', 
            'totalincome' => 'totalincome', 
            'modifiedtime' => 'modifiedtime', 
            'addtime' => 'addtime',
            'taskincome'=>'taskincome',
            'otherincome'=>'otherincome',
            'sendincome'=>'sendincome',
            'payingincome'=>'payingincome'
        );
    }

}
