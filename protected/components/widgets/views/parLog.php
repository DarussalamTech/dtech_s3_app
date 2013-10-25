<?php
//if (Yii::app()->user->roleuser == "SuperAdmin")
{
    /**
     *  jquery function for hida and show the logs
     */
    Yii::app()->clientScript->registerScript('activity_log_script', "
        $('#log-btn').click(function(){
         var email_Plusimg_src='" . Yii::app()->baseUrl . '/images/icons/plus.gif' . "';
         var email_Minusimg_src='" . Yii::app()->baseUrl . '/images/icons/minus.gif' . "';
        $('#log-plus').toggleClass('plus_rotate');
        $('.log-details').animate(
            {opacity: 'toggle', left: '+=50', height: 'toggle'}, 500, 
            function(){
                     if( $('.log-details').is(':visible')==false)
                    {
                        $('#log-plus').attr('src',email_Plusimg_src);
                    }
                    else
                    {
                         $('#log-plus').attr('src',email_Minusimg_src);
                    }
                }
        );
    });"
            , CClientScript::POS_END);

    $plusImage = "<div class='left_float' style='padding-top:2px'>" .
            CHtml::image(Yii::app()->baseUrl . '/images/icons/plus.gif', 'bingo', array('class' => 'rotate_iamge', 'id' => 'log-plus', 'class' => 'log-plus')) .
            "</div>";
    ?>

    <div class="child-container">
        <div class="subsection-header">
            <div>
                <?php echo CHtml::link($plusImage . ' System Log', 'Javascript:void(0)', array('id' => 'log-btn')); ?>
            </div>
        </div>
        <div class="log-details" style="">
            Created by <?php echo isset(Users::model()->findByPk($this->model->create_user_id)->username) ? Users::model()->findByPk($this->model->create_user_id)->username : ""; ?> 
            on <?php echo $this->model->create_time; ?>
            <br />
            <?php
            if (!empty($this->model->activity_log))
            {
                echo str_replace('\n', '<br />', $this->model->activity_log);
            } else
            {
                echo 'Modified by ' . Users::model()->findByPk($this->model->create_user_id)->username . ' on ' . $this->model->update_time;
            }
            ?>

        </div>
    </div>

    <?php
}
?>