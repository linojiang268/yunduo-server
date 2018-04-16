<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MyController extends CController {

    protected function outechoError($status, $errorStr) {
        if (empty($errorStr)) {
            $this->outecho(array('status' => $status, 'msg' => '参数错误，请检查协议'));
        } else {
            $this->outecho(array('status' => $status, 'msg' => $errorStr));
        }
    }

    /**
     * 按json格式输出数据
     * @param type $arr
     */
    protected function outecho($arr) {
//        foreach ($arr as $key => $value) {
//            $r[$key] = urlencode($value);
//        }
//        echo urldecode(json_encode($arr));
//        -------------------
        echo header('Content-Type: text/html; charset=UTF-8');
        $code = json_encode($arr);
        echo preg_replace("#\\\u(([0-9a-f]+?){4})#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $code);
//        -------------------
    }

    /**
     * 验证是否已登录
     * @param type $userId
     * @param type $sessionId
     * @return boolean
     */
    protected function validateSession($userId, $sessionId) {
//        var_dump(Yii::app()->memcache);
        $memcache = Yii::app()->memcache;
        $memcache_sessionId = $memcache->get('user' . $userId);
        if (!empty($memcache_sessionId)) {
            if ($sessionId == $memcache_sessionId) {
                return TRUE;
            } else {
                $this->outechoError(1, '同一账号其它地方登录，您被迫下线');
                return FALSE;
            }
        } else {
            $this->outechoError(1, '未登录');
            return FALSE;
        }
    }

    /*
     * 功能：循环检测并创建文件夹 
     * 参数：$path 文件夹路径 
     * 返回： 
     */

    protected function createDir($path) {
        if (!file_exists($path)) {
            $this->createDir(dirname($path));
            mkdir($path, 0777);
        }
    }

    /**
     * 获取上传文件，并保存到指定目录
     * @param type $key
     * @param type $root
     * @return null|string
     */
    protected function getPostFiles($key, $root, $wid, $hei) {
        ///withFriendsServer/index.php?r=userInfo/updateInfo
//        echo $_SERVER['REQUEST_URI'];
//        echo '<br>';
        ///Users/laurencetsang/zy_work/workspace_for_php_netbeans/withFriendsServer/index.php
//        echo $_SERVER['SCRIPT_FILENAME'];
//        echo '<br>';
        ///Users/laurencetsang/zy_work/workspace_for_php_netbeans
//        echo $_SERVER['DOCUMENT_ROOT'];
//        echo '<br>';
        ///withFriendsServer/index.php
//        echo $_SERVER['PHP_SELF'];
//        echo '<br>';
//        return NULL;
        //===================文件上传===================
//        if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/pjpeg")) && ($_FILES["file"]["size"] < 20000)) {
        if ((($_FILES[$key]["type"] == "image/gif") || ($_FILES[$key]["type"] == "image/jpeg") || ($_FILES[$key]["type"] == "image/pjpeg")) && ($_FILES[$key]["size"] < 1024 * 600)) {
            if ($_FILES[$key]["error"] > 0) {
//                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
                $this->outecho(array('status' => 1, 'msg' => 'error:' . $_FILES[$key]["error"]));
                return NULL;
            } else {
//                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
//                echo "Type: " . $_FILES["file"]["type"] . "<br />";
//                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
//                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
//                if (file_exists("upload/" . $_FILES["file"]["name"])) {
//                    echo $_FILES["file"]["name"] . " already exists. ";
//                } else {
//                    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
//                    echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
//                }
                $timeName = time();
                $storedRoot = $_SERVER['DOCUMENT_ROOT'] . '/withFriendsServerData';
                $storedPath = "/images/" . $root . '/' . $timeName . '.jpg';
                $storedReSizePath = "/images/" . $root . '_s' . '/' . $timeName . '.jpg';
                $this->createDir(dirname($storedRoot . $storedPath));
                move_uploaded_file($_FILES[$key]["tmp_name"], $storedRoot . $storedPath);
                $this->createDir(dirname($storedRoot . $storedReSizePath));
                new ResizeImage($storedRoot . $storedPath, $wid, $hei, "0", $storedRoot . $storedReSizePath);
//                echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
                return $storedPath;
            }
        } else {
//            echo "Invalid file";
            $this->outecho(array('status' => 1, 'msg' => 'Invalidfile. Type:' . $_FILES[$key]["type"] . ',Size:' . ($_FILES[$key]["size"] / 1024) . 'Kb'));
            return NULL;
        }
        //===================文件上传===================
    }

    protected function getPostArrFiles($index, $key, $root, $wid, $hei) {
        if ((($_FILES[$key]["type"][$index] == "image/gif") || ($_FILES[$key]["type"][$index] == "image/jpeg") || ($_FILES[$key]["type"][$index] == "image/pjpeg")) && ($_FILES[$key]["size"][$index] < 1024 * 600)) {
            if ($_FILES[$key]["error"][$index] > 0) {
                $this->outecho(array('status' => 1, 'msg' => 'error:' . $_FILES[$key]["error"][$index]));
                return NULL;
            } else {
                $timeName = time();
                $storedRoot = $_SERVER['DOCUMENT_ROOT'] . '/withFriendsServerData';
                $storedPath = "/images/" . $root . '/' . $timeName . '.jpg';
                $storedReSizePath = "/images/" . $root . '_s' . '/' . $timeName . '.jpg';
                $this->createDir(dirname($storedRoot . $storedPath));
                move_uploaded_file($_FILES[$key]["tmp_name"][$index], $storedRoot . $storedPath);
                $this->createDir(dirname($storedRoot . $storedReSizePath));
                new ResizeImage($storedRoot . $storedPath, $wid, $hei, "0", $storedRoot . $storedReSizePath);
                return $storedPath;
            }
        } else {
            $this->outecho(array('status' => 1, 'msg' => 'Invalidfile. Type:' . $_FILES[$key]["type"][$index] . ',Size:' . ($_FILES[$key]["size"][$index] / 1024) . 'Kb'));
            return NULL;
        }
    }

//sql:
//$sql = 'select * from user_table where id > 10 ordr by id asc';
//Yii::app()->db->createCommand($sql)->queryAll();
//$sql = 'insert into user_table (id, username) value (1, "johnny")';
//Yii::app()->db->createCommand($sql)->execute();
    protected function getPageLimitSql() {
        if (empty($_POST['page']) || empty($_POST['limit']) || $_POST['limit'] > 300) {
            $this->outechoError(1, NULL);
            return NULL;
        } else {
            $limit = $_POST['limit'];
            $firstIndex = ($_POST['page'] - 1) * $limit;
            return ' limit ' . $firstIndex . ',' . $limit;
        }
    }

    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     */
    protected function getDistance($lat1, $lng1, $lat2, $lng2) {
        //$earthRadius = 6367000;
        $earthRadius = 6378.137;
        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }

}