<?php
/* +--------------------------------------------------------------------+
 * | Trees [ AS is nature as is from~ ]
 * +--------------------------------------------------------------------+
 * | Copyright (c) 2010-2015 http://www.coospeak.com All rights reserved
 * +--------------------------------------------------------------------+
 * | Licensed ( http://www.trees.org.cn/licenses/LICENSE-2.0 )
 * +--------------------------------------------------------------------+
 * | Author: Luisxue <Blog:Luisxue.sinaapp.com> <Email:4214659@qq.com>
 * +--------------------------------------------------------------------+
 * +--------------------------------------------------------------------+
 * | Create Date: 2015.07.07
 * +--------------------------------------------------------------------+
*/
/* +--------------------------------+
 * | Trees框架 核心
 * +--------------------------------+*/
//echo "success";exit;
//框架环境检测：是否支持phalcon框架，是否只有POD POD_mysql扩展等


// 使用C语言的Phalcon类
use Phalcon\Logger\Adapter\File as Logger;
error_reporting(E_ALL);
// 加载Phalcon类的组件
try {

    $config = include __DIR__ . "/../Application/config/config.php";

    define('AliUrl', $config->aliUrl->url);
    
    include __DIR__ . "/../Application/config/loader.php";

    include __DIR__ . "/../Application/config/services.php";

    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch ( \Exception $e ) {
    $logger = new Logger("/../Application/logs/".date("Y-m-d").".log");
    $logger->error($e->getMessage());
    $logger->error($e->getTraceAsString());
    //echo $e->getMessage();
}

