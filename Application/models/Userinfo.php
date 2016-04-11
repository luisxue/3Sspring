<?php

class Userinfo extends ModelBase
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
    protected $account;

    /**
     *
     * @var string
     */
    protected $password;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var integer
     */
    protected $addtime;

    /**
     *
     * @var integer
     */
    protected $status;

    /**
     *
     * @var string
     */
    protected $birthday;

    /**
     *
     * @var string
     */
    protected $job;

    /**
     *
     * @var string
     */
    protected $phone;

    /**
     *
     * @var integer
     */
    protected $sex;

    /**
     *
     * @var string
     */
    protected $nickname;

    /**
     *
     * @var string
     */
    protected $headerurl;

    /**
     *
     * @var string
     */
    protected $payaccount;

    /**
     *
     * @var string
     */
    protected $payname;

    /**
     *
     * @var integer
     */
    protected $paytype;

    /**
     *
     * @var integer
     */
    protected $presenter;
    /**
     *
     * @var string
     */
    protected  $city;
    /**
     *
     * @var string
     */
    protected  $province;
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
     * Method to set the value of field account
     *
     * @param string $account
     * @return $this
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Method to set the value of field city
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }
    /**
     * Method to set the value of field province
     *
     * @param string $province
     * @return $this
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }
    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Method to set the value of field birthday
     *
     * @param string $birthday
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Method to set the value of field job
     *
     * @param string $job
     * @return $this
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Method to set the value of field phone
     *
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Method to set the value of field sex
     *
     * @param integer $sex
     * @return $this
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Method to set the value of field nickname
     *
     * @param string $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Method to set the value of field headerurl
     *
     * @param string $headerurl
     * @return $this
     */
    public function setHeaderurl($headerurl)
    {
        $this->headerurl = $headerurl;

        return $this;
    }

    /**
     * Method to set the value of field payaccount
     *
     * @param string $payaccount
     * @return $this
     */
    public function setPayaccount($payaccount)
    {
        $this->payaccount = $payaccount;

        return $this;
    }

    /**
     * Method to set the value of field payname
     *
     * @param string $payname
     * @return $this
     */
    public function setPayname($payname)
    {
        $this->payname = $payname;

        return $this;
    }

    /**
     * Method to set the value of field paytype
     *
     * @param integer $paytype
     * @return $this
     */
    public function setPaytype($paytype)
    {
        $this->paytype = $paytype;

        return $this;
    }

    /**
     * Method to set the value of field presenter
     *
     * @param integer $presenter
     * @return $this
     */
    public function setPresenter($presenter)
    {
        $this->presenter = $presenter;

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
     * Returns the value of field account
     *
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Returns the value of field city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Returns the value of field province
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }
    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Returns the value of field status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the value of field birthday
     *
     * @return string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Returns the value of field job
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Returns the value of field phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Returns the value of field sex
     *
     * @return integer
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Returns the value of field nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Returns the value of field headerurl
     *
     * @return string
     */
    public function getHeaderurl()
    {
        return $this->headerurl;
    }

    /**
     * Returns the value of field payaccount
     *
     * @return string
     */
    public function getPayaccount()
    {
        return $this->payaccount;
    }

    /**
     * Returns the value of field payname
     *
     * @return string
     */
    public function getPayname()
    {
        return $this->payname;
    }

    /**
     * Returns the value of field paytype
     *
     * @return integer
     */
    public function getPaytype()
    {
        return $this->paytype;
    }

    /**
     * Returns the value of field presenter
     *
     * @return integer
     */
    public function getPresenter()
    {
        return $this->presenter;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ID' => 'ID', 
            'account' => 'account', 
            'password' => 'password', 
            'name' => 'name', 
            'addtime' => 'addtime', 
            'status' => 'status', 
            'birthday' => 'birthday', 
            'job' => 'job', 
            'phone' => 'phone', 
            'sex' => 'sex', 
            'nickname' => 'nickname', 
            'headerurl' => 'headerurl', 
            'payaccount' => 'payaccount', 
            'payname' => 'payname', 
            'paytype' => 'paytype', 
            'presenter' => 'presenter',
            'province'=>'province',
            'city'=>'city'
        );
    }

}
