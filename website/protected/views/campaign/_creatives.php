<?php
$gridColumns = array(
	array(
		'class'=>'bootstrap.widgets.TbImageColumn',
	    'imagePathExpression'=>'$data->getImageUrl()',
	    'usePlaceKitten'=>FALSE,
		'header'=>'Image',
	),
	array('name'=>'reviewStatus.description','header'=>'Review Status'),
	array('name'=>'status.description','header'=>'Online Status'),
	array(
        'header' => 'Operations',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
		'template'=>'{update} {delete}',
        'updateButtonUrl'=>'array("/creative/update", "id"=>$data->id, "cid"=>'.$cid.')',
        'deleteButtonUrl'=>'array("/creative/delete", "id"=>$data->id, "cid"=>'.$cid.')',
    )
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $creativesProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));

?>