<?php

/**
 * This is the model class for table "s3_files".
 *
 * The followings are the available columns in table 's3_files':
 * @property integer $id
 * @property string $name
 * @property string $hash
 * @property integer $bucket_id
 * @property string $create_time
 * @property string $create_user_id
 * @property string $update_time
 * @property string $update_user_id
 * @property string $activity_log
 */
class S3Files extends DTActiveRecord {

    /**
     * form file instance
     * @var type 
     */
    public $file_instance, $path, $bucket_name, $link;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return S3Files the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 's3_files';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('hash, bucket_id, create_time, create_user_id, update_time, update_user_id', 'required'),
            array('bucket_id', 'numerical', 'integerOnly' => true),
            array('name', 'file', 'allowEmpty' => false,),
            array('name', 'length', 'max' => 200),
            array('hash', 'length', 'max' => 100),
            array('create_user_id, update_user_id', 'length', 'max' => 11),
            array('bucket_name,path,file_instance,activity_log', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, hash, bucket_id, create_time, create_user_id, update_time, update_user_id, activity_log', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'bucket' => array(self::BELONGS_TO, 'Buckets', 'bucket_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'File',
            'hash' => 'Hash',
            'bucket_id' => 'Bucket',
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
        $criteria->compare('hash', $this->hash, true);
        $criteria->compare('bucket_id', $this->bucket_id);
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
     * over ride method
     */
    public function beforeSave() {

        parent::beforeSave();
        $this->uploadS3File();
        return true;
    }

    /**
     * 
     */
    public function uploadS3File() {
        if ($s3Object = Yii::app()->controller->_S3->putObjectFile($this->file_instance->tempName, $this->bucket_name, $this->path, S3::ACL_PUBLIC_READ_WRITE)) {

            if (isset($s3Object->response->error) && $s3Object->response->error == false) {

                $this->hash = $s3Object->response->headers['hash'];
                $this->name = $this->file_instance->name;
            } else {
                $this->addError("name", "wrong");
            }
        }
    }

    /**
     * 
     */
    public function afterFind() {
        
        $this->link = CHtml::link($this->name, "http://" . $this->bucket->name . ".s3.amazonaws.com/" . "dtech/".$this->name);
        parent::afterFind();
    }
 
}