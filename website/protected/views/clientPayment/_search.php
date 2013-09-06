<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'payment_type',array('class'=>'span5','maxlength'=>45)); ?>

		<?php echo $form->textFieldRow($model,'amount',array('class'=>'span5')); ?>

		<?php echo $form->textAreaRow($model,'comment',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

		<?php echo $form->textFieldRow($model,'create_datetime',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'create_user_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'update_datetime',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'update_user_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'currency',array('class'=>'span5','maxlength'=>45)); ?>

		<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>45)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
