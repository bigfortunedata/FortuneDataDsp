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
class SiteScoutAPI {

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
    const SITESCOUT_API_URL = "https://api.sitescout.com/";
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

    /**
     * Constructor
     * @param array $config_array  Use it to override default values for variables
     *
     */
    public function __construct() {

        Yii::import('application.extensions.EHttpClient.*');
        Yii::import('application.extensions.EHttpClient.adapter.*');

        $this->session = Yii::app()->session;
        $this->access_token = $this->session['access_token'];

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
                        "defaultBid" => $campaign->default_bid,
                        "clickUrl" => $campaign->click_url,
                        "budget" => array(
                            "amount" => $campaign->budget_amount,
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
                        "defaultBid" => $campaign->default_bid,
                        "clickUrl" => $campaign->click_url,
                        "budget" => array(
                            "amount" => $campaign->budget_amount,
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
                            "label" => $creative_assets->id . '-' . time() . '-' . rand(1, 1000),
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
                    'label' => $response->label,
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
     *   allChildRegionsSelected
     *
     * Check if all the child regions (including itself) are selected.
     */
    private function allChildRegionsSelected($campaign, $node) {
        $regionSelected = false;
        foreach ($campaign->regions as $myRegion) {
            if ($node->id === $myRegion->id) {
                $regionSelected = true;
            }
        }
        if ($regionSelected == false) {
            return false;
        }

        foreach ($node->children as $childRegion) {
            if (!$this->allChildRegionsSelected($campaign, $childRegion)) {
                return false;
            }
        }

        return true;
    }

    /**
     *   addSelectedRegions
     *
     * Add all selection regions.
     * parameter: $campaign The campaign object
     *            $node The region node
     *            $selectedRegions The array of selected regions
     */
    private function addSelectedRegions($campaign, $node, &$selectedRegions) {
        if ($this->allChildRegionsSelected($campaign, $node)) {
            $selectedRegions[] = $node;
        } else {
            foreach ($node->children as $childRegion) {
                $this->addSelectedRegions($campaign, $childRegion, $selectedRegions);
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
        $selectedRegions = array();
        $root = Region::model()->findByPk(1);
        foreach ($root->children as $myRegion) {
            $this->addSelectedRegions($campaign, $myRegion, $selectedRegions);
        }

        $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaign->sitescout_campaign_id . '/targeting/geo';
        $i = 0;
        $allGeoRule = array(array());

        foreach ($selectedRegions as $myRegion) {
            if ($myRegion->type == 'COUNTRY') {
                $GeoRule = array(
                    "countryCode" => $myRegion->code,
                );
            } elseif ($myRegion->type == 'REGION') {
                $country = Region::model()->findByPk($myRegion->parent_id);
                $GeoRule = array(
                    "countryCode" => $country->code,
                    "region" => $myRegion->code,
                );
            } elseif ($myRegion->type == 'CITY') {
                $region = Region::model()->findByPk($myRegion->parent_id);
                $country = Region::model()->findByPk($region->parent_id);
                $GeoRule = array(
                    "countryCode" => $country->code,
                    "region" => $region->code,
                    "city" => $myRegion->code,
                );
            };

            $allGeoRule[$i] = $GeoRule;
            $i = $i + 1;
        };

        //convert campaign array to json format
        $allGeoRule_json = json_encode($allGeoRule);

        //call sitescout API
        $response = $this->SiteScoutApiCall($path, EHttpClient::PUT, null, null, $headerParameters, $allGeoRule_json);
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

            //randomly fetch 10 site for one campaign creative
            //will be replaced with FD algorithm function
            $site_rule = SiteRule::model()->findAll(array(
                'select' => '*, rand() as rand',
                'limit' => 10,
                'order' => 'rand',
                    )
            );
            $site_rule_count = 0;

            foreach ($site_rule as $site_rules) {
                $campaign_site_rule = new CampaignSiteRule;
                $campaign_site_rule->campaign_id = $campaign->id;
                $campaign_site_rule->site_rule_id = $site_rules->id;
                // $campaign_site_rule->status = 'online';
                $campaign_site_rule->bid = min($campaign->default_bid, $site_rules->sitescout_ave_cpm);
                if ($campaign_site_rule->bid == 0)
                    $campaign_site_rule->bid = $campaign->default_bid;

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

                // $returnValue = (array) $response;

                if (isset($response->ruleId)) {
                    $site_rule_count = $site_rule_count + 1;

                    $campaign_site_rule->save();

                    $count = $campaign_site_rule->updateByPk(
                            $campaign_site_rule->id, array('status' => $response->reviewStatus,
                        'sitescout_rule_id' => $response->ruleId,
                        'sitescout_rule_link' => $response->links[0]->href
                    ));
                }

                //for each creative, randomly assign 10 site
                if ($site_rule_count == 10)
                    break;
            }
        }
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
                            "defaultBid" => $campaign->default_bid,
                            "clickUrl" => $campaign->click_url,
                            "budget" => array(
                                "amount" => $campaign->budget_amount,
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
                            "defaultBid" => $campaign->default_bid,
                            "clickUrl" => $campaign->click_url,
                            "budget" => array(
                                "amount" => $campaign->budget_amount,
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

            if ($campaign->fc_impressions == 0) {
//build the campaign body array
                $campaign_array =
                        array(
                            "campaignId" => $campaign->sitescout_campaign_id,
                            "name" => $campaign->name,
                            "status" => Utility::GetStatusCode($status_id),
                            "defaultBid" => $campaign->default_bid,
                            "clickUrl" => $campaign->click_url,
                            "budget" => array(
                                "amount" => $campaign->budget_amount,
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
                            "status" => Utility::GetStatusCode($status_id),
                            "defaultBid" => $campaign->default_bid,
                            "clickUrl" => $campaign->click_url,
                            "budget" => array(
                                "amount" => $campaign->budget_amount,
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
     * updateCreativeOnlineStaus
     *  
     * Update a Creative online/offline status
     * Path: /advertisers/{advertiserId}/campaigns/{campaignId}/creatives/{creativeId}
     * HTTP Method: PUT
     * parameter: Creative ID, Status Id
     */
    public function updateCreativeOnlineStaus($id, $status_id) {
        $headerParameters = array(
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $this->access_token['token_type'] . ' ' . $this->access_token['access_token']);

        //get the campaign informaton from database
        $creative = Creative::model()->findByPk($id);
        $response = new stdClass;
        $campaignId = $creative->campaigns[0]->sitescout_campaign_id;

        if (!isset($creative->id)) {
            throw new EHttpClientException(
            Yii::t('SiteScoutAPI', 'updateCreativeOnlineStaus: Failed to get the creative and campaign record from database, Please contact system adminstrator. '));
        }

        //only udate creative has been uploaded
        if (isset($creative->sitescout_creative_id)) {
            $path = self::SITESCOUT_BASE_URL . 'campaigns/' . $campaignId . '/creatives/' . $creative->sitescout_creative_id;
            //build the creative body array
            $creative_array =
                    array(
                        "creativeId" => $creative->sitescout_creative_id,
                        "label" => $creative->label,
                        "status" => Utility::GetStatusCode($status_id),
                        "width" => $creative->width,
                        "height" => $creative->height,
                        "type" => 'banner',
                        "assetUrl" => $creative->asset_url,
            );
            //convert campaign array to json format
            $creative_json = json_encode($creative_array);

            //call sitescout API
            //return value : CAMPAIGN OBJECT
            $response = $this->SiteScoutApiCall($path, EHttpClient::PUT, null, null, $headerParameters, $creative_json);

            if (isset($response->errorCode)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'SiteScout updateCampaignOnlineStaus API Failed : error- ' . $response->errorCode . '  -  ' . $response->message));
            }

            //update status into fd_campaign table
            //$count = 1
            $count = $creative->updateByPk(
                    $creative->id, array('status_id' => Utility::GetStatusId($response->status),
                'review_status_id' => Utility::GetReviewStatusId($response->reviewStatus)));

            if (!isset($count)) {
                throw new EHttpClientException(
                Yii::t('SiteScoutAPI', 'updateCreativeOnlineStaus: Failed to update campaign filed, creative id:' . $id));
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
                    "label" => $creative_asset->id . '-' . time() . '-' . rand(1, 1000),
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
            'label' => $response->label,
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

}

?>