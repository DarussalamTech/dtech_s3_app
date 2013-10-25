
<h1>All uploaded files</h1>
<?php
$i = 0;
// Get the contents of our bucket

$contents = $s3->getBucket($model->bucket_name);
foreach ($contents as $file) {
    $i++;
    //Getting the content one by one
    $fname = $file['name'];
    //Making the Access url for each item your bucket contains 
    $furl = "http://".$model->bucket_name.".s3.amazonaws.com/" . $fname;

    //output a link to the file
 
    echo CHtml::image($furl);
    //Making Links for item viewing
    echo "<a href=\"$furl\">$fname</a><br />";
    //Now we create the url string 
    //where we want to store our bucket items from the bucket to our disk
    //
    //
    $imagepath = UploadFile::uploadImage('pop', $i);
    //The $imagepath is the path of image on your disk
    //The fopen is given the url so it writes the file
    //
    $s3->getObject($model->bucket_name, $fname, fopen($imagepath . '/f' . $i . '.png', 'w'));
    
    
}
?>