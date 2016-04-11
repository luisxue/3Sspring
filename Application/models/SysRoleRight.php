<?php

class SysRoleRight extends ModelBase
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
    protected $roleId;

    /**
     *
     * @var integer
     */
    protected $rightId;

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
     * Method to set the value of field roleId
     *
     * @param integer $roleId
     * @return $this
     */
    public function setRoleid($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Method to set the value of field rightId
     *
     * @param integer $rightId
     * @return $this
     */
    public function setRightid($rightId)
    {
        $this->rightId = $rightId;

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
     * Returns the value of field roleId
     *
     * @return integer
     */
    public function getRoleid()
    {
        return $this->roleId;
    }

    /**
     * Returns the value of field rightId
     *
     * @return integer
     */
    public function getRightid()
    {
        return $this->rightId;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'roleId' => 'roleId', 
            'rightId' => 'rightId'
        );
    }

}
