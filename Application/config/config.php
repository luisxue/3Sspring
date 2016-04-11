<?php
// slave x3の設定だよ
//$slave1 = '[hostname(slave1)]:[port]'; // ex localhost:3316
//$slave2 = '[hostname(slave2)]:[port]'; // ex localhost:3326
//$slave3 = '[hostname(slave3)]:[port]'; // ex localhost:3336
return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => '127.0.0.1',
        'username'    => 'root',
        'password'    => '4214659',
        'dbname'      => 'polyrich',
        'charset'     => 'utf8',
    ),
  'mysqlSlaves' => array(
    'mysql_slave1' => array(
        'adapter'     => 'Mysql',
        'host'        => '127.0.0.1',
        'username'    => 'root',
        'password'    => '4214659',
        'dbname'      => 'polyrich',
        'charset'     => 'utf8',
    )
),
 'aliUrl'=>
        array(
            'url' =>'http://appimges.oss-cn-qingdao.aliyuncs.com/'
        ),
   'mysql_slave_count'=>
       array(
           'count' =>1
       ),
    'application' => array(
        'controllersDir' => __DIR__ .'/../'. APP_PATH .'controllers/',
        'modelsDir'      => __DIR__ .'/../'. APP_PATH .'models/',
        'viewsDir'       => __DIR__ .'/../'. APP_PATH .'views/',
        'pluginsDir'     => __DIR__ .'/../'. APP_PATH .'plugins/',
        'commonsDir'     => __DIR__ .'/../'. APP_PATH .'commons/',
        'libraryDir'     => __DIR__ .'/../'. APP_PATH .'library/',
        'cacheDir'       => __DIR__ .'/../'. APP_PATH .'cache/',
        'logsDir'        => __DIR__ .'/../'. APP_PATH .'logs/',
        'factoryDir'     => __DIR__ .'/../'. APP_PATH .'Factory/',
        'baseUri'        => '/',
    )
));
