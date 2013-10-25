<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-4">
		<div id="sidebar">
                    <?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Menu',
		));
                
                ?> <?php 
                echo '<h2>';
                echo CHtml::Link('Home',Yii::app()->createUrl('site/index')).'<br>';
                echo CHtml::Link('About Us',Yii::app()->createUrl('site/page&view=about')).'<br>';
                echo CHtml::Link('Contacts',Yii::app()->createUrl('site/contact')).'<br>';
                echo CHtml::Link('Login',Yii::app()->createUrl('site/login')).'<br>';
                echo CHtml::Link('Register',Yii::app()->createUrl('user/create')).'<br>';
                
                echo CHtml::Link('Home',Yii::app()->createUrl('user/home')).'<br></h2>';
                
		
		$this->endWidget();
	?>
	</div><!-- sidebar -->
		
	</div>
	<div id="content" class="span-14">
		<?php echo $content; ?>
	</div><!-- content -->
	
</div>
<?php $this->endContent(); ?>
