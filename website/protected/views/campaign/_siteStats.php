<?php
echo CHtml::tag('h5',array(),Yii::t('CampaignStats', 'Campaign Stats').': '.$campaign);
$gridColumns = array(
	array('name'=>'domain','header'=>Yii::t('CampaignStats', 'Domain')),
	array('name'=>'defaultBid','header'=>Yii::t('CampaignStats', 'Default Bid')),
	array('name'=>'impressionsBid','header'=>Yii::t('CampaignStats', 'Impressions Bid')),
	array('name'=>'impressionsWon','header'=>Yii::t('CampaignStats', 'Impressions Won')),
	array('name'=>'totalEffectiveCPM','header'=>Yii::t('CampaignStats', 'effectiveCPM')),
	array('name'=>'totalSpend','header'=>Yii::t('CampaignStats', 'Total Spend')),
	array('name'=>'clicks','header'=>Yii::t('CampaignStats', 'Clicks')),
	array('name'=>'clickthruRate','header'=>Yii::t('CampaignStats', 'Click Thru Rate')),
	array('name'=>'costPerClick','header'=>Yii::t('CampaignStats', 'Cost Per Click'))
	);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>
