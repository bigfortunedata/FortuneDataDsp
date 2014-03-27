<?php

class AdminCampaignController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @var The id of the selected campaign
     */
    public $cid;

    /**
     * @var The site scount api object
     */
    public $siteScoutApi;

    public function init() {
        parent::init();
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'index', 'create', 'update', 'view', and 'delete' actions
                'actions' => array('index', 'view', 'approve', 'reject', 'onhold'),
                'users' => array('admin', 'shihao', 'tony'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $this->cid = $id;
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Campaign', array(
            'criteria' => array(
                'condition' => 'status_id!=:archivedId',
                'params' => array(':archivedId' => 3),
                'order' => 'update_time DESC',
                'limit' => 100,
            ),
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Campaign('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Campaign']))
            $model->attributes = $_GET['Campaign'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Approve the campaign.
     */
    public function actionApprove($id) {
        $model = $this->loadModel($id);
        $successMessage = null;
        $failureMessage = null;
        /* if ($model->status->code != 'online') {
          $failureMessage = "The campaign is offline, can not been approved.";
          }
          else */
        if ($model->reviewStatus->code == 'approved') {
            $failureMessage = "The campaign has already been approved. No need to approve again.";
        } else if ($model->reviewStatus->code == 'pending') {
            $failureMessage = "The campaign is pending review. No need to approve again.";
        } else if ($model->reviewStatus->code == 'eligible') {
            $failureMessage = "The campaign has already been approved. No need to approve again.";
        } else {
            if ($this->siteScoutApi == null) {
                $this->siteScoutApi = new SiteScoutAPI();
            }

            // Check if the campaign has been approved before
            if ($model->sitescout_campaign_id != null) {
                $response = $this->siteScoutApi->updateCampaign($model->id);
                $this->siteScoutApi->addAllGeoRule($model->id);
               // $this->siteScoutApi->removeSiteRule($model->id);
               // $return_status = $this->siteScoutApi->addSiteRule($model->id);
                
                if (isset($response->status)&&($return_status=='success')) {
                    $successMessage = "The campaign updating has been approved.";
                    //$model->save();
                } else {
                    $failureMessage = "Encountered error in approving this campaign updating";
                }
                
                //$successMessage = "The campaign has been approved before. Updated status.";
            } else {
                //creating a new campaign 
                //1. create a basic campaign 
                //2.& 3 upload creative and assign creative to campaign
                //4&5 set page positiona and site rule
                //6 add geo targeting
                //set campaign status to its ONLINE 
                $response = $this->siteScoutApi->createCampaign($model->id);
                $this->siteScoutApi->uploadAllCreative($model->id);
                $this->siteScoutApi->addAllCreative($model->id);
                $this->siteScoutApi->setPagePosition($model->id);
                $this->siteScoutApi->addAllGeoRule($model->id);
                $return_status =$this->siteScoutApi->addSiteRule($model->id);
                //Campaign only can be set online as it has both creatives and inventory sources
                //if the orignal campaign status is online, sitescout api set its default status to offline
                //we manually set it status back to ONLINE
                if ($model->status_id == 2) {
                    $response = $this->siteScoutApi->updateCampaignOnlineStaus($model->id, 2);
                }

                if (isset($response->status)&&($return_status=='success')) {
                    $successMessage = "The new campaign is pending review.";
                    //$model->save();
                } else {
                    $failureMessage = "Encountered error in approving this new campaign";
                }
            }
        }

        $message = null;
        if ($successMessage != null) {
            $message = $successMessage;
            Yii::app()->user->setFlash('addCampaignSuccess', $successMessage);
        }
        if ($failureMessage != null) {
            $message = $failureMessage;
            Yii::app()->user->setFlash('addCampaignFailure', $failureMessage);
        }

        $this->render('/adminCampaign/view', array(
            'model' => $model,
            'message' => $message,
        ));
    }

    /**
     * Reject the campaign.
     */
    public function actionReject($id) {
        $model = $this->loadModel($id);
        $message = null;
        if ($model->reviewStatus->code != 'submitted') {
            $message = "The campaign should be in submitted status in order to be approved.";
        } else {
            $model->review_status_id = 2;
            $model->save();
            if ($model->sitescout_campaign_id != null) {
                if ($this->siteScoutApi == null) {
                    $this->siteScoutApi = new SiteScoutAPI();
                }
                $this->siteScoutApi->updateCampaign($model->id);
                $message = "The campaign is changed to " . $model->reviewStatus->description . ".";
            }
        }

        $this->render('/adminCampaign/view', array(
            'model' => $model,
            'message' => $message,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Campaign the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Campaign::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Campaign $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'campaign-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
