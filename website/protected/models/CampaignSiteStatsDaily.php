<?php

/**
 * This is the model class for table "{{campaign_site_stats_daily}}".
 *
 * The followings are the available columns in table '{{campaign_site_stats_daily}}':
 * @property integer $id
 * @property integer $campaign_id
 * @property integer $sitescout_campaign_id
 * @property integer $status_id
 * @property double $defaultBid
 * @property integer $impressionsBid
 * @property integer $impressionsWon
 * @property double $effectiveCPM
 * @property double $auctionsSpend
 * @property integer $clicks
 * @property double $clickthruRate
 * @property double $costPerClick
 * @property integer $offerClicks
 * @property double $offerClickthruRate
 * @property double $conversions
 * @property double $conversionRate
 * @property integer $viewthruConversions
 * @property double $profitPerClick
 * @property double $costPerAcquisition
 * @property double $revenuePerMille
 * @property double $revenue
 * @property double $totalEffectiveCPM
 * @property double $totalSpend
 * @property double $dataEffectiveCPM
 * @property double $dataSpend
 * @property string $campaign_date
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property integer $campaign_stats_daily_id
 * @property integer $ruleId
 * @property string $siteRef
 * @property string $domain
 * @property double $bid
 * @property string $batch_type
 * @property integer $review_status_id
 *
 * The followings are the available model relations:
 * @property CampaignStatsDaily $campaignStatsDaily
 */
class CampaignSiteStatsDaily extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CampaignSiteStatsDaily the static model class
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
		return '{{campaign_site_stats_daily}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, sitescout_campaign_id, status_id, defaultBid, campaign_stats_daily_id', 'required'),
			array('campaign_id, sitescout_campaign_id, status_id, impressionsBid, impressionsWon, clicks, offerClicks, viewthruConversions, create_user_id, update_user_id, campaign_stats_daily_id, ruleId, review_status_id', 'numerical', 'integerOnly'=>true),
			array('defaultBid, effectiveCPM, auctionsSpend, clickthruRate, costPerClick, offerClickthruRate, conversions, conversionRate, profitPerClick, costPerAcquisition, revenuePerMille, revenue, totalEffectiveCPM, totalSpend, dataEffectiveCPM, dataSpend, bid', 'numerical'),
			array('siteRef, batch_type', 'length', 'max'=>45),
			array('domain', 'length', 'max'=>300),
			array('campaign_date, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, campaign_id, sitescout_campaign_id, status_id, defaultBid, impressionsBid, impressionsWon, effectiveCPM, auctionsSpend, clicks, clickthruRate, costPerClick, offerClicks, offerClickthruRate, conversions, conversionRate, viewthruConversions, profitPerClick, costPerAcquisition, revenuePerMille, revenue, totalEffectiveCPM, totalSpend, dataEffectiveCPM, dataSpend, campaign_date, create_time, create_user_id, update_time, update_user_id, campaign_stats_daily_id, ruleId, siteRef, domain, bid, batch_type, review_status_id', 'safe', 'on'=>'search'),
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
			'campaignStatsDaily' => array(self::BELONGS_TO, 'CampaignStatsDaily', 'campaign_stats_daily_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'campaign_id' => Yii::t('CampaignStats', 'Campaign Name'),
			'sitescout_campaign_id' => 'Sitescout Campaign',
			'status_id' => 'Status',
			'defaultBid' => Yii::t('CampaignStats', 'Default Bid'),
			'impressionsBid' => Yii::t('CampaignStats', 'Impressions Bid'),
			'impressionsWon' => Yii::t('CampaignStats', 'Impressions Won'),
			'effectiveCPM' => Yii::t('CampaignStats', 'effectiveCPM'),
			'auctionsSpend' => 'Auctions Spend',
			'clicks' => Yii::t('CampaignStats', 'Clicks'),
			'clickthruRate' => Yii::t('CampaignStats', 'Click Thru Rate'),
			'costPerClick' => Yii::t('CampaignStats', 'Cost Per Click'),
			'offerClicks' => 'Offer Clicks',
			'offerClickthruRate' => 'Offer Clickthru Rate',
			'conversions' => 'Conversions',
			'conversionRate' => 'Conversion Rate',
			'viewthruConversions' => 'Viewthru Conversions',
			'profitPerClick' => 'Profit Per Click',
			'costPerAcquisition' => 'Cost Per Acquisition',
			'revenuePerMille' => 'Revenue Per Mille',
			'revenue' => 'Revenue',
			'totalEffectiveCPM' => 'Total Effective Cpm',
			'totalSpend' => 'Total Spend',
			'dataEffectiveCPM' => 'Data Effective Cpm',
			'dataSpend' => 'Data Spend',
			'campaign_date' => 'Campaign Date',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
			'campaign_stats_daily_id' => 'Campaign Stats Daily',
			'ruleId' => 'Rule',
			'siteRef' => 'Site Ref',
			'domain' => Yii::t('CampaignStats', 'Domain'),
			'bid' => 'Bid',
			'batch_type' => 'Batch Type',
			'review_status_id' => 'Review Status',
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
		$criteria->compare('campaign_id',$this->campaign_id);
		$criteria->compare('sitescout_campaign_id',$this->sitescout_campaign_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('defaultBid',$this->defaultBid);
		$criteria->compare('impressionsBid',$this->impressionsBid);
		$criteria->compare('impressionsWon',$this->impressionsWon);
		$criteria->compare('effectiveCPM',$this->effectiveCPM);
		$criteria->compare('auctionsSpend',$this->auctionsSpend);
		$criteria->compare('clicks',$this->clicks);
		$criteria->compare('clickthruRate',$this->clickthruRate);
		$criteria->compare('costPerClick',$this->costPerClick);
		$criteria->compare('offerClicks',$this->offerClicks);
		$criteria->compare('offerClickthruRate',$this->offerClickthruRate);
		$criteria->compare('conversions',$this->conversions);
		$criteria->compare('conversionRate',$this->conversionRate);
		$criteria->compare('viewthruConversions',$this->viewthruConversions);
		$criteria->compare('profitPerClick',$this->profitPerClick);
		$criteria->compare('costPerAcquisition',$this->costPerAcquisition);
		$criteria->compare('revenuePerMille',$this->revenuePerMille);
		$criteria->compare('revenue',$this->revenue);
		$criteria->compare('totalEffectiveCPM',$this->totalEffectiveCPM);
		$criteria->compare('totalSpend',$this->totalSpend);
		$criteria->compare('dataEffectiveCPM',$this->dataEffectiveCPM);
		$criteria->compare('dataSpend',$this->dataSpend);
		$criteria->compare('campaign_date',$this->campaign_date,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('campaign_stats_daily_id',$this->campaign_stats_daily_id);
		$criteria->compare('ruleId',$this->ruleId);
		$criteria->compare('siteRef',$this->siteRef,true);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('bid',$this->bid);
		$criteria->compare('batch_type',$this->batch_type,true);
		$criteria->compare('review_status_id',$this->review_status_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}