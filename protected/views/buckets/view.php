<div class="pading-bottom-5">
    <div class="left_float">
        <h1>Upload Files</h1>
    </div>
</div>
<?php
    $this->renderPartial("_s3_file_form",array("model"=>$s3Model))
?>
<h1>All uploaded files</h1>

<?php
$i = 0;
// Get the contents of our bucket
$contents = $s3->getBucket($model->name);
//CVarDumper::dump($contents,10,true);
//die();
foreach ($contents as $file) {
    $i++;
    //Getting the content one by one
    $fname = $file['name'];
    //Making the Access url for each item your bucket contains 
    $furl = "http://" . $model->name . ".s3.amazonaws.com/" . $fname;

    //output a link to the file
//        CVarDumper::dump($rest,10,true);
//        die;
    $this->widget('zii.widgets.CDetailView', array(
        'data' => $file,
        'attributes' => array(
            array(
                'name' => 'Link',
                'type' => 'raw',
                'value' => "<a href=\"$furl\">$fname</a><br />",
            ),
            array(
                'name' => 'Picture',
                'type' => 'raw',
                'value' => CHtml::image($furl),
            ),
        ),
    ));

    echo '<br/>';
//    echo CHtml::image($furl);
//    //Making Links for item viewing
//    echo "<a href=\"$furl\">$fname</a><br />";
    //Now we create the url string 
    //where we want to store our bucket items from the bucket to our disk
    //
    //
//    $imagepath = UploadFile::uploadImage('pop', $i);
    //The $imagepath is the path of image on your disk
    //The fopen is given the url so it writes the file
    //
//    $s3->getObject("testBucket_dtech", $fname, fopen($imagepath . '/f' . $i . '.png', 'w'));
}
//CVarDumper::dump($s3->deleteObject("testBucket_dtech","uplod/product/34/e.png"));
?>