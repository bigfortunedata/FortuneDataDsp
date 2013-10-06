<?php
/* @var $this CampaignController */
/* @var $data Campaign */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_id')); ?>:</b>
	<?php echo CHtml::encode($data->status->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('budget_amount')); ?>:</b>
	<?php echo CHtml::encode($data->budget_amount); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->start_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->end_datetime); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('locations')); ?>:</b> <br />
	<select multiple="multiple" disabled>
	<?php
	    $selectedRegions = $data->getAllSelectedRegions();
	    foreach ($selectedRegions as $region) {
		    echo "<option>" . $region->name . "</option>\n";
	    }
	?>
	</select>
</div>