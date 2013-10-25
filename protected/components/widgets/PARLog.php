<?php

Yii::import('zii.widgets.CPortlet');

class PARLog extends CPortlet
{
    /*
     * Log for model
     */
    public $model;


    /* id for hide and show*/
    public $id;
    
    public function init() {
        
        $this->contentCssClass='parlog_content';
        parent::init();
    }

    protected function renderContent()
    {
        $this->formatedate();
        $this->render('parLog');
    }

    /**
     * Convert to date formate reagrding MISC Conf.
     * 
     */
    private function formatedate()
    {
        $create_aray=explode(' ', $this->model->create_time);
        $update_aray=explode(' ', $this->model->update_time);

        $this->model->create_time = $create_aray[0] . " " . $create_aray[1];
        $this->model->update_time = $update_aray[0] . " " . $update_aray[1];
    }

}

?>
