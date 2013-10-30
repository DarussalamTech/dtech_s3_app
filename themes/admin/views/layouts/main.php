<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl ?>/packages/jui/js/jquery.js"></script>
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen-override.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
        <script>
            // defining js base path
            var js_basePath = '<?php echo Yii::app()->theme->baseUrl; ?>';

            var yii_base_url = "<?php echo Yii::app()->baseUrl; ?>";

        </script>

        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl ?>/media/js/dtech.js"></script>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    </head>
    <!--  body {
         behavior: url(csshover.htc); 
        font-size: 100%;
        }-->
    <body>
        <!--[if IE]>
    <style type="text/css" media="screen">
    body {
     
    font-size: 100%;
    }

    #mainmenu ul li {float: left; width: 100%;}
    #mainmenu ul li a {height: 1%;}


    }
    </style>
    <![endif]-->
        <div class="container" id="page">
            <?php
            /**
             * Complete issue management in this div.
             * Just hide it when you are loading detail view
             */
            ?>
            <div id="loading" align="center">
                Please Wait

            </div>

            <div id="header" class="menu">
                <div id="logo" style="width: 70px;">
                    <img src="<?php echo Yii::app()->baseUrl; ?>/images/logo/logo.png" width="28" />
                </div>
                <div id="afterLogo" class="menu">
                    <!-- header -->
                    <div id="profile_menu">
                        <div class="left_float">
                            <h1>
                                <?php
                                echo CHtml::link(CHtml::encode(Yii::app()->name), Yii::app()->homeUrl);
                                ?>
                            </h1>
                        </div>
                        <div>

                        </div>
                        <div class="header-top-login-details">
                            <?php
                           
                            $this->widget('zii.widgets.CMenu', array(
                                'items' => array(
                                    // array('label' => "Change Password", 'url' => array('/users/changepass'),'visible'=>(Yii::app()->user->isGuest)?0:1),
                                    //array('label' => (Yii::app()->user->theme == "Night" ? "Day" : "Night"), 'url' => array('/user/changeTheme')),
                                    //array('label' => 'Access Control', 'url' => $this->createUrl('/rights'), 'visible' => (Yii::app()->user->isAdmin || Yii::app()->user->isSuperAdmin) ? 1 : 0, 'itemOptions' => array('class' => '')),
                                    //array('label' => 'Change Password', 'url' => $this->createUrl('/user/changePassword'), 'visible' => (Yii::app()->user->isGuest) ? 0 : 1, 'itemOptions' => array('class' => '')),
                                    //array('label' => 'Configuration', 'url' => $this->createUrl('/configurations/general', array('m' => 'Misc', 'type' => 'general')), 'visible' => (Yii::app()->user->isSuperuser) ? 1 : 0, 'itemOptions' => array('class' => '')),
                                    array('label' => 'Configuration', 'url' => $this->createUrl('/configurations/load', array('m' => 'Misc', 'type' => 'other')), 'visible' => 1, 'itemOptions' => array('class' => '')),
                                    array('label' => 'Change Password', 'url' => array('/users/changePass'), 'visible' => (Yii::app()->user->isGuest) ? 0 : 1, 'itemOptions' => array('class' => '')),
                                    array('label' => 'Logout', 'url' => array('/site/logout'), 'visible' => (Yii::app()->user->isGuest) ? 0 : 1, 'itemOptions' => array('class' => 'logout border-none')),
                                    array('label' => 'Login', 'url' => array('/site/login'), 'visible' => (Yii::app()->user->isGuest) ? 1 : 0, 'itemOptions' => array('class' => 'logout border-none')),
                                ),
                            ));
                          
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php
                    //&& Yii::app()->user->type=="admin"
                    if (!empty(Yii::app()->user->id)):
                        $m = new ProDropDown();
                        $m->run();
                        ?>
                        <div style="float: left;margin: 5px 0 0px 0px; width: 100%;">
                            <?php
                            $me = Menu::model()->findAll();
                            $pidArray = array();
                            foreach ($me as $m) {
                                $pidArray[] = $m->pid;
                            }

                            /* Get Active Menu */
                            $root_parent = $this->getRootParent();
                            $this->getNavigation(0, 0, $root_parent, $pidArray);
                            echo $this->menuHtml;
                        endif;
