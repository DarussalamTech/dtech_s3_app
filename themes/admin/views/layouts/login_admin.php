<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/login_style.css" />
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl ?>/packages/jui/js/jquery.js"></script>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div class="login-container">

            <!-- =========================== Header Wrapper Start Here =========================== -->
            <div class="login-header-container"><div class="container">
                    <h1><span style="font-weight: bold">Welcome To : <?php echo Yii::app()->name; ?></span></h1>
                   
                </div>
            </div>

            <?php echo $content; ?>

            <!-- =========================== Footer Wrapper Start Here ============================ -->
            <div class="login-footer container">
                <p>Copyright © <?php echo date('Y'); ?></p>

            </div>
            <!-- =========================== Footer Wrapper Close Here ============================ -->

        </div>
    </body>
</html>
