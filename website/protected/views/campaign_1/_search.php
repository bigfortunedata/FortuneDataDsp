<?php
/* @var $this CampaignController */
/* @var $model Campaign */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status_id'); ?>
		<?php echo $form->textField($model,'status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_bid'); ?>
		<?php echo $form->textField($model,'default_bid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'review_status_id'); ?>
		<?php echo $form->textField($model,'review_status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'click_url'); ?>
		<?php echo $form->textField($model,'click_url',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'budget_amount'); ?>
		<?php echo $form->textField($model,'budget_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'budget_type_id'); ?>
		<?php echo $form->textField($model,'budget_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'budget_ede'); ?>
		<?php echo $form->textField($model,'budget_ede',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fc_impressions'); ?>
		<?php echo $form->textField($model,'fc_impressions'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fc_period_in_hours'); ?>
		<?php echo $form->textField($model,'fc_period_in_hours'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fc_type_id'); ?>
		<?php echo $form->textField($model,'fc_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'start_datetime'); ?>
		<?php echo $form->textField($model,'start_datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'end_datetime'); ?>
		<?php echo $form->textField($model,'end_datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'conversion_audience'); ?>
		<?php echo $form->textField($model,'conversion_audience'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'click_audience'); ?>
		<?php echo $form->textField($model,'click_audience'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'create_user_id'); ?>
		<?php echo $form->textField($model,'create_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_user_id'); ?>
		<?php echo $form->textField($model,'update_user_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->