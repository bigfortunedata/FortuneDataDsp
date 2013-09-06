<?php
$this->breadcrumbs=array(
	'Payment Cancel',
);
?>

<div>
	<h3>Payment Cancellation</h3>
	<p>
		The payment was cancelled by the user.
	</p>
</div>
<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'link',
                        'url'=>$this->createUrl('ClientPayment/Create'),
			'label'=>'Purchase Credit',
		)); ?>
</div>