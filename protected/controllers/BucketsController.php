<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BucketsController
 *
 * @author hamza
 */
class BucketsController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

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
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('settings', 'delete', 'update', 'view', 'editconnection', 'deleteimages'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * setting admin theme for all
     * @param type $action
     * @return type
     */
    public function beforeAction($action) {

        Yii::app()->theme = 'admin';
        return parent::beforeAction($action);
    }

    /**
     * view buckets files
     * @param type $id
     */
    public function actionView($id) {
        $model = Buckets::model()->findByPk($id);


        $this->render('view', array(
            's3' => $this->_S3,
            'model' => $model,
            "s3Model" => $this->uploadFileOnS3($model),
        ));
    }

    /**
     * upload file against bucket
     * @param type $model
     *  bucket
     * @return \S3Files
     */
    public function uploadFileOnS3($bucketModel) {
        $model = new S3Files;

        if (isset($_POST['S3Files'])) {
            $model->attributes = $_POST['S3Files'];
            $model->bucket_id = $bucketModel->id;

            $model->file_instance = DTUploadedFile::getInstance($model, 'name');


            $model->path = !empty($model->file_instance) ? 'dtech/' . $model->file_instance->name : "";
            $model->hash = "d";
            $model->bucket_name = $bucketModel->name;

            if ($model->save()) {
                $this->redirect($this->createUrl("/buckets/view", array("id" => $bucketModel->id)));
            }
        }

        return $model;
    }

    /**
     * Lists all models.
     */
    public function actionSettings() {

        $user = User::model()->findByPk(Yii::app()->user->user_id);


        $buckets = $this->_S3->listBuckets();
        $model = new Buckets;
        foreach ($buckets as $bucket) {
            $model->name = $bucket;
            $model->create_user_id = $user->id;
            $model->save();
        }



        $this->render('settings', array(
            'bucketProvider' => Buckets::model(),
            'user' => $user,
            'bucketModel' => $this->createBucket(),
        ));
    }

    /**
     *  create bucket
     *  process from here
     */
    public function createBucket() {
        $model = new Buckets;

        if (isset($_POST['Buckets'])) {

            $model->attributes = $_POST['Buckets'];
            if ($model->createBucket() && $model->save()) {

                $this->redirect($this->createUrl("/buckets/settings"));
            }
        }

        return $model;
    }

    /**
     * USER EDIT CONNECTION
     */
    public function actionEditconnection() {
        $model = new ConfigForm;

        $record = User::model()->findByPk(Yii::app()->user->user_id);
        $model->awskey = $record->awsaccesskey;
        $model->awssecret = $record->awssecretkey;

        if (isset($_POST['ConfigForm'])) {

            $model->attributes = $_POST['ConfigForm'];

            if ($model->validate()) {

                $model->conNew();
                $this->redirect($this->createUrl('buckets/settings'));
            }
        }

        $this->renderPartial('_conn_form', array('model' => $model), false, true);
    }

    /**
     * 
     * @param type $id
     */
    public function actionDeleteimages($id) {

        


        $S3file = S3Files::model()->findByPk($id);
        $bucket = Buckets::model()->findByPk($S3file->bucket_id);


        if ($this->_S3->deleteObject($bucket->name, "dtech/". $S3file->name)) {
            S3Files::model()->deleteByPk($id);
        }


//        Buckets::model()->deleteByPk($id);
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//        $this->render('view');
    }

    public function actionDelete($id) {

        $model = new Buckets;
        $bucket = Buckets::model()->findByPk($id);
        $record = User::model()->findByPk(Yii::app()->user->user_id);

//        $model->awskey = $record->awsaccesskey;
//        $model->awssecret = $record->awssecretkey;

        if ($model->removeBucket($bucket->name)) {
            Buckets::model()->deleteByPk($id);
            S3Files::model()->deleteAllByAttributes(array('bucket_id' => $id));
        }

//        Buckets::model()->deleteByPk($id);
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
//        $this->render('view');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Buckets::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}

?>
