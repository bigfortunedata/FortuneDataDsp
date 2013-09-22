<?php

/**
 * This is the model class for table "{{batch_job_log}}".
 *
 * The followings are the available columns in table '{{batch_job_log}}':
 * @property integer $id
 * @property string $start_datetime
 * @property string $end_datetime
 * @property string $batch_job_name
 * @property string $status
 * @property string $log
 */
class BatchJobLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BatchJobLog the static model class
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
		return '{{batch_job_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('batch_job_name', 'length', 'max'=>200),
			array('status', 'length', 'max'=>45),
			array('start_datetime, end_datetime, log', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, start_datetime, end_datetime, batch_job_name, status, log', 'safe', 'on'=>'search'),
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
			'start_datetime' => 'Start Datetime',
			'end_datetime' => 'End Datetime',
			'batch_job_name' => 'Batch Job Name',
			'status' => 'Status',
			'log' => 'Log',
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
		$criteria->compare('start_datetime',$this->start_datetime,true);
		$criteria->compare('end_datetime',$this->end_datetime,true);
		$criteria->compare('batch_job_name',$this->batch_job_name,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('log',$this->log,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}