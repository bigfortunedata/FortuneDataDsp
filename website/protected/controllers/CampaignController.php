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
				'actions'=>array('index', 'create','update','view','delete','admin','toggle'),
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
			if($model->save()) {
				$model->saveRegions($_POST['Campaign']);
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
}
