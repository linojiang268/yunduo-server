<?php

class AirCommandController extends MyController {

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
//            array('allow', // allow all users to perform 'index' and 'view' actions
//                'actions' => array('index', 'view'),
//                'users' => array('*'),
//            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array('create', 'update'),
//                'users' => array('@'),
//            ),
//            array('allow', // allow admin user to perform 'admin' and 'delete' actions
//                'actions' => array('admin', 'delete'),
//                'users' => array('admin'),
//            ),
//            array('deny', // deny all users
//                'users' => array('*'),
//            ),
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
        $model = new AirCommand;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['AirCommand'])) {
            $model->attributes = $_POST['AirCommand'];
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

        if (isset($_POST['AirCommand'])) {
            $model->attributes = $_POST['AirCommand'];
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
        $dataProvider = new CActiveDataProvider('AirCommand');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new AirCommand('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AirCommand']))
            $model->attributes = $_GET['AirCommand'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return AirCommand the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = AirCommand::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param AirCommand $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'air-command-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    //=========================================================
    public function actionAdd() {
        $model = new AirCommand;
        $model->length = $_POST['length'];
        $model->headSize = $_POST['headSize'];
        $model->pulseWidth = $_POST['pulseWidth'];
        $model->brand = $_POST['brand'];
        $model->mark = $_POST['mark'];
        $model->name = $_POST['name'];
        $model->descript = $_POST['descript'];
        if ($model->save()) {
            $this->outecho(array('status' => 0, 'msg' => '新增空调编码成功', 'airBrand' => $model->attributes));
        } else {
            $this->outechoError(1, '新增空调编码失败');
        }
    }

    public function actionList() {
        $pageLimitSql = $this->getPageLimitSql();
        if (empty($pageLimitSql)) {
            return;
        }
        $sql = "select * from air_command" . $pageLimitSql;
        $results = Yii::app()->db->createCommand($sql)->queryAll();
        $airCommandList = array();
        foreach ($results as $result) {
            array_push($airCommandList, $result);
        }
        $totalNum = AirCommand::model()->countBySql('select count(DISTINCT id) from air_command');
        $this->outecho(array('status' => 0, 'msg' => '成功', 'totalNum' => $totalNum, 'airCommandList' => $airCommandList));
    }

    public function actionListFitBrand() {
        $pageLimitSql = $this->getPageLimitSql();
        if (empty($pageLimitSql)) {
            return;
        }
        $type = $_POST['type'];
        $length = $_POST['length'];
        $headSize = $_POST['headSize'];
//        $pulseWidth = $_POST['pulseWidth'];
//        $mark = $_POST['mark'];
//        $sql = "select b.* from air_command c LEFT JOIN air_brand b ON c.brand=b.id where b.type=:type and c.length=:length and c.headSize=:headSize and c.pulseWidth=:pulseWidth and c.mark=:mark group by b.id" . $pageLimitSql;
        $sql = "select b.* from air_command c LEFT JOIN air_brand b ON c.brand=b.id where b.type=:type and c.length=:length and c.headSize=:headSize group by b.id" . $pageLimitSql;
//        $results = Yii::app()->db->createCommand($sql)->query(array('type' => $type, 'length' => $length, 'headSize' => $headSize, 'pulseWidth' => $pulseWidth, 'mark' => $mark));
        $results = Yii::app()->db->createCommand($sql)->query(array('type' => $type, 'length' => $length, 'headSize' => $headSize));
        $airBrandList = array();
        foreach ($results as $result) {
            array_push($airBrandList, $result);
        }
//        $totalNum = AirCommand::model()->countBySql('select count(DISTINCT c.brand) from air_command c LEFT JOIN air_brand b ON c.brand=b.id where b.type=:type and c.length=:length and c.headSize=:headSize and c.pulseWidth=:pulseWidth and c.mark=:mark', array('type' => $type, 'length' => $length, 'headSize' => $headSize, 'pulseWidth' => $pulseWidth, 'mark' => $mark));
        $totalNum = AirCommand::model()->countBySql('select count(DISTINCT c.brand) from air_command c LEFT JOIN air_brand b ON c.brand=b.id where b.type=:type and c.length=:length and c.headSize=:headSize', array('type' => $type, 'length' => $length, 'headSize' => $headSize));
        $this->outecho(array('status' => 0, 'msg' => '成功', 'totalNum' => $totalNum, 'airBrandList' => $airBrandList));
    }

    public function actionListFitCommand() {
        $pageLimitSql = $this->getPageLimitSql();
        if (empty($pageLimitSql)) {
            return;
        }
        $brand = $_POST['brand'];
//        $type = $_POST['type'];
        $length = $_POST['length'];
        $headSize = $_POST['headSize'];
//        $pulseWidth = $_POST['pulseWidth'];
//        $mark = $_POST['mark'];
//        $sql = "select c.* from air_command c left join air_brand b on c.brand=b.id where b.id=:brand and b.type=:type and c.length=:length and c.headSize=:headSize and c.pulseWidth=:pulseWidth and c.mark=:mark group by b.id" . $pageLimitSql;
        $sql = "select c.* from air_command c left join air_brand b on c.brand=b.id where b.id=:brand and c.length=:length and c.headSize=:headSize group by c.id" . $pageLimitSql;
//        $results = Yii::app()->db->createCommand($sql)->query(array('brand' => $brand, 'type' => $type, 'length' => $length, 'headSize' => $headSize, 'pulseWidth' => $pulseWidth, 'mark' => $mark));
        $results = Yii::app()->db->createCommand($sql)->query(array('brand' => $brand, 'length' => $length, 'headSize' => $headSize));
        $airCommandList = array();
        foreach ($results as $result) {
            array_push($airCommandList, $result);
        }
//        $totalNum = AirCommand::model()->countBySql('select count(DISTINCT c.id) from air_command c left join air_brand b on c.brand=b.id where b.id=:brand and c.length=:length and c.headSize=:headSize and c.pulseWidth=:pulseWidth and c.mark=:mark', array('brand' => $brand, 'length' => $length, 'headSize' => $headSize, 'pulseWidth' => $pulseWidth, 'mark' => $mark));
        $totalNum = AirCommand::model()->countBySql('select count(DISTINCT c.id) from air_command c left join air_brand b on c.brand=b.id where b.id=:brand and c.length=:length and c.headSize=:headSize', array('brand' => $brand, 'length' => $length, 'headSize' => $headSize));
        $this->outecho(array('status' => 0, 'msg' => '成功', 'totalNum' => $totalNum, 'airCommandList' => $airCommandList));
    }

    public function actionListFitCommandOnly() {
        $pageLimitSql = $this->getPageLimitSql();
        if (empty($pageLimitSql)) {
            return;
        }
        $type = $_POST['type'];
        $length = $_POST['length'];
        $sql = "select c.* from air_command c left join air_brand b on c.brand=b.id where b.type=:type and c.length=:length group by c.id" . $pageLimitSql;
        $results = Yii::app()->db->createCommand($sql)->query(array('type' => $type, 'length' => $length));
        $airCommandList = array();
        foreach ($results as $result) {
            array_push($airCommandList, $result);
        }
        $totalNum = AirCommand::model()->countBySql('select count(DISTINCT c.id) from air_command c left join air_brand b on c.brand=b.id where b.type=:type and c.length=:length', array('type' => $type, 'length' => $length));
        $this->outecho(array('status' => 0, 'msg' => '成功', 'totalNum' => $totalNum, 'airCommandList' => $airCommandList));
    }
}
