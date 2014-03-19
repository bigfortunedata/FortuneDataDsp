<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<div class="row">
		<?php
		echo Yii::t('campaignStats', 'Start Date:');
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
		echo Yii::t('campaignStats', 'To Date:');
		
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
		'header' => Yii::t('campaignStats', 'Campaign Name'),
		'url' => $this->createUrl('/campaign/siteStats', array('id'=>'$data->id', 'fromDate'=>$filters['fromDate'], 'toDate'=>$filters['toDate'])),
	),
	array('name'=>'impressionsBid','header'=>Yii::t('campaignStats', 'Impressions Bid')),
	array('name'=>'impressionsWon','header'=>Yii::t('campaignStats', 'Impressions Won')),
	array('name'=>'totalEffectiveCPM','header'=>Yii::t('campaignStats', 'effectiveCPM')),
	array('name'=>'totalSpend','header'=>Yii::t('campaignStats', 'Total Spend')),
	array('name'=>'clicks','header'=>Yii::t('campaignStats', 'Clicks')),
	array('name'=>'clickthruRate','header'=>Yii::t('campaignStats', 'Click Thru Rate')),
	array('name'=>'costPerClick','header'=>Yii::t('campaignStats', 'Cost Per Click')),
	);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type'=>'striped bordered',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => $gridColumns,
));
?>
