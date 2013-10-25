<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $model = new ConfigForm;

        if (isset($_POST['ConfigForm'])) {
            $record=  User::model()->findByPk(Yii::app()->user->user_id);
            $model->attributes = $_POST['ConfigForm'];
           $model->awskey=$record->awsaccesskey;
           $model->awssecret=$record->awssecretkey;
           
            if ( $model->bucketsave()) {

                $s3 = $this->sets3($model);
                
                $this->render('pop', array(
                's3' => $s3,
                'model' => $model,
                ));
            }
        } else {
            // renders the view file 'protected/views/site/index.php'
            // using the default layout 'protected/views/layouts/main.php'
            $this->render('index', array('model' => $model));
        }
    }

    public function actionAjaxcall() {
        $model = new ConfigForm;

        if (isset($_POST)) {
            $attributes = array(
                'uid' => Yii::app()->user->user_id,
            );

            $record = S3data::model()->findAllByAttributes($attributes);

            $model->awskey = $record->awsaccesskey;
            $model->awssecret = $record->awssecretkey;
            $model->bucket_name = $_POST['id'];
            $s3 = $this->sets3($model);
            $this->render('pop', array(
                's3' => $s3
            ));
        } else {
            // renders the view file 'protected/views/site/index.php'
            // using the default layout 'protected/views/layouts/main.php'
//            $this->render('index', array('model' => $model));
        }
    }

    public function sets3($model) {
        if (!class_exists('S3'))
            require_once('S3.php');

        //AWS access info  
        if (!defined('awsAccessKey'))
            define('awsAccessKey', $model->awskey);
        if (!defined('awsSecretKey'))
            define('awsSecretKey', $model->awssecret);

        //instantiate the class
        $s3 = new S3(awsAccessKey, awsSecretKey);

        //check whether a form was submitted
        if (isset($_POST['Submit'])) {

            //retreive post variables

            $fileName = $_FILES['theFile']['name'];
            $fileTempName = $_FILES['theFile']['tmp_name'];

//            var_dump($_FILES);
            //create a new bucket
            $folderName = 'uplod/product/34/';
            // The folder name would be the hierarchy
            //  we want to create in our bucket
            $s3->putBucket($model->bucket_name, S3::ACL_PUBLIC_READ_WRITE);

            //This method put bucket is the built in method of the api
            // which stores the image to your bucket
            //The fileTempName is the file object actually 
            //And the foldername is the way(Directory) you want to keep it in the Bucket

            if ($s3->putObjectFile($fileTempName, $model->bucket_name, $folderName . "d.png", S3::ACL_PUBLIC_READ_WRITE)) {
                echo "<strong>We successfully uploaded your file.</strong>";
            } else {
                echo "<strong>Something went wrong while uploading your file... sorry.</strong>";
            }
        }
        return $s3;
    }

    public function actionSignUp() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        $model = new SignUpForm;

//
        if (isset($_POST['SignUpForm'])) {
            $model->attributes = $_POST['SignUpForm'];

            if ($model->validate() && $model->signup()) {


                $this->redirect($this->createUrl('login'));
            } else {
                CVarDumper::dump($model->errors, 10, true);
                die('4');
            }
        }
        $this->render('signup', array('model' => $model));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**


     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect($this->createUrl('site/page'));
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionPage() {
        $this->render('page');
    }

}