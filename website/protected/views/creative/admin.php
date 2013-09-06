<?php
/* @var $this CreativeController */
/* @var $model Creative */

$this->breadcrumbs=array(
	'Creatives'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Creative', 'url'=>array('index')),
	array('label'=>'Create Creative', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#creative-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Creatives</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'creative-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'label',
		'review_status_id',
		'width',
		'height',
		/*
		'type_id',
		'vault_path',
		'asset_url',
		'code',
		'vendor_id',
		'expanding_direction_id',
		'preview_url',
		'create_time',
		'create_user_id',
		'update_time',
		'update_user_id',
		'status_id',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
