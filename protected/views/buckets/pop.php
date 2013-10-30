<script>

    function edit(obj) {

        var data_value = obj.id;
        var checked = obj.checked;
        var accesskey = document.getElementById("ConfigForm_awskey");
        var accesskey = document.getElementById("ConfigForm_awssecret");
        
        $.ajax({url: $("#user-form").attr("action"),
            data: $("#user-form").serialize(),
            type: 'post',
            success: function(output) {
                jQuery("#cboxLoadedContent").html(output);
                if(typeof(jQuery("#cboxLoadedContent .errorMessage").html()) == "undefined"){
                    
                    document.location.reload();
                }
            }
        });
    }




</script>

<?php
$this->menu = array(
    array('label' => 'Create new Bucket', 'url' => array('create')),
    array('label' => 'View Buckets', 'url' => array('views')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#courses-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row"><div class="right">
        <?php
        ColorBox::generate("button");
        echo CHtml::link('<i class=\'icon s\'></i>Edit Connection', array('/buckets/form'), array('class' => 'button'));
        ?>
    </div> </div>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $record,
    'attributes' => array(
        'username',
        'name',
        'email',
        'address',
        'phone',
        'awsaccesskey',
        'awssecretkey',
    ),
));
?>

<h1>All Buckets</h1>
<?php
$i = 0;
// Get the contents of our bucket






$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'courses-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>

<a href="http://www.facebook.com" class="abc" />