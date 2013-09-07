<?php

/**
 * This is the model class for table "fd_creative".
 *
 * The followings are the available columns in table 'fd_creative':
 * @property integer $id
 * @property integer $user_id
 * @property string $label
 * @property integer $review_status_id
 * @property integer $width
 * @property integer $height
 * @property integer $type_id
 * @property string $vault_path
 * @property string $asset_url
 * @property string $code
 * @property integer $vendor_id
 * @property integer $expanding_direction_id
 * @property string $preview_url
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property integer $status_id
 * @property string $image
 *
 * The followings are the available model relations:
 * @property Campaign[] $fdCampaigns
 * @property CampaignStatus $status
 * @property CreativeExpandingDirection $expandingDirection
 * @property ReviewStatus $reviewStatus
 * @property CreativeType $type
 * @property User $user
 * @property User $createUser
 * @property User $updateUser
 * @property CreativeVendor $vendor
 */
class Creative extends FortuneDataActiveRecord
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

			// The review status id is set to the default
			$this->review_status_id = 5;
			$this->width = 0;
			$this->height = 0;
			$this->type_id = 1;
		}
		return parent::beforeSave();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Creative the static model class
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
		return 'fd_creative';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, review_status_id, width, height, type_id, vendor_id, expanding_direction_id, create_user_id, update_user_id, status_id', 'numerical', 'integerOnly'=>true),
			array('label, vault_path, asset_url, code, preview_url', 'length', 'max'=>45),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, label, review_status_id, width, height, type_id, vault_path, asset_url, code, vendor_id, expanding_direction_id, preview_url, create_time, create_user_id, update_time, update_user_id, status_id', 'safe', 'on'=>'search'),
            array('image', 'file','on'=>'create',
                'types'=> 'jpg,png',
                'maxSize' => 1024 * 1024 * 10, // 10MB
                'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.',                
            ),
            array('image', 'file','on'=>'update',
                'allowEmpty' => true,
                'types'=> 'jpg,png',
                'maxSize' => 1024 * 1024 * 10, // 10MB
                'tooLarge' => 'The file was larger than 10MB. Please upload a smaller file.',                
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
			'campaigns' => array(self::MANY_MANY, 'Campaign', 'fd_campaign_creative(creative_id, campaign_id)'),
			'status' => array(self::BELONGS_TO, 'CampaignStatus', 'status_id'),
			'expandingDirection' => array(self::BELONGS_TO, 'CreativeExpandingDirection', 'expanding_direction_id'),
			'reviewStatus' => array(self::BELONGS_TO, 'ReviewStatus', 'review_status_id'),
			'type' => array(self::BELONGS_TO, 'CreativeType', 'type_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user_id'),
			'updateUser' => array(self::BELONGS_TO, 'User', 'update_user_id'),
			'vendor' => array(self::BELONGS_TO, 'CreativeVendor', 'vendor_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'label' => 'Creative Name',
			'review_status_id' => 'Review Status',
			'width' => 'Width',
			'height' => 'Height',
			'type_id' => 'Creative Type',
			'vault_path' => 'Vault Path',
			'asset_url' => 'Asset Url',
			'code' => 'Code',
			'vendor_id' => 'Vendor',
			'expanding_direction_id' => 'Expanding Direction',
			'preview_url' => 'Preview Url',
			'create_time' => 'Create Datetime',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Datetime',
			'update_user_id' => 'Update User',
			'status_id' => 'Status',
			'image' => 'Creative Image',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('review_status_id',$this->review_status_id);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('vault_path',$this->vault_path,true);
		$criteria->compare('asset_url',$this->asset_url,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('vendor_id',$this->vendor_id);
		$criteria->compare('expanding_direction_id',$this->expanding_direction_id);
		$criteria->compare('preview_url',$this->preview_url,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('status_id',$this->status_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Retrieves a list of vendors.
	 * @return array an array of available vendors.
	 */
	public function getAllVendors()
	{
		$allVendors = array();
		$allVendorRecords = CreativeVendor::model()->findAll();
		foreach($allVendorRecords as $vendorRecord) {
			$allVendors[$vendorRecord->id] = $vendorRecord->description;
		}
		return $allVendors;
	}
	
	/**
	 * Retrieves a list of creative types.
	 * @return array an array of available creative types.
	 */
	public function getAllTypes()
	{
		$allTypes = array();
		$allTypeRecords = CreativeType::model()->findAll();
		foreach($allTypeRecords as $typeRecord) {
			$allTypes[$typeRecord->id] = $typeRecord->description;
		}
		return $allTypes;
	}

	/**
	 * Retrieves a list of Expanding Directions.
	 * @return array an array of available Expanding Directions.
	 */
	public function getAllExpandingDirections()
	{
		$allExpandingDirections = array();
		$allExpandingDirectionRecords = CreativeExpandingDirection::model()->findAll();
		foreach($allExpandingDirectionRecords as $allExpandingDirectionRecord) {
			$allExpandingDirections[$allExpandingDirectionRecord->id] = $allExpandingDirectionRecord->description;
		}
		return $allExpandingDirections;
	}
	
	public function getImageUrl()
	{
		return Yii::app()->request->baseUrl.'/upload/'.$this->image;	
	}
}