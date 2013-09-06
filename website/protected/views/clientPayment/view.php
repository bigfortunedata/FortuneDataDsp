<?php
$this->breadcrumbs=array(
	'Client Payments'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List ClientPayment','url'=>array('index')),
array('label'=>'Create ClientPayment','url'=>array('create')),
array('label'=>'Update ClientPayment','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete ClientPayment','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage ClientPayment','url'=>array('admin')),
);
?>

<h1>View ClientPayment #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'user_id',
		'payment_type',
		'amount',
		'comment',
		'create_datetime',
		'create_user_id',
		'update_datetime',
		'update_user_id',
		'currency',
		'status',
),
)); ?>
