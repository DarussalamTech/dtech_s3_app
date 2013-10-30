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
                if (typeof(jQuery("#cboxLoadedContent .errorMessage").html()) == "undefined") {

                    document.location.reload();
                }
            }
        });
    }

</script>


<div class="pading-bottom-5">
    <div class="left_float">
        <h1>Your Settings</h1>
    </div>

    <?php /* Convert to Monitoring Log Buttons */ ?>
    <div class = "right_float">
        <span class="creatdate">
            <?php
            ColorBox::generate("colr_bx",array("height"=>"500","width"=>"500"));
            echo CHtml::link('Edit Connection', array('/buckets/editconnection'), array('class' => 'print_link_btn colr_bx'));
            ?>
        </span>
    </div>
</div>


<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $user,
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

<h1>Create Bucket</h1>
<?php $this->renderPartial("_bucket_form", array("model" => $bucketModel)); ?>
<h1>All Buckets</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'courses-grid',
    'dataProvider' => $bucketProvider->search(),
    'filter' => $bucketProvider,
    'columns' => array(
        'name',
        array(
            'class' => 'CButtonColumn',
            'template'=>'{view}{delete}'
        ),
    ),
));
?>

