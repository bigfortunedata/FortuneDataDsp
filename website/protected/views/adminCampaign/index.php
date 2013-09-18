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

<h4>Campaigns</h4>
<?php echo $this->renderPartial('/adminCampaign/_gridView', array('dataProvider'=>$dataProvider)); ?>
