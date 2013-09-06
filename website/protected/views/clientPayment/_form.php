<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'client-payment-form',
	'enableAjaxValidation'=>false,
)); ?>


<?php echo $form->errorSummary($model); ?>

	<!--?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?-->

	<!--?php echo $form->textFieldRow($model,'payment_type',array('class'=>'span5','maxlength'=>45)); ?-->

	<?php echo $form->textFieldRow($model,'amount',array('class'=>'span2', 'value'=>'100.00',)); ?>

	<?php echo $form->textAreaRow($model,'comment',array('rows'=>6, 'cols'=>50, 'class'=>'span5','maxlength'=>500)); ?>

	<!--?php echo $form->textFieldRow($model,'create_datetime',array('class'=>'span5','value'=>date("Y-m-d H:i:s") )); ?-->

        <?php echo $form->hiddenField($model,'create_datetime',array('type'=>"hidden",'size'=>2,'value'=>date("Y-m-d H:i:s"),'maxlength'=>2)); ?>
        <?php echo $form->hiddenField($model,'user_id',array('type'=>"hidden",'size'=>2,'value'=>Yii::app()->user->getId(),'maxlength'=>2)); ?>
        <?php echo $form->hiddenField($model,'create_user_id',array('type'=>"hidden",'value'=>Yii::app()->user->getId(), 'size'=>2,'maxlength'=>2)); ?>
        
	<!--?php echo $form->textFieldRow($model,'create_user_id',array('class'=>'span5')); ?-->

	<!--?php echo $form->textFieldRow($model,'update_datetime',array('class'=>'span5')); ?-->

	<!--?php echo $form->textFieldRow($model,'update_user_id',array('class'=>'span5')); ?-->

	<!--?php echo $form->textFieldRow($model,'currency',array('class'=>'span5','maxlength'=>45)); ?-->

	<!--?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>45)); ?-->

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'PAY NOW' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>
