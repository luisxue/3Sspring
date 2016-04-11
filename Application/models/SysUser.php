<?php
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model\Validator\StringLength as StringLength;
use Phalcon\Mvc\Model\Validator\PresenceOf as PresenceOf;
class SysUser extends ModelBase
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
    protected $Account;

    /**
     *
     * @var string
     */
    protected $Password;

    /**
     *
     * @var string
     */
    protected $Name;

    /**
     *
     * @var integer
     */
    protected $Status;

    /**
     *
     * @var integer
     */
    protected $RoleId;

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
     * Method to set the value of field Account
     *
     * @param string $Account
     * @return $this
     */
    public function setAccount($Account)
    {
        $this->Account = $Account;

        return $this;
    }

    /**
     * Method to set the value of field Password
     *
     * @param string $Password
     * @return $this
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;

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
     * Method to set the value of field Status
     *
     * @param integer $Status
     * @return $this
     */
    public function setStatus($Status)
    {
        $this->Status = $Status;

        return $this;
    }

    /**
     * Method to set the value of field RoleId
     *
     * @param integer $RoleId
     * @return $this
     */
    public function setRoleid($RoleId)
    {
        $this->RoleId = $RoleId;

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
     * Returns the value of field Account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->Account;
    }

    /**
     * Returns the value of field Password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->Password;
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

    /**
     * Returns the value of field Status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * Returns the value of field RoleId
     *
     * @return integer
     */
    public function getRoleid()
    {
        return $this->RoleId;
    }
    public function validation()
    {
        $this->validate( new PresenceOf(array(
            'field' => 'Password',
            'message' => '密码不能为空'
        )));
        $this->validate( new PresenceOf(array(
            'field' => 'Account',
            'message' => '账号不能为空'
        )));
        $this->validate( new PresenceOf(array(
            'field' => 'Name',
            'message' => '账号不能为空'
        )));
        /*
        $this->validate( new StringLength(array(
            'field' => 'Password',
            'max' => 12,
            'min' => 6,
            'messageMaximum' => '密码长度不大于12位',
            'messageMinimum' => '密码长度最小6位'
        )));
        */
        $this->validate(new UniquenessValidator(array(
            'field' => 'Account',
            'message' => '已经存在此账号'
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
            'Account' => 'Account', 
            'Password' => 'Password', 
            'Name' => 'Name', 
            'Status' => 'Status', 
            'RoleId' => 'RoleId'
        );
    }

}
