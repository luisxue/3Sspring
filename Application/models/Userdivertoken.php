<?php

class Userdivertoken extends ModelBase
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
    protected $modieytime;

    /**
     *
     * @var integer
     */
    protected $createtime;

    /**
     *
     * @var string
     */
    protected  $diverToken;


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
     * Method to set the value of field modieytime
     *
     * @param integer $modieytime
     * @return $this
     */
    public function setModieytime($modieytime)
    {
        $this->modieytime = $modieytime;

        return $this;
    }

    /**
     * Method to set the value of field createtime
     *
     * @param integer $createtime
     * @return $this
     */
    public function setCreatetime($createtime)
    {
        $this->createtime = $createtime;

        return $this;
    }

    /**
     * Method to set the value of field diverToken
     *
     * @param string $diverToken
     * @return $this
     */
    public function setDiverToken($diverToken)
    {
        $this->diverToken = $diverToken;

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
     * Returns the value of field modieytime
     *
     * @return integer
     */
    public function getModieytime()
    {
        return $this->modieytime;
    }

    /**
     * Returns the value of field createtime
     *
     * @return integer
     */
    public function getCreatetime()
    {
        return $this->createtime;
    }

    /**
     * Returns the value of field diverToken
     *
     * @return string
     */
    public function getDiverToken()
    {
        return $this->diverToken;
    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID',
            'userId' => 'userId',
            'diverToken' => 'diverToken',
            'createtime' => 'createtime',
            'modieytime' => 'modieytime'
        );
    }

}
