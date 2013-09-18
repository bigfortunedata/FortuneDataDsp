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
        'header' => 'View',
        'htmlOptions' => array('nowrap'=>'nowrap'),
        'class'=>'bootstrap.widgets.TbButtonColumn',
		'template'=>'{approve}{reject}',
		'buttons'=>array(
			'approve' => array(
	            'label'=>'Approve',
	            'icon'=>'icon-thumbs-up',
	            'url'=>'array("/adminCreative/approve", "id"=>$data->id, "cid"=>'.$cid.')',
	            'options'=>array(
	                'class'=>'btn btn-small',
					'confirm'=>'Do you want to approve this creative?',
	            ),
	        ),
			'reject' => array(
	            'label'=>'Reject',
	            'icon'=>'icon-thumbs-down',
	            'url'=>'array("/adminCreative/reject", "id"=>$data->id, "cid"=>'.$cid.')',
	            'options'=>array(
	                'class'=>'btn btn-small',
					'confirm'=>'Do you want to reject this creative?',
	            ),
	        ),
		),
    ),
);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));


?>