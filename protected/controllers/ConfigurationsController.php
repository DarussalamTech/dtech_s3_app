<?php

class ConfigurationsController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
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
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
//            array('allow', // allow all users to perform 'index' and 'view' actions
//                'actions' => array(),
//                'users' => array('*'),
//            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array(),
//                'users' => array('@'),
//            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('index', 'load', 'delete', 'appSettings'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Conf default page.
     */
    public function actionIndex() {
        $this->render('index');
    }

    /**
     * Load Configuration
     * 
     * @param <string> $m (Model name without Conf)
     * @param <int> $id
     */
    public function actionLoad($m, $id = 0, $module = '') {
        /* Complete Model name */
        $model_name = 'Conf' . $m;
//        echo $model_name;die;   

        /* For add new or update */
        $model = new $model_name;

        if ($id != 0) {
            $model = $model->findByPk($id);
        }
//        echo $model->confViewName;
//        exit;
        /* Perform ajax validation */
//        $this->performAjaxValidation($model);

        /* if form is posted */
        if (isset($_POST[$model_name])) {
            /* Assign attributes */
            $model->attributes = $_POST[$model_name];
            /* Save record */
            if ($model->save())
                $this->redirect(array('load', 'm' => $m, 'module' => $module));
        }

        $this->render($model->confViewName, array('model' => $model, 'm' => $m, 'module' => $module));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {


        if (isset($_POST['ajax']) && $_POST['ajax'] === 'project-form') {
            //echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Set comments for appSettng action 
     */
    public function actionAppSettings() {
        /* Complete Model name */
        $model = new ConfMisc();

        $this->render("appSettings/index", array('model' => $model));
    }

}
