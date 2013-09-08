<?php

/**
 * SiteScoutController .php class file
 *
 * This controller serves two functionalities
 * 1.   Implement the Oauth2 Authorization code
 *
 * 2.   Make api calls for RTB functions
 *
 * @author: Tony Zhang<tony.zhang@bigfortunedata.com>
 * @link http://www.bigfortunedata.com/
 * @copyright Copyright &copy; Fortune Data Inc 2013
 * @license The MIT License
 * @category Yii
 * @package
 * @version 1.0
 */
class SiteScoutController extends Controller {
    /*
     * SiteScoutAPI class instance
     */

    public $SD;

    public function init() {
        Yii::import('application.extensions.SiteScout.SiteScoutAPI');
        parent::init();
    }

    public function actionGetCode() {

        //$redirect = Yii::app()->request->hostInfo . '/' . $this->id . '/' . 'getAccessTokenFromCode';
        $this->SD = new SiteScoutAPI();
        $this->SD->getAuthorizationCode();
    }

    public function actionGetActiveAdExchange() {

        $this->SD = new SiteScoutAPI();
        $this->SD->getActiveAdExchange();
    }

    /**
     * Create a new campaign in SiteScout.
     * If create is successful, the browser will be redirected to the 'capaign manage' page.
     * @param integer $id the ID of the model to be create
     */
    public function actionCreateCampaign($id) {

        $this->SD = new SiteScoutAPI();
        $this->SD->createCampaign($id);
        $this->SD->uploadAllCreative($id);
        $this->SD->addAllCreative($id);
        //  $this->SD->addAllGeoRule($id);
         $this->SD->setPagePosition($id);
         $this->SD->addSiteRule($id);
    }

    /**
     *Update a campaign in SiteScout.
     *  
     * @param campaign ID
     */
    public function actionUpdateCampaign($id) {

        $this->SD = new SiteScoutAPI();
        $this->SD->updateCampaign($id);
        //  $this->SD->addGeoRule($id);
     }

    /**
     * remove a campaign record from SiteScout.
     *     * @param integer $campaign ID
     */
    public function actionRemoveCampaign($id) {

        $this->SD = new SiteScoutAPI();
        $this->SD->removeCampaign($id);
    }

    /**
     * remove a Creative record from SiteScout.
     *     * @param integer $Creative ID
     */
    public function actionRemoveCreative($id) {

        $this->SD = new SiteScoutAPI();
        $this->SD->removeCreative($id);
    }

    /**
     * upload a Creative record to SiteScout.
     * and add it to the Campaign
     *     * @param integer $Creative ID
     */
    public function actionUploadAddCreative($id) {

        $this->SD = new SiteScoutAPI();
        $this->SD->uploadOneCreative($id);
        $this->SD->addOneCreative($id);
    }
    /**
     *   Test API calls,examples.Uncomment one every time ,and test.
     */
    public function actionIndex() {
        // $this->SD = new SiteScoutAPI();
        //  $this->SD->getActiveAdExchange();
    }

}
