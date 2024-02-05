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

        case "about_us_list":
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
                $searchQuery = " AND (al.course_name LIKE '%" . $searchValue . "%' OR al.name LIKE '%" . $searchValue . "%' OR al.email LIKE '%" . $searchValue . "%' OR al.mobile_no LIKE '%" . $searchValue . "%' OR al.branch_name LIKE '%" . $searchValue . "%' OR al.institute_name LIKE '%" . $searchValue . "%' OR c.name LIKE '%" . $searchValue . "%')";
            }

            ## Total number of records without filtering
            $stmt = $conn->prepare("SELECT COUNT(al.id) AS allcount FROM admin_login al LEFT JOIN cities c ON c.id=al.city WHERE 1=1" . $searchQuery);
            $stmt->execute();
            $res = $stmt->get_result();
            $records = $res->fetch_assoc();
            $totalRecords = $records['allcount'];

            ## Total number of record with filtering
            $totalRecordwithFilter = $totalRecords;

            ## Pagination query
            $stmt2 = $conn->prepare("SELECT al.*, c.name AS city_name FROM admin_login al LEFT JOIN cities c ON c.id=al.city WHERE 1=1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $numRow . ", " . $rowperpage . "");
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            $row1 = array();
            if ($res2->num_rows > 0) {

                while ($row = $res2->fetch_assoc()) {

                    $row1[] = array(
                        "id"                => $row['id'],
                        "name"              => $row['name'],
                        "email"             => $row['email'],
                        "mobile_no"         => $row['mobile_no'],
                        "course_name"       => $row['course_name'],
                        "branch_name"       => $row['branch_name'],
                        "institute_name"    => $row['institute_name'],
                        "city"              => $row['city_name'],
                        "blog_website"      => $row['blog_website'],
                        "status"            => $row['status'],
                        "profile_pic"       => $row['profile_pic'],
                        "user_id"           => $user_data['id']
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