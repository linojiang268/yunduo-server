<?php

/**
 * This is the model class for table "air_mark".
 *
 * The followings are the available columns in table 'air_mark':
 * @property integer $id
 * @property integer $commandId
 * @property integer $tagId
 * @property string $mark
 * @property string $name
 * @property string $descript
 */
class AirMark extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'air_mark';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commandId, tagId, mark, name, descript', 'required'),
			array('commandId, tagId', 'numerical', 'integerOnly'=>true),
			array('mark', 'length', 'max'=>2048),
			array('name', 'length', 'max'=>50),
			array('descript', 'length', 'max'=>120),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, commandId, tagId, mark, name, descript', 'safe', 'on'=>'search'),
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
			'commandId' => '所属的标签id',
			'tagId' => '对应的按键id',
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
		$criteria->compare('commandId',$this->commandId);
		$criteria->compare('tagId',$this->tagId);
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
	 * @return AirMark the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
