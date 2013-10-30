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
        $this->render("page");
    }

    public function actionFile() {
        $model = new ConfigForm;
//        CVarDumper::dump($id,10,true);
//        die();
        if (isset($_POST['Submit'])) {

            //retreive post variables
            $bucket = $_POST['bucket'];

            $fileName = $_FILES['theFile']['name'];
            $fileTempName = $_FILES['theFile']['tmp_name'];

//            var_dump($_FILES);
            //create a new bucket
            $folderName = 'uplod/product/34/';
            // The folder name would be the hierarchy
            //  we want to create in our bucket
            $this->_S3->putBucket($bucket, S3::ACL_PUBLIC_READ_WRITE);

            //This method put bucket is the built in method of the api
            // which stores the image to your bucket
            //The fileTempName is the file object actually 
            //And the foldername is the way(Directory) you want to keep it in the Bucket

            if ($this->_S3->putObjectFile($fileTempName, $bucket, $folderName . "e.png", S3::ACL_PUBLIC_READ_WRITE)) {
                echo "<strong>We successfully uploaded your file.</strong>";
            } else {
                echo "<strong>Something went wrong while uploading your file... sorry.</strong>";
            }
            $record = Buckets::model()->findByAttributes(array("name" => $bucket));

            $this->actionView($record->id);
        }
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
        
        $this->layout = "//layouts/login_admin";

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
//            
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
        $model = new LoginForm;
        Yii::app()->homeUrl = $this->createUrl("site/login");
        $this->redirect(Yii::app()->homeUrl, array('model' => $model));
    }

   

    public function actionPage() {

        $this->render('page');
    }

}