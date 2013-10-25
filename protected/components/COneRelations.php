<?php

/*
  ###############################################
  ####                                       ####
  ####    Author : Ali Abbas                 ####
  ####    Date   : 5 Mar,2011                ####
  ####    Updated:                           ####
  ####                                       ####
  ###############################################
  
 *
 */

/**
 * @author Ali Abbas
 * @version :since 1.1
 */
/*
 * Class is used for inserting one to one relations 
 * reocrd bases on update and save case both 
 * view mode and create can both use this
 * when u want to insert multiple one to one records
 * 
 * 
 */

class COneRelations extends CActiveRecordBehavior
{

    /**
     * this variable will set all the childs 
     * in array 
     * array['childone']=>array("data"=>post,"relation"=>"relationName")
     * @var array 
     */
    private $childAr = array();
    /**
     *
     * @var boolean
     * This variable just like flag 
     * that check validation status on all childs
     * in loop if valid is ture then saving process
     * will be start
     * be default it will be false 
     */
    private $valid = false;
    /**
     * for validation status 
     * scanrio
     * used making model object
     * @var type 
     */
    public $scanario="";

    /**
     *
     * @param String $relation
     *      it is relation name that will be used 
     *      will be used to distinguish each relation 
     * @param array $postArray
     *          it is array that is comming from your controller 
     *          $_POST of every model just like $_POST['Model']
     *          custom array can also be used for this 
     * @param boolean $viewMode 
     *         if this variable mode is set then it will be working for 
     *         single child add and update
     *         
     */
    public function saveOnetToOneMultipleChilds($relation, $postArray, $viewMode="",$scanario="")
    {


        $model = $this->owner;
        $activeRelation = $model->getActiveRelation($relation);
        $className = $activeRelation->className;

        $this->saveViewMode($relation, $model, array($className => $postArray), $viewMode);
        $this->childAr[$className] = array_merge(array("data" => $postArray), array("relation" => $relation));
        $this->scanario=$scanario;
    }

    /**
     * It is the function that save and upate record in view mode
     * only for single child 
     * @param type $relation
     *       it is relation name that will be used 
     *      will be used to distinguish each relation 
     * @param type $model
     *           it is array that is comming from your controller 
     *          $_POST of every model just like $_POST['Model']
     *          custom array can also be used for this 
     * @param type $postArray
     *          post array that will be used
     *          if other array will be set just like post it 
     *          will be also 
     * @param type $viewMode
     *         if this variable mode is set then it will be working for 
     *         single child add and update
     * @return type 
     */
    private function saveViewMode($relation, $model, $postArray, $viewMode)
    {


        $activeRelation = $model->getActiveRelation($relation);
        $className = $activeRelation->className;

        if (!empty($viewMode) && $viewMode == true && !empty($postArray[$className]))
        {

            $fk_attribute = $activeRelation->foreignKey;

            $owner = $this->owner;
            $fk_attribute = $activeRelation->foreignKey;
            $pk = $owner->primaryKey;
            if (isset($postArray[$className]))
            {

                $m = empty($postArray[$className][$fk_attribute]) ? new $className($this->scanario) : $className::model()->findByPk($postArray[$className][$fk_attribute]);

                $m->attributes = $postArray[$className];

                if ($m->validate())
                {
                    $m->$fk_attribute = $pk;
                    $m->save(false);
                    Yii::app()->controller->redirect(array('view', 'id' => $owner->$fk_attribute, '#' => $relation));
                }
                else
                {
                    $model->$relation = $m;
                    return false;
                }
            }
        }
    }

    /**
     * it is the behavior that automatcially validate the childs 
     * before validate of parent model
     * 
     * @param type $event 
     */
    public function beforeValidate($event)
    {
        parent::beforeValidate($event);
        $model = $this->owner;
        $this->valid = false;

        foreach ($this->childAr as $class => $child)
        {

            /**
             * child relation name
             */
            $childRelation = $child["relation"];


            $activeRelation = $model->getActiveRelation($childRelation);
            /**
             * class name of child relational model
             */
            $className = $activeRelation->className;

            if (isset($child['data']))
            {

                $fk_attribute = $activeRelation->foreignKey;

                $m = empty($child['data'][$fk_attribute]) ? new $className($this->scanario) : $className::model()->findByPk($child['data'][$fk_attribute]);
                $m->attributes = $child['data'];

                if ($m->validate())
                {
                    $this->valid = true;
                    $model->$childRelation = $m;
                }
                else
                {
                    $model->$childRelation = $m;
                    $this->valid = false;
                    $model->addError($childRelation, "An error occured during the save of {$childRelation}");
                    return false;
                }
            }
        }
    }

    /**
     * it is the event that save the child after save of parent
     * 
     * @param type $event 
     */
    public function afterSave($event)
    {
        parent::afterSave($event);
        $model = $this->owner;

        $transaction = Yii::app()->db->getCurrentTransaction();
        if ($transaction === null || !$transaction->getActive())
        {
            $transaction = Yii::app()->db->beginTransaction();
        }
        try
        {
            foreach ($this->childAr as $class => $child)
            {

                /**
                 * child relation name
                 */
                $childRelation = $child["relation"];
                $activeRelation = $model->getActiveRelation($childRelation);
                /**
                 * class name of child relational model
                 */
                $className = $activeRelation->className;

                /**
                 * foriegn key attribue of parent 
                 * that will be used as primary key attribute in child
                 * 
                 */
                $fk_attribute = $activeRelation->foreignKey;
                
                /**
                 * pk attribue od model
                 */
                $pk = $model->primaryKey;
                
                if (isset($child['data']) && $this->valid == true)
                {
                    $m = empty($child['data'][$fk_attribute]) ? new $className() : $className::model()->findByPk($child['data'][$fk_attribute]);
                    $m->attributes = $child['data'];
                    $m->$fk_attribute = $pk;                       
                    $m->save(false);
                }
            }
            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollback();
            /**
             * after roleback of every child 
             * then parent should also be deleted
             */
            $model->deleteByPk($pk);
        }
    }

}

?>
