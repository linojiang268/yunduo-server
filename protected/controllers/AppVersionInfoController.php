<?php

class AppVersionInfoController extends MyController {

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

//=========================================
    public function actionLatestIntro() {
        if (0 != strcmp($_POST['userName'], 'yunduo')) {
            $this->outechoError(1, '无权限');
            exit();
        }
        if (0 != strcmp($_POST['userPass'], 'yunduo,123')) {
            $this->outechoError(1, '无权限');
            exit();
        }
        header("Location:/yunduoserver/doc/yunduo_intro.doc");
    }

    public function actionLatestProtocol() {
        if (0 != strcmp($_POST['userName'], 'yunduo')) {
            $this->outechoError(1, '无权限');
            exit();
        }
        if (0 != strcmp($_POST['userPass'], 'yunduo,123')) {
            $this->outechoError(1, '无权限');
            exit();
        }
        header("Location:/yunduoserver/doc/yunduo_protocol.xls");
    }

    public function actionUpdateInfo() {
        if (0 != strcmp($_POST['userName'], 'yunduo')) {
            $this->outechoError(1, '无权限');
            exit();
        }
        if (0 != strcmp($_POST['userPass'], 'yunduo,123')) {
            $this->outechoError(1, '无权限');
            exit();
        }
        $appVersionInfo = AppVersionInfo::model()->find('appPlatform=:appPlatform and versionCode=:versionCode', array(':appPlatform' => $_POST['appPlatform'], ':versionCode' => trim($_POST['versionCode'])));
        if (!empty($appVersionInfo)) {
            $appVersionInfo->versionName = trim($_POST['versionName']);
            if (isset($_FILES['downloadFile'])) {
                $appVersionInfo->downloadUrl = $this->getVersionFile('downloadFile', $appVersionInfo->appPlatform, $appVersionInfo->versionCode);
                if (empty($appVersionInfo->downloadUrl)) {
                    return;
                }
            }
            if ($appVersionInfo->update()) {
                $this->outecho(array('status' => 0, 'msg' => '修改版本成功', '$appVersionInfo' => $appVersionInfo->attributes));
            } else {
                $this->outechoError(1, '修改版本失败');
            }
        } else {
            $model = new AppVersionInfo;
            $model->appPlatform = $_POST['appPlatform'];
            if (1 == $model->appPlatform || 2 == $model->appPlatform || 3 == $model->appPlatform) {
                $model->versionCode = $_POST['versionCode'];
                $model->versionName = trim($_POST['versionName']);
                if (isset($_FILES['downloadFile'])) {
                    $model->downloadUrl = $this->getVersionFile('downloadFile', $model->appPlatform, $model->versionCode);
                    if (empty($model->downloadUrl)) {
                        return;
                    }
                }
                if ($model->save()) {
                    $this->outecho(array('status' => 0, 'msg' => '新增版本成功', 'appVersionInfo' => $model->attributes));
                } else {
                    $this->outechoError(1, '新增版本失败');
                }
            } else {
                $this->outechoError(1, '新增版本参数错误');
            }
        }
    }

    public function actionCheckUpdate() {
        $sql = 'select * from app_version_info where appPlatform=:appPlatform order by versionCode desc';
        $results = Yii::app()->db->createCommand($sql)->query(array(':appPlatform' => $_POST['appPlatform']));
        $appVersionInfoList = array();
        foreach ($results as $result) {
            array_push($appVersionInfoList, $result);
            break;
        }
        if (!empty($appVersionInfoList) && count($appVersionInfoList) > 0) {
            $this->outecho(array('status' => 0, 'msg' => '成功', 'appVersionInfo' => reset($appVersionInfoList)));
        } else {
            $this->outechoError(1, '无版本');
        }
    }

    public function actionLatestVersion() {
        $sql = 'select * from app_version_info where appPlatform=:appPlatform order by versionCode desc';
        $results = Yii::app()->db->createCommand($sql)->query(array(':appPlatform' => $_GET['appPlatform']));
        $appVersionInfoList = array();
        foreach ($results as $result) {
            array_push($appVersionInfoList, $result);
            break;
        }
        if (!empty($appVersionInfoList) && count($appVersionInfoList) > 0) {
            header("Location:" . $appVersionInfoList[0]['downloadUrl']);
        } else {
            $this->outechoError(1, '无版本');
        }
    }

    protected function getVersionFile($key, $platform, $versionCode) {
        //===================文件上传===================
        if ($_FILES[$key]["error"] > 0) {
            $this->outecho(array('status' => 1, 'msg' => 'error:' . $_FILES[$key]["error"]));
            return NULL;
        } else {
//            $storedRoot = $_SERVER['DOCUMENT_ROOT'] . '/downloads/app/android';
//            $storedPath = "/versions/" . $root . '/' . $timeName . '.apk';
            $storedRoot;
            $storedPath;
            if (1 == $platform) {
                //android
                $storedRoot = $_SERVER['DOCUMENT_ROOT'];
                $storedPath = '/downloads/app/android/indeocenter_' . $versionCode . '.apk';
            } else if (2 == $platform) {
                //ios    
                $storedRoot = $_SERVER['DOCUMENT_ROOT'];
                $storedPath = '/downloads/app/ios/indeocenter_' . $versionCode . '.ipa';
            } else {
                //fireware
                $storedRoot = $_SERVER['DOCUMENT_ROOT'];
                $storedPath = '/downloads/fireware/indeocenter_' . $versionCode . '.img';
            }
            $this->createDir(dirname($storedRoot . $storedPath));
            if (file_exists($storedRoot . $storedPath)) {
                unlink($storedRoot . $storedPath);
            }
            move_uploaded_file($_FILES[$key]["tmp_name"], $storedRoot . $storedPath);
            return $storedPath;
        }
        //===================文件上传===================
    }

}
