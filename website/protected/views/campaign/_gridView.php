<?php
$gridColumns = array(
	array('name'=>'name','header'=>'Name'),
	array(
        'header' => 'View',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
		'template'=>'{online}{offline}',
		'buttons'=>array(
			'online' => array(
	            'label'=>'Online',
	            'url'=>'array("campaign/offline", "id"=>$data->id)',
	            'options'=>array(
	                'class'=>'btn btn-small',
					'confirm'=>'The campaign is currently online. Do you want to change it to offline?',
	            ),
				'visible'=>'$data->isOnline',
				'imageUrl'=>Yii::app()->request->baseUrl . '/images/icon_online.png',
	        ),
			'offline' => array(
	            'label'=>'Offline',
	            'url'=>'array("campaign/online", "id"=>$data->id)',
	            'options'=>array(
	                'class'=>'btn btn-small',
					'confirm'=>'The campaign is currently offline. Do you want to change it to online?',
	            ),
				'visible'=>'$data->isOffline',
				'imageUrl'=>Yii::app()->request->baseUrl . '/images/icon_offline.png',
	        ),
		),
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