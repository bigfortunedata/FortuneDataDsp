<?php
$this->breadcrumbs=array(
	'Client Payments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List ClientPayment','url'=>array('index')),
	array('label'=>'Create ClientPayment','url'=>array('create')),
	array('label'=>'View ClientPayment','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage ClientPayment','url'=>array('admin')),
	);
	?>

	<h1>Update ClientPayment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>