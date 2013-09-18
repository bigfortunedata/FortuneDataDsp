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
class Campaign extends FortuneDataActiveRecord
{
	/**
	 * Called before saving.
	 */
	protected function beforeSave()
	{
		if (null !== Yii::app()->user) {
			$id = Yii::app()->user->id;
		}
		else {
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
		return parent::beforeSave();
	}
	
	protected function afterFind(){
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
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fd_campaign';
	}

	/**
	 * Set default values for the model
	 */
	public function setDefaultValues()
	{
		$this->fc_impressions = 0;
		$this->fc_period_in_hours = 24;
		$this->budget_type_id = 1;
		$this->budget_ede = 1;
		$this->fc_type_id = 1;
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, default_bid, click_url, budget_amount, start_datetime, end_datetime, category_id', 'required'),
			array('user_id, status_id, review_status_id, budget_type_id, fc_impressions, fc_period_in_hours, fc_type_id, conversion_audience, click_audience, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('budget_amount', 'numerical', 'min'=>1.0),
			array('default_bid', 'numerical', 'min'=>0.1),
			array('location', 'length', 'max'=>300),
			array('name, click_url', 'length', 'max'=>45),
			array('budget_ede', 'length', 'max'=>10),
			array('create_time, update_time', 'safe'),
			array('name', 'unique', 'criteria'=>array(
				'condition'=>'user_id=:userId',
				'params'=>array(':userId'=>Yii::app()->user->id),
			)),
			array(
			  'budget_amount',
			  'compare',
			  'compareAttribute'=>'default_bid',
			  'operator'=>'>', 
			  'allowEmpty'=>false , 
			  'message'=>'{attribute} must be greater than {compareAttribute}.'
			),
			array(
			  'end_datetime',
			  'compare',
			  'compareAttribute'=>'start_datetime',
			  'operator'=>'>', 
			  'allowEmpty'=>false , 
			  'message'=>'{attribute} must be greater than {compareAttribute}.'
			),
			array('click_url', 'url', 'allowEmpty'=>false, 'defaultScheme' => 'http'),
			array('id, name, user_id, status_id, default_bid, review_status_id, click_url, budget_amount, budget_type_id, budget_ede, fc_impressions, fc_period_in_hours, fc_type_id, start_datetime, end_datetime, conversion_audience, click_audience, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
            array('creative_image', 'FDImageSizeValidator'             
            ),
			array('creative_image', 'file','on'=>'insert',
                'types'=> 'jpg,png',
                'maxSize' => 120 * 1024, // 120KB
                'tooLarge' => 'The file was larger than 120KB. Please upload a smaller file.',
            ),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
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
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Campaign Name',
			'user_id' => 'User',
			'status_id' => 'Status',
			'default_bid' => 'Default Bid',
			'review_status_id' => 'Review Status',
			'click_url' => 'Click Url',
			'budget_amount' => 'Daily Budget ($)',
			'budget_type_id' => 'Budget Type',
			'budget_ede' => 'Budget Even Delivery',
			'fc_impressions' => 'Frequency Capping (Daily)',
			'fc_period_in_hours' => 'Fc Period In Hours',
			'fc_type_id' => 'Fc Type',
			'start_datetime' => 'Start Datetime',
			'end_datetime' => 'End Datetime',
			'conversion_audience' => 'Conversion Audience',
			'click_audience' => 'Click Audience',
			'location' => 'Target Locations',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
			'creative_image' => 'Creative Image',
			'category_id' => 'Campaign Category',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('default_bid',$this->default_bid);
		$criteria->compare('review_status_id',$this->review_status_id);
		$criteria->compare('click_url',$this->click_url,true);
		$criteria->compare('budget_amount',$this->budget_amount);
		$criteria->compare('budget_type_id',$this->budget_type_id);
		$criteria->compare('budget_ede',$this->budget_ede,true);
		$criteria->compare('fc_impressions',$this->fc_impressions);
		$criteria->compare('fc_period_in_hours',$this->fc_period_in_hours);
		$criteria->compare('fc_type_id',$this->fc_type_id);
		$criteria->compare('start_datetime',$this->start_datetime,true);
		$criteria->compare('end_datetime',$this->end_datetime,true);
		$criteria->compare('conversion_audience',$this->conversion_audience);
		$criteria->compare('click_audience',$this->click_audience);
		$criteria->compare('location',$this->location);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('category_id',$this->category_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Add a creative.
	 * @param creativeId the id of the creative.
	 */
	public function addCreative($creativeId)
	{
		$command = Yii::app()->db->createCommand();
		$command->insert('fd_campaign_creative', array(
			'creative_id'=>$creativeId,
			'campaign_id'=>$this->id,
		));
	}

	/**
	 * Delete a creative.
	 * @param creativeId the id of the creative.
	 */	
	public function removeCreative($creativeId)
	{
		$command = Yii::app()->db->createCommand();
		$command->delete('fd_campaign_creative', 'campaign_id=:campaignId AND creative_id=:creativeId',
		array(':campaignId'=>$this->id, ':creativeId'=>$creativeId));
	}
	
	/**
	 * Delete the campaign.
	 */	
	public function removeCampaign()
	{
		$command = Yii::app()->db->createCommand();
		$command->update('fd_campaign', array(
		    'status_id'=>3,
		), 'id=:campaignId', array(':campaignId'=>$this->id));
	}
	
	/**
	 * Delete a site rule.
	 * @param siteRuleId the id of the site rule.
	 */	
	public function removeSiteRule($siteRuleId)
	{
		$command = Yii::app()->db->createCommand();
		$command->delete('fd_campaign_site_rule', 'campaign_id=:campaignId AND site_rule_id=:siteRuleId',
		array(':campaignId'=>$this->id, ':siteRuleId'=>$siteRuleId));
	}
	
	/**
	 * Delete all creatives.
	 */	
	public function removeAllCreatives()
	{
		$command = Yii::app()->db->createCommand();
		$command->delete('fd_campaign_creative', 'campaign_id=:campaignId', array(':campaignId'=>$this->id));
	}
	
	/**
	 * Delete all site rules.
	 */	
	public function removeAllSiteRules()
	{
		$command = Yii::app()->db->createCommand();
		$command->delete('fd_campaign_site_rule', 'campaign_id=:campaignId',
		array(':campaignId'=>$this->id));
	}
	
	/**
	 * Retrieves a list of budget types.
	 * @return array an array of available budget types.
	 */
	public function getBudgetTypes()
	{
		$budgetTypes = array();
		$budgetTypeRecords = BudgetType::model()->findAll();
		foreach($budgetTypeRecords as $budgetTypeRecord) {
			$budgetTypes[$budgetTypeRecord->id] = $budgetTypeRecord->description;
		}
		return $budgetTypes;
	}

	/**
	 * Retrieves Yes/No options
	 * @return array an array of available options.
	 */
	public function getYesNoOptions()
	{
		$options = array(1 => 'Yes', 2 =>'No');
		return $options;
	}
	
	/**
	 * Retrieves a list of Fc types.
	 * @return array an array of available Fc types.
	 */
	public function getFcTypes()
	{
		$fcTypes = array();
		$fcTypeRecords = FcType::model()->findAll();
		foreach($fcTypeRecords as $fcTypeRecord) {
			$fcTypes[$fcTypeRecord->id] = $fcTypeRecord->description;
		}
		return $fcTypes;
	}
	
	/**
	 * Retrieves a list of categories.
	 * @return array an array of available categories.
	 */
	public function getCategories()
	{
		$categories = array();
		$categoryRecords = CampaignCategory::model()->findAll();
		foreach($categoryRecords as $categoryRecord) {
			$categories[$categoryRecord->id] = $categoryRecord->description;
		}
		return $categories;
	}
	
	/**
	 * Save the regions
	 * @return the new regions
	 */
	public function saveRegions($newRegions)
	{
		$allRegions = Region::model()->findAll();
		$command = Yii::app()->db->createCommand();
		$command->delete('fd_campaign_region', 'campaign_id=:campaignId',
		array(':campaignId'=>$this->id));
		foreach($allRegions as $region) {
		    if (isset($newRegions['region_'.$region->id])) {
				$command->insert('fd_campaign_region', array(
					'region_id'=>$region->id,
					'campaign_id'=>$this->id,
				));
		    }
		}
		
	}
	
	/**
	 * Whether the campaign is online
	 */
	public function getIsOnline()
	{
		if ($this->status_id == 2) return "online";
		else return false;
	}
	
	/**
	 * Retrieves the root region.
	 * @return the root region
	 */
	public function buildRegionsTree($node)
	{
		$regionsTree = "";
		if ($node == null) {
			$node = Region::model()->findByPk(1);
			$regionsTree .= '<ul id="tree2">';
		}
		
		$checked = "";
		if (!$this->isNewRecord) {
			// For update, select the current regions. (NOT an efficient way. Better use a hashmap.)
			foreach($this->regions as $myRegion) {
			    if ($node->id === $myRegion->id) {
			        $checked = "checked";
			        break;
			    }
			}
		}
		else {
			$checked = 'checked';
		}
		
		$regionsTree .= '<li><input name="Campaign[region_' . $node->id . ']" id="campaign_region_' . $node->id . '" type="checkbox"' . $checked .'>' . $node->name . "\n";
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