<?php
//?res=users --> danh sách user
//?res=users&id==1 ==> xem chi tiết 1 user
function listTruyen()
{
    global $objConn;
    try {
        $sql_str = "SELECT * FROM `tb_truyen`";

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

function truyen($id)
{
    global $objConn;

    // đã nhập tên loại rồi ==> lưu vào CSDL
    try {
        $stmt = $objConn->prepare("SELECT * FROM `tb_truyen` WHERE (id) = " . $id . ";");
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

function themTruyen()
{
    global $objConn;

    $ten_truyen = $_POST['ten_truyen'];
    $tac_gia = $_POST['tac_gia'];
    $nam_xb = $_POST['nam_xb'];
    $anh_bia = $_POST['anh_bia'];
    if (empty ($ten_truyen) || empty ($tac_gia) || empty ($nam_xb) || empty ($anh_bia)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "INSERT INTO tb_truyen (ten_truyen, tac_gia, nam_xb, anh_bia) VALUES (:ts_ten_truyen,:ts_tac_gia,:ts_nam_xb,:ts_anh_bia);");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_ten_truyen", $ten_truyen);
            $stmt->bindParam(":ts_tac_gia", $tac_gia);
            $stmt->bindParam(":ts_nam_xb", $nam_xb);
            $stmt->bindParam(":ts_anh_bia", $anh_bia);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Đã thêm thành công',
                listTruyen()
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

function suaTruyen($_PUT)
{
    global $objConn;

    $id = $_PUT['id'];
    $ten_truyen = $_PUT['ten_truyen'];
    $tac_gia = $_PUT['tac_gia'];
    $nam_xb = $_PUT['nam_xb'];
    $anh_bia = $_PUT['anh_bia'];
    if (empty ($id) || empty ($ten_truyen) || empty ($tac_gia) || empty ($nam_xb) || empty ($anh_bia)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "UPDATE tb_truyen SET ten_truyen = :ts_ten_truyen, tac_gia = :ts_tac_gia,nam_xb = :ts_nam_xb,anh_bia = :tb_truyen WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            $stmt->bindParam(":ts_ten_truyen", $ten_truyen);
            $stmt->bindParam(":ts_tac_gia", $tac_gia);
            $stmt->bindParam(":ts_nam_xb", $nam_xb);
            $stmt->bindParam(":tb_truyen", $anh_bia);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Sửa thành công',
                truyen($id)
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
function xoaTruyen($_DELETE)
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
                "DELETE FROM `tb_truyen` WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Xóa thành công',
                listTruyen()
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
        listTruyen(); // gọi hàm listALL
    } else {
        truyen($_GET['id']);
        // gọi hàm xem chi tiết
    }
}
if ($method == 'POST') { // đã là post thì chỉ thêm mới, nếu muốn sửa đổi PUT
    themTruyen();
}
if ($method == 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    suaTruyen($_PUT);
}
if ($method == 'DELETE'){
    parse_str(file_get_contents('php://input'), $_DELETE);
    xoaTruyen($_DELETE);
}