<?php

class RedisHelper {
    
    const HOST = '127.0.0.1';
    const PORT = 6379;
    const AUTH = null; //replace with a string to use Redis authentication
    protected  $logger;
    public function setLogger($ID)
    {
        $this->logger = $ID;

        return $this;
    }
    public function getLogger()
    {
        return $this->logger;
    }

    function __construct() {
        $this->setUp();
    }
    /**
     * @var Redis
     */
    public $redis;

    public function setUp()
    {
        $this->redis = $this->newInstance();
    }

    private function newInstance() {
        $r = new Redis();
        $r->connect(self::HOST, self::PORT);

        if(self::AUTH) {
            $r->auth(self::AUTH);
        }
        return $r;
    }
    public function tearDown()
    {
        if($this->redis) {
            $this->redis->close();
        }
        unset($this->redis);
    }

}