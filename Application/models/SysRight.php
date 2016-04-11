<?php

class SysRight extends ModelBase
{

    /**
     *
     * @var integer
     */
    protected $ID;

    /**
     *
     * @var string
     */
    protected $rightinfo;

    protected $description;
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

    public function setDescription($Description)
    {
        $this->description = $Description;

        return $this;
    }

    /**
     * Method to set the value of field right
     *
     * @param string $right
     * @return $this
     */
    public function setRightinfo($right)
    {
        $this->rightinfo = $right;

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
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Returns the value of field right
     *
     * @return string
     */
    public function getRightinfo()
    {
        return $this->rightinfo;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'rightinfo' => 'rightinfo',
            'description'=>'description'
        );
    }

}
