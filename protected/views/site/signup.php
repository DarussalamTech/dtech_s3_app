<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->pageTitle=Yii::app()->name . ' - SignUp';
$this->breadcrumbs=array(
	
	'SignUp',
);
//CVarDumper::dump($model->signup_check);
?>

<script>
 $(window).load(function(){
 $('#yw0_button').click();
 });
</script>
<?php ?>

<h1>SignUp</h1>

<p></p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'signup-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

        <div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone'); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
         <div class="row">
		<?php echo $form->labelEx($model,'retype_email'); ?>
		<?php echo $form->textField($model,'retype_email'); ?>
		<?php echo $form->error($model,'retypeemail'); ?>
	</div>
         <div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address'); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>
        
	<div class="row">
            
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		
	</div>
         <div class="row">
		<?php echo $form->labelEx($model,'retype_password'); ?>
		<?php echo $form->passwordField($model,'retype_password'); ?>
		<?php echo $form->error($model,'retype_password'); ?>
		
	</div>
        <div class="row">
            
		<?php echo $form->labelEx($model,'awsaccesskey'); ?>
		<?php echo $form->textField($model,'awsaccesskey'); ?>
		<?php echo $form->error($model,'awsaccesskey'); ?>
	</div>
        <div class="row">
            
		<?php echo $form->labelEx($model,'awssecretkey'); ?>
		<?php echo $form->textField($model,'awssecretkey'); ?>
		<?php echo $form->error($model,'awssecretkey'); ?>
	</div>
        <?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); 
  
                ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>



	<div class="row buttons">
		<?php echo CHtml::submitButton('Sign up'); ?>
	</div>

<?php $this->endWidget();

 ?>
</div>