<?php

/**
 * This is the model class for table "conf_misc". created by Kashif
 *
 * The followings are the available columns in table 'conf_misc':
 * @property string $id
 * @property string $title
 * @property string $param
 * @property string $value
 * @property string $field_type
 * @property string $create_time
 * @property string $create_user_id
 * @property string $update_time
 * @property string $update_user_id
 */
class ConfMisc extends DTActiveRecord {

    public $confViewName = 'confMisc/Misc';
    public $paramsOptions = array(
        "dateformat" => array("m/d/y" => "m/d/y", "Y-m-d", "Y-m-d"),
        "smtp" => array("1" => "Enabled", "0" => "Disabled"),
    );

    /**
     * Returns the static model of the specified AR class.
     * @return ConfMisc the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'conf_misc';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, param, value, create_time, create_user_id, update_time, update_user_id', 'required'),
            array('title, param', 'unique'),
            array('title, param', 'length', 'max' => 255),
            array('create_user_id, update_user_id', 'length', 'max' => 11),
            array('field_type', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, value, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('application', 'ID'),
            'title' => Yii::t('application', 'Title'),
            'param' => Yii::t('application', 'Param'),
            'value' => Yii::t('application', 'Value'),
            'create_time' => Yii::t('application', 'Create Time'),
            'create_user_id' => Yii::t('application', 'Creat User'),
            'update_time' => Yii::t('application', 'Update Time'),
            'update_user_id' => Yii::t('application', 'Update User'),
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id, true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

}