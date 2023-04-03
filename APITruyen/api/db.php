<?php
$objConn = null;
$db_host = 'localhost'; // 
$db_name = 'db_truyen';
$db_user = 'root';
$db_pass = '';
try{
    $objConn = new PDO("mysql:host=$db_host;dbname=$db_name",$db_user, $db_pass);
    $objConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e){
    die('Lỗi kết nối CSDL: ' . $e->getMessage());
}