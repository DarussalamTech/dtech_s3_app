<?php
$this->menu = array(
    array('label' => 'Create new Bucket', 'url' => array('create')),
    array('label' => 'View Buckets', 'url' => array('views')),
   
);
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'config-form',
        'enableClientValidation' => true,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model, 'Bucket Name'); ?>
        <?php echo $form->textField($model, 'bucket_name'); ?>
        <?php echo $form->error($model, 'bucket_name'); ?>
    </div>




    <div class="row buttons">
        <?php echo CHtml::submitButton('Create Bucket'); ?>
    </div>

    <?php $this->endWidget(); ?>