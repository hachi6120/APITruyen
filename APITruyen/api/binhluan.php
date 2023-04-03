<?php
//?res=users --> danh sách user
//?res=users&id==1 ==> xem chi tiết 1 user
function listbinhluan()
{
    global $objConn;
    try {
        $sql_str = "SELECT * FROM `tb_binh_luan`";

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

function binhluan($id)
{
    global $objConn;

    // đã nhập tên loại rồi ==> lưu vào CSDL
    try {
        $stmt = $objConn->prepare("SELECT * FROM `tb_binh_luan` WHERE (id) = " . $id . ";");
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

function binhluantruyen($id)
{
    global $objConn;

    // đã nhập tên loại rồi ==> lưu vào CSDL
    try {
        $stmt = $objConn->prepare("SELECT tb_binh_luan.*, tb_truyen.ten_truyen, tb_user.username
                                            FROM tb_binh_luan 
                                            INNER JOIN tb_truyen ON tb_binh_luan.id_truyen = tb_truyen.id
                                            INNER JOIN tb_user ON tb_binh_luan.id_user = tb_user.id
                                            WHERE tb_binh_luan.id_truyen = $id");
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

function thembinhluan()
{
    global $objConn;

    $id_truyen = $_POST['id_truyen'];
    $id_user = $_POST['id_user'];
    $noi_dung = $_POST['noi_dung'];

    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $ngay_gio = date('Y-m-d H:i:s');
    if (empty ($id_truyen) || empty ($id_user) || empty ($noi_dung) || empty ($ngay_gio)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "INSERT INTO tb_binh_luan (id_truyen, id_user, noi_dung, ngay_gio) VALUES (:ts_id_truyen,:ts_id_user,:ts_noi_dung,:ts_ngay_gio);");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id_truyen", $id_truyen);
            $stmt->bindParam(":ts_id_user", $id_user);
            $stmt->bindParam(":ts_noi_dung", $noi_dung);
            $stmt->bindParam(":ts_ngay_gio", $ngay_gio);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Đã thêm thành công',
                listbinhluan()
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

function suabinhluan($_PUT)
{
    global $objConn;

    $id = $_PUT['id'];
    $id_truyen = $_PUT['id_truyen'];
    $id_user = $_PUT['id_user'];
    $noi_dung = $_PUT['noi_dung'];
    if (empty ($id) || empty ($id_truyen) || empty ($id_user) || empty ($noi_dung)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "UPDATE tb_binh_luan SET noi_dung = :ts_noi_dung WHERE id = :ts_id AND id_truyen = :ts_id_truyen AND id_user = :ts_id_user");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            $stmt->bindParam(":ts_id_truyen", $id_truyen);
            $stmt->bindParam(":ts_id_user", $id_user);
            $stmt->bindParam(":ts_noi_dung", $noi_dung);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Sửa thành công',
                binhluan($id)
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
function xoabinhluan($_DELETE)
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
                "DELETE FROM `tb_binh_luan` WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Xóa thành công',
                listbinhluan()
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
        listbinhluan(); // gọi hàm listALL
    } else {
        binhluantruyen($_GET['id']);
        /*binhluan($_GET['id']);*/
        // gọi hàm xem chi tiết
    }
}
if ($method == 'POST') { // đã là post thì chỉ thêm mới, nếu muốn sửa đổi PUT
    thembinhluan();
}
if ($method == 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    suabinhluan($_PUT);
}
if ($method == 'DELETE'){
    parse_str(file_get_contents('php://input'), $_DELETE);
    xoabinhluan($_DELETE);
}