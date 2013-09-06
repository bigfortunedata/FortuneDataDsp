<?php
$gridColumns = array(
	array('name'=>'label','header'=>'Name'),
	array('name'=>'reviewStatus.description','header'=>'Review Status'),
	'asset_url',
	array(
        'header' => 'Operations',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
        'viewButtonUrl'=>'array("view", "id"=>$data->id, "cid"=>$cid)',
        'updateButtonUrl'=>'array("update", "id"=>$data->id, "cid"=>$cid)',
        'deleteButtonUrl'=>'array("delete", "id"=>$data->id, "cid"=>$cid)',
    ));

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>