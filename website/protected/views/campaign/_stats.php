<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<div class="row">
		<?php
		echo Yii::t('CampaignStats', 'Start Date:');
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		    'name'=>'FromDate',
			'attribute'=>'FromDate',
			'value'=>$filters['fromDate'],
		    'options'=>array(
		        'showAnim'=>'fold',
		            'dateFormat'=>'yy-mm-dd',
		            'debug'=>true,
	            ),
	            'htmlOptions'=>array(
		            'style'=>'height:20px;width:80px;'
		        ),
		    ));
		echo Yii::t('CampaignStats', 'To Date:');
		
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		    'name'=>'ToDate',
			'attribute'=>'ToDate',
			'value'=>$filters['toDate'],
		    'options'=>array(
		        'showAnim'=>'fold',
		            'dateFormat'=>'yy-mm-dd',
		            'debug'=>true,
	            ),
	            'htmlOptions'=>array(
		            'style'=>'height:20px;width:80px;'
		        ),
		    ));
		echo '<br>' . CHtml::submitButton('Go');
		?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php
$gridColumns = array(
	array(
		'class'=>'bootstrap.widgets.TbRelationalColumn',
		'name' => 'campaignName',
		'header' => Yii::t('CampaignStats', 'Campaign Name'),
		'url' => $this->createUrl('/campaign/siteStats', array('id'=>'$data->id', 'fromDate'=>$filters['fromDate'], 'toDate'=>$filters['toDate'])),
	),
	array('name'=>'impressionsBid','header'=>Yii::t('CampaignStats', 'Impressions Bid')),
	array('name'=>'impressionsWon','header'=>Yii::t('CampaignStats', 'Impressions Won')),
	array('name'=>'totalEffectiveCPM','header'=>Yii::t('CampaignStats', 'effectiveCPM')),
	array('name'=>'totalSpend','header'=>Yii::t('CampaignStats', 'Total Spend')),
	array('name'=>'clicks','header'=>Yii::t('CampaignStats', 'Clicks')),
	array('name'=>'clickthruRate','header'=>Yii::t('CampaignStats', 'Click Thru Rate')),
	array('name'=>'costPerClick','header'=>Yii::t('CampaignStats', 'Cost Per Click')),
	);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>
