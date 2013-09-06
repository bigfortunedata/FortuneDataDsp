<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Campaign', 'url'=>array('index')),
	array('label'=>'Create Campaign', 'url'=>array('create')),
	array('label'=>'Update Campaign', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Campaign', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Add Creative', 'url'=>array('creative/create', 'cid'=>$model->id)),
	#array('label'=>'Manage Campaign', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('_fullView', array('data'=>$model)); ?>

<div id="creatives">
<?php if($model->creativeCount > 0): ?>
  <h3>
    <?php echo $model->creativeCount>1?$model->creativeCount . ' creatives' : 'One creative'; ?>
  </h3>
  <?php $this->renderPartial('_creatives', array(
  	'creatives'=>$model->creatives,
  	'cid'=>$model->id,
  ));?>
<?php endif;?>
</div>
