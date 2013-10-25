<?php

/**
 * PGrid Count Column
 * 
 * purpose of this class to get the sum of particular column
 * that require in table
 * This can be only apply header type column not name column 
 * 
 * @author Ali Abbas <ali.abbas@darussalampk.com>
 * @since 0.1
 */
/** How to apply this class on gridview columns
 * 
 * $repForceModel=new DbModel (any)
 * 
 * $this->widget('zii.widgets.grid.CGridView', array(
  'id' => 'labor-gridsc',
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
Yii::import('zii.widgets.grid.CGridColumn');

class DtGridCountColumn extends CGridColumn
{

    /**
     *
     * @var int
     * used for setting sum value of particular column that can gain the
     * sum  
     */
    private $_total = 0;

    /**
     *
     * @var string
     * used for getting particular column name 
     * it is the index of object data to get particular 
     * column value 
     * 
     * $this->$columnName /* get the column name *******
      $value=$data->$this->$columnName
     * value of column from db
     */
    public $columnName;
    //used for printing value in decimal 
    public $decimal = false;
    public $columnSorter;
    public $model;
    public $value;
    public $valueCondition; //any condition that can be applied
    public $labelHeading; //Heading Total in bold
    public $relationColumn; //can be used print value
    public $relation; //if relational column 
    public $remove;
    
    /**
     *
     * @var type 
     * provide currecny symbol
     */
    public $currencySymbol;

    /**
     *
     * @param type $row *** for row number
     * @param type $data ** data object containing column values
     * 
     *  set the every column sum on $_total variable 
     *  and used for printing particular column value on every 
     *  particular row cell 
     */
    public function renderDataCellContent($row, $data)
    {
        if ($this->remove != true)
        {
            $columnValue = $this->columnName;
            if ($this->decimal == true)
            {
                echo number_format($data->$columnValue, 2)." ".$this->currencySymbol;
            }
            else if ($this->labelHeading == false)
            {
                echo $data->$columnValue." ".$this->currencySymbol;
            }
            if (isset($this->labelHeading) && $this->labelHeading == true)
            {
                $relation = $this->relation;
                $value = $this->value;
                $relationColumn = $this->relationColumn;
                if (isset($this->relation) && $relation != "")
                {
                    echo $data->$relation->$relationColumn;
                }
                else
                {
                    if (isset($this->valueCondition) && $data->$columnValue != $this->valueCondition)
                    {
                        echo $data->$columnValue. " ".$this->currencySymbol;
                    }
                    else
                    {
                        echo $data->$relationColumn. " ".$this->currencySymbol;
                    }
                }
            }
            else
            {
                $this->_total += is_numeric($data->$columnValue) ? $data->$columnValue : 0;
            }
        }
    }

    /**
     *  to print the column sum 
     *  on under of every particular column that 
     *  used for getting sum
     */
    public function renderFooterCellContent()
    {
        if ($this->remove != true)
        {
            if ($this->decimal == true)
            {
                echo "<span>Total</span> = ".number_format($this->_total, 2). " ".$this->currencySymbol;
            }
            else if ($this->labelHeading == true)
            {
                echo "Total";
            }
            else
            {
                echo $this->_total. " ".$this->currencySymbol;
            }
        }
    }

    protected function renderHeaderCellContent()
    {

        if ($this->remove != true)
        {
            if ($this->columnSorter == true)
            {
                $sorUrl = Yii::app()->controller->createUrl('index', array($this->model . "_sort" => $this->columnName));
                echo CHtml::link($this->header, $sorUrl, array('class' => 'pointerlabel'));
            }
            else
            {
                parent::renderHeaderCellContent();
            }
        }
    }

}

?>
