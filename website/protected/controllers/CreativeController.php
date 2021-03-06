<?php

class CreativeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	private $_campaign = null;
	
	protected function loadCampaign($campaignId) {
		if ($this->_campaign === null)
		{
			$campaign = Campaign::model()->findByPk($campaignId);
			if ($campaign->user_id === Yii::app()->user->id) {
				$this->_campaign = $campaign;
			}
			
			if ($this->_campaign === null)
			{
				throw new CHttpException(404, 'The requested campaign does not exist.');
			}
		}

		return $this->_campaign;
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'campaignContext + create view index update delete admin toggle',
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
				'actions'=>array('index', 'create','update','view','delete','admin','upload','toggle'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'cid'=>$this->_campaign->id,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Creative;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Creative']))
		{
			$model->attributes=$_POST['Creative'];
			$rnd = rand(0,9999);  // generate random number between 0-9999
			$uploadedFile=CUploadedFile::getInstance($model,'image');
			$fileName = "{$rnd}-{$uploadedFile}";  // random number + file name
			if ($uploadedFile && $uploadedFile !== "") $model->image = $fileName;
			if($model->save()) {
				$uploadedFile->saveAs(Yii::app()->basePath.'/../upload/'.$fileName);
				$this->_campaign->addCreative($model->id);
				$this->redirect(array('view','id'=>$model->id, 'cid'=>$this->_campaign->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'cid'=>$this->_campaign->id,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		
		if ($model->status_id == 1) {
			$model->status_id = 2;
		}
		else if ($model->status_id == 2) {
			$model->status_id = 1;
		}
		
		if($model->save())
		{
			$this->redirect(array('/campaign/view', 'id'=>$this->_campaign->id));
		}
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
		if ($model->sitescout_creative_id != NULL) {
			$sdApiObject = new SiteScoutAPI();
			$sdApiObject->removeCreative($model->id);
		}
		
		$this->_campaign->removeCreative($id);
		$this->loadModel($id)->delete();
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CArrayDataProvider($this->_campaign->creatives);
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'cid'=>$this->_campaign->id,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Creative('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Creative']))
			$model->attributes=$_GET['Creative'];

		$this->render('admin',array(
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
					$this->redirect(array('/campaign/view', 'id'=>$this->_campaign->id));
				}
			}
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Creative the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Creative::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Creative $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='creative-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function filterCampaignContext($filterChain)
	{
		if (isset($_GET['cid']))
		{
			$this->loadCampaign($_GET['cid']);
		}
		else
		{
			throw new CHttpException(403, 'Must specify a campaign before performing this action.');
		}
		$filterChain->run();
	}
}
