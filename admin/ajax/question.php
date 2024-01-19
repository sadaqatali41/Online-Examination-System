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

        case "question_list":
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
                $searchQuery = " AND (cc.course_name LIKE '%" . $searchValue . "%' OR q.question_name LIKE '%" . $searchValue . "%')";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(q.id) AS allcount FROM questions q INNER JOIN courses cc ON cc.id=q.course_id WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT q.*, cc.course_name FROM questions q INNER JOIN courses cc ON cc.id=q.course_id WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id" => $row['id'],
                        "course_id" => $row['course_name'],
                        "question_name" => $row['question_name'],
                        "optionA" => $row['optionA'],
                        "optionB" => $row['optionB'],
                        "optionC" => $row['optionC'],
                        "optionD" => $row['optionD'],
                        "correct_option" => $row['correct_option'],
                        "question_status" => $row['question_status'],
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

        case 'questionAddSubmit':
            $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            $question_name = filter_input(INPUT_POST, 'question_name', FILTER_SANITIZE_STRING);
            $optionA = filter_input(INPUT_POST, 'optionA', FILTER_SANITIZE_STRING);
            $optionB = filter_input(INPUT_POST, 'optionB', FILTER_SANITIZE_STRING);
            $optionC = filter_input(INPUT_POST, 'optionC', FILTER_SANITIZE_STRING);
            $optionD = filter_input(INPUT_POST, 'optionD', FILTER_SANITIZE_STRING);
            $correct_option = filter_input(INPUT_POST, 'correct_option', FILTER_SANITIZE_STRING);

            if(empty($course_id)) {
                $error[] = 'Course Name is required.';
            }
            if(empty($question_name)) {
                $error[] = 'Question Name is required.';
            }
            if(empty($optionA)) {
                $error[] = 'Option A is required.';
            }
            if(empty($optionB)) {
                $error[] = 'Option B is required.';
            }
            if(empty($optionC)) {
                $error[] = 'Option C is required.';
            }
            if(empty($optionD)) {
                $error[] = 'Option D is required.';
            }
            if(empty($correct_option)) {
                $error[] = 'Correct Option is required.';
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("INSERT INTO questions(course_id, question_name, optionA, optionB, optionC, optionD, correct_option, created_by) VALUES (?,?,?,?,?,?,?,?)");
                    $stmt->bind_param("issssssi", $course_id, $question_name, $optionA, $optionB, $optionC, $optionD, $correct_option, $user_data['id']);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't insert in questions. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Question is added.';
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
