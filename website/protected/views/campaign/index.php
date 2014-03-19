<?php
/* @var $this CampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Campaigns',
);

$this->menu=array(
	array('label'=>'Create Campaign', 'url'=>array('create')),
	#array('label'=>'Manage Campaign', 'url'=>array('admin')),
);
?>

<h4><?php echo Yii::t('campaign', 'Campaigns'); ?></h4>
<?php echo $this->renderPartial('_gridView', array('dataProvider'=>$dataProvider)); ?>
