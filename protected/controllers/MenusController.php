<?php

/**
 * Drop Down Navigation
 *  MPN is deployed on different sub-domains for different projects. For each 
 * MPN project it is required to have different menus, different title, 
 * sub-menus and main menus. To achieve that functionality we developed a 
 * navigation control system. Each menu item of main navigation is saved in 
 * database in menus table. We can assign menu or un-assign, set sort order, 
 * sub-menu at any level through that system.
 * 
 * Note: This functionality is not under client’s control. Only MPN developers can use it
 * 
 * ProDropDown extension is a drop down menu which provide us proper suitable 
 * CSS and JS the rest code is divided into simple Yii CRUD.
 * 
 * @author Mohsin Shoaib
 */
class MenusController extends Controller {

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
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index', 'create', 'update', 'delete', 'addChild', 'sort',
                    'installMenu', 'generateData', 'json'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Menu;

        if (isset($_POST['Menu'])) {
            $model->attributes = $_POST['Menu'];

            $model->pid = 0;
            $model->is_assigned = "Yes";
            $model->weight = $model->getMainMenuWeight();
            $model->default_title = $model->user_title;

            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('create', array('model' => $model));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $previousPID = $model->pid;

        if (isset($_POST['Menu']) && !isset($_POST['Menu']['id'])) {
            /* Update attributes */
            $model->attributes = $_POST['Menu'];

            if ($model->main_menu == true && $model->pid == 0 && $model->is_assigned == "No") {
                $model->is_assigned = "Yes";
                $model->weight = $model->getMainMenuWeight();
            }

            /*
             * Find if it is main menu submitted or menu item 
             * Inf is_assigned == 'No' and pid = 0 then it is menu item
             * And if it is menu item then update pid and set is_assigned = yes
             */
            if ($model->main_menu == false && $previousPID != $model->pid && $model->pid > 0) {
                $model->is_assigned = "Yes";
                $model->weight = $model->getMenuItemWeight();
            }
            /* If no parent is selected and it has parent before. */ else if ($model->main_menu == false && $model->pid == 0 && $previousPID > 0) {
                $model->is_assigned = "No";
                $model->weight = $model->getMenuItemWeightUnAssigned();
            }

            if ($model->save())
                $this->redirect(array('index'));
        }

        $this->render('update', array('model' => $model));
    }

    /**
     * Add child or menu sub-item 
     */
    public function actionAddChild() {
        if (isset($_POST['Menu']) && $_POST['Menu']['id'] > 0) {
            $id = $_POST['Menu']['id'];

            $model = $this->loadModel($id);

            /* Update attributes */
            $model->attributes = $_POST['Menu'];

            $model->root_parent = Menu::model()->getRootParent($model->pid);

            $model->is_assigned = "Yes";
            $model->weight = $model->getMenuItemWeight();

            if ($model->save()) {
                $this->redirect(array('update', 'id' => $model->pid));
            }
        } else {
            $this->redirect(array('update', 'id' => $_POST['Menu']['pid']));
        }
        $this->redirect(array('index'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);

        /* Set some attributes to put this menu in un-assigneds */
        $pid = $model->pid;
        $model->pid = 0;
        $model->root_parent = 0;
        $model->is_assigned = "No";
        $model->weight = $model->getMenuItemWeightUnAssigned();

        /* Save changings */
        $model->save();

        /**
         * Suppose we have this data of nvigation
         * User Management
         *      1. List All 
         *      2. Create User
         *      3. View Single User
         *      4. Delete Single User
         *      5. Delete All Users
         * 
         * Now if we delete '3' menu item then sequence/weight/sort order will become 1,2,4,5
         * So to make sort order or weight proper we have to set Main menu's items weights/sort order.
         * And after that it will become 1,2,3,4
         */
        $model = Menu::model()->findAllByAttributes(array("pid" => $pid, "is_assigned" => "Yes"));

        foreach ($model as $weight => $menu) {
            $m = Menu::model()->findByPk($menu->id);
            $m->weight = $weight;
            $m->save();
        }

        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new Menu('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Menu']))
            $model->attributes = $_GET['Menu'];

        $this->render('index', array('model' => $model,));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Menu::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * When user press up or down button in menu page to sort it this function is called :)
     * @param type $id
     * @param type $type 
     */
    public function actionSort($id, $direction) {
        $model = Menu::model()->findByPk($id);

        $criteria = new CDbCriteria();

        if ($direction == "up") {
            $criteria->condition = "weight < " . $model->weight . " AND pid = " . $model->pid . " AND is_assigned = 'Yes'";
            $criteria->order = "weight DESC";
        } else {
            $criteria->condition = "weight > " . $model->weight . " AND pid = " . $model->pid . " AND is_assigned = 'Yes'";
            $criteria->order = "weight ASC";
        }

        $model2 = Menu::model()->find($criteria);

        if (count($model2) == 1) {
            $wc = $model->weight;
            $model->weight = $model2->weight;
            $model2->weight = $wc;
            $model->save();
            $model2->save();
        }

        $this->redirect(array('index'));
    }

    /**
     * Install menu only first time when it is deployed. 
     */
    public function actionInstallMenu() {
        Menu::model()->deleteAll();

        $ary[] = array("id" => "1", "pid" => "0", "root_parent" => "1", "controller" => "site", "action" => "page", "default_title" => "Home", "user_title" => "Home", "is_assigned" => "Yes", "min_permission" => "View", "weight" => "1");


//        $ary[] = array("id" => "2", "pid" => "0", "root_parent" => "2", "controller" => "category", "action" => "index", "default_title" => "Category", "user_title" => "Category", "is_assigned" => "Yes", "min_permission" => "View", "weight" => "2");
//        $ary[] = array("id" => "3", "pid" => "2", "root_parent" => "2", "controller" => "category", "action" => "index", "default_title" => "List All", "user_title" => "List All", "is_assigned" => "Yes", "min_permission" => "View", "weight" => "0");
//        $ary[] = array("id" => "4", "pid" => "2", "root_parent" => "2", "controller" => "category", "action" => "create", "default_title" => "Create", "user_title" => "Create", "is_assigned" => "Yes", "min_permission" => "View", "weight" => "2");


        foreach ($ary as $attr) {
            $model = new Menu();
            $model->attributes = $attr;
            CVarDumper::dump($model->attributes, 10, true);

            if (!$model->save()) {
                CVarDumper::dump($model->getErrors(), 10, true);
            }
        }
    }

}
