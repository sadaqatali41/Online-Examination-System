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
                $searchQuery = " AND (c.center_name LIKE '%" . $searchValue . "%' OR c.center_code LIKE '%" . $searchValue . "%' OR ct.name LIKE '%" . $searchValue . "%' OR c.center_address LIKE '%" . $searchValue . "%')";
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

        case "findCity":
            $search = "";
            $resPerPage = 10;

            if (filter_has_var(INPUT_POST, "searchTerm")) {
                $searchTerm = strtoupper(filter_input(INPUT_POST, "searchTerm", FILTER_SANITIZE_STRING));
                if (isset($searchTerm) && !empty($searchTerm)) {
                    $search = " AND (c.id LIKE '%$searchTerm%' OR c.name LIKE '%$searchTerm%')";
                }
            }
            $page = 1;
            if (filter_has_var(INPUT_POST, "page")) {
                $page = filter_input(INPUT_POST, "page", FILTER_VALIDATE_INT);
            }

            #total records without filter
            $st = $conn->prepare("SELECT c.id, c.name FROM cities c WHERE 1=1 $search");
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT c.id, c.name FROM cities c WHERE 1=1 $search LIMIT " . $numRow . ", " . $resPerPage . "");
            $st1->execute();
            $res2 = $st1->get_result();
            $num_row = $res2->num_rows;

            $json['items'] = [];
            if ($num_row > 0) {
                while ($row = $res2->fetch_assoc()) {
                    $json['items'][] = [
                        'id' => $row['id'],
                        'text' => $row['name'],
                    ];
                }
            }
            $json["count_filtered"] = $tot_res;

            echo json_encode($json);
            break;

        case 'centerSubmit':
            $center_name = filter_input(INPUT_POST, 'center_name', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $center_code = filter_input(INPUT_POST, 'center_code', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            $center_city = filter_input(INPUT_POST, 'center_city', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

            $totInput = count($center_code) - 1;

            for($i = 0; $i <= $totInput; $i++) {
                if(empty($center_name[$i])) {
                    $error[] = 'Center Name is required.';
                }
                if(empty($center_code[$i])) {
                    $error[] = 'Center Code is required.';
                }
                if(empty($center_city[$i])) {
                    $error[] = 'Center City is required.';
                }
                if(empty($address[$i])) {
                    $error[] = 'Address is required.';
                }
            }

            if(empty($error)) {

            } else {
                $addRes['status'] = 'error';
                $addRes['error'] = $error;
            }

            echo json_encode($addRes);
            break;
    }
}
