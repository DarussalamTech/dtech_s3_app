<?php

/**
 * Multiple records
 *      For Add, Edit, Delete multiple records. For now it is working for just saving
 *      multiple records
 */
class CMultipleRecords extends CActiveRecordBehavior {

    /**
     * Save Multiple records
     * 
     * @param <string> $relation
     *      If childe class/table's records are being saved than we have some relation
     *      name, and id. so just set it while declaring.
     * @param <integer> $parentID
     *      If childe class/table's records are being saved than we have some relation
     *      name, and id. so just set it while declaring.
     * 
     * @return <array>
     */
    public function saveMultiple($relation = '', $parentID = 0) {
        /* If recodes are being saved in child table */
        if (!empty($relation)) {
            $activeRelation = $this->owner->getActiveRelation($relation);

            $fk = $activeRelation->foreignKey;
        }

        $count = 0;
        $models = array();
        $errors = false;

        /* Get current class name to check which form is posted */
        $class_name = get_class($this->owner);



        /* Read POST and assign it to models array */
        foreach ($_POST[$class_name] as $key => $value) {

            if (is_array($this->owner->tableSchema->primaryKey)) {
                $array = array();
                foreach($this->owner->tableSchema->primaryKey as $key){
                    $array[$key] = $value[$key];
                }
                $model = ($value['id'] > 0 ? $this->owner->findByPk($array) : new $this->owner); //new $this->owner;
            } else {
                $model = ($value['id'] > 0 ? $this->owner->findByPk($value['id']) : new $this->owner); //new $this->owner;
            }
            /* Assign value[] */
            $model->attributes = $value;

            if (!empty($relation))
                $model->$fk = $parentID;

            /* Validate it */
            if (!$model->validate())
                $errors = true;

            $models[$count] = $model;

            $count++;

            
        }
  
        /* Save all records */
        if ($errors == false) {
            /* Save each record */
            foreach ($models as $key => $model) {
                $model->save();
            }
            /* Send bland model */
            $models = new $this->owner;
            return array("models" => $models, "result" => true);
        } else {
            return array("models" => $models, "result" => false);
        }
    }

}

?>