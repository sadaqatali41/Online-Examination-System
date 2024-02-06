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

        case 'profileUpdateSubmit':
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $mobile_no = filter_input(INPUT_POST, 'mobile_no', FILTER_SANITIZE_STRING);
            $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);
            $branch_name = filter_input(INPUT_POST, 'branch_name', FILTER_SANITIZE_STRING);
            $institute_name = filter_input(INPUT_POST, 'institute_name', FILTER_SANITIZE_STRING);
            $city = filter_input(INPUT_POST, 'city', FILTER_VALIDATE_INT);
            $blog_website = filter_input(INPUT_POST, 'blog_website', FILTER_VALIDATE_URL);

            if(empty($name)) {
                $error[] = "Name is required.";
            }
            if(empty($email)) {
                $error[] = "Email is required.";
            }
            if(empty($mobile_no)) {
                $error[] = "Mobile No is required.";
            }
            if(empty($course_name)) {
                $error[] = "Course Name is required.";
            }
            if(empty($branch_name)) {
                $error[] = "Branch Name is required.";
            }
            if(empty($institute_name)) {
                $error[] = "Instiute Name is required.";
            }
            if(empty($city)) {
                $error[] = "City is required.";
            }
            if(empty($blog_website)) {
                $error[] = "Blog / Website URL is required.";
            }
            if(empty($error)) {
                $stck = $conn->prepare("SELECT al.email, al.mobile_no FROM admin_login al WHERE al.id <> ? AND (al.email=? OR al.mobile_no=?)");
                $stck->bind_param("iss", $user_data['id'], $email, $mobile_no);
                $stck->execute();
                $resck = $stck->get_result();
                $stck->close();
                if($resck->num_rows > 0) {
                    $error[] = "Email : {$email} OR Mobile No : {$mobile_no} exists. Please choose another one.";
                }
            }
            #profile upload
            if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $filename = $_FILES["avatar"]["name"];
				$fileTempName = $_FILES["avatar"]["tmp_name"];
				$filetype = $_FILES["avatar"]["type"];
				$filesize = $_FILES["avatar"]["size"];
				$errorCode = $_FILES["avatar"]["error"];
                $maxsize = 3 * 1024 * 1024; #3MB
                $ext = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
                $maxWidth = 250;
                $maxHeight = 250;

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

                    $stmt = $conn->prepare("UPDATE admin_login SET name=?, email=?, mobile_no=?, course_name=?, branch_name=?, institute_name=?, city=?, blog_website=? WHERE id=?");
                    $stmt->bind_param("ssssssisi", $name, $email, $mobile_no, $course_name, $branch_name, $institute_name, $city, $blog_website, $user_data['id']);

                    if($stmt->execute() === false) {
                        throw new Exception("Can't update Profile Detail. Reason : " . $stmt->error);
                    }
                    $stmt->close();

                    #profile upload
                    if(is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                        $targetPath = "../about-us/";
                        if(!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $avatarName = $user_data['id'] . '.' . $ext;
                        if (!move_uploaded_file($fileTempName, $targetPath . '/' . $avatarName)) {
							throw new Exception("Can't upload avatar. Reason : " . $errorCode);
						}
                        #update avatar
                        $st1 = $conn->prepare("UPDATE admin_login SET profile_pic=? WHERE id=?");
                        $st1->bind_param("si", $avatarName, $user_data['id']);

                        if($st1->execute() === false) {
                            throw new Exception("Can't update Profile Picture. Reason : " . $st1->error);
                        }
                        $st1->close();
                    }
                    #update session value
                    if($user_data['name'] !== $name) {
                        $_SESSION['user_data']['name'] = $name;
                    }
                    if($user_data['email'] !== $email) {
                        $_SESSION['user_data']['email'] = $email;
                    }

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = 'Success, Profile is updated.';
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