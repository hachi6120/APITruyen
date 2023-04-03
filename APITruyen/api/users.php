<?php
//?res=users --> danh sách user
//?res=users&id==1 ==> xem chi tiết 1 user
function listUser()
{
    global $objConn;
    try {
        $sql_str = "SELECT * FROM `tb_user`";

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

function user($id)
{
    global $objConn;

    // đã nhập tên loại rồi ==> lưu vào CSDL
    try {
        $stmt = $objConn->prepare("SELECT * FROM `tb_user` WHERE (id) = " . $id . ";");
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

function addUser()
{
    global $objConn;

    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    if (empty ($username) || empty ($passwd) || empty ($email) || empty ($fullname)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "INSERT INTO tb_user (username, passwd, email, fullname) VALUES (:ts_username,:ts_passwd,:ts_elmail,:ts_fullname);");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_username", $username);
            $stmt->bindParam(":ts_passwd", $passwd);
            $stmt->bindParam(":ts_elmail", $email);
            $stmt->bindParam(":ts_fullname", $fullname);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Đã thêm thành công',
                listUser()
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

function updateUser($_PUT)
{
    global $objConn;

    $id = $_PUT['id'];
    $username = $_PUT['username'];
    $passwd = $_PUT['passwd'];
    $email = $_PUT['email'];
    $fullname = $_PUT['fullname'];
    if (empty ($id) || empty ($username) || empty ($passwd) || empty ($email) || empty ($fullname)) {
        $dataRes = [
            'status' => 0,
            'msg' => 'Nhập thiếu thông tin'
        ];

    } else {
        // đã nhập tên loại rồi ==> lưu vào CSDL
        try {

            $stmt = $objConn->prepare(
                "UPDATE tb_user SET username = :ts_username, passwd = :ts_passwd,email = :ts_elmail,fullname = :ts_fullname WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            $stmt->bindParam(":ts_username", $username);
            $stmt->bindParam(":ts_passwd", $passwd);
            $stmt->bindParam(":ts_elmail", $email);
            $stmt->bindParam(":ts_fullname", $fullname);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Sửa thành công',
                user($id)
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
function deleteUser($_DELETE)
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
                "DELETE FROM `tb_user` WHERE id = :ts_id");

            // gán tham số cho câu lệnh
            $stmt->bindParam(":ts_id", $id);
            // thực thi
            $stmt->execute();

            $dataRes = [
                'status' => 1,
                'msg' => 'Xóa thành công',
                listUser()
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
        listUser(); // gọi hàm listALL
    } else {
        user($_GET['id']);
        // gọi hàm xem chi tiết
    }
}
if ($method == 'POST') { // đã là post thì chỉ thêm mới, nếu muốn sửa đổi PUT
    addUser();
}
if ($method == 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    updateUser($_PUT);
}
if ($method == 'DELETE'){
    parse_str(file_get_contents('php://input'), $_DELETE);
    deleteUser($_DELETE);
}