<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<div class="row">
		<?php
		echo "Start Date: ";
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
		echo "To Date: ";
		
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
