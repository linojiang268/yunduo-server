<?php

/**
 * This is the model class for table "app_version_info".
 *
 * The followings are the available columns in table 'app_version_info':
 * @property integer $id
 * @property integer $appPlatform
 * @property integer $versionCode
 * @property string $versionName
 * @property string $downloadUrl
 */
class AppVersionInfo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'app_version_info';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('appPlatform, versionCode, versionName, downloadUrl', 'required'),
            array('appPlatform, versionCode', 'numerical', 'integerOnly' => true),
            array('versionName', 'length', 'max' => 16),
            array('downloadUrl', 'length', 'max' => 120),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, appPlatform, versionCode, versionName, downloadUrl', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'appPlatform' => 'app平台（安卓1，ios2）',
            'versionCode' => '版本versionCode',
            'versionName' => '版本versionName',
            'downloadUrl' => '下载url',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('appPlatform', $this->appPlatform);
        $criteria->compare('versionCode', $this->versionCode);
        $criteria->compare('versionName', $this->versionName, true);
        $criteria->compare('downloadUrl', $this->downloadUrl, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AppVersionInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
