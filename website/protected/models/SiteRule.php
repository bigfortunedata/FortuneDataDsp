<?php

/**
 * This is the model class for table "{{site_rule}}".
 *
 * The followings are the available columns in table '{{site_rule}}':
 * @property integer $id
 * @property string $sitescout_site_id
 * @property string $domain
 * @property string $exchange
 * @property string $sitescout_category
 * @property string $sitescout_ave_cpm
 * @property string $sitescout_imps
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id
 * @property string $type
 * @property string $status
 */
class SiteRule extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SiteRule the static model class
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
		return '{{site_rule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sitescout_site_id, domain', 'required'),
			array('create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('sitescout_site_id, exchange, sitescout_category, sitescout_ave_cpm, sitescout_imps, type, status', 'length', 'max'=>45),
			array('domain', 'length', 'max'=>200),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sitescout_site_id, domain, exchange, sitescout_category, sitescout_ave_cpm, sitescout_imps, create_time, create_user_id, update_time, update_user_id, type, status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sitescout_site_id' => 'Sitescout Site',
			'domain' => 'Domain',
			'exchange' => 'Exchange',
			'sitescout_category' => 'Sitescout Category',
			'sitescout_ave_cpm' => 'Sitescout Ave Cpm',
			'sitescout_imps' => 'Sitescout Imps',
			'create_time' => 'Create Time',
			'create_user_id' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_id' => 'Update User',
			'type' => 'Type',
			'status' => 'Status',
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
		$criteria->compare('sitescout_site_id',$this->sitescout_site_id,true);
		$criteria->compare('domain',$this->domain,true);
		$criteria->compare('exchange',$this->exchange,true);
		$criteria->compare('sitescout_category',$this->sitescout_category,true);
		$criteria->compare('sitescout_ave_cpm',$this->sitescout_ave_cpm,true);
		$criteria->compare('sitescout_imps',$this->sitescout_imps,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}