<div class="form" align="center">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        <div class="row">
		<?php echo $form->labelEx($model,'awsaccesskey'); ?>
		<?php echo $form->textField($model,'awskey',array('value'=>$model->awskey)); ?>
		<?php echo $form->error($model,'awskey'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'awssecret'); ?>
		<?php echo $form->textField($model,'awssecret'); ?>
		<?php echo $form->error($model,'awssecret'); ?>
	</div>

	
	

	<div class="row buttons">
		<?php echo CHtml::button( 'Edit',array('onClick' => 'edit(this)') ); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->