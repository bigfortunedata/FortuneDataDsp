<?php

/**
 * This is the model class for table "{{campaign_site_rule}}".
 *
 * The followings are the available columns in table '{{campaign_site_rule}}':
 * @property integer $id
 * @property integer $campaign_id
 * @property integer $site_rule_id
 * @property integer $status_id
 * @property integer $sitescout_rule_id
 * @property double $bid
 * @property integer $create_user_id
 * @property string $create_datetime
 * @property string $update_datetime
 * @property integer $update_user_id
 * @property string $sitescout_rule_link
 * @property integer $review_status_id
 *
 * The followings are the available model relations:
 * @property CampaignStatus $status
 * @property ReviewStatus $reviewStatus
 * @property Campaign $campaign
 * @property SiteRule $siteRule
 */
class CampaignSiteRule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CampaignSiteRule the static model class
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
		return '{{campaign_site_rule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('campaign_id, site_rule_id, bid', 'required'),
			array('campaign_id, site_rule_id, status_id, sitescout_rule_id, create_user_id, update_user_id, review_status_id', 'numerical', 'integerOnly'=>true),
			array('bid', 'numerical'),
			array('sitescout_rule_link', 'length', 'max'=>300),
			array('create_datetime, update_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, campaign_id, site_rule_id, status_id, sitescout_rule_id, bid, create_user_id, create_datetime, update_datetime, update_user_id, sitescout_rule_link, review_status_id', 'safe', 'on'=>'search'),
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
			'status' => array(self::BELONGS_TO, 'CampaignStatus', 'status_id'),
			'reviewStatus' => array(self::BELONGS_TO, 'ReviewStatus', 'review_status_id'),
			'campaign' => array(self::BELONGS_TO, 'Campaign', 'campaign_id'),
			'siteRule' => array(self::BELONGS_TO, 'SiteRule', 'site_rule_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'campaign_id' => 'Campaign',
			'site_rule_id' => 'Site Rule',
			'status_id' => 'Status',
			'sitescout_rule_id' => 'Sitescout Rule',
			'bid' => 'Bid',
			'create_user_id' => 'Create User',
			'create_datetime' => 'Create Datetime',
			'update_datetime' => 'Update Datetime',
			'update_user_id' => 'Update User',
			'sitescout_rule_link' => 'Sitescout Rule Link',
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
		$criteria->compare('site_rule_id',$this->site_rule_id);
		$criteria->compare('status_id',$this->status_id);
		$criteria->compare('sitescout_rule_id',$this->sitescout_rule_id);
		$criteria->compare('bid',$this->bid);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('create_datetime',$this->create_datetime,true);
		$criteria->compare('update_datetime',$this->update_datetime,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('sitescout_rule_link',$this->sitescout_rule_link,true);
		$criteria->compare('review_status_id',$this->review_status_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}