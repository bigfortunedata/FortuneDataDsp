<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

  <div class="row-fluid">
	<div class="span2">
		<div class="sidebar-nav">
        
		  <?php 
		    $menuItems = array(
			    array('label'=>'<i class="icon icon-home"></i>  '.Yii::t('column', 'Home'), 'url'=>array('/site/index'),'itemOptions'=>array('class'=>'')), 
                        //<span class="label label-info pull-right">BETA</span>
                array('label'=>'<i class="icon icon-book"></i> '.Yii::t('column', 'Reports'), 'url'=>array('/campaign/stats' )),
                array('label'=>'<i class="icon icon-star"></i> '.Yii::t('column', 'Manage Campaign'), 'url'=>array('/campaign/index' )),
                array('label'=>'<i class="icon icon-pencil"></i>  '.Yii::t('column', 'Create Campaign'), 'url'=>array('/campaign/create' )),
				//array('label'=>'<i class="icon icon-search"></i> About this theme <span class="label label-important pull-right">HOT</span>', 'url'=>'http://www.webapplicationthemes.com/abound-yii-framework-theme/'),
				// Include the operations menu
				
			    // Dynamic labels
			    //if ($this->id === 'campaign' && $this->cid != null) {
			    //array('label'=>'<i class="icon icon-user"></i>  Add Creative', 'url'=>array('creative/create', 'cid'=>$this->cid)),
                array('label'=>'<i class="icon icon-user"></i>  '.Yii::t('column', 'Edit Profile'), 'url'=>array('/profile/profile/update' )),
                array('label'=>'<i class="icon icon-search"></i>  '.Yii::t('column', 'Payment History'), 'url'=>array('/clientPayment/index' )),
                array('label'=>'<i class="icon icon-repeat"></i>  '.Yii::t('column', 'Purchase Credit'), 'url'=>array('/clientPayment/create' )),
                array('label'=>'<i class="icon icon-lock"></i>  '.Yii::t('column', 'Change Password'), 'url'=>array('/user/user/changePassword' )),
               // array('label'=>'<i class="icon icon-inbox"></i>  Inbox', 'url'=>array('/message/message/index' )),
                array('label'=>'<i class="icon icon-remove"></i>  '.Yii::t('column', 'Logout'), 'url'=>array('/user/user/logout' )),
			);
		    $this->widget('zii.widgets.CMenu', array(
			/*'type'=>'list',*/
			'encodeLabel'=>false,
			'items'=> $menuItems,
			));?>
		</div>
        <br>
        <!--table class="table table-striped table-bordered">
          <tbody>
            <tr>
              <td width="50%">Bandwith Usage</td>
              <td>
              	<div class="progress progress-danger">
                  <div class="bar" style="width: 80%"></div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Disk Spage</td>
              <td>
             	<div class="progress progress-warning">
                  <div class="bar" style="width: 60%"></div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Conversion Rate</td>
              <td>
             	<div class="progress progress-success">
                  <div class="bar" style="width: 40%"></div>
                </div>
              </td>
            </tr>
            <tr>
              <td>Closed Sales</td>
              <td>
              	<div class="progress progress-info">
                  <div class="bar" style="width: 20%"></div>
                </div>
              </td>
            </tr>
          </tbody>
        </table-->
		<!--div class="well">
        
            <dl class="dl-horizontal">
              <dt>Account status</dt>
              <dd>$1,234,002</dd>
              <dt>Open Invoices</dt>
              <dd>$245,000</dd>
              <dt>Overdue Invoices</dt>
              <dd>$20,023</dd>
              <dt>Converted Quotes</dt>
              <dd>$560,000</dd>
              
            </dl>
      </div-->
		
    </div><!--/span-->
    <div class="span9">
    
    <?php if(!isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link('Dashboard'),
			'htmlOptions'=>array('class'=>'breadcrumb')
        )); ?><!-- breadcrumbs -->
    <?php endif?>
    
    <!-- Include content pages -->
    <?php echo $content; ?>

	</div><!--/span-->
  </div><!--/row-->


<?php $this->endContent(); ?>