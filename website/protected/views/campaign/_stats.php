<?php
$gridColumns = array(
	array(
		'class'=>'bootstrap.widgets.TbRelationalColumn',
		'name' => 'campaignName',
		'header' => 'Campaign Name',
		'url' => $this->createUrl('/campaign/siteStats', array('id'=>'$data->id', 'fromDate'=>$filters['fromDate'], 'toDate'=>$filters['toDate'])),
	),
	array('name'=>'impressionsBid','header'=>'Impressions Bid'),
	array('name'=>'impressionsWon','header'=>'Impressions Won'),
	array('name'=>'totalEffectiveCPM','header'=>'eCPM'),
	array('name'=>'totalSpend','header'=>'Total Spend'),
	array('name'=>'clicks','header'=>'Clicks'),
	array('name'=>'clickthruRate','header'=>'Click Thru Rate'),
	array('name'=>'costPerClick','header'=>'Cost per Click'),
	);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>
