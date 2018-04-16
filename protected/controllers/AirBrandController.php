<?php

class AirBrandController extends MyController {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions'=>array('index','view'),
//				'users'=>array('*'),
//			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'users'=>array('@'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new AirBrand;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AirBrand'])) {
            $model->attributes = $_POST['AirBrand'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AirBrand'])) {
            $model->attributes = $_POST['AirBrand'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('AirBrand');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new AirBrand('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AirBrand']))
            $model->attributes = $_GET['AirBrand'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return AirBrand the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = AirBrand::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param AirBrand $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'air-brand-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //=========================================================
    public function actionAdd() {
        $model = new AirBrand;
        $model->type = $_POST['type'];
        $model->name = $_POST['name'];
        $model->descript = $_POST['descript'];
        if ($model->save()) {
            $this->outecho(array('status' => 0, 'msg' => '新增品牌成功', 'airBrand' => $model->attributes));
        } else {
            $this->outechoError(1, '新增品牌失败');
        }
    }

    public function actionList() {
        $pageLimitSql = $this->getPageLimitSql();
        if (empty($pageLimitSql)) {
            return;
        }
        $sql = "select * from air_brand" . $pageLimitSql;
        $results = Yii::app()->db->createCommand($sql)->queryAll();
        $airBrandList = array();
        foreach ($results as $result) {
            array_push($airBrandList, $result);
        }
        $totalNum = AirBrand::model()->countBySql('select count(DISTINCT id) from air_brand');
        $this->outecho(array('status' => 0, 'msg' => '成功', 'totalNum' => $totalNum, 'airBrandList' => $airBrandList));
    }

}
