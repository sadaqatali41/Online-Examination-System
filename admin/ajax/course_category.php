<?php
include '../class/config.php';
include '../class/database.php';
include '../class/userAuth.php';
$config = new Config();
$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$auth->sessionStart();
if ($auth->loginCheck($conn) === false) {
    header("Location: ../login.php");
}
$user_data = $_SESSION['user_data'];

if (filter_has_var(INPUT_POST, 'act') && filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING) !== null) {
    $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);

    switch ($act) {

        case "course_category_list":
            $draw = filter_var($_POST['draw'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $numRow = filter_var($_POST['start'], FILTER_VALIDATE_INT);
            $rowperpage = filter_var($_POST['length'], FILTER_VALIDATE_INT); // Rows display per page
            $columnIndex = filter_var($_POST['order'][0]['column'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column index
            $columnName = filter_var($_POST['columns'][$columnIndex]['data'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column name
            $columnSortOrder = filter_var($_POST['order'][0]['dir'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // asc or desc
            $searchValue = filter_var($_POST['search']['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Search value
            ## Search
            $searchQuery = " ";
            if ($searchValue != '') {
                $searchQuery = " AND (cc.cc_name LIKE '%" . $searchValue . "%' OR al.name LIKE '%" . $searchValue . "%' OR alu.name LIKE '%" . $searchValue . "%')";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(cc.id) AS allcount FROM course_category cc LEFT JOIN admin_login al ON al.id=cc.created_by LEFT JOIN admin_login alu ON alu.id=cc.updated_by WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT cc.*, al.name, alu.name AS u_name, (SELECT COUNT(c.cc_id) FROM courses c WHERE c.cc_id=cc.id) AS tot_course FROM course_category cc LEFT JOIN admin_login al ON al.id=cc.created_by LEFT JOIN admin_login alu ON alu.id=cc.updated_by WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id" => $row['id'],
                        "cc_name" => $row['cc_name'],
                        "cc_code" => $row['cc_code'],
                        "name" => $row['name'],
                        "created_by" => $row['name'],
                        "updated_by" => $row['u_name'],
                        "cc_status" => $row['cc_status'],
                        "tot_course" => $row['tot_course'],
                    );
                }
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecordwithFilter,
                "iTotalDisplayRecords" => $totalRecords,
                "data" => $row1,
            );

            print json_encode($response);
            break;

        case 'centerEditSubmit':
            $cc_id = filter_input(INPUT_POST, 'cc_id', FILTER_VALIDATE_INT);
            $cc_name = filter_input(INPUT_POST, 'cc_name', FILTER_SANITIZE_STRING);
            $cc_status = filter_input(INPUT_POST, 'cc_status', FILTER_SANITIZE_STRING);

            if(empty($cc_name)) {
                $error[] = 'Category Name is required.';
            }
            if(empty($cc_id)) {
                $error[] = 'Something went wrong.';
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("UPDATE course_category SET cc_status=? WHERE id=?");
                    $stmt->bind_param("si", $cc_status, $cc_id);
                    
                    if($stmt->execute() === false) {
                        throw new Exception("Can't update Course Category. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Course Category is updated.';
                    }

                } catch (Exception $th) {
                    $error[] = $th->getMessage();
                    $addRes['status'] = 'error';
                    $addRes['error'] = $error;
                    $conn->rollback();
                }
            } else {
                $addRes['status'] = 'error';
                $addRes['error'] = $error;
            }

            echo json_encode($addRes);
            break;
    }
}
