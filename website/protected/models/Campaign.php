<?php

/**
 * This is the model class for table "fd_campaign".
 *
 * The followings are the available columns in table 'fd_campaign':
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $status_id
 * @property double $default_bid
 * @property integer $review_status_id
 * @property string $click_url
 * @property double $budget_amount
 * @property integer $budget_type_id
 * @property string $budget_ede
 * @property integer $fc_impressions
 * @property integer $fc_period_in_hours
 * @property integer $fc_type_id
 * @property string $start_datetime
 * @property string $end_datetime
 * @property integer $conversion_audience
 * @property integer $click_audience
 * @property string $location
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $creative_image
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property User $updateUser
 * @property BudgetType $budgetType
 * @property FcType $fcType
 * @property ReviewStatus $reviewStatus
 * @property CampaignStatus $status
 * @property User $user
 * @property Creative[] $fdCreatives
 */
class Campaign extends FortuneDataActiveRecord {

    /**
     * @var The site scount api object
     */
    public $siteScoutApi;

    /**
     * Called before saving.
     */
    protected function beforeSave() {
        if (null !== Yii::app()->user) {
            $id = Yii::app()->user->id;
        } else {
            $id = 1;
        }

        $this->user_id = $id;

        if ($this->isNewRecord) {
            // The status id is set to the default
            $this->status_id = 2;
            $this->review_status_id = 8;
            $this->fc_period_in_hours = 24;
            $this->fc_type_id = 1;
            $this->budget_type_id = 1;
        }


        $curr = self::findByPk($this->id);
        //1. if user switching the online/offline, call API to change campaign status, the review status is returned from SiteScout
        //2. if user change an online campaign, call API to set the campaign status to offline, the review status is setup to SUBMITTED
        //admin will approve campaign and set it back to online/eligible

        if ($curr && isset($curr->sitescout_campaign_id) && ($curr->review_status_id != 8) && ($this->status_id != $curr->status_id)) {
            $this->siteScoutApi = new SiteScoutAPI();
            $response = $this->siteScoutApi->updateCampaignOnlineStaus($this->id, $this->status_id);
            $this->status_id = Utility::GetStatusId($response->status);
            $this->review_status_id = Utility::GetReviewStatusId($response->reviewStatus);
        } elseif ($curr && isset($curr->sitescout_campaign_id) && ($curr->status_id == 2) && ($this->review_status_id == 8)) {
            $this->siteScoutApi = new SiteScoutAPI();
            $end_date = null;
            if ($curr->end_datetime != $this->end_datetime)
            {$end_date = $this->end_datetime;}
            $response = $this->siteScoutApi->updateCampaignOnlineStaus($this->id, 1,$end_date);
        }
  
       
        return parent::beforeSave();
    }

    protected function afterFind() {
        $this->start_datetime = Yii::app()->dateFormatter->format('yyyy-MM-dd', CDateTimeParser::parse($this->start_datetime, 'yyyy-MM-dd hh:mm:ss'));
        $this->end_datetime = Yii::app()->dateFormatter->format('yyyy-MM-dd', CDateTimeParser::parse($this->end_datetime, 'yyyy-MM-dd hh:mm:ss'));
        return parent::afterFind();
    }

    protected function beforeDelete() {
        // Delete connections to creatives, site rules, etc.
        $this->removeAllSiteRules();
        $this->removeAllCreatives();
        return parent::beforeDelete();
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Campaign the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'fd_campaign';
    }

