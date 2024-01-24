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

        case "eligibility_criteria_list":
            $draw = filter_var($_POST['draw'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $numRow = filter_var($_POST['start'], FILTER_VALIDATE_INT);
            $rowperpage = filter_var($_POST['length'], FILTER_VALIDATE_INT); // Rows display per page
            $columnIndex = filter_var($_POST['order'][0]['column'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column index
            $columnName = filter_var($_POST['columns'][$columnIndex]['data'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column name
            $columnSortOrder = filter_var($_POST['order'][0]['dir'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // asc or desc
            $searchValue = filter_var($_POST['search']['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Search value
            #custom filter
            $course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);
            $ec_status = filter_var($_POST['ec_status'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            ## Search
            $searchQuery = " ";
            if ($searchValue != '') {
                $searchQuery = " AND (cc.course_name LIKE '%" . $searchValue . "%' OR ec.eligibility_criteria LIKE '%" . $searchValue . "%')";
            }
            if(isset($course_id) && !empty($course_id)) {
                $searchQuery .= " AND ec.course_id=" . $course_id;
            }
            if(isset($ec_status) && !empty($ec_status)) {
                $searchQuery .= " AND ec.ec_status='". $ec_status ."'";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(ec.id) AS allcount FROM eligibility_criteria ec INNER JOIN courses cc ON cc.id=ec.course_id WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT ec.*, cc.course_name FROM eligibility_criteria ec INNER JOIN courses cc ON cc.id=ec.course_id WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id" => $row['id'],
                        "course_id" => $row['course_name'],
                        "eligibility_criteria" => $row['eligibility_criteria'],
                        "ec_status" => $row['ec_status'],
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

        case "findCountry":
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
            $st = $conn->prepare("SELECT c.id, c.name, c.phonecode FROM countries c WHERE 1=1 $search");
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT c.id, c.name, c.phonecode FROM countries c WHERE 1=1 $search LIMIT " . $numRow . ", " . $resPerPage . "");
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

        case "findState":
            $search = "";
            $resPerPage = 10;
            $country = filter_input(INPUT_POST, 'country', FILTER_VALIDATE_INT);

            if (filter_has_var(INPUT_POST, "searchTerm")) {
                $searchTerm = strtoupper(filter_input(INPUT_POST, "searchTerm", FILTER_SANITIZE_STRING));
                if (isset($searchTerm) && !empty($searchTerm)) {
                    $search = " AND (s.id LIKE '%$searchTerm%' OR s.name LIKE '%$searchTerm%')";
                }
            }
            $page = 1;
            if (filter_has_var(INPUT_POST, "page")) {
                $page = filter_input(INPUT_POST, "page", FILTER_VALIDATE_INT);
            }

            #total records without filter
            $st = $conn->prepare("SELECT s.id, s.name FROM states s WHERE s.country_id=? $search");
            $st->bind_param("i", $country);
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT s.id, s.name FROM states s WHERE s.country_id=? $search LIMIT " . $numRow . ", " . $resPerPage . "");
            $st1->bind_param("i", $country);
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

        case "findCity":
            $search = "";
            $resPerPage = 10;
            $state = filter_input(INPUT_POST, 'state', FILTER_VALIDATE_INT);

            if (filter_has_var(INPUT_POST, "searchTerm")) {
                $searchTerm = strtoupper(filter_input(INPUT_POST, "searchTerm", FILTER_SANITIZE_STRING));
                if (isset($searchTerm) && !empty($searchTerm)) {
                    $search = " AND (s.id LIKE '%$searchTerm%' OR s.name LIKE '%$searchTerm%')";
                }
            }
            $page = 1;
            if (filter_has_var(INPUT_POST, "page")) {
                $page = filter_input(INPUT_POST, "page", FILTER_VALIDATE_INT);
            }

            #total records without filter
            $st = $conn->prepare("SELECT s.id, s.name FROM cities s WHERE s.state_id=? $search");
            $st->bind_param("i", $state);
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT s.id, s.name FROM cities s WHERE s.state_id=? $search LIMIT " . $numRow . ", " . $resPerPage . "");
            $st1->bind_param("i", $state);
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

        case "findCourse":
            $search = "";
            $resPerPage = 10;

            if (filter_has_var(INPUT_POST, "searchTerm")) {
                $searchTerm = strtoupper(filter_input(INPUT_POST, "searchTerm", FILTER_SANITIZE_STRING));
                if (isset($searchTerm) && !empty($searchTerm)) {
                    $search = " AND (cc.id LIKE '%$searchTerm%' OR cc.course_name LIKE '%$searchTerm%')";
                }
            }
            $page = 1;
            if (filter_has_var(INPUT_POST, "page")) {
                $page = filter_input(INPUT_POST, "page", FILTER_VALIDATE_INT);
            }

            #total records without filter
            $st = $conn->prepare("SELECT cc.id, cc.course_name FROM courses cc WHERE cc.course_status='A' $search");
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT cc.id, cc.course_name FROM courses cc WHERE cc.course_status='A' $search LIMIT " . $numRow . ", " . $resPerPage . "");
            $st1->execute();
            $res2 = $st1->get_result();
            $num_row = $res2->num_rows;

            $json['items'] = [];
            if ($num_row > 0) {
                while ($row = $res2->fetch_assoc()) {
                    $json['items'][] = [
                        'id' => $row['id'],
                        'text' => $row['course_name'],
                    ];
                }
            }
            $json["count_filtered"] = $tot_res;

            echo json_encode($json);
            break;

        case "findCenter":
            $search = "";
            $resPerPage = 10;

            if (filter_has_var(INPUT_POST, "searchTerm")) {
                $searchTerm = strtoupper(filter_input(INPUT_POST, "searchTerm", FILTER_SANITIZE_STRING));
                if (isset($searchTerm) && !empty($searchTerm)) {
                    $search = " AND (cc.id LIKE '%$searchTerm%' OR cc.center_name LIKE '%$searchTerm%')";
                }
            }
            $page = 1;
            if (filter_has_var(INPUT_POST, "page")) {
                $page = filter_input(INPUT_POST, "page", FILTER_VALIDATE_INT);
            }

            #total records without filter
            $st = $conn->prepare("SELECT cc.id, cc.center_name FROM centers cc WHERE cc.center_status='A' $search");
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT cc.id, cc.center_name FROM centers cc WHERE cc.center_status='A' $search LIMIT " . $numRow . ", " . $resPerPage . "");
            $st1->execute();
            $res2 = $st1->get_result();
            $num_row = $res2->num_rows;

            $json['items'] = [];
            if ($num_row > 0) {
                while ($row = $res2->fetch_assoc()) {
                    $json['items'][] = [
                        'id' => $row['id'],
                        'text' => $row['center_name'],
                    ];
                }
            }
            $json["count_filtered"] = $tot_res;

            echo json_encode($json);
            break;

        case 'studentAddSubmit':
            $fname = trim(filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING));
            $lname = trim(filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING));
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_VALIDATE_EMAIL));
            $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
            $mobile = trim(filter_input(INPUT_POST, 'mobile', FILTER_SANITIZE_STRING));
            $country = filter_input(INPUT_POST, 'country', FILTER_VALIDATE_INT);
            $state = filter_input(INPUT_POST, 'state', FILTER_VALIDATE_INT);
            $city = filter_input(INPUT_POST, 'city', FILTER_VALIDATE_INT);
            $pin = filter_input(INPUT_POST, 'pin', FILTER_VALIDATE_INT);
            $dob = trim(filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING));
            $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING));
            $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            $center_id = filter_input(INPUT_POST, 'center_id', FILTER_VALIDATE_INT);
            $gender = trim(filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING));
            $status = trim(filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING));

            if(empty($fname)) {
                $error[] = 'First Name is required.';
            }
            if(empty($lname)) {
                $error[] = 'Last Name is required.';
            }
            if(empty($email)) {
                $error[] = 'Email is required.';
            }
            if(empty($password)) {
                $error[] = 'Password is required.';
            } else if(strlen($password) < 8) {
                $error[] = "Password should be atleast 8 chars.";
            }
            if(empty($mobile)) {
                $error[] = 'Mobile No. is required.';
            }
            if(empty($country)) {
                $error[] = 'Country is required.';
            }
            if(empty($state)) {
                $error[] = 'State is required.';
            }
            if(empty($city)) {
                $error[] = 'City is required.';
            }
            if(empty($pin)) {
                $error[] = 'Pin No. is required.';
            }
            if(empty($dob)) {
                $error[] = 'DOB is required.';
            }
            if(empty($address)) {
                $error[] = 'Address is required.';
            }
            if(empty($course_id)) {
                $error[] = 'Course is required.';
            }
            if(empty($center_id)) {
                $error[] = 'Center is required.';
            }
            if($gender === '') {
                $error[] = 'Gender is required.';
            }

            if(empty($error)) {
                $stck = $conn->prepare("SELECT s.email, s.phone FROM students s WHERE s.email=? OR s.phone=?");
                $stck->bind_param("ss", $email, $mobile);
                $stck->execute();
                $resck = $stck->get_result();
                $stck->close();
                if($resck->num_rows > 0) {
                    $error[] = "Email : {$email} OR Mobile : {$mobile} exists. Please choose another one.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("INSERT INTO students(fname, lname, email, password, gender, dob, phone, course_id, center_id, country, state, city, pin, address, status, created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("sssssssiiiiiisis", $fname, $lname, $email, $password, $gender, $dob, $mobile, $course_id, $center_id, $country, $state, $city, $pin, $address, $status, $user_data['id']);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't insert in student. Reason : " . $stmt->error);
                    }
                    $last_id = $stmt->insert_id;
                    #profile upload
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Student is added.';
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

        case 'ecEditSubmit':
            $ec_id = filter_input(INPUT_POST, 'ec_id', FILTER_VALIDATE_INT);
            $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            $eligibility_criteria = trim(filter_input(INPUT_POST, 'eligibility_criteria', FILTER_SANITIZE_STRING));
            $ec_status = trim(filter_input(INPUT_POST, 'ec_status', FILTER_SANITIZE_STRING));

            if(empty($course_id)) {
                $error[] = 'Course Name is required.';
            }
            if(empty($eligibility_criteria)) {
                $error[] = 'Eligibility Criteria is required.';
            }
            if(empty($error)) {
                $stck = $conn->prepare("SELECT ec.course_id, ec.eligibility_criteria FROM eligibility_criteria ec WHERE ec.course_id=? AND id!=?");
                $stck->bind_param("ii", $course_id, $ec_id);
                $stck->execute();
                $resck = $stck->get_result();
                $stck->close();
                if($resck->num_rows > 0) {
                    $error[] = "Criteria is already added for selected course. Please `Edit` it.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("UPDATE eligibility_criteria SET course_id=?, eligibility_criteria=?, updated_by=?, ec_status=? WHERE id=?");
                    $stmt->bind_param("isisi", $course_id, $eligibility_criteria, $user_data['id'], $ec_status, $ec_id);
                    
                    if($stmt->execute() === false) {
                        throw new Exception("Can't update eligibility criteria. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Eligibility Criteria is updated.';
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
