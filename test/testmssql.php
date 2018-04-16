<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$conn = mssql_connect("192.168.0.159:1433", "sa", "touch,touch");

//测试连接
if ($conn) {
    echo 'linux mssql success!';
}  else {
    echo "linux mssql fail!";
}
?>
