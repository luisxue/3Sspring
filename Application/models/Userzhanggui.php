<?php

class Userzhanggui extends ModelBase
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
    protected $status;

    /**
     *
     * @var integer
     */
    protected $opentime;

    /**
     *
     * @var integer
     */
    protected $modifiedtime;

    /*
     * @var string
     */
    protected  $version;
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
     * Method to set the value of field version
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

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
     * Returns the value of field version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
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
     * Returns the value of field opentime
     *
     * @return integer
     */
    public function getOpentime()
    {
        return $this->opentime;
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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'userid' => 'userid', 
            'status' => 'status', 
            'opentime' => 'opentime', 
            'modifiedtime' => 'modifiedtime',
            'version'=>'version'
        );
    }

}
