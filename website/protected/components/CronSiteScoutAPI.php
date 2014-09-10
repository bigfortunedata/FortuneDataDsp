<?php

/**
 * SiteScoutAPI.php class file
 *
 * REST reference (Live Connect)
 * http://www.sitescout.com/support/api/
 *
 * @author: Tony Zhang<tony.zhang@bigfortunedata.com>
 * @link http://www.bigfortunedata.com/
 * @copyright Copyright &copy; Fortune Data Inc 2013
 * @license The MIT License
 * @category Yii
 * @package
 * @version 1.0
 */
class CronSiteScoutAPI {

    /**
     * The access token array
     * This is a short lived (one hour) essential token included in every API call.
     * Stored in session and renewed using refresh token when it expires.
     * @var array
     * Array keys:  ([scope],[access_token],[token_type],[expires_in][created])
     */
    private $access_token;

    /**
     * ACCESS_TOKEN_CREATED
     * @var time
     */
    private $access_token_created;

    /**
     * CLIENT_ID
     * @var string
     */
    private $client_id = 'bigdata2013';

    /**
     * CLIENT_PASSWORD
     * @var string
     */
    private $client_secret = 'yCtX@XhSLg$^u';

    /**
     * CLIENT_CREDENTIAL
     * Combining the client credentials as shown: clientID:clientSecret (e.g., beethoven:letmein)
     * Encoding the combined credentials using Base64 (e.g., YmVldGhvdmVuOmxldG1laW4=)
     * Prepending the encoded value with ??Basic?? (e.g., Basic YmVldGhvdmVuOmxldG1laW4=)
     * @var string
     */
    private $client_credential = 'Basic YmlnZGF0YTIwMTM6eUN0WEBYaFNMZyRedQ==';

    /**
     * Content-Type
     * @var string
     */
    private $content_type = 'application/x-www-form-urlencoded';

    /**
     * Accept
     * @var string
     */
    private $accept = 'application/json';

    /**
     * Grant Type
     * @var string
     */
    private $grant_type = 'client_credentials';

    /**
     * SCOPE
     * STATS for Reporting,
     * AUDIENCES for Audience Management,
     * CONTROL for Campaign Management.
     * indicate which scopes requesting access to; the scope values must be space delimited
     * (e.g., STATS AUDIENCES). If parameter scope is not submitted, the client will be issued
     * an access token for all scopes selected at the client registration time.
     * @var string
     */
    private $scope = '';

    /**
     * HOST
     * @var string
     */
    private $host = 'api.sitescout.com';

    /**
     * AUTHORIZATION CODE
     * @var string
     */
    private $code = '';

    /**
     * AUTHORIZATION CODE FLAG
     * @var bool
     */
    private $getcode;
    private $session;

    /**
     * SiteScout Authorization Server and API URL
     */

    const SITESCOUT_AUTHORIZATION_URL = "https://api.sitescout.com/oauth/token";
    const SITESCOUT_API_URL = "https://api.sitescout.com/v1";
    const SITESCOUT_BASE_URL = "https://api.sitescout.com/advertisers/14551/";
    const STATUS_PENDING = "peinding";
    const STATUS_ELIGIBLE = "eligible";
    const STATUS_REVIEW = "review";
    const STATUS_HOLD = "hold";
    const STATUS_DENIED = "denied";
    const STATUS_BLOCKED = "blocked";
    const STATUS_ONLINE = "online";
    const STATUS_OFFLINE = "offline";
    const STATUS_ARCHIVED = "archived";
    const PROFIT_MARGIN =0.2 ;

    /**
     * Constructor
     * @param array $config_array  Use it to override default values for variables
     *
     */
    public function __construct() {

        Yii::import('application.extensions.EHttpClient.*');
        Yii::import('application.extensions.EHttpClient.adapter.*');

        // $this->session = Yii::app()->session;
        // $this->access_token = $this->session['access_token'];

        if ($this->isAccessTokenExpired()) {
            $this->access_token = $this->refreshAccessToken();
            $this->session['access_token'] = $this->access_token;
        }
    }

    /**
     * isAccessTokenExpired
     *
     *  Checks to see if access token expires in next 30 secs or if it is unavailable in session.
     * @return bool returns true if the access_token is expired.
     */
    public function isAccessTokenExpired() {
        if (empty($this->access_token) || !isset($this->access_token['access_token']) || !isset($this->access_token['token_type']) ||
                !isset($this->access_token['created']) || !isset($this->access_token['expires_in'])) {
            return true;
        }

        if (null == $this->access_token['access_token']) {
            return true;
        }

        // If the token is set to expire in the next 30 seconds.
        $expired = ($this->access_token['created'] + ($this->access_token['expires_in'] - 30)) < time();
        return $expired;
    }

    /**
     *  getAuthorizationCode
     *
     * Starts the authorization code grant flow
     * See OAuth2 http://msdn.microsoft.com/en-us/library/live/hh243647.aspx
     */
    public function getAuthorizationCode() {
        $path = self::SITESCOUT_AUTHORIZATION_URL;
        $postParameters = array(
            'grant_type' => $this->grant_type,
            'expect-continue' => 'true'
        );

        $headerParameters = array(
            'Host' => $this->host,
            'Authorization' => $this->client_credential,
            'Content-Type' => $this->content_type,
            'Accept' => $this->accept
        );

        $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, $postParameters, $headerParameters);

