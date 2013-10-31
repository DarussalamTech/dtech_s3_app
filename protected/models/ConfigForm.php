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

    public $awssecret;
    public $awskey;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('awssecret,awskey', 'required'),
            array('awssecret', 'verifyConnection'),
                // email has to be a valid email address
        );
    }

    /**
     * 
     */
    public function verifyConnection() {

        $s3 = new S3($this->awskey, $this->awssecret);

        $con = $s3->verfiyConnection();

        if (isset($con->error) && $con->error != false) {

            $this->addError('awssecret', $con->error['code']);
        }

        return true;
    }

    public function conNew() {

        User::model()->updateByPk(Yii::app()->user->user_id, array('awsaccesskey' => $this->awskey, 'awssecretkey' => $this->awssecret));
    }

   

}

?>
