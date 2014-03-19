<?php
/* @var $this CampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Campaigns',
);
?>

<h4><?php echo Yii::t('campaignStats', 'Campaign Stats');?></h4>
<?php echo $this->renderPartial('_stats', array('dataProvider'=>$dataProvider, 'filters'=>$filters)); ?>
