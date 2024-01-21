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

        case "course_list":
            $draw = filter_var($_POST['draw'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $numRow = filter_var($_POST['start'], FILTER_VALIDATE_INT);
            $rowperpage = filter_var($_POST['length'], FILTER_VALIDATE_INT); // Rows display per page
            $columnIndex = filter_var($_POST['order'][0]['column'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column index
            $columnName = filter_var($_POST['columns'][$columnIndex]['data'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column name
            $columnSortOrder = filter_var($_POST['order'][0]['dir'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // asc or desc
            $searchValue = filter_var($_POST['search']['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Search value
            #custom filter
            $cc_id = filter_var($_POST['cc_id'], FILTER_VALIDATE_INT);
            $course_status = filter_var($_POST['course_status'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            ## Search
            $searchQuery = " ";
            if ($searchValue != '') {
                $searchQuery = " AND (c.course_name LIKE '%" . $searchValue . "%' OR c.course_code LIKE '%" . $searchValue . "%' OR cc.cc_name LIKE '%" . $searchValue . "%')";
            }
            if(isset($cc_id) && !empty($cc_id)) {
                $searchQuery .= " AND c.cc_id=" . $cc_id;
            }
            if(isset($course_status) && !empty($course_status)) {
                $searchQuery .= " AND c.course_status='". $course_status ."'";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(c.id) AS allcount FROM courses c INNER JOIN course_category cc ON cc.id=c.cc_id WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT c.*, cc.cc_name FROM courses c INNER JOIN course_category cc ON cc.id=c.cc_id WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id" => $row['id'],
                        "course_name" => $row['course_name'],
                        "course_code" => $row['course_code'],
                        "cc_id" => $row['cc_name'],
                        "course_status" => $row['course_status'],
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

        case "findCourseCat":
            $search = "";
            $resPerPage = 10;

            if (filter_has_var(INPUT_POST, "searchTerm")) {
                $searchTerm = strtoupper(filter_input(INPUT_POST, "searchTerm", FILTER_SANITIZE_STRING));
                if (isset($searchTerm) && !empty($searchTerm)) {
                    $search = " AND (cc.id LIKE '%$searchTerm%' OR cc.cc_name LIKE '%$searchTerm%')";
                }
            }
            $page = 1;
            if (filter_has_var(INPUT_POST, "page")) {
                $page = filter_input(INPUT_POST, "page", FILTER_VALIDATE_INT);
            }

            #total records without filter
            $st = $conn->prepare("SELECT cc.id, cc.cc_name FROM course_category cc WHERE cc.cc_status='A' $search");
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT cc.id, cc.cc_name FROM course_category cc WHERE cc.cc_status='A' $search LIMIT " . $numRow . ", " . $resPerPage . "");
            $st1->execute();
            $res2 = $st1->get_result();
            $num_row = $res2->num_rows;

            $json['items'] = [];
            if ($num_row > 0) {
                while ($row = $res2->fetch_assoc()) {
                    $json['items'][] = [
                        'id' => $row['id'],
                        'text' => $row['cc_name'],
                    ];
                }
            }
            $json["count_filtered"] = $tot_res;

            echo json_encode($json);
            break;

        case 'courseAddSubmit':
            $cc_id = filter_input(INPUT_POST, 'cc_id', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
            $course_code = filter_input(INPUT_POST, 'course_code', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

            $totInput = count($course_code) - 1;

            for($i = 0; $i <= $totInput; $i++) {
                if(empty($cc_id[$i])) {
                    $error[] = 'Course Category is required.';
                }
                if(empty($course_name[$i])) {
                    $error[] = 'Course Name is required.';
                }
                if(empty($course_code[$i])) {
                    $error[] = 'Course Code is required.';
                }
                if(empty($error)) {
                    if(strlen($course_name[$i]) > 30) {
                        $error[] = "Course Name can not be greater than 30 characters.";
                    }
                    if(strlen($course_code[$i]) > 11) {
                        $error[] = "Course Code can not be greater than 11 characters.";
                    }
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    #courses - insert
                    $stmt = $conn->prepare("INSERT INTO courses(course_name, course_code, cc_id, created_by) VALUES (?,?,?,?)");

                    #course - fetch for duplicate
                    $stmt1 = $conn->prepare("SELECT * FROM courses WHERE course_code=?");

                    for ($i = 0; $i <= $totInput; $i++) {
                        #courses - fetch
                        $stmt1->bind_param("s", $course_code[$i]);
                        if($stmt1->execute() === false) {
                            throw new Exception("Can't fetch courses. Reason : " . $stmt1->error);
                        }
                        $res1 = $stmt1->get_result();
                        if($res1->num_rows > 0) {
                            throw new Exception("Course Code ({$course_code[$i]}) is already exists.");
                        }
                        #courses - insert
                        $stmt->bind_param("siis", $course_name[$i], $course_code[$i], $cc_id[$i], $user_data['id']);
                        if($stmt->execute() === false) {
                            throw new Exception("Can't insert in courses. Reason : " . $stmt->error);
                        }
                    }
                    $stmt->close();
                    $stmt1->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Course is added.';
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

        case 'courseEditSubmit':
            $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            $cc_id = filter_input(INPUT_POST, 'cc_id', FILTER_VALIDATE_INT);
            $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);
            $course_code = filter_input(INPUT_POST, 'course_code', FILTER_VALIDATE_INT);
            $course_status = filter_input(INPUT_POST, 'course_status', FILTER_SANITIZE_STRING);

            if(empty($cc_id)) {
                $error[] = 'Course Category is required.';
            }
            if(empty($course_name)) {
                $error[] = 'Course Name is required.';
            }
            if(empty($course_code)) {
                $error[] = 'Course Code is required.';
            }
            if(empty($error)) {
                if(strlen($course_name) > 30) {
                    $error[] = "Course Name can not be greater than 30 characters.";
                }
                if(strlen($course_code) > 11) {
                    $error[] = "Course Code can not be greater than 11 characters.";
                }
                #courses - fetch for duplicate
                $stmt1 = $conn->prepare("SELECT * FROM courses WHERE course_code=? AND id!=?");
                $stmt1->bind_param("si", $course_code, $course_id);
                $stmt1->execute();
                $res1 = $stmt1->get_result();
                $stmt1->close();
                if($res1->num_rows > 0) {
                    $error[] = "Course Code ({$course_code}) is already exists.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("UPDATE courses SET course_name=?, course_code=?, cc_id=?, updated_by=?, course_status=? WHERE id=?");
                    $stmt->bind_param("ssissi", $course_name, $course_code, $cc_id, $user_data['id'], $course_status, $course_id);
                    
                    if($stmt->execute() === false) {
                        throw new Exception("Can't update courses. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Course is updated.';
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
