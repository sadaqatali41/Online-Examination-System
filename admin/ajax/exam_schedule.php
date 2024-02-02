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

        case "exam_schedule_list":
            $draw = filter_var($_POST['draw'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $numRow = filter_var($_POST['start'], FILTER_VALIDATE_INT);
            $rowperpage = filter_var($_POST['length'], FILTER_VALIDATE_INT); // Rows display per page
            $columnIndex = filter_var($_POST['order'][0]['column'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column index
            $columnName = filter_var($_POST['columns'][$columnIndex]['data'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column name
            $columnSortOrder = filter_var($_POST['order'][0]['dir'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // asc or desc
            $searchValue = filter_var($_POST['search']['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Search value
            #custom filter
            $course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);
            $es_status = filter_var($_POST['es_status'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            ## Search
            $searchQuery = " ";
            if ($searchValue != '') {
                $searchQuery = " AND (cc.course_name LIKE '%" . $searchValue . "%' OR ec.eligibility_criteria LIKE '%" . $searchValue . "%')";
            }
            if(isset($course_id) && !empty($course_id)) {
                $searchQuery .= " AND es.course_id=" . $course_id;
            }
            if(isset($es_status) && !empty($es_status)) {
                $searchQuery .= " AND es.es_status='". $es_status ."'";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(es.id) AS allcount FROM exam_schedule es INNER JOIN courses cc ON cc.id=es.course_id WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT es.*, cc.course_name FROM exam_schedule es INNER JOIN courses cc ON cc.id=es.course_id WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id" => $row['id'],
                        "course_name" => $row['course_name'],
                        "for_year" => $row['for_year'],
                        "regis_last_date" => (new DateTime($row['regis_last_date']))->format('d M Y h:i A'),
                        "exam_date" => (new DateTime($row['exam_date']))->format('d M Y'),
                        "start_time" => (new DateTime($row['start_time']))->format('h:i A'),
                        "end_time" => (new DateTime($row['end_time']))->format('h:i A'),
                        "es_status" => $row['es_status'],
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

        case 'examScheduleAddSubmit':
            $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            $regis_last_date = trim(filter_input(INPUT_POST, 'regis_last_date', FILTER_SANITIZE_STRING));
            $exam_date = trim(filter_input(INPUT_POST, 'exam_date', FILTER_SANITIZE_STRING));
            $start_time = trim(filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING));
            $end_time = trim(filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING));
            $for_year = date('Y');

            if(empty($course_id)) {
                $error[] = 'Course Name is required.';
            }
            if(empty($regis_last_date)) {
                $error[] = 'Last Registration Date is required.';
            }
            if(empty($exam_date)) {
                $error[] = 'Exam Date is required.';
            }
            if(empty($start_time)) {
                $error[] = 'Start Time is required.';
            }
            if(empty($end_time)) {
                $error[] = 'End Time is required.';
            }
            if(empty($error)) {
                $ed = new DateTime($exam_date);
                $rd = new DateTime($regis_last_date);
                $diff = $rd->diff($ed);
                $diffInDays = $diff->days;
                if($diffInDays < 5) {
                    $error[] = "Exam Date {$exam_date} should be 5 days greater than the Last Registration Date {$regis_last_date}";
                }
            }
            if(empty($error)) {
                $t1 = new DateTime('2024-01-01 ' . $start_time);
                $t2 = new DateTime('2024-01-01 ' . $end_time);
                $interval = $t1->diff($t2);
                $diffInHours = $interval->h + ($interval->i / 60);
                if($diffInHours < 2) {
                    $error[] = "Minimum Diff. between Exam Start Time & Exam End Time should be 2 Hours.";
                }
            }
            if(empty($error)) {
                $start_time = (new DateTime($start_time))->format('H:i:s');
                $end_time = (new DateTime($end_time))->format('H:i:s');
            }
            if(empty($error)) {
                $stck = $conn->prepare("SELECT es.id FROM exam_schedule es WHERE es.course_id=? AND es.for_year=?");
                $stck->bind_param("ii", $course_id, $for_year);
                $stck->execute();
                $resck = $stck->get_result();
                $stck->close();
                if($resck->num_rows > 0) {
                    $error[] = "Exam Schedule is already added for selected course. Please `Edit` it.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("INSERT INTO exam_schedule(course_id, for_year, regis_last_date, exam_date, start_time, end_time, created_by) VALUES (?,?,?,?,?,?,?)");
                    $stmt->bind_param("iissssi", $course_id, $for_year, $regis_last_date, $exam_date, $start_time, $end_time, $user_data['id']);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't insert in exam schedule. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Exam Schedule is added.';
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

        case 'examScheduleEditSubmit':
            $es_id = filter_input(INPUT_POST, 'es_id', FILTER_VALIDATE_INT);
            $course_id = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            $regis_last_date = trim(filter_input(INPUT_POST, 'regis_last_date', FILTER_SANITIZE_STRING));
            $exam_date = trim(filter_input(INPUT_POST, 'exam_date', FILTER_SANITIZE_STRING));
            $start_time = trim(filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING));
            $end_time = trim(filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING));
            $es_status = trim(filter_input(INPUT_POST, 'es_status', FILTER_SANITIZE_STRING));
            $for_year = date('Y');

            if(empty($course_id)) {
                $error[] = 'Course Name is required.';
            }
            if(empty($regis_last_date)) {
                $error[] = 'Last Registration Date is required.';
            }
            if(empty($exam_date)) {
                $error[] = 'Exam Date is required.';
            }
            if(empty($start_time)) {
                $error[] = 'Start Time is required.';
            }
            if(empty($end_time)) {
                $error[] = 'End Time is required.';
            }
            if(empty($error)) {
                $ed = new DateTime($exam_date);
                $rd = new DateTime($regis_last_date);
                $diff = $rd->diff($ed);
                $diffInDays = $diff->days;
                if($diffInDays < 5) {
                    $error[] = "Exam Date {$exam_date} should be 5 days greater than the Last Registration Date {$regis_last_date}";
                }
            }
            if(empty($error)) {
                $t1 = new DateTime('2024-01-01 ' . $start_time);
                $t2 = new DateTime('2024-01-01 ' . $end_time);
                $interval = $t1->diff($t2);
                $diffInHours = $interval->h + ($interval->i / 60);
                if($diffInHours < 2) {
                    $error[] = "Minimum Diff. between Exam Start Time & Exam End Time should be 2 Hours.";
                }
            }
            if(empty($error)) {
                $start_time = (new DateTime($start_time))->format('H:i:s');
                $end_time = (new DateTime($end_time))->format('H:i:s');
            }
            if(empty($error)) {
                $stck = $conn->prepare("SELECT es.id FROM exam_schedule es WHERE es.course_id=? AND es.for_year=? AND es.id <> ?");
                $stck->bind_param("iii", $course_id, $for_year, $es_id);
                $stck->execute();
                $resck = $stck->get_result();
                $stck->close();
                if($resck->num_rows > 0) {
                    $error[] = "Exam Schedule is already added for selected course. Please `Edit` it.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("UPDATE exam_schedule SET course_id=?, for_year=?, regis_last_date=?, exam_date=?, start_time=?, end_time=?, updated_by=?, es_status=? WHERE id=?");
                    $stmt->bind_param("iissssisi", $course_id, $for_year, $regis_last_date, $exam_date, $start_time, $end_time, $user_data['id'], $es_status, $es_id);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't updated exam schedule. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Exam Schedule is updated.';
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
