<?php
/* @var $this CampaignController */
/* @var $model Campaign */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo Yii::t('campaign', 'Fields with <span class="required">*</span> are required.');?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'category_id'); ?>
		<?php echo $form->dropDownList($model,'category_id', $model->getCategories()); ?>
		<?php echo $form->error($model,'category_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budget_amount'); ?>
		<?php echo $form->textField($model,'budget_amount'); ?>
		<?php echo $form->error($model,'budget_amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'default_bid').Utility::GetBidRange().'<br>'; ?>
		<?php echo $form->textField($model,'default_bid'); ?>
		<?php echo $form->error($model,'default_bid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_datetime'); ?>
		<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		    'name'=>'Campaign[start_datetime]',
			'attribute'=>'Campaign[start_datetime]',
		    'value'=>$model->start_datetime,  // pre-fill the value
		    'options'=>array(
		        'showAnim'=>'fold',
		            'dateFormat'=>'yy-mm-dd',
		            'debug'=>true,
	            ),
	            'htmlOptions'=>array(
		            'style'=>'height:20px;'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'start_datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_datetime'); ?>
		<?php 
		$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		    'name'=>'Campaign[end_datetime]',
			'attribute'=>'Campaign[end_datetime]',
		    'value'=>$model->end_datetime,  // pre-fill the value
		    'options'=>array(
		        'showAnim'=>'fold',
		            'dateFormat'=>'yy-mm-dd',
		            'debug'=>true,
	            ),
	            'htmlOptions'=>array(
		            'style'=>'height:20px;'
		        ),
		    ));
		?>
		<?php echo $form->error($model,'end_datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'click_url'); ?>
		<?php echo $form->textField($model,'click_url',array('size'=>250,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'click_url'); ?>
	</div>
	
<?php
if ($model->isNewRecord) {
?>
	<div class="row">
		<?php echo $form->labelEx($model,'creative_image'); ?>
		<?php echo CHtml::activeFileField($model, 'creative_image'); ?>
		<?php echo $form->error($model,'creative_image'); ?>
	</div>
	
	<div class="row">
	     <?php
	          if ($model->creative_image && $model->creative_image !== "")
		          echo CHtml::image(Yii::app()->request->baseUrl.'/upload/'.$model->creative_image,"image",array("width"=>200)); 
		 ?>
	</div>
	<br>
<?php
}
?>
	<div class="row">
		<?php echo $form->labelEx($model,'budget_ede'); ?>
		<?php echo $form->dropDownList($model,'budget_ede', $model->getYesNoOptions()); ?>
		<?php echo $form->error($model,'budget_ede'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fc_impressions') . Yii::t('campaign', '0 means no frequency capping.'); ?><br>
		<?php echo $form->textField($model,'fc_impressions'); ?>
		<?php echo $form->error($model,'fc_impressions'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location'); ?>
		<?php 
		$this->beginWidget('ext.ECheckBoxTree.ECheckBoxTree', array(
			'options'=>array(
				'collapseImage'=>'/images/downArrow.gif',
			    'expandImage'=>'/images/rightArrow.gif',
			    'collapsed'=>true,
			    'initializeChecked'=>'collapsed',
			    'initializeUnchecked'=>'collapsed',
			    ))
		    );?>
		<?php echo $model->buildRegionsTree(null); ?>
		
		<?php $this->endWidget() ?>
		<?php echo $form->error($model,'location'); ?>
	</div>

	<div class="row buttons">
		<?php
		if ($model->isNewRecord) {
			echo CHtml::submitButton(Yii::t('campaign', 'Create'));
		}
		else {
			echo CHtml::submitButton(Yii::t('campaign', 'Save'), array('confirm'=>Yii::t('campaign', 'After you update the campaign, it will be reviewed by us before it can be put back online. Are you sure you want to update it?')));
		}
		?>
	</div>

<?php $this->endWidget(); ?>
        <p><b><?php echo Yii::t('campaign', 'Supported Ad Image File:'); ?></b><br>
<?php echo Yii::t('campaign', 'File Size'); ?> <= 120K<br>
<?php echo Yii::t('campaign', 'Image Size:'); ?> 300×250, 728×90, 160×600, 468×60, 120×600, 300×50 (mobile), and 320×50 (mobile).<br><br> 

<b><?php echo Yii::t('campaign', 'Denied Vertical/Ad Types:'); ?></b><br>
	*Dating<br>
	*Download/Toolbar<br>
	*E-Cigs(ElectronicCigarettes)<br>
	*Skin/Wrinkle<br>
	*Diet/Weightloss<br>
	*Relationships & Relationship Advice<br>
	*Bizopp/Work-From-Home<br>
	*Gambling<br>
	*Teeth/TeethWhitening<br>
	*Dating/Personals<br>
	*Religion, Faith & Spirituality<br>
	*SocialNetworking<br>
	*Sexual/Reproductive Health<br>
	*Restricted (Alchohol/Tobacco/Gambling/etc.)<br>
	*Alchohol<br>
	*Smileys, Cursors, Wallpaper, Screensavers<br>
	*PennyAuction<br>
	*Sweepstakes, Freebies, Giveways<br>
	*Military<br>
	*Politics<br>
	*Astrology/PsychicReadings<br>
	*Firearms & Weapons<br>
	*Tobacco & Smoking<br>
	*BellyFat<br>
	*Expandable-Automatic<br>
	*Audio-Automatic<br>
	*Adult/Provocative/Suggestive<br>
	*SystemWarning/WindowsBox</p>
</div><!-- form -->