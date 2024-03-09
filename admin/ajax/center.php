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
            #custom filter
            $center_city = filter_var($_POST['center_city'], FILTER_VALIDATE_INT);
            $center_status = filter_var($_POST['center_status'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            ## Search
            $searchQuery = " ";
            if ($searchValue != '') {
                $searchQuery = " AND (c.center_name LIKE '%" . $searchValue . "%' OR c.center_code LIKE '%" . $searchValue . "%' OR ct.name LIKE '%" . $searchValue . "%' OR c.center_address LIKE '%" . $searchValue . "%')";
            }
            if(isset($center_city) && !empty($center_city)) {
                $searchQuery .= " AND c.center_city=" . $center_city;
            }
            if(isset($center_status) && !empty($center_status)) {
                $searchQuery .= " AND c.center_status='". $center_status ."'";
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
            $stmt2 = $conn->prepare("SELECT c.*, ct.name, (SELECT COUNT(s.center_id) FROM students s WHERE s.center_id=c.id) AS tot_student FROM centers c LEFT JOIN cities ct ON ct.id=c.center_city WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
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
                        "tot_student" => $row['tot_student'],
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

        case 'centerAddSubmit':
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
                if(empty($error)) {
                    if(strlen($center_name[$i]) > 20) {
                        $error[] = "Center Name can not be greater than 20 characters.";
                    }
                    if(strlen($center_code[$i]) > 20) {
                        $error[] = "Center Code can not be greater than 11 characters.";
                    }
                    if(strlen($address[$i]) > 255) {
                        $error[] = "Address can not be greater than 255 characters.";
                    }
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    #centers - insert
                    $stmt = $conn->prepare("INSERT INTO centers(center_name, center_code, center_city, center_address, created_by) VALUES (?,?,?,?,?)");

                    #center - fetch for duplicate
                    $stmt1 = $conn->prepare("SELECT * FROM centers WHERE center_code=?");

                    for ($i = 0; $i <= $totInput; $i++) {
                        #centers - fetch
                        $stmt1->bind_param("s", $center_code[$i]);
                        if($stmt1->execute() === false) {
                            throw new Exception("Can't fetch centers. Reason : " . $stmt1->error);
                        }
                        $res1 = $stmt1->get_result();
                        if($res1->num_rows > 0) {
                            throw new Exception("Center Code ({$center_code[$i]}) is already exists.");
                        }
                        #centers - insert
                        $stmt->bind_param("siiss", $center_name[$i], $center_code[$i], $center_city[$i], $address[$i], $user_data['id']);
                        if($stmt->execute() === false) {
                            throw new Exception("Can't insert in centers. Reason : " . $stmt->error);
                        }
                    }
                    $stmt->close();
                    $stmt1->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Center is added.';
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

        case 'centerEditSubmit':
            $center_id = filter_input(INPUT_POST, 'center_id', FILTER_VALIDATE_INT);
            $center_name = filter_input(INPUT_POST, 'center_name', FILTER_SANITIZE_STRING);
            $center_code = filter_input(INPUT_POST, 'center_code', FILTER_VALIDATE_INT);
            $center_city = filter_input(INPUT_POST, 'center_city', FILTER_VALIDATE_INT);
            $center_status = filter_input(INPUT_POST, 'center_status', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

            if(empty($center_name)) {
                $error[] = 'Center Name is required.';
            }
            if(empty($center_code)) {
                $error[] = 'Center Code is required.';
            }
            if(empty($center_city)) {
                $error[] = 'Center City is required.';
            }
            if(empty($address)) {
                $error[] = 'Address is required.';
            }
            if(empty($error)) {
                if(strlen($center_name) > 20) {
                    $error[] = "Center Name can not be greater than 20 characters.";
                }
                if(strlen($center_code) > 20) {
                    $error[] = "Center Code can not be greater than 11 characters.";
                }
                if(strlen($address) > 255) {
                    $error[] = "Address can not be greater than 255 characters.";
                }
                #centers - fetch for duplicate
                $stmt1 = $conn->prepare("SELECT * FROM centers WHERE center_code=? AND id!=?");
                $stmt1->bind_param("si", $center_code, $center_id);
                $stmt1->execute();
                $res1 = $stmt1->get_result();
                $stmt1->close();
                if($res1->num_rows > 0) {
                    $error[] = "Center Code ({$center_code}) is already exists.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("UPDATE centers SET center_name=?, center_code=?, center_city=?, center_address=?, updated_by=?, center_status=? WHERE id=?");
                    $stmt->bind_param("siisssi", $center_name, $center_code, $center_city, $address, $user_data['id'], $center_status, $center_id);
                    
                    if($stmt->execute() === false) {
                        throw new Exception("Can't update centers. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Center is updated.';
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