        if (isset($response->errorCode)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'SiteScout getAuthorizationCode API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
        }
    }

    /**
     *   SiteScoutApiCall
     *
     * Core function of the class,makes all API calls to SiteScout.
     *
     * @param  string $path  the request URL
     * @param  string $method  the request method.GET,PUT,POST,DELETE.
     * @param array   $getParameters GET  parameters array ( key=>value)
     * @param array   $postParameters GET  parameters array ( key=>value)
     * @param array   $headerParameters GET  parameters array ( key=>value)
     *
     * @return stdClass $ $response_obj
     */
    public function SiteScoutApiCall($path, $method = EHttpClient::POST, $getParameters = null, $postParameters = null, $headerParameters = null, $postBody = null) {

        $adapter = new EHttpClientAdapterCurl();

        $client = new EHttpClient($path, array(
            'maxredirects' => 2,
            'timeout' => 30,
            'adapter' => 'EHttpClientAdapterCurl'
        ));

        $client->setMethod($method);

        if (!empty($postBody)) {
            $client->setRawData($postBody, 'application/json');
        }


        if (!empty($getParameters))
            $client->setParameterGet($getParameters);

        if (!empty($postParameters))
            $client->setParameterPost($postParameters);

        if (!empty($headerParameters))
            $client->setHeaders($headerParameters);

        $client->setAdapter($adapter);
        $adapter->setConfig(array(
            'curloptions' => array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FAILONERROR => false,
                CURLOPT_SSL_VERIFYPEER => false,
            )
        ));

        $response = $client->request();

        //$access_token_created = time();
        //return stdClass object for all other API calls
        $response_obj = json_decode($response->getBody());

        return $response_obj;
    }

    /**
     *   refreshAccessToken
     *
     * Gets a new access token when the latter has  expired or it's  unavailable in session.
     * @return array $access_token
     */
    public function refreshAccessToken() {
        $path = self::SITESCOUT_AUTHORIZATION_URL;
        $postParameters = array(
            'grant_type' => $this->grant_type,
            'expect-continue' => 'true'
        );
        $headerParameters = array(
            'Host' => $this->host,
            'Authorization' => $this->client_credential,
            'Content-Type' => $this->content_type,
            'Accept' => $this->accept
        );

        $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, $postParameters, $headerParameters);

        if (isset($response->errorCode)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'SiteScout refreshAccessToken API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
        }



        $access_token = (array) $response;
        $access_token['created'] = time();
        return $access_token;
    }

    /**
     *   getActiveAdExchange
     *
     * Get Active Ads Exchange
     */
    public function getSite() {
        $response = new stdClass;
        
        $i=0;

        $headerParameters = array(
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        $siterule = SiteRule::model()->findAll();

        foreach ($siterule as $siterules) {
            $path = self::SITESCOUT_API_URL . 'sites/' . $siterules->sitescout_site_id;
            $response = $this->SiteScoutApiCall($path, EHttpClient::GET, null, null, $headerParameters);

            if (isset($response->siteRef)) {
                SiteRule::model()->updateByPk($siterules->id, array('status' => 'online'));
                $i = $i+1;
            } else {
                SiteRule::model()->updateByPk($siterules->id, array('status' => 'offline'));
            }
      }
         if ($i>5)
             return 'success';
        // return $response;
    }

    /**
     *   getActiveAdExchange
     *
     * Get Active Ads Exchange
     */
    public function getActiveAdExchange() {
        $path = self::SITESCOUT_API_URL . 'exchanges';
        $headerParameters = array(
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        $response = $this->SiteScoutApiCall($path, EHttpClient::GET, null, null, $headerParameters);

        if (isset($response->errorCode)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'SiteScout getActiveAdExchange API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
        }

        $adExchange = (array) $response;

        CVarDumper::dump($adExchange);

        // return $access_token;
    }

    /**
     *   createCampaign
     *
     * Create a Campaign
     * Path: /advertisers/{advertiserId}/campaigns
     * HTTP Method: POST
     */
    public function createCampaign($id) {
        $path = self::SITESCOUT_BASE_URL . 'campaigns';
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);

        if (!isset($campaign->id)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'createCampaign: Failed to get the campaing record from database, campaign ID:' . $id));
        }

        //
        if ($campaign->fc_impressions == 0) {
            //build the campaign body array
            $campaign_array =
                    array(
                        "name" => $campaign->name . '-' . $campaign->id . '-' . time(),
                        "status" => $campaign->status->code,
                        "defaultBid" => ROUND($campaign->default_bid / (1+self::PROFIT_MARGIN),3),
                        "clickUrl" => $campaign->click_url,
                        "budget" => array(
                            "amount" => ROUND($campaign->budget_amount / (1+self::PROFIT_MARGIN)),
                            "type" => 'daily',
                            "evenDeliveryEnabled" => 'true'
                        ),
                        "flightDates" => array(
                            "from" => str_replace('-', '', substr($campaign->start_datetime, 0, 12)),
                            "to" => str_replace('-', '', substr($campaign->end_datetime, 0, 12))
                        )
            );
        } else {
            //build the campaign body array
            $campaign_array =
                    array(
                        "name" => $campaign->name . '-' . $campaign->id . '-' . time(),
                        "status" => $campaign->status->code,
                        "defaultBid" => ROUND( $campaign->default_bid  / (1+self::PROFIT_MARGIN) ,3),
                        "clickUrl" => $campaign->click_url,
                        "budget" => array(
                            "amount" => ROUND($campaign->budget_amount / (1+self::PROFIT_MARGIN)),
                            "type" => 'daily',
                            "evenDeliveryEnabled" => 'true'
                        ),
                        "frequencyCapping" => array(
                            "impressions" => $campaign->fc_impressions,
                            "periodInHours" => $campaign->fc_period_in_hours,
                            "type" => 'campaign'
                        ),
                        "flightDates" => array(
                            "from" => str_replace('-', '', substr($campaign->start_datetime, 0, 12)),
                            "to" => str_replace('-', '', substr($campaign->end_datetime, 0, 12))
                        )
            );
        }



        //convert campaign array to json format
        $campaign_json = json_encode($campaign_array);

        //call sitescout API
        //return value : CAMPAIGN OBJECT
        $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, null, $headerParameters, $campaign_json);

        if (isset($response->errorCode)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'SiteScout createCampaign API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
        }

        //update campaignID in sitesouct into fd_campaign table
        //$count = 1
        $count = $campaign->updateByPk(
                $campaign->id, array('sitescout_campaign_id' => $response->campaignId,
            'status_id' => Utility::GetStatusId($response->status),
            'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));

        if ($count != 1) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'createCampaign: Failed to update campaign sitescout_campaign_id filed, campaign id:' . $id));
        }

        return $response;
    }

    /**
     *   uploadAllCreative
     *
     * Upload Campaign Creative Asset
     * Path: /advertisers/{advertiserId}/creatives/assets
     * HTTP Method: POST
     * parameter: Campaign ID
     */
    public function uploadAllCreative($id) {
        $path = self::SITESCOUT_BASE_URL . 'creatives/assets';
        $auth = $this->access_token['token_type'] . ' ' . $this->access_token['access_token'];

        //get the campaign and createive informaton from database
        $campaign = Campaign::model()->findByPk($id);
        $creative_asset = $campaign->creatives;

        foreach ($creative_asset as $creative_assets) {

            $creative_name = Yii::app()->basePath . '/../upload/' . $creative_assets->image;
            $content_type = CFileHelper::getMimeType($creative_name);
            $data = file_get_contents($creative_name);

            $headerParameters = array(
                "Authorization: $auth",
                "Content-Type: $content_type",
                "Accept: application/json");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerParameters);
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);

            curl_close($ch);

            $response_obj = json_decode($response);

            if (!isset($response_obj->assetUrl)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'uploadAllCreative: Failed to upload creative to sitescout server,campaign id:' . $id . '  creative name:' . $creative_name));
            }

            //update asset_url in sitesouct into fd_creative table
            //$count = 1
            $count = $creative_assets->updateByPk(
                    $creative_assets->id, array('asset_url' => $response_obj->assetUrl));
            if ($count != 1) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'uploadAllCreative: Failed to update creative asset_url filed, campaign id:' . $id . '  creative name:' . $creative_name));
            }
        }
    }

    /**
     *   addAllCreative
     *
     * Add all Creative to a Campaign
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/creatives
     * HTTP Method: POST
     * parameter: Campaign ID
     */
    public function addAllCreative($id) {

        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);
        $creative_asset = $campaign->creatives;

        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id . '/creatives';

        foreach ($creative_asset as $creative_assets) {
            //only upload creative status is submitted
            if ($creative_assets->review_status_id == 8) {
                //build the creative body array
                $creative_array =
                        array(
                            "label" => 'API TESTING' . '-' . time() . '-' . rand(1, 1000),
                            "status" => Utility::GetStatusCode($creative_assets->status_id),
                            "width" => $creative_assets->width,
                            "height" => $creative_assets->height,
                            "type" => 'banner',
                            "assetUrl" => $creative_assets->asset_url,
                );


                //convert campaign array to json format
                $creative_json = json_encode($creative_array);

                //call sitescout API
                //return value : creative OBJECT
                $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, null, $headerParameters, $creative_json);

                if (isset($response->errorCode)) {
                    throw new EHttpClientException(
                    Yii::t('SiteScoutAPI', 'SiteScout addAllCreative API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
                }

                //update return value in sitesouct into fd_creative table
                //$count = 1
                $count = $creative_assets->updateByPk(
                        $creative_assets->id, array('sitescout_creative_id' => $response->creativeId,
                    'width' => $response->width,
                    'height' => $response->height,
                    'status_id' => Utility::GetStatusId($response->status),
                    'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));

                if ($count != 1) {
                    throw new EHttpClientException(
                    Yii::t('SiteScoutAPI', 'addAllCreative: Failed to update creative sitescout_creative_id, width and height filed, campaign id:' . $id . '  creative name:' . $creative_assets->image));
                }
            }
        }
    }

    /**
     *   addCampaignAllGeoRule
     *
     * Add all Geo Rule to a campaign
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/targeting/geo
     * HTTP Method: POST
     * parameter: Campaign ID
     */
    public function addAllGeoRule($id) {

        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);
        $creative_asset = $campaign->creatives;

        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id . '/targeting/geo';

        foreach ($creative_asset as $creative_assets) {
            //build the creative body array
            $creative_array =
                    array(
                        "label" => $creative_assets->label . '-' . time() . '-' . rand(1, 1000) . '---API TESTING',
                        "status" => self::STATUS_ONLINE,
                        "width" => $creative_assets->width,
                        "height" => $creative_assets->height,
                        "type" => 'banner',
                        "assetUrl" => $creative_assets->asset_url,
            );


            //convert campaign array to json format
            $creative_json = json_encode($creative_array);

            //call sitescout API
            //return value : creative OBJECT
            $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, null, $headerParameters, $creative_json);

            //update campaignID in sitesouct into fd_campaign table
            //$count = 1
            $count = $creative_assets->updateByPk(
                    $creative_assets->id, array('sitescout_creative_id' => $response->creativeId));
        }

        return $response;
    }

    /**
     *   setPagePosition
     *
     * Set Page Position Targeting
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/targeting/pagePositions
     * HTTP Method: POST
     * set all page to above_the_fold
     */
    public function setPagePosition($id) {

        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);

        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id . '/targeting/pagePositions';

        //build the creative body array
        $position_array =
                array('above_the_fold');

        //convert campaign array to json format
        $position_json = json_encode($position_array);

        //call sitescout API
        //return value :  OBJECT
        $response = $this->SiteScoutApiCall($path, EHttpClient::PUT, null, null, $headerParameters, $position_json);

        if (isset($response->errorCode)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'SiteScout setPagePosition API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
        }
        return $response;
    }

    /**
     *   addSiteRule
     *
     * Add a Site Rule
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/sources/siteRules
     * HTTP Method: POST
     * parameter: Campaign ID
     */
    public function addSiteRule($id) {

        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);

        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id . '/sources/siteRules';

        $creative_asset = $campaign->creatives;

        foreach ($creative_asset as $creative_assets) {

            //randomly fetch 1 site for the campaign
            //will be replaced with FD algorithm function
            $site_rule = SiteRule::model()->findAll();

            foreach ($site_rule as $site_rules) {
                $campaign_site_rule = new CampaignSiteRule;
                $campaign_site_rule->campaign_id = $campaign->id;
                $campaign_site_rule->site_rule_id = $site_rules->id;
                // $campaign_site_rule->status = 'online';
                $campaign_site_rule->bid = min(ROUND($campaign->default_bid/ (1+self::PROFIT_MARGIN),3), $site_rules->sitescout_ave_cpm);
                if ($campaign_site_rule->bid == 0)
                    $campaign_site_rule->bid = ROUND($campaign->default_bid/ (1+self::PROFIT_MARGIN),3);

                $campaign_site_rule_array =
                        array(
                            "siteRef" => $site_rules->sitescout_site_id,
                            "dimensions" => $creative_assets->width . "x" . $creative_assets->height,
                            "pagePosition" => "above_the_fold",
                            "bid" => $campaign_site_rule->bid,
                            "status" => "online",
                            "reviewStatus" => "eligible",
                );
                //convert campaign_site_rule array to json format
                $campaign_site_rule_json = json_encode($campaign_site_rule_array);
                //call sitescout API
                //return value :  OBJECT
                $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, null, $headerParameters, $campaign_site_rule_json);

                $returnValue = (array) $response;

                if (isset($response->ruleId)) {
                    $campaign_site_rule->save();
                    $count = $campaign_site_rule->updateByPk(
                            $campaign_site_rule->id, array('status' => $response->reviewStatus,
                        'sitescout_rule_id' => $response->ruleId,
                        'sitescout_rule_link' => $response->links[0]->href
                    ));
                }
            }
        }
        return $response;
    }

    /**
     * updateCampaign
     * the function only be called in campaign basic update
     * Update a Campaign  
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}
     * HTTP Method: PUT
     * parameter: Campaign ID
     */
    public function updateCampaign($id) {
        $path = self::SITESCOUT_BASE_URL . 'campaigns';
        $response = new stdClass;
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);

        if (!isset($campaign->id)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'updateCampaign: Failed to get the campaing record from database, campaign ID:' . $id));
        }
        //only udate campaign has been uploaded
        if (isset($campaign->sitescout_campaign_id)) {
            $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id;
            if ($campaign->fc_impressions == 0) {
//build the campaign body array
                $campaign_array =
                        array(
                            "campaignId" => $campaign->sitescout_campaign_id,
                            "name" => $campaign->name,
                            "status" => $campaign->status->code,
                            "defaultBid" => ROUND($campaign->default_bid / (1+self::PROFIT_MARGIN),3),
                            "clickUrl" => $campaign->click_url,
                            "budget" => array(
                                "amount" => ROUND($campaign->budget_amount / (1+self::PROFIT_MARGIN) ),
                                "type" => 'daily',
                                "evenDeliveryEnabled" => 'true'
                            ),
                            "flightDates" => array(
                                "from" => str_replace('-', '', substr($campaign->start_datetime, 0, 12)),
                                "to" => str_replace('-', '', substr($campaign->end_datetime, 0, 12))
                            )
                );
            } else {//build the campaign body array
                $campaign_array =
                        array(
                            "campaignId" => $campaign->sitescout_campaign_id,
                            "name" => $campaign->name,
                            "status" => $campaign->status->code,
                            "defaultBid" => ROUND( $campaign->default_bid  / (1+self::PROFIT_MARGIN), 3),
                            "clickUrl" => $campaign->click_url,
                            "budget" => array(
                                "amount" => ROUND($campaign->budget_amount / (1+self::PROFIT_MARGIN)),
                                "type" => 'daily',
                                "evenDeliveryEnabled" => 'true'
                            ),
                            "frequencyCapping" => array(
                                "impressions" => $campaign->fc_impressions,
                                "periodInHours" => $campaign->fc_period_in_hours,
                                "type" => 'campaign'
                            ),
                            "flightDates" => array(
                                "from" => str_replace('-', '', substr($campaign->start_datetime, 0, 12)),
                                "to" => str_replace('-', '', substr($campaign->end_datetime, 0, 12))
                            )
                );
            };

            //convert campaign array to json format
            $campaign_json = json_encode($campaign_array);

            //call sitescout API
            //return value : CAMPAIGN OBJECT
            $response = $this->SiteScoutApiCall($path, EHttpClient::PUT, null, null, $headerParameters, $campaign_json);

            if (isset($response->errorCode)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'SiteScout updateCampaign API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
            }

            //update status into fd_campaign table
            //$count = 1
            $count = $campaign->updateByPk(
                    $campaign->id, array('status_id' => Utility::GetStatusId($response->status),
                'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));

            if (!isset($count)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'updateCampaign: Failed to update campaign filed, campaign id:' . $id));
            }
        }

        return $response;
    }

    /**
     * updateCampaignOnlineStaus
     *  
     * Update a Campaign online/offline status
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}
     * HTTP Method: PUT
     * parameter: Campaign ID
     */
    public function updateCampaignOnlineStaus($id, $status_id) {
        $path = self::SITESCOUT_BASE_URL . 'campaigns';
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);
        $response = new stdClass;


        if (!isset($campaign->id)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'updateCampaignOnlineStaus: Failed to get the campaing record from database, please contact system administrator'));
        }

        //only udate campaign has been uploaded
        if (isset($campaign->sitescout_campaign_id)) {
            $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id;

//build the campaign body array
            $campaign_array =
                    array(
                        "campaignId" => $campaign->sitescout_campaign_id,
                        "name" => $campaign->name,
                        "status" => Utility::GetStatusCode($status_id),
                        "defaultBid" => $campaign->default_bid,
                        "clickUrl" => $campaign->click_url,
            );


            //convert campaign array to json format
            $campaign_json = json_encode($campaign_array);

            //call sitescout API
            //return value : CAMPAIGN OBJECT
            $response = $this->SiteScoutApiCall($path, EHttpClient::PUT, null, null, $headerParameters, $campaign_json);

            if (isset($response->errorCode)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'SiteScout updateCampaignOnlineStaus API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
            }

            //update status into fd_campaign table
            //$count = 1
            $count = $campaign->updateByPk(
                    $campaign->id, array('status_id' => Utility::GetStatusId($response->status),
                'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));

            if (!isset($count)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'updateCampaignOnlineStaus: Failed to update campaign filed, campaign id:' . $id));
            }
        }

        return $response;
    }

    /**
     *   removeCreative
     *
     * Remove a Creative from a Campaign
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/creatives/{creativeId}
     * HTTP Method: DELETE
     *  This function should be called before deleting record from fd_campaign_creative
     *  and DO NOT delete the physical record from database
     * parameter: Creative ID
     */
    public function removeCreative($id) {

        $headerParameters = array(
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $creative = Creative::model()->findByPk($id);
        $campaignId = $creative->campaigns[0]->sitescout_campaign_id;

        if (!isset($creative->id)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'removeCreative: Failed to get the creative and campaign record from database, Please contact system adminstrator. '));
        }


        //remove the create from sitescout and local database, if it is uploaded
        if (isset($creative->sitescout_creative_id)) {
            $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaignId . '/creatives/' . $creative->sitescout_creative_id;

            //Remove a Creative from a Campaign
            $response = $this->SiteScoutApiCall($path, EHttpClient::DELETE, null, null, $headerParameters);

            if (isset($response->errorCode)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'SiteScout removeCreative, Fiailed to remove a Creative from a Campaign : error- ' . $response->errorCode . '  -  ' . $response->message . '   Please contact system adminstrator.'));
            }
        }
    }

    /**
     *   removeCampaign
     *
     * Remove a Campaign  
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}
     * HTTP Method: PUT
     * parameter: Campaign ID
     * return stdClass object
     */
    public function removeCampaign($id) {
        $path = self::SITESCOUT_BASE_URL . 'campaigns';
        $response = new stdClass;
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findByPk($id);

        if (!isset($campaign->id)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'removeCampaign: Failed to get the campaing record from database, campaign ID:' . $id));
        }

        //campaign has been approved and uploaded to sitescout
        if (isset($campaign->sitescout_campaign_id)) {
            $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id;

            //build the campaign body array, set the status to ARCHIVED
            $campaign_array =
                    array(
                        "campaignId" => $campaign->sitescout_campaign_id,
                        "name" => $campaign->name,
                        "status" => self::STATUS_ARCHIVED,
                        "defaultBid" => $campaign->default_bid,
                        "clickUrl" => $campaign->click_url,
            );

            //convert campaign array to json format
            $campaign_json = json_encode($campaign_array);

            //call sitescout API
            //return value : CAMPAIGN OBJECT
            $response = $this->SiteScoutApiCall($path, EHttpClient::PUT, null, null, $headerParameters, $campaign_json);

            if (isset($response->errorCode)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'SiteScout removeCampaign API Failed : error- ' . $response->errorCode . '  -  ' . $response->message . '  Please contact system administrator.'));
            }
        }
        return $response;
    }

    /**
     *   uploadOneCreative
     * Upload A new Creative Asset
     * Path: /advertisers/{advertiserId}/creatives/assets
     * HTTP Method: POST
     * parameter: Creative ID
     */
    public function uploadOneCreative($id) {
        $path = self::SITESCOUT_BASE_URL . 'creatives/assets';
        $auth = $this->access_token['token_type'] . ' ' . $this->access_token['access_token'];

        //get the campaign and createive informaton from database
        $creative_asset = Creative::model()->findByPk($id);


        $creative_name = Yii::app()->basePath . '/../upload/' . $creative_asset->image;
        $content_type = CFileHelper::getMimeType($creative_name);
        $data = file_get_contents($creative_name);

        $headerParameters = array(
            "Authorization: $auth",
            "Content-Type: $content_type",
            "Accept: application/json");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerParameters);
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        curl_close($ch);

        $response_obj = json_decode($response);

        if (!isset($response_obj->assetUrl)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'uploadOneCreative: Failed to upload creative to sitescout server,creative id:' . $id . '  creative name:' . $creative_name));
        }

        //update asset_url in sitesouct into fd_creative table
        //$count = 1
        $count = $creative_asset->updateByPk(
                $creative_asset->id, array('asset_url' => $response_obj->assetUrl));
        if ($count != 1) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'uploadOneCreative: Failed to update creative asset_url filed, creative id:' . $id . '  creative name:' . $creative_name));
        }
        return $response;
    }

    /**
     *   addCreative
     *
     * Add ONE Creative to a Campaign
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/creatives
     * HTTP Method: POST
     * parameter: Creative ID
     */
    public function addOneCreative($id) {

        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $creative_asset = Creative::model()->findByPk($id);
        $campaign_id = $creative_asset->campaigns[0]->sitescout_campaign_id;

        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign_id . '/creatives';

        //build the creative body array
        $creative_array =
                array(
                    "label" => $creative_asset->label . '-' . time() . '-' . rand(1, 1000) . '---API TESTING',
                    "status" => Utility::GetStatusCode($creative_asset->status_id),
                    "width" => $creative_asset->width,
                    "height" => $creative_asset->height,
                    "type" => 'banner',
                    "assetUrl" => $creative_asset->asset_url,
        );


        //convert campaign array to json format
        $creative_json = json_encode($creative_array);

        //call sitescout API
        //return value : creative OBJECT
        $response = $this->SiteScoutApiCall($path, EHttpClient::POST, null, null, $headerParameters, $creative_json);

        if (isset($response->errorCode)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'SiteScout addOneCreative API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
        }

        //update return value in sitesouct into fd_creative table
        //$count = 1
        $count = $creative_asset->updateByPk(
                $creative_asset->id, array('sitescout_creative_id' => $response->creativeId,
            'width' => $response->width,
            'height' => $response->height,
            'status_id' => Utility::GetStatusId($response->status),
            'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));

        if ($count != 1) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'addOneCreative: Failed to update creative sitescout_creative_id, width and height filed, campaign id:' . $id . '  creative name:' . $creative_assets->image));
        }
        return $response;
    }

    /**
     *   fetchCampaignStatus
     *
     * Get Campaign Status 
     * update the status on fd_campaigns  
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}
     * HTTP Method: GET
     */
    public function fetchCampaignStatus() {
        $path = self::SITESCOUT_BASE_URL . 'campaigns/';
        $response = new stdClass;
        $return = 0;
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $campaign = Campaign::model()->findAll('review_status_id = :review_status_id and status_id != :status_id', array('review_status_id' => 5, ':status_id' => 3));

        foreach ($campaign as $campaigns) {

            if (isset($campaigns->sitescout_campaign_id)) {
                $api_path = $path . $campaigns->sitescout_campaign_id;
                //call sitescout API
                //return value : CAMPAIGN OBJECT
                $response = $this->SiteScoutApiCall($api_path, EHttpClient::GET, null, null, $headerParameters);

                if (isset($response->errorCode)) {

                    $message = $campaigns->id . '-' . $campaigns->sitescout_campaign_id . ' fetchCampaignStatus failed, error message : ' . $response->errorCode . '  -  ' . $response->message;
                    Yii::log($message, 'error');
                    $return = $return + 1;
                } else {

                    Campaign::model()->updateByPk($campaigns->id, array('status_id' => Utility::GetStatusId($response->status),
                        'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));
                }
            }
        };

        return $return;
    }

    /**
     *   fetchCreativeStatus
     *
     * Get Creative Status 
     * update the status on fd_creatives  
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/creatives/{creativeId}
     * HTTP Method: GET
     */
    public function fetchCreativeStatus() {
        $path = self::SITESCOUT_BASE_URL . 'campaigns/';
        $response = new stdClass;
        $return = 0;
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $creative = Creative::model()->findAll('review_status_id = :review_status_id and status_id != :status_id', array('review_status_id' => 5, ':status_id' => 3));

        foreach ($creative as $creatives) {

            if (isset($creatives->sitescout_creative_id)) {
                if ($creatives->campaigns[0]->status_id == 2) {
                    $api_path = $path . $creatives->campaigns[0]->sitescout_campaign_id . '/creatives/' . $creatives->sitescout_creative_id;
                    //call sitescout API
                    //return value : CREATIVE OBJECT
                    $response = $this->SiteScoutApiCall($api_path, EHttpClient::GET, null, null, $headerParameters);

                    if (isset($response->errorCode)) {

                        $message = $creatives->id . '-' . $creatives->sitescout_creative_id . ' fetchCreativeStatus failed, error message : ' . $response->errorCode . '  -  ' . $response->message;
                        Yii::log($message, 'error');
                        $return = $return + 1;
                    } else {

                        Creative::model()->updateByPk($creatives->id, array('status_id' => Utility::GetStatusId($response->status),
                            'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));
                    }
                }
            }
        };

        return $return;
    }

    /**
     *   retrieveStatCampSite
     *
     * Retrieve Statistics for a Campaign, per Site
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/stats/sites
     * HTTP Method: GET
     * Accept: application/json and text/csv
     */
    public function retrieveStatCampSite($campaignDate = NULL) {

        $return = 0;

        if (isset($campaignDate)) {
            $dateFrom = $campaignDate;
            $batch_type = 'DAILY';
        } else {
            $dateFrom = date("Ymd");
            $batch_type = 'HOURLY';
            //  $campaign = Campaign::model()->findAll(array('condition' => 'status_id=:status_id AND review_status_id=:review_status_id AND  sitescout_campaign_id IS NOT NULL', 'params' => array(':status_id' => 2, ':review_status_id' => 3)));
        };

        //get the eligible campaign records from database
        //for darily batch, we return all online campaign, and offline campaign but updated yesterday
        //for hourly batch, we return all online campaign, and offline campaign but updated yesterday

        $dateTo = $dateFrom;
        $campaign = Campaign::model()->findAll(array('condition' => '(status_id=:status_id AND review_status_id=:review_status_id) 
                                   OR (DATE_FORMAT(update_time,"%Y%m%d")=:update_time AND status_id!=:status_id)
                                   AND  sitescout_campaign_id IS NOT NULL',
            'params' => array(':status_id' => 2, ':review_status_id' => 3, ':update_time' => $dateFrom)));

        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        foreach ($campaign as $campaigns) {

            $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaigns->sitescout_campaign_id . '/stats';

            //get the all the campain stats as of today
            $stats_array =
                    array(
                        "dateFrom" => '20131001',
            );

            $response = $this->SiteScoutApiCall($path, EHttpClient::GET, $stats_array, null, $headerParameters, null);


            if (isset($response->errorCode)) {

                $message = '[BATCH TYPE:]' . $batch_type . '-[DATE FROM:]' . $dateFrom . '-[CAMPAIGN ID:]' . $campaigns->id . '-[SITESCOUT CAMPAIGN ID:]' . $campaigns->sitescout_campaign_id . ' retrieveStatCampSite get campaign stats failed, error message : ' . $response->errorCode . '  -  ' . $response->message;
                Yii::log($message, 'error');
                $return = $return + 1;
            } else {

                if (($response->entity->status = 'online') OR ($response->entity->status != 'online' AND $response->stats->totalSpend > 0)) {
                    $campaignStatsSummary = CampaignStatsSummary::model()->findByAttributes(array('campaign_id' => $campaigns->id));

                    if (isset($campaignStatsSummary->id)) {
                        $campaignStatsSummary->updateByPk(
                                $campaignStatsSummary->id, array(
                            'campaign_id' => $campaigns->id,
                            'sitescout_campaign_id' => $campaigns->sitescout_campaign_id,
                            'status_id' => Utility::GetStatusId($response->entity->status),
                                   'defaultBid' =>   8,
                           // 'defaultBid' => $response->entity->defaultBid,
                            'impressionsBid' => $response->stats->impressionsBid,
                            'impressionsWon' => $response->stats->impressionsWon  ,
                            'effectiveCPM' => $response->stats->effectiveCPM *(1+self::PROFIT_MARGIN),
                            'auctionsSpend' => $response->stats->auctionsSpend *(1+self::PROFIT_MARGIN),
                            'clicks' => $response->stats->clicks,
                            'clickthruRate' => $response->stats->clickthruRate,
                            'costPerClick' => $response->stats->costPerClick *(1+self::PROFIT_MARGIN),
                            'offerClicks' => $response->stats->offerClicks,
                            'offerClickthruRate' => $response->stats->offerClickthruRate,
                            'conversions' => $response->stats->conversions,
                            'conversionRate' => $response->stats->conversionRate,
                            'viewthruConversions' => $response->stats->viewthruConversions,
                            'profitPerClick' => $response->stats->profitPerClick,
                            'costPerAcquisition' => $response->stats->costPerAcquisition,
                            'revenuePerMille' => $response->stats->revenuePerMille,
                            'revenue' => $response->stats->revenue,
                            'totalEffectiveCPM' => $response->stats->totalEffectiveCPM*(1+self::PROFIT_MARGIN),
                            'totalSpend' => $response->stats->totalSpend*(1+self::PROFIT_MARGIN),
                            'dataEffectiveCPM' => $response->stats->dataEffectiveCPM ,
                            'dataSpend' => $response->stats->dataSpend,
                            'update_time' => date('Y-m-d H:i:s'),
                            'batch_type' => 'HOURLY'
                        ));
                    } else {
                        $campaignStatsSummary = new CampaignStatsSummary;
                        $campaignStatsSummary->campaign_id = $campaigns->id;
                        $campaignStatsSummary->sitescout_campaign_id = $campaigns->sitescout_campaign_id;
                        $campaignStatsSummary->status_id = Utility::GetStatusId($response->entity->status);
                       $campaignStatsSummary->defaultBid = 8;
                        // $campaignStatsSummary->defaultBid = $response->entity->defaultBid;
                        $campaignStatsSummary->impressionsBid = $response->stats->impressionsBid;
                        $campaignStatsSummary->impressionsWon = $response->stats->impressionsWon;
                        $campaignStatsSummary->effectiveCPM = $response->stats->effectiveCPM*(1+self::PROFIT_MARGIN);
                        $campaignStatsSummary->auctionsSpend = $response->stats->auctionsSpend*(1+self::PROFIT_MARGIN);
                        $campaignStatsSummary->clicks = $response->stats->clicks;
                        $campaignStatsSummary->clickthruRate = $response->stats->clickthruRate;
                        $campaignStatsSummary->costPerClick = $response->stats->costPerClick*(1+self::PROFIT_MARGIN);
                        $campaignStatsSummary->offerClicks = $response->stats->offerClicks;
                        $campaignStatsSummary->offerClickthruRate = $response->stats->offerClickthruRate;
                        $campaignStatsSummary->conversions = $response->stats->conversions;
                        $campaignStatsSummary->conversionRate = $response->stats->conversionRate;
                        $campaignStatsSummary->viewthruConversions = $response->stats->viewthruConversions;
                        $campaignStatsSummary->profitPerClick = $response->stats->profitPerClick;
                        $campaignStatsSummary->costPerAcquisition = $response->stats->costPerAcquisition;
                        $campaignStatsSummary->revenuePerMille = $response->stats->revenuePerMille;
                        $campaignStatsSummary->revenue = $response->stats->revenue;
                        $campaignStatsSummary->totalEffectiveCPM = $response->stats->totalEffectiveCPM*(1+self::PROFIT_MARGIN);
                        $campaignStatsSummary->totalSpend = $response->stats->totalSpend*(1+self::PROFIT_MARGIN);
                        $campaignStatsSummary->dataEffectiveCPM = $response->stats->dataEffectiveCPM ;
                        $campaignStatsSummary->dataSpend = $response->stats->dataSpend;
                        $campaignStatsSummary->update_time = date('Y-m-d H:i:s');
                        $campaignStatsSummary->batch_type = 'HOURLY';
                        $campaignStatsSummary->save();
                    };

                    //get TODAY  campain stats
                    $stats_array =
                            array(
                                "dateFrom" => $dateFrom,
                                "dateTo" => $dateFrom,
                    );
                    $response = $this->SiteScoutApiCall($path, EHttpClient::GET, $stats_array, null, $headerParameters, null);

                    if (isset($response->errorCode)) {
                        $message = '[BATCH TYPE:]' . $batch_type . '-[DATE FROM:]' . $dateFrom . '-[CAMPAIGN ID:]' . $campaigns->id . '-[SITESCOUT CAMPAIGN ID:]' . $campaigns->sitescout_campaign_id . ' retrieveStatCampSite get campaign daily stats failed, error message : ' . $response->errorCode . '  -  ' . $response->message;

                        Yii::log($message, 'error');
                        $return = $return + 1;
                    } else {

                        $campaignStatsDaily = CampaignStatsDaily::model()->findByAttributes(array('campaign_id' => $campaigns->id,
                            'campaign_date' => $dateFrom,
                        ));

                        if (isset($campaignStatsDaily->id)) {
                            $campaignStatsDaily->updateByPk(
                                    $campaignStatsDaily->id, array(
                                'campaign_id' => $campaigns->id,
                                'sitescout_campaign_id' => $campaigns->sitescout_campaign_id,
                                'status_id' => Utility::GetStatusId($response->entity->status),
                                'campaign_date' => $dateFrom,
                                         'defaultBid' => 8,
                              //  'defaultBid' => $response->entity->defaultBid,
                                'impressionsBid' => $response->stats->impressionsBid,
                                'impressionsWon' => $response->stats->impressionsWon,
                                'effectiveCPM' => $response->stats->effectiveCPM*(1+self::PROFIT_MARGIN),
                                'auctionsSpend' => $response->stats->auctionsSpend*(1+self::PROFIT_MARGIN),
                                'clicks' => $response->stats->clicks,
                                'clickthruRate' => $response->stats->clickthruRate,
                                'costPerClick' => $response->stats->costPerClick*(1+self::PROFIT_MARGIN),
                                'offerClicks' => $response->stats->offerClicks,
                                'offerClickthruRate' => $response->stats->offerClickthruRate,
                                'conversions' => $response->stats->conversions,
                                'conversionRate' => $response->stats->conversionRate,
                                'viewthruConversions' => $response->stats->viewthruConversions,
                                'profitPerClick' => $response->stats->profitPerClick,
                                'costPerAcquisition' => $response->stats->costPerAcquisition,
                                'revenuePerMille' => $response->stats->revenuePerMille,
                                'revenue' => $response->stats->revenue,
                                'totalEffectiveCPM' => $response->stats->totalEffectiveCPM*(1+self::PROFIT_MARGIN),
                                'totalSpend' => $response->stats->totalSpend*(1+self::PROFIT_MARGIN),
                                'dataEffectiveCPM' => $response->stats->dataEffectiveCPM,
                                'dataSpend' => $response->stats->dataSpend,
                                'update_time' => date('Y-m-d H:i:s'),
                                'batch_type' => $batch_type,
                            ));
                        } else {
                            $campaignStatsDaily = new CampaignStatsDaily;
                            $campaignStatsDaily->campaign_stats_summary_id = $campaignStatsSummary->id;
                            $campaignStatsDaily->campaign_id = $campaigns->id;
                            $campaignStatsDaily->sitescout_campaign_id = $campaigns->sitescout_campaign_id;
                            $campaignStatsDaily->status_id = Utility::GetStatusId($response->entity->status);
                            $campaignStatsDaily->campaign_date = $dateFrom;
                             $campaignStatsDaily->defaultBid = 8;
                          //  $campaignStatsDaily->defaultBid = $response->entity->defaultBid;
                            $campaignStatsDaily->impressionsBid = $response->stats->impressionsBid;
                            $campaignStatsDaily->impressionsWon = $response->stats->impressionsWon;
                            $campaignStatsDaily->effectiveCPM = $response->stats->effectiveCPM*(1+self::PROFIT_MARGIN);
                            $campaignStatsDaily->auctionsSpend = $response->stats->auctionsSpend*(1+self::PROFIT_MARGIN);
                            $campaignStatsDaily->clicks = $response->stats->clicks;
                            $campaignStatsDaily->clickthruRate = $response->stats->clickthruRate;
                            $campaignStatsDaily->costPerClick = $response->stats->costPerClick*(1+self::PROFIT_MARGIN);
                            $campaignStatsDaily->offerClicks = $response->stats->offerClicks;
                            $campaignStatsDaily->offerClickthruRate = $response->stats->offerClickthruRate;
                            $campaignStatsDaily->conversions = $response->stats->conversions;
                            $campaignStatsDaily->conversionRate = $response->stats->conversionRate;
                            $campaignStatsDaily->viewthruConversions = $response->stats->viewthruConversions;
                            $campaignStatsDaily->profitPerClick = $response->stats->profitPerClick;
                            $campaignStatsDaily->costPerAcquisition = $response->stats->costPerAcquisition;
                            $campaignStatsDaily->revenuePerMille = $response->stats->revenuePerMille;
                            $campaignStatsDaily->revenue = $response->stats->revenue;
                            $campaignStatsDaily->totalEffectiveCPM = $response->stats->totalEffectiveCPM*(1+self::PROFIT_MARGIN);
                            $campaignStatsDaily->totalSpend = $response->stats->totalSpend*(1+self::PROFIT_MARGIN);
                            $campaignStatsDaily->dataEffectiveCPM = $response->stats->dataEffectiveCPM;
                            $campaignStatsDaily->dataSpend = $response->stats->dataSpend;
                            $campaignStatsDaily->create_time = date('Y-m-d H:i:s');
                            $campaignStatsDaily->batch_type = $batch_type;
                            $campaignStatsDaily->save();
                        };


                        //get TODAY  campain stats per site
                        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaigns->sitescout_campaign_id . '/stats/sites';

                        $stats_array =
                                array(
                                    "dateFrom" => $dateFrom,
                                    "dateTo" => $dateFrom,
                                    "pageSize" => 100,
                        );

                        $response = $this->SiteScoutApiCall($path, EHttpClient::GET, $stats_array, null, $headerParameters, null);

                        if (isset($response->errorCode)) {

                            $message = '[BATCH TYPE:]' . $batch_type . '-[DATE FROM:]' . $dateFrom . '-[CAMPAIGN ID:]' . $campaigns->id . '-[SITESCOUT CAMPAIGN ID:]' . $campaigns->sitescout_campaign_id . ' retrieveStatCampSite get campaign site daily stats failed, error message : ' . $response->errorCode . '  -  ' . $response->message;
                            Yii::log($message, 'error');
                            $return = $return + 1;
                        } else {
                            foreach ($response->results as $results) {
                                if (($results->stats->totalSpend) > 0) {

                                    $campaignSiteStatsDaily = CampaignSiteStatsDaily::model()->findByAttributes(array('campaign_id' => $campaigns->id,
                                        'campaign_date' => $dateFrom,
                                        'ruleId' => $results->entity->ruleId,));

                                    if (isset($campaignSiteStatsDaily->id)) {
                                        $campaignSiteStatsDaily->updateByPk(
                                                $campaignSiteStatsDaily->id, array(
                                            'campaign_id' => $campaigns->id,
                                            'sitescout_campaign_id' => $campaigns->sitescout_campaign_id,
                                            'status_id' => Utility::GetStatusId($results->entity->status),
                                            'campaign_date' => $dateFrom,
                                            'review_status_id' => Utility::GetReviewStatusId($results->entity->reviewStatus),
                                            'ruleId' => $results->entity->ruleId,
                                            'siteRef' => $results->entity->siteRef,
                                            'domain' => $results->entity->domain,
                                            'campaign_stats_daily_id' => $campaignStatsDaily->id,
                                                    'defaultBid' => 8,
                                            //'defaultBid' => $results->entity->bid,
                                            'impressionsBid' => $results->stats->impressionsBid,
                                            'impressionsWon' => $results->stats->impressionsWon,
                                            'effectiveCPM' => $results->stats->effectiveCPM*(1+self::PROFIT_MARGIN),
                                            'auctionsSpend' => $results->stats->auctionsSpend*(1+self::PROFIT_MARGIN),
                                            'clicks' => $results->stats->clicks,
                                            'clickthruRate' => $results->stats->clickthruRate,
                                            'costPerClick' => $results->stats->costPerClick*(1+self::PROFIT_MARGIN),
                                            'offerClicks' => $results->stats->offerClicks,
                                            'offerClickthruRate' => $results->stats->offerClickthruRate,
                                            'conversions' => $results->stats->conversions,
                                            'conversionRate' => $results->stats->conversionRate,
                                            'viewthruConversions' => $results->stats->viewthruConversions,
                                            'profitPerClick' => $results->stats->profitPerClick,
                                            'costPerAcquisition' => $results->stats->costPerAcquisition,
                                            'revenuePerMille' => $results->stats->revenuePerMille,
                                            'revenue' => $results->stats->revenue,
                                            'totalEffectiveCPM' => $results->stats->totalEffectiveCPM*(1+self::PROFIT_MARGIN),
                                            'totalSpend' => $results->stats->totalSpend*(1+self::PROFIT_MARGIN),
                                            'dataEffectiveCPM' => $results->stats->dataEffectiveCPM,
                                            'dataSpend' => $results->stats->dataSpend,
                                            'update_time' => date('Y-m-d H:i:s'),
                                            'batch_type' => $batch_type,
                                        ));
                                    } else {

                                        $campaignSiteStatsDaily = new CampaignSiteStatsDaily;
                                        $campaignSiteStatsDaily->campaign_stats_daily_id = $campaignStatsDaily->id;
                                        $campaignSiteStatsDaily->campaign_id = $campaigns->id;
                                        $campaignSiteStatsDaily->sitescout_campaign_id = $campaigns->sitescout_campaign_id;
                                        $campaignSiteStatsDaily->status_id = Utility::GetStatusId($results->entity->status);
                                        $campaignSiteStatsDaily->review_status_id = Utility::GetReviewStatusId($results->entity->reviewStatus);
                                        $campaignSiteStatsDaily->campaign_date = $dateFrom;
                                        $campaignSiteStatsDaily->ruleId = $results->entity->ruleId;
                                        $campaignSiteStatsDaily->siteRef = $results->entity->siteRef;
                                        $campaignSiteStatsDaily->domain = $results->entity->domain;
                                        $campaignSiteStatsDaily->defaultBid = 8;
                                        //$campaignSiteStatsDaily->defaultBid = $results->entity->bid;
                                        $campaignSiteStatsDaily->impressionsBid = $results->stats->impressionsBid;
                                        $campaignSiteStatsDaily->impressionsWon = $results->stats->impressionsWon;
                                        $campaignSiteStatsDaily->effectiveCPM = $results->stats->effectiveCPM*(1+self::PROFIT_MARGIN);
                                        $campaignSiteStatsDaily->auctionsSpend = $results->stats->auctionsSpend*(1+self::PROFIT_MARGIN);
                                        $campaignSiteStatsDaily->clicks = $results->stats->clicks;
                                        $campaignSiteStatsDaily->clickthruRate = $results->stats->clickthruRate;
                                        $campaignSiteStatsDaily->costPerClick = $results->stats->costPerClick*(1+self::PROFIT_MARGIN);
                                        $campaignSiteStatsDaily->offerClicks = $results->stats->offerClicks;
                                        $campaignSiteStatsDaily->offerClickthruRate = $results->stats->offerClickthruRate;
                                        $campaignSiteStatsDaily->conversions = $results->stats->conversions;
                                        $campaignSiteStatsDaily->conversionRate = $results->stats->conversionRate;
                                        $campaignStatsDaily->viewthruConversions = $results->stats->viewthruConversions;
                                        $campaignStatsDaily->profitPerClick = $results->stats->profitPerClick;
                                        $campaignSiteStatsDaily->costPerAcquisition = $results->stats->costPerAcquisition;
                                        $campaignSiteStatsDaily->revenuePerMille = $results->stats->revenuePerMille;
                                        $campaignSiteStatsDaily->revenue = $results->stats->revenue;
                                        $campaignSiteStatsDaily->totalEffectiveCPM = $results->stats->totalEffectiveCPM*(1+self::PROFIT_MARGIN);
                                        $campaignSiteStatsDaily->totalSpend = $results->stats->totalSpend*(1+self::PROFIT_MARGIN);
                                        $campaignSiteStatsDaily->dataEffectiveCPM = $results->stats->dataEffectiveCPM;
                                        $campaignSiteStatsDaily->dataSpend = $results->stats->dataSpend;
                                        $campaignSiteStatsDaily->create_time = date('Y-m-d H:i:s');
                                        $campaignSiteStatsDaily->batch_type = $batch_type;
                                        $campaignSiteStatsDaily->save();
                                    };
                                }
                            }
                        }
                    }
                }
            }
        }
        return $return;
    }

}

?>