<?php

class Userappinfo extends ModelBase
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
    protected $userId;

    /**
     *
     * @var integer
     */
    protected $opentime;

    /**
     *
     * @var integer
     */
    protected $endtime;

    /**
     *
     * @var integer
     */
    protected $opencount;
    /**
     *
     * @var string
     */
    protected  $remark;
    /**
     *
     * @var string
     */
    protected  $appname;
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
     * Method to set the value of field userId
     *
     * @param integer $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Method to set the value of field opentime
     *
     * @param integer $opentime
     * @return $this
     */
    public function setOpentime($opentime)
    {
        $this->opentime = $opentime;

        return $this;
    }

    /**
     * Method to set the value of field endtime
     *
     * @param integer $endtime
     * @return $this
     */
    public function setEndtime($endtime)
    {
        $this->endtime = $endtime;

        return $this;
    }

    /**
     * Method to set the value of field opencount
     *
     * @param integer $opencount
     * @return $this
     */
    public function setOpencount($opencount)
    {
        $this->opencount = $opencount;

        return $this;
    }


    /**
     * Method to set the value of field remark
     *
     * @param string $remark
     * @return $this
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }
    /**
     * Method to set the value of field appname
     *
     * @param string $appname
     * @return $this
     */
    public function setAppname($appname)
    {
        $this->appname = $appname;

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
     * Returns the value of field userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Returns the value of field opencount
     *
     * @return integer
     */
    public function getOpencount()
    {
        return $this->opencount;
    }

    /**
     * Returns the value of field opentime
     *
     * @return integer
     */
    public function getOpentime()
    {
        return $this->opentime;
    }

    /**
     * Returns the value of field endtime
     *
     * @return integer
     */
    public function getEndtime()
    {
        return $this->endtime;
    }

    /**
     * Returns the value of field remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }
    /**
     * Returns the value of field appname
     *
     * @return string
     */
    public function getAppname()
    {
        return $this->appname;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID',
            'userId' => 'userId',
            'appname'=>'appname',
            'opentime' => 'opentime',
            'endtime' => 'endtime',
            'opencount' => 'opencount',
            'remark'=>'remark'
        );
    }

}
