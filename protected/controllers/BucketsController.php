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
                'actions' => array('index', 'delete', 'view', 'create', 'update', 'form'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('register', 'captcha'),
                'users' => array('?'),
            ),
            array('allow',
                'actions' => array('changePass'),
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionView($id) {
        $record1 = Buckets::model()->findByPk($id);

        $this->render('view', array(
            's3' => $this->_S3,
            'model' => $record1
        ));
    }

    /**
     * This Show the user data
     */
    public function actionViews() {
        
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

   

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $record = User::model()->findByPk(Yii::app()->user->user_id);





        $buckets = $this->_S3->listBuckets();

  
        $i = count($buckets);
        while ($i > 0) {

            $i--;
            $model = new ConfigForm();
            $model->bucket_name = $buckets[$i];


//            if ($model->validate())
                $model->bucketsave();
        }

        $model1 = new Buckets();

        $this->render('pop', array(
            'model' => $model1,
            'record' => $record,
        ));
    }

    public function actionCreate() {
        $model = new ConfigForm;

        if (isset($_POST['ConfigForm'])) {
            $record = User::model()->findByPk(Yii::app()->user->user_id);
            $model->bucket_name = $_POST['ConfigForm']['bucket_name'];

            $model->awskey = $record->awsaccesskey;
            $model->awssecret = $record->awssecretkey;

            if ($model->validate()) {

                $s3 = $model->createBucket();
                $model->bucketsave();
                $model1 = new Buckets();
                $this->render('pop', array(
                    's3' => $s3,
                    'model' => $model1,
                    'record' => $record,
                ));
            }
        } else {
            // renders the view file 'protected/views/site/index.php'
            // using the default layout 'protected/views/layouts/main.php'
            $this->render('create', array('model' => $model));
        }
    }

    public function actionForm() {
        $model = new ConfigForm;

        $record = User::model()->findByPk(Yii::app()->user->user_id);
        $model->awskey = $record->awsaccesskey;
        $model->awssecret = $record->awssecretkey;

        if (isset($_POST['ConfigForm'])) {

            $model->attributes = $_POST['ConfigForm'];




            if ($model->validate()) {

                $model->conNew();
                $this->redirect($this->createUrl('buckets/index'));
            }
        }

        $this->renderPartial('_form', array('model' => $model));
    }


 


    public function actionDelete($id) {

        $model = new ConfigForm;
        $record1 = Buckets::model()->findByPk($id);
        $record = User::model()->findByPk(Yii::app()->user->user_id);

        $model->awskey = $record->awsaccesskey;
        $model->awssecret = $record->awssecretkey;
        $model->bucket_name = $record1->name;
        $model->removeBucket();

        Buckets::model()->deleteByPk($id);
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
