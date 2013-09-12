<?php
$gridColumns = array(
	array('name'=>'name','header'=>'Name'),
	array(
		'class'=>'bootstrap.widgets.TbToggleColumn',
    	'toggleAction'=>'campaign/toggle',
    	'name' => 'isOnline',
    	'header' => 'Status',
		'displayText' => 'Change status',
		'checkedButtonLabel' => 'Online',
		'uncheckedButtonLabel' => 'Offline',
	),
	'default_bid',
	array('name'=>'reviewStatus.description','header'=>'Review Status'),
	'click_url',
	'budget_amount',
	'start_datetime',
	'end_datetime',
	array(
        'header' => 'Operations',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'viewButtonUrl'=>'array("view", "id"=>$data->id)',
        'updateButtonUrl'=>'array("update", "id"=>$data->id)',
        'deleteButtonUrl'=>'array("delete", "id"=>$data->id)',
    ));

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>