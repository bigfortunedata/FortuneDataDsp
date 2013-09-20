<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Campaign', 'url'=>array('index')),
	array('label'=>'Create Campaign', 'url'=>array('create')),
	array('label'=>'Update Campaign', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Campaign', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Add Creative', 'url'=>array('creative/create', 'cid'=>$model->id)),
	#array('label'=>'Manage Campaign', 'url'=>array('admin')),
);
?>

<?php echo $this->renderPartial('/adminCampaign/_fullView', array('data'=>$model)); ?>
<p><p>
<?php
if(isset($message) && $message != null) {
            $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                        'id'=>1,
                        'options'=>array(
                            'show' => 'blind',
                            'hide' => 'explode',
                            'modal' => 'true',
                            'title' => "Message",
                            'autoOpen'=>true,
                            ),
                        ));
 
            printf('<span class="dialog">%s</span>', $message);
 
            $this->endWidget('zii.widgets.jui.CJuiDialog');
}
?>

<?php
if ($model->reviewStatus->description == 'Submitted' && !isset($model->sitescout_campaign_id)) {
	echo CHtml::button('Approve New Campaign', array('submit' =>Yii::app()->createUrl("adminCampaign/approve", array("id" => $model->id)), 'confirm'=>'Are you sure to approve this new campaign?', 'name'=>'approve')); 
	echo "<p><p>\n";
	echo CHtml::button('Reject New Campaign', array('submit' =>Yii::app()->createUrl("adminCampaign/reject", array("id" => $model->id)), 'confirm'=>'Are you sure to reject this new campaign?', 'name'=>'reject'));
}
else if ($model->reviewStatus->description == 'Submitted' && isset($model->sitescout_campaign_id)) {
	echo CHtml::button('Approve Campaign Update', array('submit' =>Yii::app()->createUrl("adminCampaign/approve", array("id" => $model->id)), 'confirm'=>'Are you sure to approve this campaign update?', 'name'=>'approve')); 
	echo "<p><p>\n";
	echo CHtml::button('Reject Campaign Update', array('submit' =>Yii::app()->createUrl("adminCampaign/reject", array("id" => $model->id)), 'confirm'=>'Are you sure to reject this campaign update?', 'name'=>'reject'));
}

?>

<div id="creatives">
  <br>
  <h4>
    <?php echo 'Creatives'; ?>
  </h4>
  <?php $this->renderPartial('/adminCampaign/_creatives', array(
  	'cid'=>$model->id,
	'creatives'=>$model->creatives,
  ));?>
</div>
