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
            array('awssecret,awskey', 'required'),
            array('awssecret', 'verifyConnection'),
            array('bucket_name', 'check'),
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

    public function check($attribute, $params) {
        $model = Buckets::model()->findByAttributes(array('name' => $this->bucket_name));


        if (isset($model)) {
            $this->addError('bucket_name', 'The bucket is already there');

            return false;
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

        $model->name = $this->bucket_name;
        $model->create_user_id = Yii::app()->user->user_id;

        if ($model->save()) {
            return true;
        } else {
            CVarDumper::dump($model->errors);
            return false;
        }
    }

    public function conNew() {

        User::model()->updateByPk(Yii::app()->user->user_id, array('awsaccesskey' => $this->awskey, 'awssecretkey' => $this->awssecret));
    }

    public function removeBucket() {
        if (!class_exists('S3'))
            require_once('S3.php');

        //AWS access info  
        if (!defined('awsAccessKey'))
            define('awsAccessKey', $this->awskey);
        if (!defined('awsSecretKey'))
            define('awsSecretKey', $this->awssecret);

        //instantiate the class
        $s3 = new S3(awsAccessKey, awsSecretKey);

        //check whether a form was submitted
        //retreive post variables
        // The folder name would be the hierarchy
        //  we want to create in our bucket
        if (($s3->deleteBucket($this->bucket_name))) {

            Buckets::model()->deleteAllByAttributes(array('name' => $this->bucket_name));

            return $s3;
        } else {

            die('Already Exists ');
        }

        //This method put bucket is the built in method of the api
        // which stores the image to your bucket
        //The fileTempName is the file object actually 
        //And the foldername is the way(Directory) you want to keep it in the Bucket
    }

    public function createBucket() {
        if (!class_exists('S3'))
            require_once('S3.php');

        //AWS access info  
        if (!defined('awsAccessKey'))
            define('awsAccessKey', $this->awskey);
        if (!defined('awsSecretKey'))
            define('awsSecretKey', $this->awssecret);
      
        //instantiate the class
        $s3 = new S3(awsAccessKey, awsSecretKey);

       
        $s3->putBucket($this->bucket_name , S3::ACL_PUBLIC_READ_WRITE);
        
        return $s3;
    }

}

?>
