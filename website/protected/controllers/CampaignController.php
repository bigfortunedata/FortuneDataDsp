<?php

class CampaignController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var The id of the selected campaign
	 */
	public $cid;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'index', 'create', 'update', 'view', and 'delete' actions
				'actions'=>array('index', 'create','update','view','delete','admin','toggle', 'stats', 'siteStats'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model=$this->loadModel($id);
		$newCreative = new Creative;
		if(isset($_POST['Creative']))
		{
			$uploadedFile=CUploadedFile::getInstance($newCreative,'image');
			$newCreative->attributes=$_POST['Creative'];
			$rnd = rand(0,9999);  // generate random number between 0-9999
			$fileName = "{$model->id}-{$rnd}";  // random number + file name
			
			if ($uploadedFile && $uploadedFile !== "") {
				$model->creative_image = $fileName;
				$newCreative->image = $fileName;
				if($newCreative->save() && $model->save()) {
					$uploadedFile->saveAs(Yii::app()->basePath.'/../upload/'.$fileName);
					$model->addCreative($newCreative->id);	
				}
			}
		}
		
		$this->cid = $id;
		$this->render('view',array(
			'model'=>$model,
			'creative'=>$newCreative,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Campaign;
		$model->setDefaultValues();
		
		$creative = new Creative;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Campaign']))
		{
			$model->attributes=$_POST['Campaign'];
			$model->location = $model->getSelectedRegions($_POST['Campaign']);
			if($model->save()) {
				$creative->attributes=$_POST['Campaign'];
				$rnd = rand(0,9999);  // generate random number between 0-9999
				$uploadedFile=CUploadedFile::getInstance($model,'creative_image');
				$fileName = "{$model->id}-{$rnd}";  // random number + file name
				if ($uploadedFile && $uploadedFile !== "") {
					$model->creative_image = $fileName;
					$creative->image = $fileName;
				}
				if($model->save()) {
					if($creative->save()) {
						$model->saveRegions($_POST['Campaign']);
						$uploadedFile->saveAs(Yii::app()->basePath.'/../upload/'.$fileName);
						$model->addCreative($creative->id);					
						$this->redirect(array('index'));
					}
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Toggle some properties such as status.
	 */
	public function actionToggle($id)
	{
		$model=$this->loadModel($id);
		if(isset($_GET['attribute']))
		{
			$attribute = $_GET['attribute'];
			if ($attribute === 'isOnline') {
				if ($model->status_id == 1) {
					$model->status_id = 2;
				}
				else if ($model->status_id == 2) {
					$model->status_id = 1;
				}
				
				if($model->save()) {
				}
			}
		}
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Campaign']))
		{
			$model->attributes=$_POST['Campaign'];
			$model->location = $model->getSelectedRegions($_POST['Campaign']);
			if($model->save()) {
				$model->saveRegions($_POST['Campaign']);
				
				// Check if the APIs should be called.
				//if ($model->sitescout_campaign_id != NULL) {
				//	$sdApiObject = new SiteScoutAPI();
				//	$sdApiObject->updateCampaign($model->id);
                               
                                //change the status to submit for all updating 
					$model->review_status_id = 8;
					$model->save();
				//}
				
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{	
		$model=$this->loadModel($id);
		// Check if the APIs should be called.
		if ($model->sitescout_campaign_id != NULL) {
			$sdApiObject = new SiteScoutAPI();
			$sdApiObject->removeCampaign($model->id);
		}
		
		$model->removeCampaign();
		
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Campaign', array(
			'criteria' => array(
				'condition'=>'user_id=:userId AND status_id!=:archivedId',
				'params'=>array(':userId'=>Yii::app()->user->id, ':archivedId'=>3),
			),
            'pagination' => array(
                'pageSize' => 100,
            ),
		));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Campaign('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Campaign']))
			$model->attributes=$_GET['Campaign'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Stats action.
	 */
	public function actionStats()
	{
		// Default to 3 days ago
		$fromDate = date("Y-m-d", time() - 259200);
		$toDate = date("Y-m-d", time());
		
		if(isset($_POST['FromDate']) && $this->validateDate($_POST['FromDate'], 'Y-m-d'))
			$fromDate=$_POST['FromDate'];
		if(isset($_POST['ToDate']) && $this->validateDate($_POST['ToDate'], 'Y-m-d'))
			$toDate=$_POST['ToDate'];
		
		$filters = array();
		$filters['fromDate'] = $fromDate;
		$filters['toDate'] = $toDate;
		
		$userId = Yii::app()->user->id;
		$allCampaigns=Yii::app()->db->createCommand("SELECT * FROM fd_campaign where user_id = '$userId' and status_id != 3")->queryAll();
		$campaignIdsArray = array();
		$campaignNames = array();
		foreach ($allCampaigns as $campaign) {
			$campaignIdsArray[] = $campaign['id'];
			$campaignNames[$campaign['id']] = $campaign['name'];
		}
		$campaignIds = join(',', $campaignIdsArray);

		$conditions = "campaign_date >= '$fromDate' and campaign_date <= '$toDate' and campaign_id IN ($campaignIds)";
		$rawStatsData=Yii::app()->db->createCommand("SELECT * FROM fd_campaign_site_stats_daily where $conditions")->queryAll();
		$statsData = array();
		
		foreach ($rawStatsData as $rawData) {
			$campaignId = $rawData['campaign_id'];
			$statsForCampaign = null;
			if (!isset($statsData[$campaignId])) {
				$statsForCampaign = $this->createNewCampaignStats();
				$statsData[$campaignId] = $statsForCampaign;
			}
			else {
				$statsForCampaign = $statsData[$campaignId];
			}
			// If there's already "DAILY" data, skip all the "HOURLY" data
			if ($rawData['batch_type'] == "HOURLY" && $statsForCampaign['batch_type'] == "DAILY") {
				continue;
			}
			
			$statsForCampaign['id']                  = $campaignId;
			$statsForCampaign['campaign_id']         = $campaignId;
			$statsForCampaign['campaignName']        = $campaignNames[$campaignId];
			$statsForCampaign['impressionsBid']     += $rawData['impressionsBid'];
			$statsForCampaign['impressionsWon']     += $rawData['impressionsWon'];
			$statsForCampaign['effectiveCPM']       += $rawData['effectiveCPM'];
			$statsForCampaign['auctionsSpend']      += $rawData['auctionsSpend'];
			$statsForCampaign['totalEffectiveCPM']  += $rawData['totalEffectiveCPM'];
			$statsForCampaign['totalSpend']         += $rawData['totalSpend'];
			$statsForCampaign['clicks']             += $rawData['clicks'];
			$statsForCampaign['clickthruRate']      += $rawData['clickthruRate'];
			$statsForCampaign['costPerClick']       += $rawData['costPerClick'];
			$statsForCampaign['batch_type']          = $rawData['batch_type'];
			
			$statsData[$campaignId] = $statsForCampaign;
		}
		
		$dataProvider=new CArrayDataProvider($statsData, array(
		    'id'=>'campaign_id',
		    'sort'=>array(
		        'attributes'=>array(
		             'campaign_id',
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>100,
		    ),
		));
		
		
		$this->render('stats',array(
			'dataProvider'=>$dataProvider,
			'filters'=>$filters
		));
	}
	
	/**
	 * Site stats action.
	 */
	public function actionSiteStats($id, $fromDate, $toDate)
	{
		if(!$this->validateDate($fromDate, 'Y-m-d'))
			$fromDate=date("Y-m-d", time() - 259200);
		if(!$this->validateDate($toDate, 'Y-m-d'))
			$toDate=date("Y-m-d", time());
		$conditions = "campaign_date >= '$fromDate' and campaign_date <= '$toDate' and campaign_id = '$id'";
		$rawStatsData=Yii::app()->db->createCommand("SELECT * FROM fd_campaign_site_stats_daily where $conditions")->queryAll();
		$statsDataMap = array();
		foreach ($rawStatsData as $rawData) {
			$domain = $rawData['domain'];
			$statsForSite = null;
			if (!isset($statsDataMap[$domain])) {
				$statsForSite = $this->createNewSiteStats();
				$statsDataMap[$domain] = $statsForSite;
			}
			else {
				$statsForSite = $statsDataMap[$domain];
			}
			
			// If there's already "DAILY" data, skip all the "HOURLY" data
			if ($rawData['batch_type'] == "HOURLY" && $statsForSite['batch_type'] == "DAILY") {
				continue;
			}
			
			$statsForSite['id']                  = $rawData['id'];
			$statsForSite['domain']              = $domain;
			$statsForSite['defaultBid']         += $rawData['defaultBid'];
			$statsForSite['impressionsBid']     += $rawData['impressionsBid'];
			$statsForSite['impressionsWon']     += $rawData['impressionsWon'];
			$statsForSite['effectiveCPM']       += $rawData['effectiveCPM'];
			$statsForSite['auctionsSpend']      += $rawData['auctionsSpend'];
			$statsForSite['totalEffectiveCPM']  += $rawData['totalEffectiveCPM'];
			$statsForSite['totalSpend']         += $rawData['totalSpend'];
			$statsForSite['clicks']             += $rawData['clicks'];
			$statsForSite['clickthruRate']      += $rawData['clickthruRate'];
			$statsForSite['costPerClick']       += $rawData['costPerClick'];
			$statsForSite['batch_type']          = $rawData['batch_type'];
			
			$statsDataMap[$domain] = $statsForSite;
		}
		
		$statsData = array();
		foreach ($statsDataMap as $statsItem) {
			$statsData[] = $statsItem;
		}
		
		$dataProvider=new CArrayDataProvider($statsData, array(
		    'id'=>'id',
		    'pagination'=>array(
		        'pageSize'=>100,
		    ),
		));
		
		$model=Campaign::model()->findByPk($id);
		$this->renderPartial('_siteStats',array(
			'dataProvider'=>$dataProvider,
			'campaign'=>$model->name,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Campaign the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Campaign::model()->findByPk($id);
		if ($model->user_id !== Yii::app()->user->id) $model = null;
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Campaign $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='campaign-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	function validateDate($date, $format = 'Y-m-d H:i:s')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}
	
	function createNewCampaignStats() {
		$newStats = array();
		$newStats['id'] = '';
		$newStats['campaign_id'] = '';
		$newStats['impressionsBid'] = 0;
		$newStats['impressionsWon'] = 0;
		$newStats['effectiveCPM'] = 0;
		$newStats['auctionsSpend'] = 0;
		$newStats['totalEffectiveCPM'] = 0;
		$newStats['totalSpend'] = 0;
		$newStats['clicks'] = 0;
		$newStats['clickthruRate'] = 0;
		$newStats['costPerClick'] = 0;
		$newStats['batch_type'] = "HOURLY";
		return $newStats;
	}
	
	function createNewSiteStats() {
		$newStats = array();
		$newStats['id'] = '';
		$newStats['domain'] = '';
		$newStats['defaultBid'] = 0;
		$newStats['impressionsBid'] = 0;
		$newStats['impressionsWon'] = 0;
		$newStats['effectiveCPM'] = 0;
		$newStats['auctionsSpend'] = 0;
		$newStats['totalEffectiveCPM'] = 0;
		$newStats['totalSpend'] = 0;
		$newStats['clicks'] = 0;
		$newStats['clickthruRate'] = 0;
		$newStats['costPerClick'] = 0;
		$newStats['batch_type'] = "HOURLY";
		return $newStats;
	}
}
