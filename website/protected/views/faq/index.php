<?php
/* @var $this FaqController */

$this->breadcrumbs=array(
	'Faq',
);
?>

<table width="930" border="1" align="center" cellpadding="0" cellspacing="0">
  	<tbody>
  		<tr>
  			<td height="100%" align="left" valign="top">
  				<div id="topLinks">
  					<h3><?php echo Yii::t('faq', 'title'); ?></h3><p />
<?php
    for ($i = 0; $i < count($records); $i++) {
    	echo '<h4><a href="#faq' . $i . '">'. Yii::t('faqQuestion', $records[$i]["question"]) . '</a></h4>';
    }
?>
</div>
  			</td>
  		</tr>
<?php
    for ($i = 0; $i < count($records); $i++) {
?>
  		<tr>
    		<td height="100%" align="left" valign="top">
    			<div id="faq<?php echo $i;?>">
				<h3><?php echo Yii::t('faqQuestion', $records[$i]["question"]);?></h3>
				<?php echo Yii::t('faqAnswer', $records[$i]["question"]);?>
				<p><a href="#topLinks">Back to top</a></p>
				</div>
			</td>
  		</tr>
<?php
    }
?>
	</tbody>
</table>