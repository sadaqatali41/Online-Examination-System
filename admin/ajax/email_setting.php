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

        case "emailSettingUpdateSubmit":
            $es_id = filter_input(INPUT_POST, 'es_id', FILTER_VALIDATE_INT);
            $email_subject = filter_input(INPUT_POST, 'email_subject', FILTER_SANITIZE_STRING);
            $host_name = filter_input(INPUT_POST, 'host_name', FILTER_SANITIZE_STRING);
            $port_no = filter_input(INPUT_POST, 'port_no', FILTER_VALIDATE_INT);
            $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $smtp_secure = filter_input(INPUT_POST, 'smtp_secure', FILTER_SANITIZE_STRING);
            $from_email = filter_input(INPUT_POST, 'from_email', FILTER_VALIDATE_EMAIL);
            $from_name = filter_input(INPUT_POST, 'from_name', FILTER_SANITIZE_STRING);
            $email_message = filter_input(INPUT_POST, 'email_message', FILTER_SANITIZE_STRING);

            #CC detail
            $cc_id = filter_input(INPUT_POST, 'cc_id', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            $cc_email = filter_input(INPUT_POST, 'cc_email', FILTER_VALIDATE_EMAIL, FILTER_REQUIRE_ARRAY);
            $cc_email_name = filter_input(INPUT_POST, 'cc_email_name', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

            #deleted CC array
            $del_arr = filter_input(INPUT_POST, 'del_arr', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

            if(empty($es_id)) {
                $error[] = "Something went wrong.";
            }
            if(empty($email_subject)) {
                $error[] = "Email Subject is required.";
            }
            if(empty($host_name)) {
                $error[] = "Host Name is required.";
            }
            if(empty($port_no)) {
                $error[] = "Port No is required.";
            }
            if(empty($user_name)) {
                $error[] = "User Name is required.";
            }
            if(empty($password)) {
                $error[] = "Password is required.";
            }
            if(empty($smtp_secure)) {
                $error[] = "SMTP Secure is required.";
            }
            if(empty($from_email)) {
                $error[] = "From Email is required.";
            }
            if(empty($from_name)) {
                $error[] = "From Name is required.";
            }
            if(empty($email_message)) {
                $error[] = "Email Message is required.";
            }
            if(empty($error)) {
                $totItems = count($cc_email) - 1;

                for($i = 0; $i <= $totItems; $i++) {
                    if(empty($cc_email[$i])) {
                        $error[] = "CC Email is required.";
                    }
                    if(empty($cc_email_name[$i])) {
                        $error[] = "CC Email Name is required.";
                    }
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    #email settings - update
                    $stmt = $conn->prepare("UPDATE email_settings SET email_subject=?, host_name=?, port_no=?, user_name=?, password=?, smtp_secure=?, from_email=?, from_name=?, email_message=?, updated_by=? WHERE id=?");
                    $stmt->bind_param("ssissssssii", $email_subject, $host_name, $port_no, $user_name, $password, $smtp_secure, $from_email, $from_name, $email_message, $user_data['id'], $es_id);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't update Email Settings. Reason : " . $stmt->error);
                    }

                    #CC email - insert
                    $stmt1 = $conn->prepare("INSERT INTO cc_email (cc_email, cc_name) VALUES (?,?)");
                    #CC email - update
                    $stmt2 = $conn->prepare("UPDATE cc_email SET cc_email=?, cc_name=? WHERE id=?");

                    for($i = 0; $i <= $totItems; $i++) {
                        if(empty($cc_id[$i])) {
                            $stmt1->bind_param("ss", $cc_email[$i], $cc_email_name[$i]);
                            if($stmt1->execute() === false) {
                                throw new Exception("Can't insert CC Email. Reason : " . $stmt1->error);
                            }
                        } else {
                            $stmt2->bind_param("ssi", $cc_email[$i], $cc_email_name[$i], $cc_id[$i]);
                            if($stmt2->execute() === false) {
                                throw new Exception("Can't update CC Email. Reason : " . $stmt2->error);
                            }
                        }
                    }

                    #CC email - delete
                    if(is_array($del_arr) && count($del_arr)) {
                        $stmt3 = $conn->prepare("DELETE FROM cc_email WHERE id=?");

                        foreach($del_arr as $del_id) {
                            $stmt3->bind_param("i", $del_id);
                            if($stmt3->execute() === false) {
                                throw new Exception("Can't CC Email. Reason : " . $stmt3->error);
                            }
                        }
                        $stmt3->close();
                    }

                    $stmt->close();
                    $stmt1->close();
                    $stmt2->close();

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = "Success, Email Settings is updated.";
                    }
                } catch (Exception $e) {
                    $error[] = $e->getMessage();
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