<?php

/**
 * ColorBox
 * URL: http://www.jacklmoore.com/colorbox
 * To popup div which is already loaded but hidden or by generating ajax call
 * Or loading group photo.
 * 
 * @author Mohsin Shoaib
 */

/**
  transition    : "elastic",
  speed         : 300,
  width         : false,
  initialWidth  : "600",
  innerWidth    : false,
  maxWidth      : false,
  height        : false,
  initialHeight : "450",
  innerHeight   : false,
  maxHeight     : false,
  scalePhotos   : true,
  scrolling     : true,
  inline        : false,
  html          : false,
  iframe        : false,
  fastIframe    : true,
  photo         : false,
  href          : false,
  title         : false,
  rel           : false,
  opacity       : 0.9,
  preloading    : true,
  current       : "image {current} of {total}",
  previous      : "previous",
  next          : "next",
  close         : "close",
  open          : false,
  returnFocus   : true,
  loop          : true,
  slideshow     : false,
  slideshowAuto : true,
  slideshowSpeed: 2500,
  slideshowStart: "start slideshow",
  slideshowStop : "stop slideshow",
  onOpen        : false,
  onLoad        : false,
  onComplete    : false,
  onCleanup     : false,
  onClosed      : false,
  overlayClose  : true,
  escKey        : true,
  arrowKey      : true
 */
class ColorBox extends CWidget
{

    /**
     * Register required js and css scripts
     */
    public function registerScripts()
    {
        $cs = Yii::app()->clientScript;

        $cs->registerScriptFile(Yii::app()->request->baseUrl . '/media/colorbox/jquery.colorbox.js');
        $cs->registerCssFile(Yii::app()->request->baseUrl . '/media/colorbox/colorbox.css');
    }

    /**
     * Generate Link with regarding scripts
     * @param String $class_name
     * @param Array $colorBoxOptions 
     */
    public function generate($class_name = "", $colorBoxOptions = array())
    {
        self::registerScripts();
        if (!empty($class_name))
        {
            Yii::app()->clientScript->registerScript($class_name, '
            jQuery.noConflict();
            jQuery(document).ready(function(){
                jQuery(".' . $class_name . '").colorbox(' . CJSON::encode($colorBoxOptions) . ');
            });',CClientScript::POS_END);
        }
    }

    /**
     * Link Function
     *  It is normally called just like CHtml::link() function with one additional 
     * parameter colorBoxOptions array. In which you can sett all options provided
     * in its js.
     * 
     * @param String $title
     * @param String $url
     * @param Array $htmlOptions
     * @param Array $colorBoxOptions
     * @return String
     * @throws CHttpException 
     */
    public function link($title, $url = "#", $htmlOptions = array(), $colorBoxOptions = array())
    {
        if (isset($htmlOptions["class"]))
        {
            $lastClass = end(explode(" ", $htmlOptions["class"]));

            self::generate($lastClass, $colorBoxOptions);
        }
        else
        {
            throw new CHttpException(400, 'Class is required.');
        }

        return CHtml::link($title, $url, $htmlOptions);
    }

}

?>
