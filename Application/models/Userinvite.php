<?php

class Userinvite extends ModelBase
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
        );
    }

}
