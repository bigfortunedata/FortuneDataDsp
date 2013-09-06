<?php
$this->breadcrumbs=array(
		'Payment Confirm',
);
?>

<div>
	<h3>Payment Confirmation</h3>
	<p>
		Payment completed successfully, Thanks for your business
	</p>
</div>

<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'link',
                        'url'=>$this->createUrl('ClientPayment/Index'),
			'label'=>'View Payment History',
		)); ?>
	</div>