<?php
$this->breadcrumbs=array(
	'Client Payments',
);

$this->menu=array(
array('label'=>'Create ClientPayment','url'=>array('create')),
array('label'=>'Manage ClientPayment','url'=>array('admin')),
);
?>
  	<h3>Transaction history</h3>
 



<?php echo CHtml::label('Current Balance:','balanceLabel'); ?> 
<?php echo CHtml::textField('balanceAmt',$value='US$ '.$accountBalance, $htmlOptions=array('border'=>'0px solid','readonly'=>'readonly'))  ?> 
 </br>  
 <?php $this->widget('bootstrap.widgets.TbButton',array(
	'label' => 'Purchase Credit',
	'type' => 'primary',
        'url' =>$this->createUrl('ClientPayment/create')
	 
));?>

    
       
      
 
<?php $this->widget('bootstrap.widgets.TbGridView',array(
'type'=>'striped',
	'dataProvider'=>$dataProvider,
     'summaryText' => '',
    'columns' => array(
         array('name' => 'create_datetime', 'header' => 'Date/Time','htmlOptions'=>array('width'=>'20%'),),
        array('name' => 'amount', 'header' => 'Credit (US$)', 'value' => 'Yii::app()->numberFormatter->formatCurrency($data->amount, "$")','htmlOptions'=>array('width'=>'20%'),),
        array('name' => 'tax', 'header' => 'Tax (US$)', 'value' => 'Yii::app()->numberFormatter->formatCurrency($data->tax, "$")','htmlOptions'=>array('width'=>'20%'),),
        array('name' => 'total_amount', 'header' => 'Total Amount (US$)', 'value' => 'Yii::app()->numberFormatter->formatCurrency($data->total_amount, "$")','htmlOptions'=>array('width'=>'20%'),),
        array('name' => 'payment_type', 'header' => 'Payment Type','htmlOptions'=>array('width'=>'20%'),),
        array('name' => 'comment', 'header' => 'Notes','htmlOptions'=>array('width'=>'40%'),),)	    
)); ?>
