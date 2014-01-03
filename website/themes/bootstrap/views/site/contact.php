<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form TbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Contact Us';
$this->breadcrumbs = array(
    'Contact',
);
?>

<h3 style ="margin-left:50px"><?php echo Yii::t('contact', 'title');?></h3>

<?php if (Yii::app()->user->hasFlash('contact')): ?>

    <?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'alerts' => array('contact'),
    ));
    ?>

<?php else: ?>


    <table border="0">
        <tr>
            <td><p style ="margin-left:50px">
                    <?php echo Yii::t('contact', 'message');?>
                </p>

                <div class="form">

                    <?php
                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                        'id' => 'contact-form',
                        'type' => 'horizontal',
                        'enableClientValidation' => true,
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),
                    ));
                    ?>

                    <p class="note" style ="margin-left:50px"><?php echo Yii::t('contact', 'required');?></p>

                    <?php echo $form->errorSummary($model); ?>

                    <?php echo $form->textFieldRow($model, 'name'); ?>

                    <?php echo $form->textFieldRow($model, 'email'); ?>

                    <?php echo $form->textFieldRow($model, 'subject', array('size' => 60, 'maxlength' => 128)); ?>

                    <?php echo $form->textAreaRow($model, 'body', array('rows' => 8, 'class' => 'span6')); ?>

                    <?php if (CCaptcha::checkRequirements()): ?>
                        <?php
                        echo $form->captchaRow($model, 'verifyCode', array(
                            'hint' => Yii::t('contact', 'verificationHint'),
                        ));
                        ?>
                    <?php endif; ?>

                    <p  class="note" style ="margin-left:180px">

                        <?php
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'submit',
                            'label' => Yii::t('contact', 'Submit'),
                        ));
                        ?>

                    </p>
                    <?php $this->endWidget(); ?>

                </div><!-- form --></td>
            <td valign="top"><p  >
                <h3>  East Coast Head Office<br></h3> 
                <h5> 17 Spruce Gardens<br></h5>
                <h5>Belleville, ON CANADA<br>
                    <h5>K8N 5W3</h5><br><br><br><br>
                    </p>
                    <p>
                    <h3>  Wast Coast Head Office<br></h3> 
                    <h5> 4022 Providence PL<br></h5>
                    <h5> Victoria, BC CANADA<br></h5>
                    <h5>V8N 0A6<br></h5>
                </p></td>
        </tr>

    </table>


<?php endif; ?>