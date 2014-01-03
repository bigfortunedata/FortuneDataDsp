<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />


        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php
        $baseUrl = Yii::app()->theme->baseUrl;
        $cs = Yii::app()->getClientScript();
        Yii::app()->clientScript->registerCoreScript('jquery');
        ?>
        <!-- Fav and Touch and touch icons -->
        <link rel="shortcut icon" href="<?php echo $baseUrl; ?>/img/icons/cookie.png"  >
            <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $baseUrl; ?>/img/icons/apple-touch-icon-144-precomposed.png">
                <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $baseUrl; ?>/img/icons/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="<?php echo $baseUrl; ?>/img/icons/apple-touch-icon-57-precomposed.png">
                        <?php
                        $cs->registerCssFile($baseUrl . '/css/bootstrap.min.css');
                        $cs->registerCssFile($baseUrl . '/css/bootstrap-responsive.min.css');
                        $cs->registerCssFile($baseUrl . '/css/abound.css');
                        $cs->registerCssFile($baseUrl . '/css/style-red.css');
                        ?>
                        <!-- styles for style switcher -->
                        <!--link rel="alternate stylesheet" type="text/css" href="<?php echo $baseUrl; ?>/css/style-blue.css" />
                        <link rel="alternate stylesheet" type="text/css" media="screen" title="style2" href="<?php echo $baseUrl; ?>/css/style-brown.css" />
                        <link rel="alternate stylesheet" type="text/css" media="screen" title="style3" href="<?php echo $baseUrl; ?>/css/style-green.css" />
                        <link rel="stylesheet" type="text/css" media="screen" title="style4" href="<?php echo $baseUrl; ?>/css/style-grey.css" />
                        <link rel="alternate stylesheet" type="text/css" media="screen" title="style5" href="<?php echo $baseUrl; ?>/css/style-orange.css" />
                        <link rel="alternate stylesheet" type="text/css" media="screen" title="style6" href="<?php echo $baseUrl; ?>/css/style-purple.css" />
                        <link rel="alternate stylesheet" type="text/css" media="screen" title="style7" href="<?php echo $baseUrl; ?>/css/style-red.css" /-->
                        <?php
                        $cs->registerScriptFile($baseUrl . '/js/bootstrap.min.js');
                        $cs->registerScriptFile($baseUrl . '/js/plugins/jquery.sparkline.js');
                        $cs->registerScriptFile($baseUrl . '/js/plugins/jquery.flot.min.js');
                        $cs->registerScriptFile($baseUrl . '/js/plugins/jquery.flot.pie.min.js');
                        $cs->registerScriptFile($baseUrl . '/js/charts.js');
                        $cs->registerScriptFile($baseUrl . '/js/plugins/jquery.knob.js');
                        $cs->registerScriptFile($baseUrl . '/js/plugins/jquery.masonry.min.js');
                        $cs->registerScriptFile($baseUrl . '/js/styleswitcher.js');
                        ?>

                        <?php Yii::app()->bootstrap->register(); ?>
                        </head>

                        <body>
                            <!-- Start of StatCounter Code for Default Guide -->
                            <script type="text/javascript">
                                var sc_project = 9286333;
                                var sc_invisible = 1;
                                var sc_security = "a82cac4a";
                                var scJsHost = (("https:" == document.location.protocol) ?
                                        "https://secure." : "http://www.");
                                document.write("<sc" + "ript type='text/javascript' src='" +
                                        scJsHost +
                                        "statcounter.com/counter/counter.js'></" + "script>");
                            </script>
                            <noscript><div class="statcounter"><a title="web analytics"
                                                                  href="http://statcounter.com/" target="_blank"><img
                                            class="statcounter"
                                            src="http://c.statcounter.com/9286333/0/a82cac4a/1/"
                                            alt="web analytics"></a></div></noscript>
                            <!-- End of StatCounter Code for Default Guide -->
                            <section id="navigation-main">   
                                <!-- Require the navigation -->
                                <div class="navbar navbar-inverse navbar-fixed-top">
                                    <div class="navbar-inner">
                                        <div class="container">
                                            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                                <span class="icon-bar"></span>
                                            </a>

                                            <!-- Be sure to leave the brand out there if you want it shown -->
                                            <div class="nav-collapse">


                                                <a class="brand"  href="#"> <img src="<?php echo $baseUrl; ?>/img/cookie.png" height="35" width="35" > Fortune Data: Self-Serve Advertising Platform </a>

                                                <?php
                                                $this->widget('zii.widgets.CMenu', array(
                                                    'htmlOptions' => array('class' => 'pull-right nav'),
                                                    'submenuHtmlOptions' => array('class' => 'dropdown-menu'),
                                                    'itemCssClass' => 'item-test',
                                                    'encodeLabel' => false,
                                                    'items' => array(
                                                        array('label' => 'Home', 'url' => array('/site/index')),
                                                        array('label' => 'Campaigns', 'url' => array('/campaign/index'), 'visible' => !Yii::app()->user->isGuest),
                                                        array('label' => 'Admin', 'url' => array('/adminCampaign/index'), 'visible' => Yii::app()->user->isAdmin()), // array('label' => 'My Account', 'url' => array('/profile/profile/update'), 'visible' => !Yii::app()->user->isGuest),
                                                        array('label' => 'About Us', 'url' => ('http://www.bigfortunedata.com'), 'visible' => Yii::app()->user->isGuest),
                                                        array('label' => 'Contact', 'url' => array('/site/contact')),
                                                        // array('label'=>'Terms', 'url'=>array(Yii::app()->baseUrl . '/protected/views/site/pages/FORTUNEDATA_TERMS_AND_CONDITIONS.pdf'), 'linkOptions' => array('target'=>'_blank'),'visible'=>!Yii::app()->user->isGuest),
                                                        array('label' => 'Log in', 'url' => array('/user/auth'), 'visible' => Yii::app()->user->isGuest),
                                                        array('label' => 'Sign Up', 'url' => array('/registration/registration/registration'), 'visible' => Yii::app()->user->isGuest),
                                                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                                                    ),
                                                ));

                                                $this->widget('application.components.widgets.LanguageSelector');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section><!-- /#navigation-main -->

                            <section class="main-body">
                                <!--div class="container-fluid -->
                                <?php if (isset($this->breadcrumbs)): ?>
                                    <?php
                                    $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                                        'links' => $this->breadcrumbs,
                                    ));
                                    ?><!-- breadcrumbs -->
                                <?php endif ?>
                                <!-- Include content pages -->
                                <?php echo $content; ?>
                                <!-- /div -->
                            </section>



                            <footer>
                                <div class="subnav navbar navbar-fixed-bottom">
                                    <div class="navbar-inner">
                                        <div class="container">
                                            Copyright &copy; <?php echo date('Y'); ?> by Fortune Data Inc. All Rights Reserved. 
                                            <?php
                                            echo CHtml::link('Terms and Conditions  ', Yii::app()->baseUrl . '/assets/FORTUNEDATA_TERMS_AND_CONDITIONS.pdf', array('target' => '_blank'));
                                            echo CHtml::link('Privacy Policy   ', Yii::app()->baseUrl . '/assets/FORTUNEDATA_Privacy_Policy.pdf', array('target' => '_blank'));
                                            echo CHtml::link('   @FORTUNEDATA Follow us on Twitter ', 'https://twitter.com/FORTUNEDATA', array('target' => '_blank'))
                                            ?></div>
                                    </div>
                                </div>      
                            </footer>

                            </div><!-- page -->

                        </body>
                        </html>
