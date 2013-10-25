<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItstActiveRecord
 *
 * @author Brain
 */
class DTActiveRecord extends CActiveRecord {

    //put your code here
    /**
     * Prepares attributes before performing validation.
     * create_time,
     * create_user_id,
     * update_time
     * update_user_id
     */
    public $_action;
    public $_controller;
    public $_no_condition = false;
    public $_current_module;

    public function __construct($scenario = 'insert') {

        $this->_action = isset(Yii::app()->controller->action)?Yii::app()->controller->action->id:"";
        $this->_controller = Yii::app()->controller->id;
        $this->_current_module = get_class(Yii::app()->controller->getModule());

        parent::__construct($scenario);
    }

    public function afterFind() {
        if (isset(Yii::app()->controller->action->id)) {
            $this->_action = Yii::app()->controller->action->id;
        }

        $this->attributes = $this->decodeArray($this->attributes);
        parent::afterFind();
    }

    protected function beforeValidate() {


        $this->_action = Yii::app()->controller->action->id;
        if ($this->isNewRecord) {

            // set the create date, last updated date and the user doing the creating
            $this->create_time = $this->update_time = date("Y-m-d H:i:s"); //new CDbExpression('NOW()');
            $this->create_user_id = $this->update_user_id = Yii::app()->user->id;
            // $this->users_id=1;//$this->update_user_id=Yii::app()->user->id;
        } else {
            //not a new record, so just set the last updated time and last updated user id
            $this->update_time = new CDbExpression('NOW()');
            $this->update_user_id = Yii::app()->user->id;
            // $this->users_id=1;
        }
        /**
          special conidtion
         */
        if (empty(Yii::app()->user->id)) {
            $this->create_user_id = 1;
            $this->update_user_id = 1;
        }
        parent::beforeValidate();
        $this->attributes = $this->decodeArray($this->attributes);
        return true;
    }

    /**
     *  will 
     *  be used 
     * during before save
     * @return boolean 
     */
    protected function beforeSave() {

        $update_time = date("Y-m-d") . " " . date("H:i:s");

        if ($this->_controller != "product" && $this->_action == "viewImage") {
            $this->attributes = CHtml::encodeArray($this->attributes);
        }
        parent::beforeSave();

        return true;
    }

    /**
     *
     * @return <array>
     */
    public function behaviors() {
        parent::behaviors();

        return array(
            'CMultipleRecords' => array(
                'class' => 'CMultipleRecords'
            ),
        );
    }

    /**
     *  will be used to deltee
     *  mark as dleted
     */
    public function markDeleted() {
        $this->updateByPk($this->primaryKey, array('deleted' => "1"));
    }

    public function getOrder() {
        $criteria = new CDbCriteria;
        $criteria->order = "t.order DESC";
        $criteria->select = "t.order";
        $orderM = $this->find($criteria);

        $this->order = $orderM->order + 1;
    }

    public function setUuid($length = 20) {
        $connection = Yii::app()->db;

        $command = $connection->createCommand("SELECT SUBSTRING(UUID(),1,$length) as uuid");
        $row = $command->queryRow();
        return $row['uuid'];
    }

    /*
     * method to decode an array 
     * removing special characters and slashes....
     */

    private function decodeArray($data) {
        $d = array();


        foreach ($data as $key => $value) {
            if (is_string($key))
                $key = stripslashes(htmlspecialchars_decode($key, ENT_QUOTES));
            if (is_string($value))
                $value = stripslashes(htmlspecialchars_decode($value, ENT_QUOTES));
            else if (is_array($value))
                $value = self::decodeArray($value);
            /*
             * IF condition is for arabic and internatational data handling 
             * 
             * and the else part is for local data entry for system
             */

            if (mb_detect_encoding($value) == "UTF-8") {

                $d[$key] = $this->_current_module == "WebModule" ? utf8_decode($value) : $value;
            } else {
                $d[$key] = $value;
            }
        }

        return $d;
    }

    public function updateByPk($pk, $attributes, $condition = '', $params = array()) {
        $updateAttr = array("update_time" => new CDbExpression('NOW()'), "update_user_id" => Yii::app()->user->id);
        $attributes = array_merge($attributes, $updateAttr);


        parent::updateByPk($pk, $attributes, $condition, $params);
        return true;
    }

}

?>
