<?php

/**
 * @author Ali Abbas
 * @abstract use for 
 *  setting import class
 *  
 */
include '_urls/_admin_url_manager.php'; //including url config ro admin
include '_urls/_web_url_manager.php';  //including url config for web module user's site


/*
 * merging the url of both admin 
 * and user site/webmodule to   
 * configur into single array :ubd+ab
 */
$rules = array_merge($rules_admin, $rules_web);

$url_manager = array(
    'urlFormat' => 'path',
    'showScriptName' => true,
    'rules' => $rules
);
//$url_manager = array();
?>
