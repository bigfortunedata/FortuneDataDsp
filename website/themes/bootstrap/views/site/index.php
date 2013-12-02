
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
       <b>我们用实时竞价和受众定位技术，在高端中文媒体把您的广告精准地投送到全球任何一个地区的华人客户</b>    </p>
<!--i><p style=" font-size:22px; color: #a30c22" align="center">
        <b>限时免费试用大派送 - 新用户即赠送高端中文媒体2万次广告展示，免费注册不需要合同</b>    </p>
<p style=" font-size:20px;" align="center">
       Chinese travelers spent a record <b>US$102</b> billion on international tourism in 2012, a <b>40%</b> rise from US$73 billion in 2011. 
</p></i-->  
  <p style=" font-size:20px;" align="center">   <i> Overseas Chinese consumers market continues to grow rapidly and accounts for an increasing share of global sales for certain sectors. </i>  </p>
<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'',
)); ?>
<table border="0" >
<tr>
<td width=" 50%">
<p style=" font-size:20px;"> <i> <b>The Problem</b></i></p>
<p>North American and Europe businesses have largely missed the opportunity to engage with this emerging consumer group because the available media buying tools lack the capabilities of running campaigns targeting the group meaningfully and cost-effectively.</p>
<td width=" 50%">
<p style=" font-size:20px;"> <i> <b>Our Solution</b></i></p>
<p>Fortune Data Self-Serve Programmatic Advertising Platform provides businesses one-stop solution to engage Chinese consumers in real time and deliver your relevant marketing campaign content across all vast networks.</p>
<p > <b><a href="http://dsp.bigfortunedata.com/registration/registration/registration" >Free Trial 10,000 Banner Impressions  免费试用一万次广告显示</a></b> </p>

</td>
</tr>

</table>
<?php $this->endWidget(); ?>


 


