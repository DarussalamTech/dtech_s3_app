<?php

/**
 * EmailGridView
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

  
Yii::import('zii.widgets.grid.CGridView');

class EmailGridView extends CGridView
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



    public function renderTableHeader()
    {
        $this->renderSummary();
        $this->renderPager();
        echo "<div class=clear></div>";
        parent::renderTableHeader();

       
    }

    public function renderSummary()
    {
        //if  page size of grid greater than total items 
        if ((int) $this->dataProvider->pagination->itemCount >= $this->dataProvider->pagination->pageSize)
        {

            //$this->summaryText = 'Displaying {start}-{end} of ' . $this->dataProvider->pagination->itemCount . ' result(s).';
        }
        else if(count($this->dataProvider->getData ())==0)
        {
           // $this->summaryText = 'No Record Found';
        }
        else {
            //$this->summaryText = '';
        }
        //parent::renderSummary();
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
            echo "<tfoot style='font-weight:bold'>\n";
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
       
         $background = "#E4E3E3";
        if ($this->rowCssClassExpression !== null && ($n = count($this->rowCssClass)) > 0)
        {
            $data = $this->dataProvider->data[$row];
            echo '<tr class="' . $this->evaluateExpression($this->rowCssClassExpression, array('row' => $row, 'data' => $data)) . ' ' . $this->rowCssClass[$row % $n] . '">';
        }
        else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0){
            echo '<tr class="' . $this->rowCssClass[$row % $n] . '">';
             $background = "#E4E3E3";
            if($this->rowCssClass[$row % $n] == "even"){
                $background = "#fff";
            }
        }
        else
            echo '<tr>';
        foreach ($this->columns as $column){
           
            $new_options = array("style"=>"text-align: left;background:$background;");
            $column->htmlOptions = array_merge($column->htmlOptions,$new_options);
            $column->renderDataCell($row);
        }
        echo "</tr>\n";
    }





}

?>
