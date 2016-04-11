<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Validator\StringLength as StringLength;
use Phalcon\Mvc\Model\Validator\PresenceOf as PresenceOf;
class SysRole extends ModelBase
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
    protected $Name;

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
     * Method to set the value of field Name
     *
     * @param string $Name
     * @return $this
     */
    public function setName($Name)
    {
        $this->Name = $Name;

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
     * Returns the value of field Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }
    public function validation()
    {
        $this->validate( new PresenceOf(array(
            'field' => 'Name',
            'message' => '角色名称不能空'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'Name',
            'message' => '已经存在此角色'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'Name' => 'Name'
        );
    }

}
