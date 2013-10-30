<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SignUpForm
 *
 * @author hamza
 */
class SignUpForm extends CFormModel {

    public $name;
    public $phone;
    public $email;
    public $address;
    public $retype_email;
    public $awsaccesskey;
    public $awssecretkey;
    public $username;
    public $password;
    public $retype_password;
    public $verifyCode;
//        public $rememberMe;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('name,username, password,retype_password,phone,email,retype_email,address,awsaccesskey,awssecretkey', 'required'),
            array('email', 'email'),
//            array('email', 'unique', 'message' => Yii::t('app', "This user's email adress already exists.")),
//            array('username', 'unique', 'message' => Yii::t('app', "This user's email adress already exists.")),
            array('username', 'check'),
            array('email', 'checkemail'),
            array('awsaccesskey', 'accesskey'),
            array('awssecretkey', 'secretkey'),
            //array('old_password', 'check'),
            array('password', 'compare', 'compareAttribute' => 'retype_password'),
            array('email', 'compare', 'compareAttribute' => 'retype_email'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'verifyCode' => 'Verification Code',
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function check($attribute, $params) {
        if (User::model()->findByAttributes(array("username" => $this->$attribute))) {
            $this->addError('username', 'The username is already taken');
        }
    }

    public function verifyConnection() {


        $s3 = new S3($this->awsaccesskey, $this->awssecretkey);

        $con = $s3->verfiyConnection();


        if (isset($con->error) && $con->error != false) {

            $this->addError('awsaccesskey', $con->error['code']);
        }

        return true;
    }

    public function accesskey($attribute, $params) {



        $this->verifyConnection();




        if (User::model()->findByAttributes(array("awsaccesskey" => $this->$attribute))) {


            $this->addError('awsaccesskey', 'Invalid Access Key');
            return false;
        }

        return true;
    }

    public function secretkey($attribute, $params) {
        $this->verifyConnection();
        if (User::model()->findByAttributes(array("awssecretkey" => $this->$attribute))) {


            $this->addError('awssecretkey', 'Invalid Secret Key');
            return false;
        }

        return true;
    }

    public function checkemail($attribute, $params) {
        $model = User::model()->findAll();
        if (isset($model)) {

            foreach ($model as $m) {
                if ($m->email == $this->email)
                    $this->addError('email', 'The email is already taken');
                return false;
            }
        }

        return true;
    }

    public function signup() {

        $model = new User();
        $model->name = $this->name;
        $model->email = $this->email;
        $model->phone = $this->phone;
        $model->address = $this->address;
        $model->password = md5($this->password);
        $model->awsaccesskey = $this->awsaccesskey;
        $model->awssecretkey = $this->awssecretkey;
        $model->username = $this->username;


        if ($model->save()) {

            return true;
        } else {
            CVarDumper::dump($model->errors, 10, true);
            die('3');
            return false;
        }
    }

    //put your code here
}

?>
