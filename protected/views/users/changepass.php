<div class="form wide">
    <?php
    $cs = Yii::app()->clientScript;
    $cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/form.css');

    $cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/gridform.css');
    $cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/functions.js');

    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'changepassword-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <?php echo $form->errorSummary($model); ?>

    <?php
    if (Yii::app()->user->hasFlash('success')) {
        echo "<span style='color:green'>" . Yii::app()->user->getFlash('success') . "</span>";
    }
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'old_pwd'); ?>
        <?php echo $form->passwordField($model, 'old_pwd'); ?>
        <?php echo $form->error($model, 'old_pwd'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'pwd_repeat'); ?>
        <?php echo $form->passwordField($model, 'pwd_repeat'); ?>
        <?php echo $form->error($model, 'pwd_repeat'); ?>
        <p class="hint">
            Passwords must be minimum 6 characters and can contain alphabets, numbers and special characters.
        </p>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Change pass', array("class" => "btn")); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->