<?php
include '../class/config.php';
include '../class/database.php';
include '../class/userAuth.php';
include '../class/helper.php';
$config = new Config();
$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$helper = new Helper();
$auth->sessionStart();
if ($auth->loginCheck($conn) === false) {
    header("Location: ../login.php");
}
$user_data = $_SESSION['user_data'];

if (filter_has_var(INPUT_POST, 'act') && filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING) !== null) {
    $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);

    switch ($act) {

        case "student_list":
            $draw = filter_var($_POST['draw'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            $numRow = filter_var($_POST['start'], FILTER_VALIDATE_INT);
            $rowperpage = filter_var($_POST['length'], FILTER_VALIDATE_INT); // Rows display per page
            $columnIndex = filter_var($_POST['order'][0]['column'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column index
            $columnName = filter_var($_POST['columns'][$columnIndex]['data'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Column name
            $columnSortOrder = filter_var($_POST['order'][0]['dir'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // asc or desc
            $searchValue = filter_var($_POST['search']['value'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH); // Search value
            #custom filter
            $course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);
            $center_id = filter_var($_POST['center_id'], FILTER_VALIDATE_INT);
            $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
            ## Search
            $searchQuery = " ";
            if ($searchValue != '') {
                $searchQuery = " AND (cs.course_name LIKE '%" . $searchValue . "%' OR ce.center_name LIKE '%" . $searchValue . "%' OR st.fname LIKE '%" . $searchValue . "%' OR st.lname LIKE '%" . $searchValue . "%' OR st.email LIKE '%" . $searchValue . "%' OR st.phone LIKE '%" . $searchValue . "%' OR st.address LIKE '%" . $searchValue . "%' OR c.name LIKE '%" . $searchValue . "%' OR s.name LIKE '%" . $searchValue . "%' OR ct.name LIKE '%" . $searchValue . "%')";
            }
            if(isset($course_id) && !empty($course_id)) {
                $searchQuery .= " AND st.course_id=" . $course_id;
            }
            if(isset($center_id) && !empty($center_id)) {
                $searchQuery .= " AND st.center_id=" . $center_id;
            }
            if($status !== '') {
                $searchQuery .= " AND st.status='". $status ."'";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(st.id) AS allcount FROM students st LEFT JOIN courses cs ON cs.id=st.course_id LEFT JOIN centers ce ON ce.id=st.center_id LEFT JOIN cities c ON c.id=st.city LEFT JOIN states s ON s.id=st.state LEFT JOIN countries ct ON ct.id=st.country LEFT JOIN results rs ON rs.student_id=st.id WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT st.id, CONCAT(st.fname, ' ', st.lname) AS full_name, st.email, st.password, st.phone, st.gender, st.course_id, cs.course_name, st.center_id, ce.center_name, st.city, st.pin, st.state, st.country, CONCAT(c.name, ',', s.name, ',', ct.name, ',', st.pin) AS p_address, st.address, st.avatar, st.status, rs.id AS result_id FROM students st LEFT JOIN courses cs ON cs.id=st.course_id LEFT JOIN centers ce ON ce.id=st.center_id LEFT JOIN cities c ON c.id=st.city LEFT JOIN states s ON s.id=st.state LEFT JOIN countries ct ON ct.id=st.country LEFT JOIN results rs ON rs.student_id=st.id WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = [
                        "id" => $row['id'],
                        "full_name" => $row['full_name'],
                        "email" => $row['email'],
                        "password" => $helper->passwordReplace($row['password']),
                        "phone" => $row['phone'],
                        "gender" => $row['gender'],
                        "course_name" => $row['course_name'],
                        "center_name" => $row['center_name'],
                        "p_address" => $row['p_address'],
                        "address" => $row['address'],
                        "avatar" => $row['avatar'],
                        "status" => $row['status'],
                        "result_id" => $row['result_id'],
                    ];
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
            $email = strtolower(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_VALIDATE_EMAIL)));
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
            if(!is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $error[] = "Avatar is required.";
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
            if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $filename = $_FILES["avatar"]["name"];
				$fileTempName = $_FILES["avatar"]["tmp_name"];
				$filetype = $_FILES["avatar"]["type"];
				$filesize = $_FILES["avatar"]["size"];
				$errorCode = $_FILES["avatar"]["error"];
                $maxsize = 3 * 1024 * 1024; #3MB
                $ext = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
                $maxWidth = 300;
                $maxHeight = 300;

                $allowedTypes = array(
					"JPG" => "image/JPG",
					"jpg" => "image/jpg",
					"jpeg" => "image/jpeg",					
					"png" => "image/png"
				);
                if (!in_array($filetype, $allowedTypes)) {
                    $error[] = "Invalid File type.";
                }
                if ($filesize > $maxsize) {
                    $error[] = "File size is larger than the 3MB.";
                }
				if (!array_key_exists($ext, $allowedTypes)) {
					$error[] = "Please select a valid file format.";
				}
                list($img_width, $img_height, $img_type, $img_attr) = getimagesize($fileTempName);
                if ($img_width > $maxWidth || $img_height > $maxHeight) {
                    $error[] = "Image should have maximum width of {$maxWidth}px and height of {$maxHeight}px.";
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
                    $stmt->close();

                    #profile upload
                    if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                        $targetPath = "../../students/" . $last_id;
                        if(!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $avatarName = 'avatar.' . $ext;
                        if (!move_uploaded_file($fileTempName, $targetPath . '/' . $avatarName)) {
							throw new Exception("Can't upload avatar. Reason : " . $errorCode);
						}
                        #update avatar
                        $st1 = $conn->prepare("UPDATE students SET avatar=? WHERE id=?");
                        $st1->bind_param("si", $avatarName, $last_id);

                        if($st1->execute() === false) {
                            throw new Exception("Can't update student. Reason : " . $st1->error);
                        }
                        $st1->close();
                    }

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

        case 'studentEditSubmit':
            #student info
            $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
            $cc_code = filter_input(INPUT_POST, 'cc_code', FILTER_SANITIZE_STRING);
            $fname = trim(filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING));
            $lname = trim(filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING));
            $email = strtolower(trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_VALIDATE_EMAIL)));
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

            #high school
            $school_name = filter_input(INPUT_POST, 'school_name', FILTER_SANITIZE_STRING);
            $board_name = filter_input(INPUT_POST, 'board_name', FILTER_SANITIZE_STRING);
            $roll_no = filter_input(INPUT_POST, 'roll_no', FILTER_VALIDATE_INT);
            $yop = filter_input(INPUT_POST, 'yop', FILTER_VALIDATE_INT);
            $percent = filter_input(INPUT_POST, 'percent', FILTER_VALIDATE_INT);

            #intermediate
            $college_name = filter_input(INPUT_POST, 'college_name', FILTER_SANITIZE_STRING);
            $im_board_name = filter_input(INPUT_POST, 'im_board_name', FILTER_SANITIZE_STRING);
            $im_roll_no = filter_input(INPUT_POST, 'im_roll_no', FILTER_VALIDATE_INT);
            $im_yop = filter_input(INPUT_POST, 'im_yop', FILTER_VALIDATE_INT);
            $im_percent = filter_input(INPUT_POST, 'im_percent', FILTER_VALIDATE_INT);

            #graduation
            $institute_name = filter_input(INPUT_POST, 'institute_name', FILTER_SANITIZE_STRING);
            $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);
            $branch_name = filter_input(INPUT_POST, 'branch_name', FILTER_SANITIZE_STRING);
            $enroll_no = filter_input(INPUT_POST, 'enroll_no', FILTER_VALIDATE_INT);
            $gd_yop = filter_input(INPUT_POST, 'gd_yop', FILTER_VALIDATE_INT);
            $aggregate_percent = filter_input(INPUT_POST, 'aggregate_percent', FILTER_VALIDATE_INT);

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
            #high school validation
            if(empty($error)) {
                if(empty($school_name)) {
                    $error[] = "High School : School Name is required.";
                }
                if(empty($board_name)) {
                    $error[] = "High School : Board Name is required.";
                }
                if(empty($roll_no)) {
                    $error[] = "High School : Roll No is required.";
                }
                if(empty($yop)) {
                    $error[] = "High School : Year of Passing is required.";
                }
                if(empty($percent)) {
                    $error[] = "High School : Passing % is required.";
                }
                if($percent >= 100) {
                    $error[] = "High School : Passing % can not be greater or equal to 100.";
                }
            }
            #intermediate validation
            if(empty($error)) {
                if(empty($college_name)) {
                    $error[] = "Intermediate : College Name is required.";
                }
                if(empty($im_board_name)) {
                    $error[] = "Intermediate : Board Name is required.";
                }
                if(empty($im_roll_no)) {
                    $error[] = "Intermediate : Roll No is required.";
                }
                if(empty($im_yop)) {
                    $error[] = "Intermediate : Year of Passing is required.";
                }
                if($im_yop < ($yop + 2)) {
                    $error[] = "Intermediate : Year of Passing {$im_yop} should be greater than the High School Year of Passing {$yop} by 2.";
                }
                if(empty($im_percent)) {
                    $error[] = "Intermediate : Passing % is required.";
                }
                if($im_percent >= 100) {
                    $error[] = "Intermediate : Passing % can not be greater or equal to 100.";
                }
            }
            #graduation validation
            if(empty($error) && $cc_code !== 'UG') {
                if(empty($institute_name)) {
                    $error[] = "Graduation : Institute Name is required.";
                }
                if(empty($course_name)) {
                    $error[] = "Graduation : Course Name is required.";
                }
                if(empty($branch_name)) {
                    $error[] = "Graduation : Branch Name is required.";
                }
                if(empty($enroll_no)) {
                    $error[] = "Graduation : Enroll No is required.";
                }
                if(empty($gd_yop)) {
                    $error[] = "Graduation : Year of Passing is required.";
                }
                if($gd_yop < ($im_yop + 3)) {
                    $error[] = "Graduation : Year of Passing {$gd_yop} should be greater than the Intermediate Year of Passing {$im_yop} by 3.";
                }
                if(empty($aggregate_percent)) {
                    $error[] = "Graduation : Passing % is required.";
                }
                if($aggregate_percent >= 100) {
                    $error[] = "Graduation : Passing % can not be greater or equal to 100.";
                }
            }
            if(empty($error)) {
                #high school intermediate and graduation
                $sthig = $conn->prepare("SELECT hs.student_id, hs.roll_no, 'High School' AS school FROM highschool hs WHERE hs.student_id!=? AND hs.roll_no=?
                UNION ALL
                SELECT im.student_id, im.roll_no, 'Intermediate' AS school FROM intermediate im WHERE im.student_id!=? AND im.roll_no=?
                UNION ALL
                SELECT gd.student_id, gd.enroll_no AS roll_no, 'Graduation' AS school FROM graduation gd WHERE gd.student_id!=? AND gd.enroll_no=?");
                $sthig->bind_param("iiiiii", $student_id, $roll_no, $student_id, $im_roll_no, $student_id, $enroll_no);
                $sthig->execute();
                $reshig = $sthig->get_result();
                $sthig->close();
                while($rowhig = $reshig->fetch_assoc()) {
                    $error[] = "This Roll No {$rowhig['roll_no']} is already taken in {$rowhig['school']}";
                }
            }
            if(empty($error)) {
                $stck = $conn->prepare("SELECT s.email, s.phone FROM students s WHERE s.id !=? AND (s.email=? OR s.phone=?)");
                $stck->bind_param("iss", $student_id, $email, $mobile);
                $stck->execute();
                $resck = $stck->get_result();
                $stck->close();
                if($resck->num_rows > 0) {
                    $error[] = "Email : {$email} OR Mobile : {$mobile} exists. Please choose another one.";
                }
            }
            if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $filename = $_FILES["avatar"]["name"];
				$fileTempName = $_FILES["avatar"]["tmp_name"];
				$filetype = $_FILES["avatar"]["type"];
				$filesize = $_FILES["avatar"]["size"];
				$errorCode = $_FILES["avatar"]["error"];
                $maxsize = 3 * 1024 * 1024; #3MB
                $ext = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
                $maxWidth = 300;
                $maxHeight = 300;

                $allowedTypes = array(
					"JPG" => "image/JPG",
					"jpg" => "image/jpg",
					"jpeg" => "image/jpeg",					
					"png" => "image/png"
				);
                if (!in_array($filetype, $allowedTypes)) {
                    $error[] = "Invalid File type.";
                }
                if ($filesize > $maxsize) {
                    $error[] = "File size is larger than the 3MB.";
                }
				if (!array_key_exists($ext, $allowedTypes)) {
					$error[] = "Please select a valid file format.";
				}
                list($img_width, $img_height, $img_type, $img_attr) = getimagesize($fileTempName);
                if ($img_width > $maxWidth || $img_height > $maxHeight) {
                    $error[] = "Image should have maximum width of {$maxWidth}px and height of {$maxHeight}px.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    $stmt = $conn->prepare("UPDATE students SET fname=?, lname=?, email=?, password=?, gender=?, dob=?, phone=?, course_id=?, center_id=?, country=?, state=?, city=?, pin=?, address=?, status=?, updated_by=? WHERE id=?");
                    $stmt->bind_param("sssssssiiiiiisisi", $fname, $lname, $email, $password, $gender, $dob, $mobile, $course_id, $center_id, $country, $state, $city, $pin, $address, $status, $user_data['id'], $student_id);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't update student. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    #profile upload
                    if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                        $targetPath = "../../students/" . $student_id;
                        if(!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $avatarName = 'avatar.' . $ext;
                        if (!move_uploaded_file($fileTempName, $targetPath . '/' . $avatarName)) {
							throw new Exception("Can't upload avatar. Reason : " . $errorCode);
						}
                        #update avatar
                        $st1 = $conn->prepare("UPDATE students SET avatar=? WHERE id=?");
                        $st1->bind_param("si", $avatarName, $student_id);

                        if($st1->execute() === false) {
                            throw new Exception("Can't update student. Reason : " . $st1->error);
                        }
                        $st1->close();
                    }

                    #high school - fetch
                    $sthsf = $conn->prepare("SELECT hs.id FROM highschool hs WHERE hs.student_id=?");
                    $sthsf->bind_param("i", $student_id);

                    if($sthsf->execute() === false) {
                        throw new Exception("Can't fetch High School Info. Reason : " . $sthsf->error);
                    }
                    $reshsf = $sthsf->get_result();
                    $sthsf->close();

                    #high school - update
                    $sthsu = $conn->prepare("UPDATE highschool SET roll_no=?, school_name=?, board_name=?, yop=?, percent=?, updated_by=? WHERE student_id=?");

                    #high school - insert
                    $sthsi = $conn->prepare("INSERT INTO highschool(student_id, roll_no, school_name, board_name, yop, percent, created_by) VALUES (?,?,?,?,?,?,?)");

                    if($reshsf->num_rows > 0) {
                        $sthsu->bind_param("issiiii", $roll_no, $school_name, $board_name, $yop, $percent, $user_data['id'], $student_id);
                        if($sthsu->execute() === false) {
                            throw new Exception("Can't update High School Info. Reason : " . $sthsu->error);
                        }
                    } else {
                        $sthsi->bind_param("iissiii", $student_id, $roll_no, $school_name, $board_name, $yop, $percent, $user_data['id']);
                        if($sthsi->execute() === false) {
                            throw new Exception("Can't insert in High School. Reason : " . $sthsi->error);
                        }
                    }
                    $sthsu->close();
                    $sthsi->close();

                    #intermediate - fetch
                    $stimf = $conn->prepare("SELECT im.id FROM `intermediate` im WHERE im.student_id=?");
                    $stimf->bind_param("i", $student_id);

                    if($stimf->execute() === false) {
                        throw new Exception("Can't fetch Intermediate Info. Reason : " . $stimf->error);
                    }
                    $resimf = $stimf->get_result();
                    $stimf->close();

                    #intermediate - update
                    $stimu = $conn->prepare("UPDATE `intermediate` SET roll_no=?, college_name=?, board_name=?, yop=?, percent=?, updated_by=? WHERE student_id=?");

                    #intermediate - insert
                    ($stimi = $conn->prepare("INSERT INTO `intermediate`(student_id, roll_no, college_name, board_name, yop, percent, created_by) VALUES (?,?,?,?,?,?,?)")) ? $stimi : throw new Exception($conn->error);

                    if($resimf->num_rows > 0) {
                        $stimu->bind_param("issiiii", $im_roll_no, $college_name, $im_board_name, $im_yop, $im_percent, $user_data['id'], $student_id);
                        if($stimu->execute() === false) {
                            throw new Exception("Can't update Intermediate Info. Reason : " . $stimu->error);
                        }
                    } else {
                        $stimi->bind_param("iissiii", $student_id, $im_roll_no, $college_name, $im_board_name, $im_yop, $im_percent, $user_data['id']);
                        if($stimi->execute() === false) {
                            throw new Exception("Can't insert in Intermediate. Reason : " . $stimi->error);
                        }
                    }
                    $stimu->close();
                    $stimi->close();

                    if($cc_code !== 'UG') {
                        #graduation - fetch
                        $stgdf = $conn->prepare("SELECT gd.id FROM graduation gd WHERE gd.student_id=?");
                        $stgdf->bind_param("i", $student_id);

                        if($stgdf->execute() === false) {
                            throw new Exception("Can't fetch Graduation Info. Reason : " . $stgdf->error);
                        }
                        $resgdf = $stgdf->get_result();
                        $stgdf->close();

                        #graduation - update
                        $stgdu = $conn->prepare("UPDATE graduation SET enroll_no=?, institute_name=?, branch_name=?, yop=?, aggregate_percent=?, course_name=?, updated_by=? WHERE student_id=?");

                        #graduation - insert
                        $stgdi = $conn->prepare("INSERT INTO graduation(student_id, enroll_no, institute_name, branch_name, yop, aggregate_percent, course_name, created_by) VALUES (?,?,?,?,?,?,?,?)");

                        if($resgdf->num_rows > 0) {
                            $stgdu->bind_param("issiisii", $enroll_no, $institute_name, $branch_name, $gd_yop, $aggregate_percent, $course_name, $user_data['id'], $student_id);
                            if($stgdu->execute() === false) {
                                throw new Exception("Can't update Graduation Info. Reason : " . $stgdu->error);
                            }
                        } else {
                            $stgdi->bind_param("iissiisi", $student_id, $enroll_no, $institute_name, $branch_name, $gd_yop, $aggregate_percent, $course_name, $user_data['id']);
                            if($stgdi->execute() === false) {
                                throw new Exception("Can't insert in Graduation. Reason : " . $stgdi->error);
                            }
                        }
                        $stgdu->close();
                        $stgdi->close();
                    }

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Student is updated.';
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
