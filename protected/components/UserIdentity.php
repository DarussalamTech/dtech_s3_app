<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $record = User::model()->findByAttributes(array('username' => $this->username, 'password' => $this->password));

        $users = array(
            'demo' => 'demo',
            'admin' => 'admin',
        );


        if (!isset($record)) {

            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif ($record->password !== $this->password)
            $this->errorCode = self::ERROR_PASSWORD_INVALID;

        else {
            $this->errorCode = self::ERROR_NONE;
            Yii::app()->user->setState('user_id', $record['id']);
        }
        return !$this->errorCode;
    }

}