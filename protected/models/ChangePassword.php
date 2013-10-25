<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChangePassword
 *
 * @author Brain
 */
class ChangePassword extends CFormModel {

    //put your code here
    public $pwd_repeat;
    public $old_pwd;
    public $password;

    /**
     *
     * @var type 
     */
    public $_model;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.


        return array(
            array('password,old_pwd, pwd_repeat', 'required'),
            array('password', 'compare', 'compareAttribute' => 'pwd_repeat'),
            array('old_pwd', 'checkOldPassword'),
                // The following rule is used by search().
                // Please remove those attributes that should not be searched.
        );
    }

    public function checkOldPassword($attribute, $params) {


        $user = User::model()->findBypk(Yii::app()->user->id);

        if ($user === null || $user->password != md5($this->old_pwd)) {
            $this->addError($attribute, 'Invalid Old password');
        } else {
            $this->_model = $user;
        }
    }

}

?>
