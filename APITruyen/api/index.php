<?php
/*echo "API ";
echo '<pre>';
print_r( $_GET);*/
header('Content-Type: application/json; charset=utf-8');

// kết nối CSDL
require_once 'db.php';

if (!isset($_GET['res'])){
    die('Resource notfound');
}
$file = $_GET['res'];
//Tạo đg dẫn file
$file_path = __DIR__.'/'.$file.'.php';

//Kiểm tra tồn tại file
if ( file_exists($file_path)){
    require_once $file_path;
}else{
    /*header("HTTP/1.1 404 Not Found");*/
    die('File notfound: '. $file);
}

// ko báo lỗi
/*$file = @$_GET[''];*/
?>