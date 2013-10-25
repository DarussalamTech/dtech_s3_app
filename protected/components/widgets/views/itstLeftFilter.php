<div id="quick_filter" class="<?php echo $this->cssClass ?>">
    <?php
    $model_name = get_class($this->model);

    if ($this->show_all == true) {
        echo '<div style="padding: 5px 0px">';
        echo CHtml::link("Show All", "#", array("onClick" =>
            "
            ajaxFilter('" . $model_name . "', '', '');
            return false;
        ", "style" => "font-size:12px; color:#6F6F6F"));

        echo '</div>';
    }
   
    foreach ($this->filters as $column => $fields) {
        echo CHtml::openTag('ul');
        echo CHtml::openTag('li');
        /**
         * sometime actual label name on 
         * search bar or filter are short 
         * thats y do this to give different name of model variable
         * 
         */
        if (isset($fields['actualLabel']) && $fields['actualLabel'] == false) {
            echo CHtml::tag("label", array(), ucfirst($column));
        } else {
            echo CHtml::activeLabel($this->model, $column);
        }

        if (is_array($fields)) {
            foreach ($fields as $keyField => $field) {

                echo CHtml::openTag('ul');
                echo CHtml::openTag('li');

                if ($this->ajax) {
                    echo CHtml::link(ucwords($field), "#", array("onClick" =>
                        "
                    ajaxFilter('" . $model_name . "', '" . $column . "', '" . $field . "');
                    return false;
                "));
                } else {
                    /**
                     * if $this->keyUrl==true
                     * then instead of value 
                     * key goest to url parameters
                     */
                    $htmlOptions = array();
                    if (isset($_GET[$model_name][$column]) && ($_GET[$model_name][$column] == $keyField || $_GET[$model_name][$column] == $field)) {
                        $htmlOptions['style'] = "font-weight:bold";
                    } else {
                        $htmlOptions['style'] = "";
                    }

                    /**
                     * These Extra params 
                     * will be used 
                     * in case of any extram paramter 
                     * just like in project Report
                     * where different types are presening at 
                     * a time 
                     * to preserve project report type
                     * 
                     * @var type 
                     */
                    echo CHtml::link(ucwords($field), array($this->view, $model_name . "[" . $column . "]" => $this->keyUrl == false ? $field : $keyField), $htmlOptions);
                }
                echo CHtml::closeTag('li');
                echo CHtml::closeTag('ul');
            }
        }
        echo CHtml::closeTag('li');
        echo CHtml::closeTag('ul');
    }
    ?>
</div>
    <?php
    Yii::app()->clientScript->registerScript('linkpreserve', "
    function ajaxFilter(model_name, column, field)
    {
        setCookie('search-grid','yes');setCookie('reminder', model_name+'[' + column + ']=' + field ,1);
        $.fn.yiiGridView.update('" . $this->grid_id . "', {
            url:'" . $this->action . "' ,
            data: model_name + '[' + column + ']=' + field+'" . $this->querystring . "'
            
        });
    }
    "
            , CClientScript::POS_BEGIN
    );
    ?>


<?php
/* PCM: Refine below js code. */
if ($this->multiple_grid && $this->ajax) {
    ?>
    <script>
        function doupdate()
        {

            if (getCookie('search-grid') == "yes")
            {
                setCookie('search-grid', '');
                var lastid = $('.<?php echo $this->grid_class; ?>:last').attr('id');
                $('.<?php echo $this->grid_class; ?>').each(function() {

                    var id = $(this).attr('id');
                    var urldata = "";
                    if (id != lastid)
                    {

                        urldata = getCookie('reminder');
                        if (urldata == undefined)
                        {
                            urldata = "";
                        }

                        if ((urldata.length) > 0)
                        {

                            $.fn.yiiGridView.update(id, {
                                url: "<?php echo $this->action; ?>",
                                data: urldata + "<?php echo $this->querystring; ?>"
                            });
                        }
                        else
                        {
                            $.fn.yiiGridView.update(id, {
                                url: "<?php echo $this->action; ?>",
                                data: $('.quick-search-form form').serialize() + "<?php echo $this->querystring; ?>"
                            });
                        }
                    }
                    else
                    {
                        setCookie('reminder', '', 1);
                    }

                });
                if ($(".<?php echo $this->grid_class; ?>").length == 1)
                {
                    showHideprintbutton();
                }
            }//end of grid if to avoid pagination to update grid
        }
    </script>
    <?php
}
?>
<script>
    function showHideprintbutton()
    {
        if ($(".empty").length == $(".grid-view").length)
        {
            $(".link_btn").hide();
        }
        else
        {
            $(".link_btn").show();
        }
    }
</script>
