<?php
echo CHtml::tag('h5',array(),Yii::t('campaignStats', 'Campaign Stats').': '.$campaign);
$gridColumns = array(
	array('name'=>'domain','header'=>Yii::t('campaignStats', 'Domain')),
	array('name'=>'defaultBid','header'=>Yii::t('campaignStats', 'Default Bid')),
	array('name'=>'impressionsBid','header'=>Yii::t('campaignStats', 'Impressions Bid')),
	array('name'=>'impressionsWon','header'=>Yii::t('campaignStats', 'Impressions Won')),
	array('name'=>'totalEffectiveCPM','header'=>Yii::t('campaignStats', 'effectiveCPM')),
	array('name'=>'totalSpend','header'=>Yii::t('campaignStats', 'Total Spend')),
	array('name'=>'clicks','header'=>Yii::t('campaignStats', 'Clicks')),
	array('name'=>'clickthruRate','header'=>Yii::t('campaignStats', 'Click Thru Rate')),
	array('name'=>'costPerClick','header'=>Yii::t('campaignStats', 'Cost Per Click'))
	);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>
