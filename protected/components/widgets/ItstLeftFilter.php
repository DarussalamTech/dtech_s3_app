<?php

/**
 * @author :Ali Abbas
 * @version : since 1.1
 * 
 * Purpose of this class to 
 * make common filter 
 * for all modules 
 * that having filter on left side 
 *   
 */
Yii::import('zii.widgets.CPortlet');

class ItstLeftFilter extends CPortlet
{
    /* Set Css Class */

    public $cssClass;

    /* Filters Array */
    public $filters;

    /* Set ajax true/false */
    public $ajax;

    /* where to get data */
    public $action;

    /* which model will provide search */
    public $model;

    /* Project Grid id */
    public $grid_id;
    public $show_all = false;
    
    /**
     * used only for particular view should be presetn
     * @var type 
     */
    public $view;

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
    public $extraParam = array();

    /* In case of multiple grid true give class name */
    public $multiple_grid;
    public $grid_class;
    public $querystring;
    public $tree = false;

    /* array for concatenate key value paris based on index of filters array if each index is an array of arrays */
    private $params = array();
    private $count;

    /**
     * if you want to to id (key) in url parameter
     * instead of field value 
     * do it true
     * @var type 
     */
    public $keyUrl = false;

    /**
     * Init 
     */
    public function init()
    {
        parent::init();

        /** in case of querystring ** for document linking */
        if (Yii::app()->request->getQueryString() != "")
        {
            $this->querystring = Yii::app()->request->getQueryString();
            if ($this->querystring != "")
            {
                $this->querystring = "&" . $this->querystring;
            }
        }
        
        if(empty($this->view))
        {
            $this->view = "index";
        }
    }

    /**
     * Render Contents
     */
    protected function renderContent()
    {
        if ($this->tree == true)
        {
            $this->render('itstLeftFilterTree');
        } else
        {
            $this->render('itstLeftFilter');
        }
    }

    /**
     * This is helping function takes the current index and removes all next indexes from the Array
     * @param type $first_index
     * @return type 
     */
    private function _unset_params($first_index)
    {
        $count = 0;
        $first_index;
        foreach ($this->params as $key => $value)
        {
            if (get_class($this->model) . '[' . $first_index . ']' == $key)
            {
                $count++;
                continue;
            }
            if ($count > 0)
            {
                unset($this->params[$key]);
            }
        }
        /* Set filter flag = 1 to disable/hide search. */
        $this->params['f'] = 1;
        return $this->params;
    }

    /**
     * this is the recursive function that gets the array and creates the link
     * @param type $filters 
     */
    public function TreeView($filters)
    {
        if (!empty($filters))
        {
            echo CHtml::openTag("ul");
            /* Gets key of filter array */
            $first_index = key($filters);

            /* traverse array of array to get the column name and value */
            foreach ($filters[$first_index] as $field_value => $field_label)
            {
//                echo $field_value;
                /* Concatenate array to preserve the previous values */

                echo CHtml::openTag("li");
                $this->params = array_merge($this->params, array(get_class($this->model) . '[' . $first_index . ']' => $field_value));
                $this->params = $this->_unset_params($first_index);
                /* create link if field_label is an array and call again this function */

                if (is_array($field_label))
                {

                    /* Fetch first key of array and use it as a label */


                    $label = key($field_label);
                    echo CHtml::link("", Yii::app()->controller->createUrl('index', $this->params), array('class' => 'minimize'));
                    echo CHtml::link($label, Yii::app()->controller->createUrl('index', $this->params));
                    /* call funciton recursively and give it a $field_label[$label] which is an array */
                    $this->TreeView($field_label[$label]);
                }
                /* If it is not an array */ else
                {

                    $label = $field_label;
                    if (!empty($label))
                    {

                        echo CHtml::link("", Yii::app()->controller->createUrl('index', $this->params), array('class' => 'maximize'));
                        echo CHtml::link($label, Yii::app()->controller->createUrl('index', $this->params));
                    }
                }

                echo CHtml::closeTag("li");
            }

            echo CHtml::closeTag("ul");
        }
    }

}

?>
