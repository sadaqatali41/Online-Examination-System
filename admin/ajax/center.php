<?php
include '../class/config.php';
include '../class/database.php';
include '../class/userAuth.php';
$config = new Config();
$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$auth->sessionStart();
if ($auth->loginCheck($conn) === FALSE) {
  header("Location: ../login.php");
}
$user_data = $_SESSION['user_data'];

if (filter_has_var(INPUT_POST, 'act') && filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING) !== null) {
    $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);

    switch ($act) {

        case "center_list":
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
                $searchQuery = " AND (fb.bill_no LIKE '%" . $searchValue . "%' OR fb.bill_date LIKE '%" . $searchValue . "%' OR fb.ven_cd LIKE '%" . $searchValue . "%' OR fb.vendor_type LIKE '%" . $searchValue . "%' OR fb.ven_bill_no LIKE '%" . $searchValue . "%' OR fb.ven_bill_date LIKE '%" . $searchValue . "%' OR fb.grn_no LIKE '%" . $searchValue . "%' OR fb.grn_dt LIKE '%" . $searchValue . "%' OR fccm.description LIKE '%" . $searchValue . "%' OR vm.name LIKE '%" . $searchValue . "%' OR fb.vou_no LIKE '%" . $searchValue . "%')";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM centers c LEFT JOIN cities ct ON ct.id=c.center_city WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT c.*, ct.name FROM centers c LEFT JOIN cities ct ON ct.id=c.center_city WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id" => $row['id'],
                        "center_name" => $row['center_name'],
                        "center_code" => $row['center_code'],
                        "center_city" => $row['name'],
                        "center_address" => $row['center_address'],
                        "center_status" => $row['center_status'],
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
    }
}
