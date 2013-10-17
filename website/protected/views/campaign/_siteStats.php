<?php
echo CHtml::tag('h5',array(),'Campaign Stats: '.$campaign);
$gridColumns = array(
	'domain',
	'defaultBid',
	'impressionsBid',
	'impressionsWon',
	array('name'=>'totalEffectiveCPM','header'=>'eCPM'),
	array('name'=>'totalSpend','header'=>'Total Spend'),
	array('name'=>'clicks','header'=>'Clicks'),
	array('name'=>'clickthruRate','header'=>'Click Thru Rate'),
	array('name'=>'costPerClick','header'=>'Cost per Click')
	);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>
