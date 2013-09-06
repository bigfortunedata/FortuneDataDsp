<?php
$this->pageTitle = Yum::t( "Profile");
$this->breadcrumbs=array(
		Yum::t('Edit profile'));
$this->title = Yum::t('Edit profile');
?>

<?php if(Yii::app()->user->hasFlash('profile_success')): ?>
 
<div  class="success">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'×', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
	    'profile_success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
    ),
)); ?>

</div>
 
<?php endif; ?>


<div class="form">

<?php
echo CHtml::beginForm();
?>

<?php echo Yum::requiredFieldNote(); ?>

<?php echo CHtml::errorSummary(array($user, $profile)); ?>

<?php if(Yum::module()->loginType & UserModule::LOGIN_BY_USERNAME) { ?>

<?php echo CHtml::activeLabelEx($user,'username', array('label'=>'User Name:')); ?>
<?php echo CHtml::activeTextField($user,'username',array(
			'size'=>20,'maxlength'=>20, 'readonly'=>'readonly', )); ?>
<?php echo CHtml::error($user,'username'); ?>

<?php } ?>

<?php if(isset($profile) && is_object($profile))
	$this->renderPartial('/profile/_form', array('profile' => $profile));
		?>

 	<?php

	if(Yum::module('profile')->enablePrivacySetting)
		echo CHtml::button(Yum::t('Privacy settings'), array(
					'submit' => array('/profile/privacy/update'))); ?>

	<?php
		if(Yum::hasModule('avatar'))
			echo CHtml::button(Yum::t('Upload avatar Image'), array(
				'submit' => array('/avatar/avatar/editAvatar'), 'class'=>'btn')); ?>

	<?php echo CHtml::submitButton($user->isNewRecord
			? Yum::t('Create my profile')
			: Yum::t('Save profile changes'), array('class'=>'btn')); ?>


	<?php echo CHtml::endForm(); ?>

	</div><!-- form -->
