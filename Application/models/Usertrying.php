<?php

class Usertrying extends ModelBase
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
    protected $taskid;

    /**
     *
     * @var integer
     */
    protected $begintime;

    /**
     *
     * @var integer
     */
    protected $endtime;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $validate;

    /**
     *
     * @var string
     */
    protected $remark;

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
     * Method to set the value of field taskid
     *
     * @param integer $taskid
     * @return $this
     */
    public function setTaskid($taskid)
    {
        $this->taskid = $taskid;

        return $this;
    }
    /**
     * Method to set the value of field validate
     *
     * @param integer $validate
     * @return $this
     */
    public function setValidate($validate)
    {
        $this->validate = $validate;

        return $this;
    }
    /**
     * Method to set the value of field begintime
     *
     * @param integer $begintime
     * @return $this
     */
    public function setBegintime($begintime)
    {
        $this->begintime = $begintime;

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
     * Method to set the value of field status
     *
     * @param integer $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Returns the value of field taskid
     *
     * @return integer
     */
    public function getTaskid()
    {
        return $this->taskid;
    }
    /**
     * Returns the value of field validate
     *
     * @return integer
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Returns the value of field begintime
     *
     * @return integer
     */
    public function getBegintime()
    {
        return $this->begintime;
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
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID',
            'userid' => 'userid',
            'taskid' => 'taskid',
            'begintime' => 'begintime',
            'endtime' => 'endtime',
            'status' => 'status',
            'remark' => 'remark',
            'validate'=>'validate'
        );
    }

}
