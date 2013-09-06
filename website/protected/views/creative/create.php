<?php
/* @var $this CreativeController */
/* @var $model Creative */

$this->breadcrumbs=array(
	'Creatives'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Creative', 'url'=>array('index', 'cid'=>$cid)),
	#array('label'=>'Manage Creative', 'url'=>array('admin')),
);
?>

<h1>Create Creative</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>