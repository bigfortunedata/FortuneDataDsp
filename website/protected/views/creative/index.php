<?php
/* @var $this CreativeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Creatives',
);

$this->menu=array(
	array('label'=>'Create Creative', 'url'=>array('create', 'cid'=>$cid)),
	#array('label'=>'Manage Creative', 'url'=>array('admin')),
);
?>

<h1>Creatives</h1>

<?php echo $this->renderPartial('_gridView', array('dataProvider'=>$dataProvider)); ?>

