<?php

class Userinstalledapp extends ModelBase
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
     * @var string
     */
    protected $urlschemes;

    /**
     *
     * @var integer
     */
    protected $addtime;

    /**
     *
     * @var string
     */
    protected $identifier;

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
     * Method to set the value of field urlschemes
     *
     * @param string $urlschemes
     * @return $this
     */
    public function setUrlschemes($urlschemes)
    {
        $this->urlschemes = $urlschemes;

        return $this;
    }

    /**
     * Method to set the value of field identifier
     *
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

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
     * Returns the value of field userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
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
     * Returns the value of field urlschemes
     *
     * @return string
     */
    public function getUrlschemes()
    {
        return $this->urlschemes;
    }

    /**
     * Returns the value of field identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
            'userId' => 'userId',
            'urlschemes' => 'urlschemes',
            'addtime' => 'addtime',
            'identifier' => 'identifier',
            'remark' => 'remark',
        );
    }

}
