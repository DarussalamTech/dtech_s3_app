<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

include '_config/_conf_db.php';
include '_config/_conf_logs.php';
include '_config/_conf_import.php';
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    'import' => $import,
    // preloading 'log' component
    'preload' => array('log'),
    // application components
    'components' => array(
        'db' => $conf_component_db,
        'log' => $logs,
    ),
);