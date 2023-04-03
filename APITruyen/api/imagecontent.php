<?php
//?res=users --> danh sách user
//?res=users&id==1 ==> xem chi tiết 1 user
function listtrangtruyen()
{
    global $objConn;
    try {
        $sql_str = "SELECT * FROM `tb_img_content`";

        //tạo đối tượng prepare chuẩn bị cho cú pháp thực thi truy vấn
        $stmt = $objConn->prepare($sql_str);
        // thực thi câu lệnh
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        //Lấy dữ liệu:
        $danh_sach = $stmt->fetchAll();

        $dataRes = [
            'status' => 1,
            'msg' => 'Thành công',
            'data' => $danh_sach
        ];
        die(json_encode($dataRes));
    } catch (Exception $e) {
        die('Lỗi CSDL' . $e->getMessage());
    }
}

function image($id)
{
    global $objConn;

    // đã nhập tên loại rồi ==> lưu vào CSDL
    try {
        $stmt = $objConn->prepare("SELECT * FROM `tb_img_content` WHERE (id) = " . $id . ";");
        // thực thi
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        //Lấy dữ liệu:
        $danh_sach = $stmt->fetchAll();

        $dataRes = [
            'status' => 1,
            'msg' => 'Thành công',
            'data' => $danh_sach
        ];
        die(json_encode($dataRes));

    } catch (PDOException $e) {

        $dataRes = [
            'status' => 0,
            'msg' => 'Lỗi ' . $e->getMessage()
        ];
    }

    die(json_encode($dataRes));
}

function addImage()
{
    global $objConn;

    $link_anh = $_POST['link_anh'];
    $id_truyen = $_POST['id_truyen'];

    if (empty ($link_anh) || empty ($id_truyen)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "INSERT INTO tb_img_content (link_anh, id_truyen) VALUES (:ts_link_anh,:ts_id_truyen);");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_link_anh", $link_anh);
            $stmt->bindParam(":ts_id_truyen", $id_truyen);

            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Đã thêm thành công',
                listtrangtruyen()
            ];

        } catch (PDOException $e) {

            $dataRes = [
                'status' => 0,
                'msg' => 'Lỗi ' . $e->getMessage()
            ];
        }
    }
    die(json_encode($dataRes));
}

function updateImage($_PUT)
{
    global $objConn;

    $id = $_PUT['id'];
    $link_anh = $_PUT['link_anh'];

    if (empty ($id) || empty ($link_anh)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "UPDATE tb_img_content SET link_anh = :ts_link_anh WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            $stmt->bindParam(":ts_link_anh", $link_anh);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Sửa thành công',
                image($id)
            ];

        } catch (PDOException $e) {

            $dataRes = [
                'status' => 0,
                'msg' => 'Lỗi ' . $e->getMessage()
            ];
        }
    }
    die(json_encode($dataRes));
}
function deleteImage($_DELETE)
{
    global $objConn;

    $id = $_DELETE['id'];

    if (empty ($id)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Chưa nhập id'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "DELETE FROM `tb_img_content` WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Xóa thành công',
                listtrangtruyen()
            ];

        } catch (PDOException $e) {

            $dataRes = [
                'status' => 0,
                'msg' => 'Lỗi ' . $e->getMessage()
            ];
        }
    }
    die(json_encode($dataRes));
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    if (empty($_GET['id'])) { //không có id là trang danh sách, có id là chi tiết
        listtrangtruyen(); // gọi hàm listALL
    } else {
        image($_GET['id']);
        // gọi hàm xem chi tiết
    }
}
if ($method == 'POST') { // đã là post thì chỉ thêm mới, nếu muốn sửa đổi PUT
    addImage();
}
if ($method == 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    updateImage($_PUT);
}
if ($method == 'DELETE'){
    parse_str(file_get_contents('php://input'), $_DELETE);
    deleteImage($_DELETE);
}