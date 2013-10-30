
<div class="form wide">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'config-form',
        'enableClientValidation' => true,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Create Bucket', array("class" => "btn")); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>