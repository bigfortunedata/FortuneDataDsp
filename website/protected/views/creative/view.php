<?php
/* @var $this CreativeController */
/* @var $model Creative */

$this->breadcrumbs=array(
	'Creatives'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Creative', 'url'=>array('index', 'cid'=>$cid)),
	array('label'=>'Create Creative', 'url'=>array('create', 'cid'=>$cid)),
	array('label'=>'Update Creative', 'url'=>array('update', 'id'=>$model->id, 'cid'=>$cid)),
	array('label'=>'Delete Creative', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id, 'cid'=>$cid),'confirm'=>'Are you sure you want to delete this item?')),
	#array('label'=>'Manage Creative', 'url'=>array('admin')),
);
?>

<h1>View Creative #<?php echo $model->id; ?></h1>
<?php echo $this->renderPartial('_fullView', array('data'=>$model)); ?>
