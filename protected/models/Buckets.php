<?php

/**
 * This is the model class for table "buckets".
 *
 * The followings are the available columns in table 'buckets':
 * @property integer $id
 * @property string $name
 * @property string $create_time
 * @property string $create_user_id
 * @property string $update_time
 * @property string $update_user_id
 * @property string $activity_log
 */
class Buckets extends DTActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Buckets the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'buckets';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, create_time, create_user_id, update_time, update_user_id', 'required'),
            array('name', 'length', 'max' => 200),
            array('name', 'unique'),
            array('create_user_id, update_user_id', 'length', 'max' => 11),
            array('activity_log', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, create_time, create_user_id, update_time, update_user_id, activity_log', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'create_user_id'),
            'files' => array(self::HAS_MANY, 'S3Files', 'bucket_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'create_time' => 'Create Time',
            'create_user_id' => 'Create User',
            'update_time' => 'Update Time',
            'update_user_id' => 'Update User',
            'activity_log' => 'Activity Log',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id, true);
        $criteria->compare('activity_log', $this->activity_log, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     *  create butten
     * @return type
     */
    public function createBucket() {
        $s3 = Yii::app()->controller->_S3;
        $resp = $s3->putBucket($this->name, $s3::ACL_PUBLIC_READ_WRITE);

        if (isset($resp->error) && $resp->error != false) {

            $this->addError('name', $resp->error['code']);
            return false;
        }
        return true;
    }

    public function removeBucket($name) {

        $resp = Yii::app()->controller->_S3->deleteBucket($name);
        return true;
//        if (isset($resp->error) && $resp->error != false) {
////            echo $resp->error['code'];
////            $this->addError('name', $resp->error['code']);
//            return false;
//        }
//        return true;
    }

}