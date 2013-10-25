<?php $this->menu=array(
	array('label'=>'Create new Bucket', 'url'=>array('index')),
        array('label'=>'View Buckets', 'url'=>array('view')),
	
); ?>
<h1>All Buckets files</h1>
<?php
$i = 0;
// Get the contents of our bucket

$buckets = $s3->listBuckets();

foreach ($buckets as $bucket) {
    
    
    
    echo "<h5> Bucket name :";
    echo $bucket .'<br/> </h5>';
}
?>