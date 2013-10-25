<?php

/*
 * scrolling based page loading 
 * extention 
 */

class DTScroller extends CLinkPager {

    public $ajax = false;
    public $jsMethod = "";

    /**
     * only in case when u have to append extra param
     * @var type 
     */
    public $append_param;

    public function init() {

        $this->maxButtonCount = $this->pages->getPageCount(); // to remove the next and first label from pagination menu
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . "/media/js/scrollpagination.js", CClientScript::POS_HEAD);

        Yii::app()->clientScript->registerScript('DTScroller', "
         var total_no_of_pages = '" . $this->pages->getPageCount() . "';
         $('#list_featured').scrollPagination({
                    'contentPage': 'democontent.html', // the url you are fetching the results
                    'contentData': {}, // these are the variables you can pass to the request, for example: children().size() to know which page you are
                    'scrollTarget': $(window), // who gonna scroll? in this example, the full window
                    'heightOffset': 15, // it gonna request when scroll is 10 pixels before the page ends
                    'beforeLoad': function(){ // before load function, you can display a preloader div
                        //$('#loading').fadeIn();
                     },
                     loaddata : function (){
                        setTimeout(function(){
                            nextelem = jQuery('.yiiPager li.selected').next().children().eq(0);
                            if(parseInt(jQuery.trim(nextelem.html()))<=parseInt(total_no_of_pages)){
                                nextelem.trigger('click');                          
                                jQuery('.yiiPager li.selected').attr('class', 'page');
                                jQuery(nextelem).parent().attr('class', 'page selected');
                            }
                        },500)
                     }
                 
                });

                // code for fade in element by element
                $.fn.fadeInWithDelay = function(){
                    var delay = 0;
                    return this.each(function(){
                        $(this).delay(delay).animate({opacity:1}, 200);
                        delay += 100;
                    });
                };
                       

    ");

        parent::init();
    }

    public function createPageButton($label, $page, $class, $hidden, $selected) {
        if ($this->ajax == true) {
            if ($hidden || $selected) {
                $class.=' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
            }
            $htmlOptions = array();
            if ($this->jsMethod != "") {
                $htmlOptions = array("onclick" => $this->jsMethod);
            }
            $pageUrl = $this->createPageUrl($page);
            /**
             * extra param will be append
             */
            if (!empty($this->append_param)) {
                $this->append_param = utf8_decode($this->append_param);
                if (strstr($pageUrl, "?")) {
                    $pageUrl.= "&" . $this->append_param;
                } else {
                    $pageUrl.= "?" . $this->append_param;
                }
            }
            return '<li   class="' . $class . '">' . CHtml::link($label, $pageUrl, $htmlOptions) . '</li>';
        } else {
            return parent::createPageButton($label, $page, $class, $hidden, $selected);
        }
    }

}

?>
