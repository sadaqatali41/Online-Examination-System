<?php

class UserAuth
{
    private $sessionName = 'online_examination_system_session_id';

    public function sessionStart()
    {
        $session_name = $this->sessionName;
        #FOR DEVELOPMENT ONLY
        $secure = false; 
        #This stops JavaScript being able to access the session id.
        $httponly = true; 
        #Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === false) {
            header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
            exit();
        }
        #Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
        #Sets the session name to the one set above.
        session_name($session_name);
        #Start the PHP session
        session_start(); 
        #regenerated the session, delete the old one.
        session_regenerate_id(); 
    }
    public function loginCheck($conn)
    {
        if (isset($_SESSION['user_data'], $_SESSION['login_string'])) {
            $email = $_SESSION['user_data']['email'];
            $login_string = $_SESSION['login_string'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $stmt = $conn->prepare("SELECT * FROM admin_login al WHERE al.email=? AND al.status='A'");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $res = $stmt->get_result();
            $stmt->close();

            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $login_check = hash('sha512', $row['password'] . $user_agent);
                if ($login_check === $login_string) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
