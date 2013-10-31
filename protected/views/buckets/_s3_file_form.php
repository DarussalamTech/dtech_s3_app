
<div class="form wide">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'config-form',
        'enableClientValidation' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->fileField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Upload', array("class" => "btn")); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>