<h1> <?php echo Yum::t('Registration'); ?> </h1>

<?php $this->breadcrumbs = array(Yum::t('Registration')); ?>

<div class="form">
<?php $activeform = $this->beginWidget('CActiveForm', array(
			'id'=>'registration-form',
			'enableAjaxValidation'=>true,
			'enableClientValidation'=>true,
			'focus'=>array($form,'username'),
			));
?>

<?php echo Yum::requiredFieldNote(); ?>
<?php echo CHtml::errorSummary(array($form, $profile)); ?>

<div class="row">
<div class="span12"> <?php
echo $activeform->labelEx($form,'username',array('label' => 'User Name'));
echo $activeform->textField($form,'username');
?> </div></div>

<div class="row">
<div class="span12"> <?php
echo $activeform->labelEx($profile,'email',array('label' => 'Email'));
echo $activeform->textField($profile,'email');
?> </div></div>

<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'firstname',array('label' => 'First Name'));
echo $activeform->textField($profile,'firstname');
?> </div></div>

<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'lastname',array('label' => 'Last Name'));
echo $activeform->textField($profile,'lastname');
?> </div></div>

<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'phone_no',array('label' => 'Phone No.'));
echo $activeform->textField($profile,'phone_no');
?> </div></div>  
    
<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'company_name',array('label' => 'Company Name'));
echo $activeform->textField($profile,'company_name');
?> </div></div>
    
 <div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'company_name',array('label' => 'Company WebSite'));
echo $activeform->textField($profile,'website', array('value'=>'http://'));
?> </div></div>

<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'street',array('label' => 'Address'));
echo $activeform->textField($profile,'street');
?> </div></div>

<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'city',array('label' => 'Ciyt'));
echo $activeform->textField($profile,'city');
?> </div></div>

    
<div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'state',array('label' => 'State/Province'));
//echo $activeform->textField($profile,'state');
echo CHtml::activeDropDownList($profile, 'state', array(
        "AL" => "Alabama",
        "AK" => "Alaska",
        "AZ" => "Arizona",
        "AR" => "Arkansas",
        "CA" => "California",
        "CO" => "Colorado",
        "CT" => "Connecticut",
        "DE" => "Delaware",
        "DC" => "District of Columbia",
        "FL" => "Florida",
        "GA" => "Georgia",
        "HI" => "Hawaii",
        "ID" => "Idaho",
        "IL" => "Illinois",
        "IN" => "Indiana",
        "IA" => "Iowa",
        "KS" => "Kansas",
        "KY" => "Kentucky",
        "LA" => "Louisiana",
        "ME" => "Maine",
        "MD" => "Maryland",
        "MA" => "Massachusetts",
        "MI" => "Michigan",
        "MN" => "Minnesota",
        "MS" => "Mississippi",
        "MO" => "Missouri",
        "MT" => "Montana",
        "NE" => "Nebraska",
        "NV" => "Nevada",
        "NH" => "New Hampshire",
        "NJ" => "New Jersey",
        "NM" => "New Mexico",
        "NY" => "New York",
        "NC" => "North Carolina",
        "ND" => "North Dakota",
        "OH" => "Ohio",
        "OK" => "Oklahoma",
        "OR" => "Oregon",
        "PA" => "Pennsylvania",
        "RI" => "Rhode Island",
        "SC" => "South Carolina",
        "SD" => "South Dakota",
        "TN" => "Tennessee",
        "TX" => "Texas",
        "UT" => "Utah",
        "VT" => "Vermont",
        "VA" => "Virginia",
        "WA" => "Washington",
        "WV" => "West Virginia",
        "WI" => "Wisconsin",
        "WY" => "Wyoming",
        "" => "----------------------",
        "AB" => "Alberta",
        "BC" => "British Columbia",
        "MB" => "Manitoba",
        "NB" => "New Brunswick",
        "NL" => "Newfoundland",
        "NT" => "Northwest Territory",
        "NS" => "Nova Scotia",
        "ON" => "Ontario",
        "PE" => "Prince Edward Island",
        "QC" => "Quebec",
        "SK" => "Saskatchewan",
        "YT" => "Yukon Territory"
    ));
?> </div></div>

 <div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'country',array('label' => 'Country'));
echo CHtml::activeDropDownList($profile,	'country',array('CANADA' => 'Candada', 'US' => 'United States'));
//echo $activeform->textField($profile,'country');
?> </div></div>
    
 <div class="row"><div class="span12"> <?php
echo $activeform->labelEx($profile,'postal_code',array('label' => 'Zip / Postal Code'));
echo $activeform->textField($profile,'postal_code');
?> </div></div>
    
    
<div class="row">
<div class="span12">
<?php echo $activeform->labelEx($form,'password'); ?>
<?php echo $activeform->passwordField($form,'password'); ?>
</div>
</div>

<div class="row">
<div class="span12">
<?php echo $activeform->labelEx($form,'verifyPassword'); ?>
<?php echo $activeform->passwordField($form,'verifyPassword'); ?>
</div></div>

<?php if(extension_loaded('gd') 
			&& Yum::module('registration')->enableCaptcha): ?>
	<div class="row">
    	<div class="span12">
		<?php echo CHtml::activeLabelEx($form,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo CHtml::activeTextField($form,'verifyCode'); ?>
		</div>
		<p class="hint">
		<?php echo Yum::t('Please enter the letters as they are shown in the image above.'); ?>
		<br/><?php echo Yum::t('Letters are not case-sensitive.'); ?></p>
	</div></div>
	<?php endif; ?>
	
	<div class="row submit">
    <div class="span12">
		<?php echo CHtml::submitButton(Yum::t('Registration'), array('class'=>'btn')); ?>
        </div>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
