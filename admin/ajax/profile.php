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

        case "findCity":
            $search = "";
            $resPerPage = 10;

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
            $st = $conn->prepare("SELECT s.id, s.name FROM cities s WHERE 1=1 $search");
            $st->execute();
            $res = $st->get_result();
            $tot_res = $res->num_rows;

            $numRow = ($page - 1) * $resPerPage;

            $st1 = $conn->prepare("SELECT s.id, s.name FROM cities s WHERE 1=1 $search LIMIT " . $numRow . ", " . $resPerPage . "");
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
    }
}