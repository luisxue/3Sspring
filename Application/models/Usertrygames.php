<?php

class Usertrygames extends ModelBase
{

    /**
     *
     * @var integer

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
    protected $taskId;

    /**
     *
     * @var integer
     */
    protected $price;

    /**
     *
     * @var integer
     */
    protected $gametime;

    /**
     *
     * @var integer
     */
    protected  $validate;
    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected  $appId;
    /**
     * Method to set the value of field ID
     *
     * @param integer $ID
     * @return $this

    public function setId($ID)
    {
        $this->ID = $ID;

        return $this;
    }
    /**
     * Method to set the value of field appId
     *
     * @param integer $appId
     * @return $this
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

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
     * Method to set the value of field userId
     *
     * @param integer $userId
     * @return $this
     */
    public function setUserid($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Method to set the value of field taskId
     *
     * @param integer $taskId
     * @return $this
     */
    public function setTaskid($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Method to set the value of field price
     *
     * @param integer $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Method to set the value of field gametime
     *
     * @param integer $gametime
     * @return $this
     */
    public function setGametime($gametime)
    {
        $this->gametime = $gametime;

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
     * Returns the value of field ID
     *
     * @return integer

    public function getId()
    {
        return $this->ID;
    }
    /**
     * Returns the value of field appId
     *
     * @return integer
     */
    public function getAppId()
    {
        return $this->appId;
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
     * Returns the value of field userId
     *
     * @return integer
     */
    public function getUserid()
    {
        return $this->userId;
    }

    /**
     * Returns the value of field taskId
     *
     * @return integer
     */
    public function getTaskid()
    {
        return $this->taskId;
    }

    /**
     * Returns the value of field price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Returns the value of field gametime
     *
     * @return integer
     */
    public function getGametime()
    {
        return $this->gametime;
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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'userId' => 'userId', 
            'taskId' => 'taskId', 
            'price' => 'price', 
            'gametime' => 'gametime', 
            'status' => 'status',
            'appId'=>'appId',
            'validate'=>'validate'
        );
    }

}