    /**
     * Set default values for the model
     */
    public function setDefaultValues() {
        $this->fc_impressions = 5;
        $this->fc_period_in_hours = 24;
        $this->budget_type_id = 1;
        $this->budget_ede = 1;
        $this->fc_type_id = 1;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, default_bid, click_url, budget_amount, start_datetime, end_datetime, category_id, location', 'required'),
            array('user_id, status_id, review_status_id, budget_type_id, fc_impressions, fc_period_in_hours, fc_type_id, conversion_audience, click_audience, create_user_id, update_user_id', 'numerical', 'integerOnly' => true),
            array('budget_amount', 'numerical', 'min' => 6.0),
            array('default_bid', 'numerical', 'min' => 0.3),
            array('name, click_url', 'length', 'max' => 250),
            array('budget_ede', 'length', 'max' => 10),
            array('create_time, update_time', 'safe'),
            array('name', 'unique', 'criteria' => array(
                    'condition' => 'user_id=:userId and status_id!=3',
                    'params' => array(':userId' => Yii::app()->user->id),
                )),
            array(
                'budget_amount',
                'compare',
                'compareAttribute' => 'default_bid',
                'operator' => '>',
                'allowEmpty' => false,
                'message' => '{attribute} must be greater than {compareAttribute}.'
            ),
            array('end_datetime', 'compare', 'compareValue' => date("Y-m-d"), 'operator' => '>',
                'message' => '{attribute} must be greater than current date.'),
            array(
                'end_datetime',
                'compare',
                'compareAttribute' => 'start_datetime',
                'operator' => '>',
                'allowEmpty' => false,
                'message' => '{attribute} must be greater than {compareAttribute}.'
            ),
            array('click_url', 'url', 'allowEmpty' => false, 'defaultScheme' => 'http'),
            array('id, name, user_id, status_id, default_bid, review_status_id, click_url, budget_amount, budget_type_id, budget_ede, fc_impressions, fc_period_in_hours, fc_type_id, start_datetime, end_datetime, conversion_audience, click_audience, create_time, create_user_id, update_time, update_user_id', 'safe', 'on' => 'search'),
            array('creative_image', 'FDImageSizeValidator'
            ),
            array('creative_image', 'file', 'on' => 'insert',
                'types' => 'jpg,png',
                'maxSize' => 120 * 1024, // 120KB
                'tooLarge' => 'The file was larger than 120KB. Please upload a smaller file.',
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'createUser' => array(self::BELONGS_TO, 'User', 'create_user_id'),
            'updateUser' => array(self::BELONGS_TO, 'User', 'update_user_id'),
            'budgetType' => array(self::BELONGS_TO, 'BudgetType', 'budget_type_id'),
            'fcType' => array(self::BELONGS_TO, 'FcType', 'fc_type_id'),
            'reviewStatus' => array(self::BELONGS_TO, 'ReviewStatus', 'review_status_id'),
            'status' => array(self::BELONGS_TO, 'CampaignStatus', 'status_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'category' => array(self::BELONGS_TO, 'CampaignCategory', 'category_id'),
            'creatives' => array(self::MANY_MANY, 'Creative', 'fd_campaign_creative(campaign_id, creative_id)'),
            'creativeCount' => array(self::STAT, 'Creative', 'fd_campaign_creative(campaign_id, creative_id)'),
            'regions' => array(self::MANY_MANY, 'Region', 'fd_campaign_region(campaign_id, region_id)'),
            'siteRules' => array(self::MANY_MANY, 'CampaignSiteRule', 'fd_campaign_site_rule(campaign_id, site_rule_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => Yii::t('campaign','Campaign Name'),
            'user_id' => Yii::t('campaign','User'),
            'status_id' => Yii::t('campaign','Status'),
            'default_bid' => Yii::t('campaign','Default Bid'),
            'review_status_id' => Yii::t('campaign','Review Status'),
            'click_url' => Yii::t('campaign','Click Url'),
            'budget_amount' => Yii::t('campaign','Daily Budget ($)'),
            'budget_type_id' => Yii::t('campaign','Budget Type'),
            'budget_ede' => Yii::t('campaign','Budget Even Delivery'),
            'fc_impressions' => Yii::t('campaign','Frequency Capping (Daily)'),
            'fc_period_in_hours' => Yii::t('campaign','Fc Period In Hours'),
            'fc_type_id' => Yii::t('campaign','Fc Type'),
            'start_datetime' => Yii::t('campaign','Start Datetime'),
            'end_datetime' => Yii::t('campaign','End Datetime'),
            'conversion_audience' => Yii::t('campaign','Conversion Audience'),
            'click_audience' => Yii::t('campaign','Click Audience'),
            'location' => Yii::t('campaign','Target Locations'),
            'create_time' => Yii::t('campaign','Create Time'),
            'create_user_id' => Yii::t('campaign','Create User'),
            'update_time' => Yii::t('campaign','Update Time'),
            'update_user_id' => Yii::t('campaign','Update User'),
            'creative_image' => Yii::t('campaign','Creative Image'),
            'category_id' => Yii::t('campaign','Campaign Category'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status_id', $this->status_id);
        $criteria->compare('default_bid', $this->default_bid);
        $criteria->compare('review_status_id', $this->review_status_id);
        $criteria->compare('click_url', $this->click_url, true);
        $criteria->compare('budget_amount', $this->budget_amount);
        $criteria->compare('budget_type_id', $this->budget_type_id);
        $criteria->compare('budget_ede', $this->budget_ede, true);
        $criteria->compare('fc_impressions', $this->fc_impressions);
        $criteria->compare('fc_period_in_hours', $this->fc_period_in_hours);
        $criteria->compare('fc_type_id', $this->fc_type_id);
        $criteria->compare('start_datetime', $this->start_datetime, true);
        $criteria->compare('end_datetime', $this->end_datetime, true);
        $criteria->compare('conversion_audience', $this->conversion_audience);
        $criteria->compare('click_audience', $this->click_audience);
        $criteria->compare('location', $this->location);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('create_user_id', $this->create_user_id);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('update_user_id', $this->update_user_id);
        $criteria->compare('category_id', $this->category_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Add a creative.
     * @param creativeId the id of the creative.
     */
    public function addCreative($creativeId) {
        $command = Yii::app()->db->createCommand();
        $command->insert('fd_campaign_creative', array(
            'creative_id' => $creativeId,
            'campaign_id' => $this->id,
        ));
    }

    /**
     * Delete a creative.
     * @param creativeId the id of the creative.
     */
    public function removeCreative($creativeId) {
        $this->siteScoutApi = new SiteScoutAPI();
        $this->siteScoutApi->removeCreative($creativeId);

        $command = Yii::app()->db->createCommand();
        $command->delete('fd_campaign_creative', 'campaign_id=:campaignId AND creative_id=:creativeId', array(':campaignId' => $this->id, ':creativeId' => $creativeId));
    }

    /**
     * Delete the campaign.
     */
    public function removeCampaign() {
        $this->siteScoutApi = new SiteScoutAPI();
        $this->siteScoutApi->removeCampaign($this->id);

        $command = Yii::app()->db->createCommand();
        $command->update('fd_campaign', array(
            'status_id' => 3,
                ), 'id=:campaignId', array(':campaignId' => $this->id));
    }

    /**
     * Delete a site rule.
     * @param siteRuleId the id of the site rule.
     */
    public function removeSiteRule($siteRuleId) {
        $command = Yii::app()->db->createCommand();
        $command->delete('fd_campaign_site_rule', 'campaign_id=:campaignId AND site_rule_id=:siteRuleId', array(':campaignId' => $this->id, ':siteRuleId' => $siteRuleId));
    }

    /**
     * Delete all creatives.
     */
    public function removeAllCreatives() {
        $command = Yii::app()->db->createCommand();
        $command->delete('fd_campaign_creative', 'campaign_id=:campaignId', array(':campaignId' => $this->id));
    }

    /**
     * Delete all site rules.
     */
    public function removeAllSiteRules() {
        $command = Yii::app()->db->createCommand();
        $command->delete('fd_campaign_site_rule', 'campaign_id=:campaignId', array(':campaignId' => $this->id));
    }

    /**
     * Retrieves a list of budget types.
     * @return array an array of available budget types.
     */
    public function getBudgetTypes() {
        $budgetTypes = array();
        $budgetTypeRecords = BudgetType::model()->findAll();
        foreach ($budgetTypeRecords as $budgetTypeRecord) {
            $budgetTypes[$budgetTypeRecord->id] = $budgetTypeRecord->description;
        }
        return $budgetTypes;
    }

    /**
     * Retrieves Yes/No options
     * @return array an array of available options.
     */
    public function getYesNoOptions() {
        $options = array(1 => 'Yes', 2 => 'No');
        return $options;
    }

    /**
     * Retrieves a list of Fc types.
     * @return array an array of available Fc types.
     */
    public function getFcTypes() {
        $fcTypes = array();
        $fcTypeRecords = FcType::model()->findAll();
        foreach ($fcTypeRecords as $fcTypeRecord) {
            $fcTypes[$fcTypeRecord->id] = $fcTypeRecord->description;
        }
        return $fcTypes;
    }

    /**
     * Retrieves a list of categories.
     * @return array an array of available categories.
     */
    public function getCategories() {
        $categories = array();
        $categoryRecords = CampaignCategory::model()->findAll();
        foreach ($categoryRecords as $categoryRecord) {
            $categories[$categoryRecord->id] = $categoryRecord->description;
        }
        return $categories;
    }

    /**
     * Save the regions
     * @return the new regions
     */
    public function saveRegions($newRegions) {
        $allRegions = Region::model()->findAll();
        $command = Yii::app()->db->createCommand();
        $command->delete('fd_campaign_region', 'campaign_id=:campaignId', array(':campaignId' => $this->id));
        foreach ($allRegions as $region) {
            if (isset($newRegions['region_' . $region->id])) {
                $command->insert('fd_campaign_region', array(
                    'region_id' => $region->id,
                    'campaign_id' => $this->id,
                ));
            }
        }
    }

    /**
     * Get the selected regions
     * @return the selected regions
     */
    public function getSelectedRegions($selectedRegions) {
        $allRegions = Region::model()->findAll();
        $selectedRegionsArray = array();
        foreach ($allRegions as $region) {
            if (isset($selectedRegions['region_' . $region->id])) {
                $selectedRegionsArray[] = $region->id;
            }
        }
        return implode(',', $selectedRegionsArray);
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
     *   getAllSelectedRegions
     *
     *   Get all the selectedregions
     */
    public function getAllSelectedRegions() {
        $selectedRegions = array();
        $root = Region::model()->findByPk(1);
        foreach ($root->children as $myRegion) {
            $this->addSelectedRegions($this, $myRegion, $selectedRegions);
        }
        return $selectedRegions;
    }

    /**
     * Whether the campaign is online
     */
    public function getIsOnline() {
        if ($this->status_id == 2)
            return true;
        else
            return false;
    }

    /**
     * Whether the campaign is offline
     */
    public function getIsOffline() {
        if ($this->status_id == 2)
            return false;
        else
            return true;
    }

    /**
     * Retrieves the root region.
     * @return the root region
     */
    public function buildRegionsTree($node) {
        $regionsTree = "";
        $rootNode = 0;
        if ($node == null) {
            $node = Region::model()->findByPk(1);
            $regionsTree .= '<ul id="tree2">';
            $rootNode = 1;
        }

        $checked = "";
        if (!$this->isNewRecord) {
            // For update, select the current regions. (NOT an efficient way. Better use a hashmap.)
            foreach ($this->regions as $myRegion) {
                if ($node->id === $myRegion->id) {
                    $checked = "checked";
                    break;
                }
            }
        } else {
            if ($node->selected == 1)
                $checked = 'checked';
        }

        if (!$rootNode) {
            $regionsTree .= '<li><input name="Campaign[region_' . $node->id . ']" id="campaign_region_' . $node->id . '" type="checkbox"' . $checked . '>' . $node->name . "\n";
        }
        if (count($node->children) > 0) {
            $regionsTree .= "<ul>\n";
            for ($i = 0; $i < count($node->children); $i++) {
                $child = $node->children[$i];
                $regionsTree .= $this->buildRegionsTree($child);
            }
            $regionsTree .= "</ul>\n";
        }

        if ($node->id == 1) {
            $regionsTree .= "</ul>\n";
        }
        return $regionsTree;
    }

}