<?php
$this->pageTitle = Yii::app()->name . ' - Login';
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'focus' => ($model->hasErrors()) ? '.error:first' : array($model, 'user_name'),
        ));
?>
   <?php
    if (Yii::app()->user->hasFlash('login'))
    {
        echo "<span style='color:green'>" . Yii::app()->user->getFlash('login') . "</span>";
    }
    ?>
<div class="login-content-wrapper"><div class="container">
        <fieldset class="login-fieldset">
            <legend><img src="<?php echo Yii::app()->baseUrl; ?>/images/logo/logo.png" /></legend>
            <div class="login-title">Login</div>
            <div class="login-form">
                <?php
                if (Yii::app()->user->hasFlash("registration"))
                {
                    echo "<span class='flash'>";
                    echo Yii::app()->user->getFlash("registration");
                    echo "</span>";
                    echo "<span clear='both'></span>";
                }
                ?>

                <p>Fields with  <span class="font-red"> * </span> are required</p>
                <div class="row">
                    <?php echo $form->labelEx($model, 'username'); ?>
                    <?php echo $form->textField($model, 'username', array('class' => 'text-field')); ?>
<!--                    <label>User Name  <span class="font-red">*</span></label>
                    <input type="text" class="text-field">-->
                </div>
                <div class="row">
                    <?php echo $form->labelEx($model, 'password'); ?>
                    <?php echo $form->passwordField($model, 'password', array('class' => 'text-field')); ?>
<!--                    <label>Password  <span class="font-red">*</span></label>
                    <input type="password" class="text-field">-->
                </div>

                <div class="row"><div class="right">
                        <?php echo CHtml::submitButton('Login', array('class' => 'button')); ?>
<!--                        <input type="button" class="button" value="Login" >-->
                    </div></div>

            </div>
        </fieldset>
    </div></div>
<!-- =========================== Contant Wrapper Close Here =========================== -->

<!-- ============================= Message Box Start Here ============================= -->
<?php
if (count($model->getErrors()) != 0)
{
    ?>
    <div class="login-messages">
        <div class="login-messages-error">
            <?php echo $form->error($model, 'username'); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
    </div>
    <?php
}
?>
<?php $this->endWidget(); ?>