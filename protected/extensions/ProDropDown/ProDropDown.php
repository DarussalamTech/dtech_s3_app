<?php
/**
 * Drop Down Navigation
 *  MPN is deployed on different sub-domains for different projects. For each 
 * MPN project it is required to have different menus, different title, 
 * sub-menus and main menus. To achieve that functionality we developed a 
 * navigation control system. Each menu item of main navigation is saved in 
 * database in menus table. We can assign menu or un-assign, set sort order, 
 * sub-menu at any level through that system.
 * 
 * Note: This functionality is not under client’s control. Only MPN developers can use it
 * 
 * For this extension, it is a drop down menu which provide us proper suitable 
 * CSS and JS the rest code is divided into simple Yii CRUD.
 * 
 * @author Mohsin Shoaib
 */
class ProDropDown extends CWidget
{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        parent::run();
        
        $cs = Yii::app()->clientScript;
        
        $cs->registerScriptFile(Yii::app()->request->baseUrl . '/packages/resources/stuHover.js');
        $cs->registerCssFile(Yii::app()->request->baseUrl . '/packages/resources/pro_drop_1.css');
    }
    
}//end of class
?>
