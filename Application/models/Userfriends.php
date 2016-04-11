<?php

class Userfriends extends ModelBase
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
    protected $friendsId;

    /**
     *
     * @var integer
     */
    protected $Addtime;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var integer
     */
    protected $price;

    /**
     *
     * @var string
     */
    protected  $content;

    /**
     *
     * @var integer
     */
    protected  $taskId;
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
     * Method to set the value of field friendsId
     *
     * @param integer $friendsId
     * @return $this
     */
    public function setFriendsId($friendsId)
    {
        $this->friendsId = $friendsId;

        return $this;
    }

    /**
     * Method to set the value of field Addtime
     *
     * @param integer $Addtime
     * @return $this
     */
    public function setAddtime($Addtime)
    {
        $this->Addtime = $Addtime;

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
     * Method to set the value of field taskId
     *
     * @param integer $taskId
     * @return $this
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }
    /**
     * Method to set the value of field content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

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
     * Returns the value of field friendsId
     *
     * @return integer
     */
    public function getFriendsId()
    {
        return $this->friendsId;
    }

    /**
     * Returns the value of field Addtime
     *
     * @return integer
     */
    public function getAddtime()
    {
        return $this->Addtime;
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
     * Returns the value of field price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * Returns the value of field taskId
     *
     * @return integer
     */
    public function getTaskId()
    {
        return $this->taskId;
    }
    /**
     * Returns the value of field content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'userId' => 'userId', 
            'friendsId' => 'friendsId', 
            'Addtime' => 'Addtime', 
            'status' => 'status', 
            'price' => 'price',
            'taskId'=>'taskId',
            'content'=>'content'
        );
    }

}