//                        CVarDumper::dump(Yii::app()->params['project_report_types'], 10, true);
                        ?>
                    </div>
                </div>
            </div>
            <div class="menu" id="submenu">
                <div class="menu" id="submenu_hold">
                </div>
            </div>

            <!-- mainmenu -->
            <div class="clear"></div>
            <?php echo $content; ?>
            <div class="clearbottom"></div>
            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> 
                <?php
                echo CHtml::link(CHtml::encode(Yii::app()->name), Yii::app()->homeUrl);
                ?>
            
                All Rights Reserved. <br/>
                Powered by :<a target="_blank" href="http://www.dtechsystems.com.uk/"> Dtech Systems</a>
                <?php
                /**
                 * Display Seal
                 */
                ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>

    <?php
    Yii::app()->clientScript->registerScript('popupwindow', '
          function printpdf(path)
          {
           var left = (screen.width/2)-(700/2);
           var top = (screen.height/2)-(490/2);
           var str="height=540,scrollbars=yes,width=700,status=yes,";
           str+="toolbar=no,menubar=no,location=no,left="+left+",top="+top+"";

           window.open(path,null,str);

         }

        ', CClientScript::POS_HEAD);
    ?>
    <?php
    Yii::app()->clientScript->registerScript('leavePageFunction', "
           function onLeavePage()
            {
                
                    
                    var warning = true;
                    window.onbeforeunload = function(e) {
                        if(submitbutton==false)
                        {
                            if (warning)
                            {
                                return 'This page is asking you to confirm that you want to leave - data you have entered may not be saved.';
                            }
                        }
                    }
              
            }
    ", CClientScript::POS_HEAD);

    //do for plus minus image on click on child containers
    Yii::app()->clientScript->registerScript('plus_minus_image', "
               $('.plus').parent().parent().bind('click',function()
                {
                   var minus_img='" . Yii::app()->theme->baseUrl . "/images/icons/minus.gif'; 
                   var plus_img='" . Yii::app()->theme->baseUrl . "/images/icons/plus.gif'; 
                    
                   if(typeof $($(this).children().children().get(0)).attr('class')!='undefined')
                   {    
                         
                        if($($(this).children().children().get(0)).attr('class').search('plus_rotate')!=-1)
                        {
                           
                            $($(this).children().children().get(0)).attr('src',minus_img)
                        }
                        else
                       {
                            $($(this).children().children().get(0)).attr('src',plus_img)
                       }
                   }
                   
                   
                })", CClientScript::POS_READY);

    Yii::app()->clientScript->registerScript('plus_minus_image_function', "
               function changePlusMinuImage(obj)
                {
                   //console.log(obj);
                   var minus_img='" . Yii::app()->theme->baseUrl . "/images/minus.png'; 
                   var plus_img='" . Yii::app()->theme->baseUrl . "/images/plus.png'; 
                   if(document.URL.search('create')==-1)
                   {
                         if($(obj).attr('class').search('plus_rotate')!=-1)
                           {
                                $(obj).attr('src',minus_img)
                           }
                       else
                           {
                                $(obj).attr('src',plus_img)
                           }
                   }
                  
                }", CClientScript::POS_HEAD);
    ?>
    <script type="text/javascript">
        //used for save and send to prevent reloading message
        var color_box_open = false;
        var submitbutton = false;


        function getquerystring()
        {
            var urlParams = {};
            var e,
                    a = /\+/g, // Regex for replacing addition symbol with a space
                    r = /([^&=]+)=?([^&]*)/g,
                    d = function(s) {
                return decodeURIComponent(s.replace(a, " "));
            },
                    q = window.location.search.substring(1);

            while (e = r.exec(q))
            {
                urlParams[d(e[1])] = d(e[2]);
            }

            return urlParams;
        }

        /**
         *  finding length of object of js
         */
        function objectlength(obj)
        {
            var count = 0;
            for (var prop in obj)
            {
                count++;
            }
            return count;

        }

        $(function() {
            $("input[type=submit]").click(function()
            {
                window.onbeforeunload = null;
            }
            )
        });

    </script>

</html>
