<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadFile
 *
 * @author hamza
 */
class UploadFile extends CUploadedFile {

    //put your code here



    public function uploadImage($controller, $id) {


        if (!is_dir(realpath(Yii::app()->basePath . '/../uplod'))) {
            mkdir('uplod', 0777, true);
            chmod('uplod', 0777);
        }
            if (!is_dir(realpath(Yii::app()->basePath . '/../uplod' . DIRECTORY_SEPARATOR . $controller))) {
                
                mkdir('uplod' . DIRECTORY_SEPARATOR . $controller, 0777, true);
                chmod('uplod' . DIRECTORY_SEPARATOR . $controller, 0777);
            }
            if (!is_dir(realpath(Yii::app()->basePath . '/../uplod' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $id))) {
           
                mkdir("uplod" . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $id, 0777, true);
                chmod("uplod" . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $id, 0777);
                
            }
        


        return realpath(Yii::app()->basePath . '/../uplod' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $id);
    }

}

?>
