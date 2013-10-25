<?php

/**
 *  doing custom configurations files
 *  for handle seperate configurations
 *  seperately
 */
include '_config/_conf_import.php';
include '_config/_conf_modules.php';
include '_config/_conf_component.php';
include '_config/_conf_url_manager.php';
include '_config/_conf_db.php';
include '_config/_conf_logs.php';
include '_config/_conf_params.php';

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Dtech S3',
    'theme' => 'default',
    'homeUrl'=>array('/web/default/index'),
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => $import,
    'modules' => $modules,
    // application components
    'components' => array(
        'user' => $conf_component_user,
        'urlManager' => $url_manager,
        'db' => $conf_component_db,
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'error/error', //error controoler/error actions
        ),
        'log' => $logs,
        //'email' => $conf_email_user,
        'messages' => array(
            'class' => 'CPhpMessageSource',
        // additional parameters for CDbMessageSource here
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => $params,
);