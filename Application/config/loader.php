<?php

$loader = new \Phalcon\Loader();

/**
 * 应用树定义类的 自动加载
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->logsDir,
        $config->application->libraryDir,
        $config->application->pluginsDir,
        $config->application->commonsDir,
        $config->application->factoryDir,
        $config->application->cacheDir
    )
)->register();
