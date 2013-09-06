<?php
/* @var $this CampaignController */
/* @var $data Campaign */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_id')); ?>:</b>
	<?php echo CHtml::encode($data->status->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default_bid')); ?>:</b>
	<?php echo CHtml::encode($data->default_bid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('review_status_id')); ?>:</b>
	<?php echo CHtml::encode($data->reviewStatus->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('click_url')); ?>:</b>
	<?php echo CHtml::encode($data->click_url); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('budget_amount')); ?>:</b>
	<?php echo CHtml::encode($data->budget_amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('budget_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->budget_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('budget_ede')); ?>:</b>
	<?php echo CHtml::encode($data->budget_ede); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fc_impressions')); ?>:</b>
	<?php echo CHtml::encode($data->fc_impressions); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fc_period_in_hours')); ?>:</b>
	<?php echo CHtml::encode($data->fc_period_in_hours); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fc_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->fc_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->start_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->end_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('conversion_audience')); ?>:</b>
	<?php echo CHtml::encode($data->conversion_audience); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('click_audience')); ?>:</b>
	<?php echo CHtml::encode($data->click_audience); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->create_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->update_user_id); ?>
	<br />

	*/ ?>

</div>