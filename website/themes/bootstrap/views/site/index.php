
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
 
</br>
 
  
<?php $this->widget('bootstrap.widgets.TbCarousel', array(
  'items'=>array(
      array(
		'image'=>Yii::app()->theme->baseUrl.'/img/page1.jpg ', 
		 ),
      array(
		'image'=>Yii::app()->theme->baseUrl.'/img/page2.jpg ',
		 ),
      array(
		'image'=>Yii::app()->theme->baseUrl.'/img/page3.jpg ',
		 ),
  ),
));?>
</br> 
<p style=" font-size:20px;" align="center">
     <i> In North America and Europe,the Chinese travelers and local community consumer spending is continuing to grow </i>  </p>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'',
)); ?>
<table border="0" >
<tr>
<td width=" 50%">
<p style=" font-size:20px;"> <i> <b>The Problem</b></i></p>
<p>Businesses have largely missed the opportunity to engage with this emerging consumer group because there are very few ways business can reach and market to Chinese consumers meaningful and cost-effectively.</p>
<td width=" 50%">
<p style=" font-size:20px;"> <i> <b>Our Solution</b></i></p>
<p>Fortune Data programmatic marketing platform provides businesses one-stop solution to engage Chinese consumers in real time and deliver your relevant marketing campaign content across all vast networks. </p>

</td>
</tr>

</table>
<?php $this->endWidget(); ?>


 


