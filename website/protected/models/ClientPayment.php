<?php

/**
 * This is the model class for table "{{client_payment}}".
 *
 * The followings are the available columns in table '{{client_payment}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $payment_type
 * @property double $amount
 * @property string $comment
 * @property string $create_datetime
 * @property integer $create_user_id
 * @property string $update_datetime
 * @property integer $update_user_id
 * @property string $currency
 * @property string $status
 * @property double $tax
 * @property double $total_amount
 *
 * The followings are the available model relations:
 * @property User $user
 */
class ClientPayment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ClientPayment the static model class
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
		return '{{client_payment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, amount', 'required'),
			array('user_id, create_user_id, update_user_id', 'numerical', 'integerOnly'=>true),
			array('amount, tax, total_amount', 'numerical'),
               		array('amount', 'numerical','min'=>10.00,'max'=>5000),
			array('payment_type, currency, status', 'length', 'max'=>45),
			array('comment, create_datetime, update_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, payment_type, amount, comment, create_datetime, create_user_id, update_datetime, update_user_id, currency, status, tax, total_amount', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
			'payment_type' => 'Payment Type',
			'amount' => 'Credit (US$):',
			'comment' => 'Comment',
			'create_datetime' => 'Create Datetime',
			'create_user_id' => 'Create User',
			'update_datetime' => 'Update Datetime',
			'update_user_id' => 'Update User',
			'currency' => 'Currency',
			'status' => 'Status',
			'tax' => 'Tax',
			'total_amount' => 'Total Amount',
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
		$criteria->compare('payment_type',$this->payment_type,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('create_datetime',$this->create_datetime,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_datetime',$this->update_datetime,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('tax',$this->tax);
		$criteria->compare('total_amount',$this->total_amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}