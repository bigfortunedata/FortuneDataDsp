
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
<p style=" font-size:22px;" align="center">
       <b><?php echo Yii::t('home', 'centerTitle');?></b>    </p>
<!--i><p style=" font-size:22px; color: #a30c22" align="center">
        <b>限时免费试用大派送 - 新用户即赠送高端中文媒体2万次广告展示，免费注册不需要合同</b>    </p>
<p style=" font-size:20px;" align="center">
       Chinese travelers spent a record <b>US$102</b> billion on international tourism in 2012, a <b>40%</b> rise from US$73 billion in 2011. 
</p></i-->  
  <p style=" font-size:20px;" align="center">   <i> <?php echo Yii::t('home', 'centerContent');?> </i>  </p>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'',
)); ?>
<table border="0" >
<tr>
<td width=" 50%">
<p style=" font-size:20px;"> <i> <b><?php echo Yii::t('home', 'leftTitle');?></b></i></p>
<p><?php echo Yii::t('home', 'leftContent');?></p>
<td width=" 50%">
<p style=" font-size:20px;"> <i> <b><?php echo Yii::t('home', 'rightTitle');?></b></i></p>
<p><?php echo Yii::t('home', 'rightContent');?></p>
<p > <b><a href="http://dsp.bigfortunedata.com/campaign/index" >Free Trial 10,000 Banner Impressions  免费试用一万次广告显示</a></b> </p>

</td>
</tr>

</table>
<?php $this->endWidget(); ?>


 


