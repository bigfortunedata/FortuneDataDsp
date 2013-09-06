<?php
$dataProvider=new CArrayDataProvider($creatives);

$gridColumns = array(
	array('name'=>'label','header'=>'Name'),
	array('name'=>'reviewStatus.description','header'=>'Review Status'),
	array(
		'class'=>'bootstrap.widgets.TbImageColumn',
	    'imagePathExpression'=>'$data->getImageUrl()',
	    'usePlaceKitten'=>FALSE
	),
	array(
        'header' => 'Operations',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'viewButtonUrl'=>'array("/creative/view", "id"=>$data->id, "cid"=>'.$cid.')',
        'updateButtonUrl'=>'array("/creative/update", "id"=>$data->id, "cid"=>'.$cid.')',
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