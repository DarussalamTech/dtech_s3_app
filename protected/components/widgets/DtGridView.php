<?php

/**
 * DtGridView
 * 
 * purpose of this class to customize the grid 
 * properties and summaries 
 * This can be used to handle error summary customization 
 * and in case of using renderEmptyText 
 * 
 * My purpose to use renderEmptyText()
 * to handle the access of projects
 * if user has project right then he can viset Project data
 * other wise noe
 * 
 * @author Ali Abbas <ali.abbas@darussalampk.com>
 * @since 0.1
 */
/** How to apply this class on gridview columns
 * 
 * $repForceModel=new DbModel (any)
 * 
 * $this->widget('ParGridView', array(
  'id' => 'labor-gridsc',
  'user_acccess'=>0,[if zero then wont eb able to see this other wise he will be able see in case greater then 0]
  'dataProvider' => $labor_provider,
  'columns' => array(
  array(
  'name' => 'sub_contractor_name',
  'type' => 'Raw',
  'value' => '$data->sub_contractor_name',
  'htmlOptions' => array('width' => '150'),
  ),

 * ***  This way to apply this *****  

  /*
  array(
  'header' => CHtml::activeLabel($repForceModel, 'st'),
  'class' => 'PGridCountColumn',
  'columnName' => 'st',
  'footer' => '',
  ),
  array(
  'header' => CHtml::activeLabel($repForceModel, 'ot'),
  'class' => 'PGridCountColumn',
  'columnName' => 'ot',
  'footer' => '',
  ),

  array
  (
  'class' => 'CButtonColumn',
  'visible' => $this->getPermission($this->id . ".Create"),
  'template' => '{update} {delete}',
  ),
  ),
  ));
  ?>
 */
Yii::import('zii.widgets.grid.CGridView');

class DtGridView extends CGridView
{
    /* ---it will be used for access control   --- */

    /* User access */

    public $user_acccess;
    public $totalHeading = ""; //used for calculation of total of celss

    /**
     * This variable 
     * used to handle the request against when 
     * the grid is open document linking and
     * email liking
     * @var type 
     */
    public $colorBox = false;

    /* If sort url is given than this component will enable dragable rows. */
    public $sortUrl;
    public $rowCssClass = array('even', 'odd');

    /**
     * Init Par grid view. 
     */
    public function init()
    {

        /**
         * set the colorBox
         * variable true
         * in document liking
         */
        if (!empty($_GET['colorbox']))
        {
            $this->colorBox = true;
            /**
             * setting checBox at header
             * 
             */
            $this->generateHeaderCheckBox();
        }

        parent::init();


        /* If sort url is given then register dragable row script */
        if (isset($this->sortUrl))
        {
            $this->registerDragableScript();
        }
    }

    /**
     * Register Drabable Row JS Script
     * @author Mohsin Shoaib 
     */
    public function registerDragableScript()
    {
        
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->request->baseUrl . '/packages/jui/js/jquery-ui.min.js');
        $str_js = "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };
 
