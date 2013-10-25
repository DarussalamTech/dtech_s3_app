<?php

/**
 * This is the model class for table "menus".
 *
 * The followings are the available columns in table 'menus':
 * @property string $id
 * @property string $pid
 * @property integer $root_parent
 * @property string $controller
 * @property string $action
 * @property string $default_title
 * @property string $user_title
 * @property string $is_assigned
 * @property integer $weight
 * @property string $min_permission
 * @property string $root_class
 * @property string $create_time
 * @property string $create_user_id
 * @property string $update_time
 * @property string $update_user_id
 */
class Menu extends DTActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Menus the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'menus';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id,root_parent, is_assigned, min_permission', 'required'),
            array('root_parent, weight', 'numerical', 'integerOnly' => true),
            array('pid, create_user_id, update_user_id', 'length', 'max' => 11),
            array('controller, action, default_title, user_title, min_permission, root_class', 'length', 'max' => 255),
            array('is_assigned', 'length', 'max' => 3),
            array('create_time, create_user_id, update_time, update_user_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, pid, root_parent, controller, action, default_title, user_title, is_assigned, weight, min_permission, root_class, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'pid' => 'Main Menu',
            'controller' => 'Controller',
            'action' => 'Action',
            'default_title' => 'Default Title',
            'user_title' => 'User Title',
            'is_assigned' => 'Is Assigned',
            'weight' => 'Weight',
            'main_menu' => 'Is It Main Menu?',
            'create_time' => 'Create Time',
            'create_user_id' => 'Create User',
            'update_time' => 'Update Time',
            'update_user_id' => 'Update User',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('pid', $this->pid, true);
        $criteria->compare('controller', $this->controller, true);
        $criteria->compare('action', $this->action, true);
        $criteria->compare('default_title', $this->default_title, true);
        $criteria->compare('user_title', $this->user_title, true);
        $criteria->compare('is_assigned', $this->is_assigned, true);
        $criteria->compare('weight', $this->weight);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id, true);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function beforeFind() {
        $criteria = $this->getDbCriteria();
        $criteria->order = 'weight ASC';

        return parent::beforeFind();
    }

    /**
     * Get menu item weight regarding pid
     * @return Integer
     */
    public function getMenuItemWeight() {
        return Menu::model()->count("pid=" . $this->pid);
    }

    /**
     * Get un-assigned menu item weight
     * @return integer
     */
    public function getMenuItemWeightUnAssigned() {
        return Menu::model()->count("pid =0 AND is_assigned = 'No'");
    }

    /**
     * Get main menu item weight
     * @return integer
     */
    public function getMainMenuWeight() {
        return Menu::model()->count("pid =0 AND is_assigned = 'Yes'");
    }

    /**
     * get update image
     * @return string
     */
    public function getUpdateImage() {
        return CHtml::image(Yii::app()->request->baseUrl . "/images/action_buttons/update.png", "Update");
    }

    /**
     * Get delete image
     * @return string
     */
    public function getDeleteImage() {
        return CHtml::image(Yii::app()->request->baseUrl . "/images/action_buttons/delete.png", "Delete");
    }

    /**
     * Generate / Get menu items which are assigned to application. It is a 
     * Recursive function.
     * @param int $pid
     * @param int $level 
     */
    public function getMenuItems($pid = 0, $level = 0) {

        $menuItems = Menu::model()->findAllByAttributes(array("pid" => $pid, "is_assigned" => "Yes"));

        $padding = $level * 30;

        foreach ($menuItems as $menuItem) {
            echo "<div style='padding:5px 10px 5px " . $padding . "px;' class='menuItem'>"; {
                echo "<div style='float:left;" . ($level == 0 ? 'font-weight:bold' : '') . "'>"; {
                    echo $menuItem->user_title;
                }
                echo "</div>";
                echo "<div style='float:right;'>"; {
                    echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . "/images/arrow/up-1.png", "Up", array("style" => "height:14px;")), array("sort", "id" => $menuItem->id, "direction" => "up"));
                    echo " ";
                    echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . "/images/arrow/down-1.png", "Down", array("style" => "height:14px;")), array("sort", "id" => $menuItem->id, "direction" => "down"));
                    echo " ";
                    echo CHtml::link($this->getUpdateImage(), array("update", "id" => $menuItem->id));
                    echo " ";
                    echo CHtml::link($this->getDeleteImage(), array("delete", "id" => $menuItem->id));
                }
                echo "</div>";
                echo "<div class='clear'></div>";
            }
            echo "</div>";
            $this->getMenuItems($menuItem->id, ($level + 1));
        }
    }

    /**
     * Get / Generate view Un-Assigned menu items
     * @param Int $pid
     * @param Int $level 
     */
    public function getUnAssignedMenuItems($pid = 0, $level = 0) {
        if ($level == 0)
            $menuItems = Menu::model()->findAllByAttributes(array("pid" => $pid, "is_assigned" => "No"));
        else
            $menuItems = Menu::model()->findAllByAttributes(array("pid" => $pid, "is_assigned" => "Yes"));

        $padding = $level * 30;

        foreach ($menuItems as $menuItem) {
            echo "<div style='padding:5px 10px 5px " . $padding . "px;' class='menuItem'>";
            echo "<div style='float:left;" . ($level == 0 ? 'font-weight:bold' : '') . "'>";
            echo $menuItem->user_title;
            echo "</div>";
            echo "<div style='float:right;'>";
            echo CHtml::link($this->getUpdateImage(), array("update", "id" => $menuItem->id));
            echo " ";
            if ($level != 0)
                echo CHtml::link($this->getDeleteImage(), array("delete", "id" => $menuItem->id));
            echo "</div>";
            echo "<div class='clear'></div>";
            echo "</div>";
            $this->getUnAssignedMenuItems($menuItem->id, ($level + 1));
        }
    }

    /**
     * Get root parent id to make it selected in view when user click at any of
     * its sub-menu or on it.
     * @param int $id
     * @return int
     */
    public function getRootParent($id) {

        $model = Menu::model()->findByPk($id);

        if ($model->pid != 0)
            return $this->getRootParent($model->pid);
        else {
            return $model->id;
        }
    }

}