<?php
/* @var $this CreativeController */
/* @var $model Creative */

$this->breadcrumbs=array(
	'Creatives'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Creative', 'url'=>array('index', 'cid'=>$cid)),
	array('label'=>'Create Creative', 'url'=>array('create', 'cid'=>$cid)),
	array('label'=>'View Creative', 'url'=>array('view', 'id'=>$model->id, 'cid'=>$cid)),
	#array('label'=>'Manage Creative', 'url'=>array('admin', 'cid'=>$campaign->id)),
);
?>

<h1>Update Creative <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>