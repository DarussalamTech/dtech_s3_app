<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $type
 * @property string $address
 * @property integer $phone
 * @property string $awsaccesskey
 * @property string $awssecretkey
 * @property string $create_time
 * @property string $create_user_id
 * @property string $update_time
 * @property string $update_user_id
 * @property string $activity_log
 */
class User extends DTActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, password, name, email, address, phone, awsaccesskey, awssecretkey, create_time, create_user_id, update_time, update_user_id', 'required'),
            array('phone', 'numerical', 'integerOnly' => true),
            array('username, password, name, awsaccesskey, awssecretkey', 'length', 'max' => 200),
            array('email', 'length', 'max' => 100),
            array('address', 'length', 'max' => 250),
            array('create_user_id, update_user_id', 'length', 'max' => 11),
            array('type,activity_log', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, password, name, email, address, phone, awsaccesskey, awssecretkey, create_time, create_user_id, update_time, update_user_id, activity_log', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'email' => 'Email',
            'address' => 'Address',
            'phone' => 'Phone',
            'awsaccesskey' => 'Awsaccesskey',
            'awssecretkey' => 'Awssecretkey',
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
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone);
        $criteria->compare('awsaccesskey', $this->awsaccesskey, true);
        $criteria->compare('awssecretkey', $this->awssecretkey, true);
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
     * validate password using in login process
     * @param type $password
     * @param type $old_password
     * @return type
     */
    public function validatePassword($password, $old_password) {
        
        return md5($password) === $old_password;
        //return $password;
    }

}