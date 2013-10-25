
<div class="pading-bottom-5">
    <div class="left_float">
        <h1>Configurations</h1>
    </div>
</div>

<div class="clear"></div>
<div id="statusMsg" style="width: 100%;">
    <?php if (Yii::app()->user->hasFlash('back_up')): ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('back_up'); ?>
        </div>
    <?php endif; ?>

</div>


<div class="clear"></div>
<?php
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/form.css');
/*
 * Comment just below if, to enable creating it.
 */
if (!$model->isNewRecord) {
    ?>
    <div class="form wide">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'project-form',
//            'enableAjaxValidation' => true,
        ));
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title', array('maxlength' => 255)); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>

        <?php
        if ($model->isNewRecord) {
            ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'param'); ?>
                <?php echo $form->textField($model, 'param', array('maxlength' => 255)); ?>
                <?php echo $form->error($model, 'param'); ?>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'value'); ?>
            <?php
            /**
             * PCM: Make this code proper, because if there are multiple dropdowns
             * then we have to make array for each of it.
             */
            if ($model->field_type == "textArea") {
                echo $form->textArea($model, 'value');
            } else if ($model->field_type == "dropDown") {

                echo $form->dropDownList($model, 'value', $model->paramsOptions[$model->param]);
            } else {
                echo $form->textField($model, 'value');
            }
            ?>

            <?php echo $form->error($model, 'value'); ?>
        </div>

        <div class="row buttons">
            <?php
            echo CHtml::submitButton($model->isNewRecord ? 'Create New' : 'Update Existing', array('class' => 'btn btn btn-primary'));
            echo " or ";
            echo CHtml::link('Cancel', array('load', 'm' => $m));
            ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <?php
}
?>
<?php
$config = array(
    'pagination' => array('pageSize' => 30)
);
$provider = new CActiveDataProvider("ConfMisc", $config);
/* Show Grid */
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'misc-grid',
    'itemsCssClass' => 'table table-bordered',
    'dataProvider' => $provider, //ConfMisc::model()->search(),
    'columns' => array(
        //'id',
        array(
            'name' => 'title',
            'type' => 'Raw',
            'value' => '$data->title',
        ),
        array(
            'name' => 'value',
            'type' => 'Raw',
            'value' => 'strlen($data->value)>50?substr($data->value,0,70):$data->value',
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array
                (
                'update' => array
                    (
                    'label' => 'update',
                    'url' => 'Yii::app()->controller->createUrl("load", array("m" => "' . $m . '", "id"=> $data->id))',
                ),
            ),
        ),
    ),
));
?>
