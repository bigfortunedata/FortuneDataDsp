<?php

/**
 * This is the model class for table "fd_fc_type".
 *
 * The followings are the available columns in table 'fd_fc_type':
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property string $create_datetime
 * @property integer $create_user_id
 * @property string $update_datetime
 * @property integer $update_user_id
 *
 * The followings are the available model relations:
 * @property Campaign[] $campaigns
 * @property User $createUser
 * @property User $updateUser
 */
class FcType extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FcType the static model class
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
		return 'fd_fc_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'required'),
			array('create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('code, description', 'length', 'max'=>45),
			array('create_datetime, update_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, description, create_datetime, create_user_id, update_datetime, update_user_id', 'safe', 'on'=>'search'),
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
			'campaigns' => array(self::HAS_MANY, 'Campaign', 'fc_type_id'),
			'createUser' => array(self::BELONGS_TO, 'User', 'create_user_id'),
			'updateUser' => array(self::BELONGS_TO, 'User', 'update_user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'description' => 'Description',
			'create_datetime' => 'Create Datetime',
			'create_user_id' => 'Create User',
			'update_datetime' => 'Update Datetime',
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('create_datetime',$this->create_datetime,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_datetime',$this->update_datetime,true);
		$criteria->compare('update_user_id',$this->update_user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}