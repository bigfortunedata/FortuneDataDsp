<?php
$gridColumns = array(
	array('name'=>'user.username','header'=>'User'),
	array('name'=>'name','header'=>'Name'),
	array('name'=>'status.description','header'=>'Status'),
	'default_bid',
	array('name'=>'reviewStatus.description','header'=>'Review Status'),
	'click_url',
	'budget_amount',
	'start_datetime',
	'end_datetime',
	array(
        'header' => 'View',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
		'template'=>'{view}',
        'viewButtonUrl'=>'array("view", "id"=>$data->id)',
    ));

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>