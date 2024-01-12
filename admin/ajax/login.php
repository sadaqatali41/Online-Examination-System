<?php
include '../class/database.php';
include '../class/userAuth.php';

$db = new Database();
$conn = $db->connectDB();
$auth = new UserAuth();
$auth->sessionStart();

if(filter_has_var(INPUT_POST, 'act') && filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING) !== null) {
    $act = filter_input(INPUT_POST, 'act', FILTER_SANITIZE_STRING);

    switch ($act) {

        case 'loginSubmit':
            $email = strtolower(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL));
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

            if(empty($email)) {
                $error[] = "Email is required.";
            }
            if(empty($password)) {
                $error[] = "Password is required.";
            }

            #email check
            if(empty($error)) {
                $stal = $conn->prepare("SELECT * FROM admin_login al WHERE al.email=?");
                $stal->bind_param("s", $email);
                $stal->execute();
                $resal = $stal->get_result();
                $stal->close();
                if($resal->num_rows > 0) {
                    $rowal = $resal->fetch_assoc();
                    if($rowal['status'] === 'A') {
                        if($rowal['password'] === $password) {
                            $login_at = date('Y-m-d H:i:s');
                            #update admin login
                            $stmt = $conn->prepare("UPDATE admin_login SET login_at=? WHERE email=?");
                            $stmt->bind_param("ss", $login_at, $email);
                            $stmt->execute();
                            $stmt->close();
                            #create seesion array
                            $user_agent = $_SERVER['HTTP_USER_AGENT'];
                            $_SESSION['user_data'] = array(
                                'id' => $rowal['id'],
                                'name' => $rowal['name'],
                                'email' => $rowal['email'],
                            );
                            $_SESSION['login_string'] = hash('sha512', $rowal['password'] . $user_agent);
                        } else {
                            $error[] = "Invalid Password.";
                        }
                    } else {
                        $error[] = "Your account is not active.";
                    }
                } else {
                    $error[] = "Invalid Email.";
                }
            }

            if(empty($error)) {
                $addRes['status'] = 'success';
                $addRes['message'] = 'Login successfully.';
            } else {
                $addRes['status'] = 'error';
                $addRes['error'] = $error;
            }

            echo json_encode($addRes);
            break;
        
        default:
            # code...
            break;
    }
}