<?php

/**
 * This is the model class for table "fd_user".
 *
 * The followings are the available columns in table 'fd_user':
 * @property integer $id
 * @property string $username
 * @property string $company_name
 * @property string $first_name
 * @property string $last_name
 * @property string $website
 * @property string $email
 * @property integer $phone
 * @property string $password
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property BudgetType[] $budgetTypes
 * @property BudgetType[] $budgetTypes1
 * @property Campaign[] $campaigns
 * @property Campaign[] $campaigns1
 * @property Campaign[] $campaigns2
 * @property CampaignReviewStatus[] $campaignReviewStatuses
 * @property CampaignReviewStatus[] $campaignReviewStatuses1
 * @property CampaignStatus[] $campaignStatuses
 * @property CampaignStatus[] $campaignStatuses1
 * @property Creative[] $creatives
 * @property Creative[] $creatives1
 * @property Creative[] $creatives2
 * @property CreativeExpandingDirection[] $creativeExpandingDirections
 * @property CreativeExpandingDirection[] $creativeExpandingDirections1
 * @property CreativeType[] $creativeTypes
 * @property CreativeType[] $creativeTypes1
 * @property CreativeVendor[] $creativeVendors
 * @property CreativeVendor[] $creativeVendors1
 * @property FcType[] $fcTypes
 * @property FcType[] $fcTypes1
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'fd_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, company_name, first_name, last_name, website, email, phone, password', 'required'),
			array('phone, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('username, company_name, first_name, last_name, website, email, password', 'length', 'max'=>50),
			array('last_login_time, create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, company_name, first_name, last_name, website, email, phone, password, last_login_time, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'budgetTypes' => array(self::HAS_MANY, 'BudgetType', 'create_user_id'),
			'budgetTypes1' => array(self::HAS_MANY, 'BudgetType', 'update_user_id'),
			'campaigns' => array(self::HAS_MANY, 'Campaign', 'create_user_id'),
			'campaigns1' => array(self::HAS_MANY, 'Campaign', 'update_user_id'),
			'campaigns2' => array(self::HAS_MANY, 'Campaign', 'user_id'),
			'campaignReviewStatuses' => array(self::HAS_MANY, 'CampaignReviewStatus', 'create_user_id'),
			'campaignReviewStatuses1' => array(self::HAS_MANY, 'CampaignReviewStatus', 'update_user_id'),
			'campaignStatuses' => array(self::HAS_MANY, 'CampaignStatus', 'create_user_id'),
			'campaignStatuses1' => array(self::HAS_MANY, 'CampaignStatus', 'update_user_id'),
			'creatives' => array(self::HAS_MANY, 'Creative', 'user_id'),
			'creatives1' => array(self::HAS_MANY, 'Creative', 'create_user_id'),
			'creatives2' => array(self::HAS_MANY, 'Creative', 'update_user_id'),
			'creativeExpandingDirections' => array(self::HAS_MANY, 'CreativeExpandingDirection', 'create_user_id'),
			'creativeExpandingDirections1' => array(self::HAS_MANY, 'CreativeExpandingDirection', 'update_user_id'),
			'creativeTypes' => array(self::HAS_MANY, 'CreativeType', 'create_user_id'),
			'creativeTypes1' => array(self::HAS_MANY, 'CreativeType', 'update_user_id'),
			'creativeVendors' => array(self::HAS_MANY, 'CreativeVendor', 'create_user_id'),
			'creativeVendors1' => array(self::HAS_MANY, 'CreativeVendor', 'update_user_id'),
			'fcTypes' => array(self::HAS_MANY, 'FcType', 'create_user_id'),
			'fcTypes1' => array(self::HAS_MANY, 'FcType', 'update_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'company_name' => 'Company Name',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'website' => 'Website',
			'email' => 'Email',
			'phone' => 'Phone',
			'password' => 'Password',
			'last_login_time' => 'Last Login Time',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}