<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    //'theme'=>'abound',
    'theme' => 'bootstrap',
    'aliases' => array(
        // yiistrap configuration
        'bootstrap' => realpath(__DIR__ . '/../extensions/bootstrap'), // change if necessary
       // 'bootstrap' => realpath(  '/homepages/35/d249932593/htdocs/bigdata/dsp/protected/extensions/bootstrap'),
       // 'bootstrap' => realpath(  '/var/www/bigdata/dsp/protected/extensions/bootstrap'),
        
        
    ),
    // preloading 'log' component
    'preload' => array('log',
        'bootstrap',),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
         'bootstrap.helpers.TbHtml',
        'application.extensions.states.*',
    ),
    'modules' => array(
        'user' => array(
            'debug' => true,
            'returnUrl' => '/',
            'returnLogoutUrl' => '/',
            'userTable' => 'fd_user',
            'translationTable' => 'fd_translation',
            'activationPasswordSet' => true,
            'mailer' => 'PHPMailer',
            'phpmailer' => array(
                'transport' => 'smtp',
                'html' => true,
                'properties' => array(
                    'CharSet' => 'UTF-8',
                    'SMTPDebug' => false,
                    'SMTPAuth' => true,
                    'SMTPSecure' => 'tls',
                    'Host' => 'smtp.1and1.com',
                    'Port' => 587,
                    'Username' => 'noreply@bigfortunedata.com',
                    'Password' => 'Abcd12345',
                ),
                'msgOptions' => array(
                    'fromName' => '',
                    'toName' => '',
                ),),
        ),
        'registration' => array(),
        'usergroup' => array(
            'usergroupTable' => 'fd_usergroup',
            'usergroupMessageTable' => 'fd_user_group_message',
        ),
        'membership' => array(
            'membershipTable' => 'fd_membership',
            'paymentTable' => 'fd_payment',
        ),
        'friendship' => array(
            'friendshipTable' => 'fd_friendship',
        ),
        'profile' => array(
            'privacySettingTable' => 'fd_privacysetting',
            'profileTable' => 'fd_profile',
            'profileCommentTable' => 'fd_profile_comment',
            'profileVisitTable' => 'fd_profile_visit',
        ),
        'role' => array(
            'roleTable' => 'fd_role',
            'userRoleTable' => 'fd_user_role',
            'actionTable' => 'fd_action',
            'permissionTable' => 'fd_permission',
        ),
        'message' => array(
            'messageTable' => 'fd_message',
        ),
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'password',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array('bootstrap.gii'),
        ),
    ),
    // application components
    'components' => array(
        'Paypal' => array(
            'class' => 'application.components.Paypal',
            //  'apiUsername' => 'tony_zhy_api1.yahoo.ca',
            //	   'apiPassword' => 'TAXZA9RVZNMR6BBY',
            //	   'apiSignature' => 'AG39LSvF2ew5ykqkWXfGZtV8u4BIAKRt.ckRWX8ZMyyWTB1WRG7UqOOG',
            'apiUsername' => 'tony_zhy-facilitator_api1.yahoo.ca',
            'apiPassword' => '1376242638',
            'apiSignature' => 'ATMsUAAGY3d7lRgqNHjAmHltgCNcAsz1JQPCI7jpgQKG.diilV4X84nm',
            'apiLive' => false,
            'returnUrl' => 'paypal/confirm/', //regardless of url management component
            'cancelUrl' => 'paypal/cancel/', //regardless of url management component
            // Default currency to use, if not set USD is the default
            'currency' => 'USD',
        // Default description to use, defaults to an empty string
        //'defaultDescription' => '',
        // Default Quantity to use, defaults to 1
        //'defaultQuantity' => '1',
        //The version of the paypal api to use, defaults to '3.0' (review PayPal documentation to include a valid API version)
        //'version' => '3.0',
        ),
        'user' => array(
            'class' => 'application.modules.user.components.YumWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/user/login'),
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
            'responsiveCss' => true,
        ),
        'cache' => array('class' => 'system.caching.CDummyCache'),
        // uncomment the following to enable URLs in path-format
        /*
          'urlManager'=>array(
          'urlFormat'=>'path',
          'rules'=>array(
          '<controller:\w+>/<id:\d+>'=>'<controller>/view',
          '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
          '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
          ),
          ),
         */
        /* 'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ), */
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=pw-bigdata2013.cundn37xxxsr.us-west-2.rds.amazonaws.com;dbname=bigdata2013',
            'emulatePrepare' => true,
            'username' => 'bigdata',
            'password' => 'bigdata2013',
            'charset' => 'utf8',
            'tablePrefix' => 'fd_',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'info, trace',
                    'logFile' => 'infoMessages.log',
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'noreply@bigfortunedata.com',
    ),
);