<?php

/**
 * This is the model class for table "air_command".
 *
 * The followings are the available columns in table 'air_command':
 * @property integer $id
 * @property integer $length
 * @property integer $headSize
 * @property string $pulseWidth
 * @property integer $brand
 * @property string $mark
 * @property string $name
 * @property string $descript
 */
class AirCommand extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'air_command';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('length, headSize, brand, mark, name, descript', 'required'),
			array('length, headSize, brand', 'numerical', 'integerOnly'=>true),
			array('pulseWidth', 'length', 'max'=>16),
			array('mark', 'length', 'max'=>2048),
			array('name', 'length', 'max'=>50),
			array('descript', 'length', 'max'=>120),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, length, headSize, pulseWidth, brand, mark, name, descript', 'safe', 'on'=>'search'),
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
			'length' => '长度',
			'headSize' => '头大小',
			'pulseWidth' => '脉宽',
			'brand' => '所属品牌',
			'mark' => '空调编码转换后的标识符字符串',
			'name' => '名称',
			'descript' => '描述',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('length',$this->length);
		$criteria->compare('headSize',$this->headSize);
		$criteria->compare('pulseWidth',$this->pulseWidth,true);
		$criteria->compare('brand',$this->brand);
		$criteria->compare('mark',$this->mark,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('descript',$this->descript,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AirCommand the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