        $('#" . $this->id . " table.items tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
                serial = $('#" . $this->id . " table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'class'});
                jQuery('#loading').show();
                $.ajax({
                    'url': '" . $this->sortUrl . "',
                    'type': 'post',
                    'data': serial,
                    'success': function(data){
                        jQuery('#flash-message').show();
                        jQuery('#flash-message').html('Order has been updated');
                        jQuery('#loading').hide();
                        setTimeout(function() {
                            $('#" . $this->id . "').yiiGridView.update('" . $this->id . "');
                            jQuery('#flash-message').hide();
                            window.location.reload();
                      }, 2000);
                        
                    },
                    'error': function(request, status, error){
                        alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');
                    }
                });
            },
            helper: fixHelper
        }).disableSelection();
    ";
        Yii::app()->clientScript->registerScript('sortable-project', $str_js);
    }

    public function renderTableHeader()
    {
        //$this->renderSummary();
        $this->renderPager();
        echo "<div class=clear></div>";
        parent::renderTableHeader();

        /**
         * in case of when document liking
         */
        //if($this->colorBox==true)
        {

            // $this->generateHeaderCheckBox();
        }
    }

    public function renderSummary()
    {
        //if  page size of grid greater than total items 
        if ((int) $this->dataProvider->pagination->itemCount >= $this->dataProvider->pagination->pageSize)
        {

            $this->summaryText = 'Displaying {start}-{end} of ' . $this->dataProvider->pagination->itemCount . ' result(s).';
        }
        else if(count($this->dataProvider->getData ())==0)
        {
            $this->summaryText = 'No Record Found';
        }
        else {
            $this->summaryText = '';
        }
        parent::renderSummary();
    }

    public function renderEmptyText()
    {
        if (isset($this->user_acccess) && $this->user_acccess != 0)
        {
            parent::renderEmptyText();
        }
        else
        {
            echo "No record ";
        }
    }

    public function renderTableFooter()
    {
        $colspan = count($this->columns) - 1;
        $alltotal = 0;
        $hasFilter = $this->filter !== null && $this->filterPosition === self::FILTER_POS_FOOTER;
        $hasFooter = $this->getHasFooter();
        $count = 0;
        if ($hasFilter || $hasFooter)
        {
            echo "<tfoot>\n";
            if ($hasFooter)
            {
                echo "<tr>\n";
                foreach ($this->columns as $column)
                {
                    $total = $column->renderFooterCell();
                    if ($this->totalHeading != "" && isset($column->grid->columns[$count]->_total))
                    {
                        $alltotal+=$column->grid->columns[$count]->_total;
                    }
                    $count++;
                }

                echo "</tr>\n";
                if ($this->totalHeading != "")
                {
                    $htmlOptions['colspan'] = $colspan;
                    $htmlOptions['style'] = 'font-weight:bold;font-style:italic';
                    echo "<tr>";
                    echo CHtml::openTag("td", $htmlOptions);
                    echo $this->totalHeading;
                    echo CHtml::closeTag("td");

                    $htmlOptions['colspan'] = 1;
                    echo CHtml::openTag("td", $htmlOptions);
                    echo $alltotal;
                    $this->setTotalOfAllTotals($alltotal);
                    echo CHtml::closeTag("td");

                    echo "</tr>";
                }
            }
            if ($hasFilter)
                $this->renderFilter();
            echo "</tfoot>\n";
        }
    }

    /**
     * This function will be used 
     * to set of take the sum of all totals
     * in flash variables
     * @param type $total 
     * 
     */
    private function setTotalOfAllTotals($total)
    {
        if (Yii::app()->user->hasFlash('totalGridcount'))
        {

            $totalFlashCount = Yii::app()->user->getFlash('totalGridcount') + $total;
            Yii::app()->user->setFlash('totalGridcount', $totalFlashCount);
        }
        else
        {
            Yii::app()->user->setFlash('totalGridcount', $total);
        }
    }

    /**
     * Renders a table body row.
     * @param integer $row the row number (zero-based).
     */
    public function renderTableRow($row)
    {
        /**
         * this area belongs to document liking 
         * where extram column will be displayed
         * of check box
         * 
         */
        
           $data = $this->dataProvider->data[$row];
          
        
        if ($this->colorBox == true)
        {
            $this->generateChecBoxCol($this->dataProvider->data[$row]->id);
        }
        if ($this->rowCssClassExpression !== null && ($n = count($this->rowCssClass)) > 0)
        {
            $data = $this->dataProvider->data[$row];
           
            echo '<tr class="' . $this->evaluateExpression($this->rowCssClassExpression, array('row' => $row, 'data' => $data)) . ' ' . $this->rowCssClass[$row % $n] . '">';
        }
        else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0)
            echo '<tr class="' . $this->rowCssClass[$row % $n] . '">';
        else
            echo '<tr>';
        foreach ($this->columns as $column)
            $column->renderDataCell($row);
        echo "</tr>\n";
    }

    /**
     * this will be going 
     * to add the column at first of every 
     * grid in case of grid opening in colorBox
     * then the problem will be resolved
     * 
     * (this function is not used now )
     * @param type $id 
     */
    private function generateChecBoxCol($id)
    {
        //echo '<td>' . Chtml::checkBox("document[" . $id . "]", false, array("value" => $id, "class" => "document_checkbox")) . '</td>';
    }

    /**
     * this will be going 
     * to add the header for every checkbox column
     * grid in case of grid opening in colorBox
     * then the problem will be resolved
     *  
     */
    private function generateHeaderCheckBox()
    {
        
        $column = array(
            'header' => '<input type="checkbox" value="0" id="checkallassociation" onclick="getGridId(this)" />',
            'type' => 'Raw',
            'value' => 'Chtml::checkBox("document[" . $data->id . "]", false, array("value" => $data->id, "class" => "document_checkbox"))',
        );
        ;

        $this->columns = array_merge(array($column), $this->columns);
    }

}

?>
