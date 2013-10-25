<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConfigForm
 *
 * @author hamza
 */
class ConfigForm extends CFormModel {

    //put your code here
    public $bucket_name;
    public $awssecret;
    public $awskey;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('bucket_name,awssecret,awskey', 'required'),
            array('bucket_name', 'check'),
                // email has to be a valid email address
        );
    }

    public function check($attribute, $params) {
        $model = Buckets::model()->findAll();
        if (isset($model)) {

            foreach ($model as $m) {
                if ($m->name == $this->bucket_name && $m->uid == Yii::app()->user->user_id )
                    $this->addError('bucket_name', 'The bucket is already there');
                return false;
            }
        }

        return true;
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        
    }

    public function bucketsave() {
        $model = new Buckets();
     
         $model->name=  $this->bucket_name;
        $model->create_user_id = Yii::app()->user->user_id;

        if ($model->save() ) {
            return true;
        } else {
            CVarDumper::dump($model->errors);
            return false;
        }
    }

}

?>
