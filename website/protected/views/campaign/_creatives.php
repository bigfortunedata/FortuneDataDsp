<?php
$dataProvider=new CArrayDataProvider($creatives);

$gridColumns = array(
	array(
		'class'=>'bootstrap.widgets.TbImageColumn',
	    'imagePathExpression'=>'$data->getImageUrl()',
	    'usePlaceKitten'=>FALSE,
		'header'=>'Image',
	),
	array('name'=>'reviewStatus.description','header'=>'Review Status'),
	array(
		'class'=>'bootstrap.widgets.TbToggleColumn',
    	'toggleAction'=>'creative/toggle',
    	'name' => 'isOnline',
    	'header' => 'Status',
		'displayText' => 'Change status',
		'checkedButtonLabel' => 'Online',
		'uncheckedButtonLabel' => 'Offline',
		'additionalParam' => '"cid"=>'.$cid,
	),
	array(
        'header' => 'Delete',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
		'template'=>'{delete}',
        'deleteButtonUrl'=>'array("/creative/delete", "id"=>$data->id, "cid"=>'.$cid.')',
    )
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));

?>