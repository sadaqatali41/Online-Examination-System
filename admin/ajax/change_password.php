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

        case "changePasswordFormSubmit":
            $old_password = filter_input(INPUT_POST, 'old_password', FILTER_SANITIZE_STRING);
            $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
            $minLength = 8;
            $maxLength = 20;

            if(empty($old_password)) {
                $error[] = "Old Password is required.";
            }
            if(empty($new_password)) {
                $error[] = "New Password is required.";
            }
            if(empty($error)) {
                $passwordLength = strlen($new_password);
                if($passwordLength < $minLength || $passwordLength > $maxLength) {
                    $error[] = "New Password length ({$passwordLength}) should be between {$minLength} & {$maxLength} characters.";
                }
            }
            if(empty($error)) {
                $stmt = $conn->prepare("SELECT al.password FROM admin_login al WHERE al.id=?");
                $stmt->bind_param("i", $user_data['id']);
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();
                if($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    if($row['password'] != $old_password) {
                        $error[] = "Old Password is invalid.";
                    } else if($new_password == $row['password']) {
                        $error[] = "Old Password & New Password can't be same.";
                    }
                } else {
                    $error[] = "Sorry! Record not Found.";
                }
            }
            
            if(empty($error)) {
                $stmt = $conn->prepare("UPDATE admin_login al SET al.password=? WHERE al.id=?");
                $stmt->bind_param("si", $new_password, $user_data['id']);
                if($stmt->execute()) {
                    $addRes['status'] = 'success';
                    $addRes['message'] = 'Success, Password is updated.';
                    #update session
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['login_string'] = hash('sha512', $new_password . $user_agent);
                }
                $stmt->close();
            } else {
                $addRes['status'] = 'error';
                $addRes['error'] = $error;
            }

            echo json_encode($addRes);
            break;
    }
}