<?php
/* @var $this CreativeController */
/* @var $data Creative */
?>

<div class="view">
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('label')); ?>:</b>
	<?php echo CHtml::encode($data->label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('review_status_id')); ?>:</b>
	<?php echo CHtml::encode($data->reviewStatus->description); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('asset_url')); ?>:</b>
	<?php echo CHtml::encode($data->asset_url); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_id')); ?>:</b>
	<?php echo CHtml::encode($data->status->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('picture')); ?>:</b>
	<?php if ($data->image)
          echo CHtml::image(Yii::app()->request->baseUrl.'/upload/'.$data->image,"image",array("width"=>200)); ?>
	<br />


	
</div>