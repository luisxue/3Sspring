<?php

class SysUserDone extends ModelBase
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
    protected $done;

    /**
     *
     * @var integer
     */
    protected $donetime;

    /**
     *
     * @var string
     */
    protected $content;

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
     * Method to set the value of field done
     *
     * @param string $done
     * @return $this
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Method to set the value of field donetime
     *
     * @param integer $donetime
     * @return $this
     */
    public function setDonetime($donetime)
    {
        $this->donetime = $donetime;

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
    public function getUserid()
    {
        return $this->userId;
    }

    /**
     * Returns the value of field done
     *
     * @return string
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Returns the value of field donetime
     *
     * @return integer
     */
    public function getDonetime()
    {
        return $this->donetime;
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
            'done' => 'done', 
            'donetime' => 'donetime', 
            'content' => 'content', 
            'remark' => 'remark'
        );
    }

}
