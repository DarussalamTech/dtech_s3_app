<?php
$cs = Yii::app()->clientScript;

$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/form.css');
?>
<div class="pading-bottom-5">
    <div class="left_float">
        <h1>Connection Setting</h1>
    </div>

</div>
<div class="clear"></div>
<div class="form wide">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => true,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model, 'awsaccesskey'); ?>
        <?php echo $form->textField($model, 'awskey', array('value' => $model->awskey)); ?>
        <?php echo $form->error($model, 'awskey'); ?>
    </div>


    <div class="row">
        <?php echo $form->labelEx($model, 'awssecret'); ?>
        <?php echo $form->textField($model, 'awssecret'); ?>
        <?php echo $form->error($model, 'awssecret'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::button('Edit', array('onClick' => 'edit(this)',"class"=>"btn")); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->