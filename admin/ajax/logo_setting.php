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

        case "logoSettingUpdateSubmit":
            $ls_id = filter_input(INPUT_POST, 'ls_id', FILTER_VALIDATE_INT);

            #university logo
            if(is_uploaded_file($_FILES['uni_logo']['tmp_name'])) {
                $filename = $_FILES["uni_logo"]["name"];
				$fileTempName = $_FILES["uni_logo"]["tmp_name"];
				$filetype = $_FILES["uni_logo"]["type"];
				$filesize = $_FILES["uni_logo"]["size"];
				$errorCode = $_FILES["uni_logo"]["error"];
                $maxsize = 3 * 1024 * 1024; #3MB
                $uniLogoExt = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
                $maxWidth = 670;
                $maxHeight = 145;

                $allowedTypes = array(
					"JPG" => "image/JPG",
					"jpg" => "image/jpg",
					"jpeg" => "image/jpeg",					
					"png" => "image/png"
				);
                if (!in_array($filetype, $allowedTypes)) {
                    $error[] = "University Logo : Invalid File type.";
                }
                if ($filesize > $maxsize) {
                    $error[] = "University Logo : File size is larger than the 3MB.";
                }
				if (!array_key_exists($uniLogoExt, $allowedTypes)) {
					$error[] = "University Logo : Please select a valid file format.";
				}
                list($img_width, $img_height, $img_type, $img_attr) = getimagesize($fileTempName);
                if ($img_width > $maxWidth || $img_height > $maxHeight) {
                    $error[] = "University Logo : Image should have maximum width of {$maxWidth}px and height of {$maxHeight}px.";
                }
            }
            #exam controller logo
            if(is_uploaded_file($_FILES['exam_controller']['tmp_name'])) {
                $filename = $_FILES["exam_controller"]["name"];
				$fileTempName = $_FILES["exam_controller"]["tmp_name"];
				$filetype = $_FILES["exam_controller"]["type"];
				$filesize = $_FILES["exam_controller"]["size"];
				$errorCode = $_FILES["exam_controller"]["error"];
                $maxsize = 3 * 1024 * 1024; #3MB
                $examContExt = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
                $maxWidth = 300;
                $maxHeight = 300;

                $allowedTypes = array(
					"JPG" => "image/JPG",
					"jpg" => "image/jpg",
					"jpeg" => "image/jpeg",					
					"png" => "image/png"
				);
                if (!in_array($filetype, $allowedTypes)) {
                    $error[] = "Exam Controller Logo : Invalid File type.";
                }
                if ($filesize > $maxsize) {
                    $error[] = "Exam Controller Logo : File size is larger than the 3MB.";
                }
				if (!array_key_exists($examContExt, $allowedTypes)) {
					$error[] = "Exam Controller Logo : Please select a valid file format.";
				}
                list($img_width, $img_height, $img_type, $img_attr) = getimagesize($fileTempName);
                if ($img_width > $maxWidth || $img_height > $maxHeight) {
                    $error[] = "Exam Controller Logo : Image should have maximum width of {$maxWidth}px and height of {$maxHeight}px.";
                }
            }
            #result logo
            if(is_uploaded_file($_FILES['result_logo']['tmp_name'])) {
                $filename = $_FILES["result_logo"]["name"];
				$fileTempName = $_FILES["result_logo"]["tmp_name"];
				$filetype = $_FILES["result_logo"]["type"];
				$filesize = $_FILES["result_logo"]["size"];
				$errorCode = $_FILES["result_logo"]["error"];
                $maxsize = 3 * 1024 * 1024; #3MB
                $resLogoExt = trim(strtolower(pathinfo($filename, PATHINFO_EXTENSION)));
                $maxWidth = 580;
                $maxHeight = 130;

                $allowedTypes = array(
					"JPG" => "image/JPG",
					"jpg" => "image/jpg",
					"jpeg" => "image/jpeg",					
					"png" => "image/png"
				);
                if (!in_array($filetype, $allowedTypes)) {
                    $error[] = "Result Logo : Invalid File type.";
                }
                if ($filesize > $maxsize) {
                    $error[] = "Result Logo : File size is larger than the 3MB.";
                }
				if (!array_key_exists($resLogoExt, $allowedTypes)) {
					$error[] = "Result Logo : Please select a valid file format.";
				}
                list($img_width, $img_height, $img_type, $img_attr) = getimagesize($fileTempName);
                if ($img_width > $maxWidth || $img_height > $maxHeight) {
                    $error[] = "Result Logo : Image should have maximum width of {$maxWidth}px and height of {$maxHeight}px.";
                }
            }

            if(empty($error)) {

                try {
                    $conn->autocommit(false);

                    #university logo
                    if(is_uploaded_file($_FILES['uni_logo']['tmp_name'])) {
                        $targetPath = "../logos";
                        if(!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $avatarName = 'university_logo.' . $uniLogoExt;
                        $fileTempName = $_FILES["uni_logo"]["tmp_name"];
                        if (!move_uploaded_file($fileTempName, $targetPath . '/' . $avatarName)) {
							throw new Exception("Can't upload avatar. Reason : " . $errorCode);
						}
                        #update logo
                        $st1 = $conn->prepare("UPDATE logo_settings SET university_logo=?, updated_by=? WHERE id=?");
                        $st1->bind_param("sii", $avatarName, $user_data['id'], $ls_id);

                        if($st1->execute() === false) {
                            throw new Exception("Can't update University Logo. Reason : " . $st1->error);
                        }
                        $st1->close();
                    }
                    #exam controller logo
                    if(is_uploaded_file($_FILES['exam_controller']['tmp_name'])) {
                        $targetPath = "../logos";
                        if(!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $avatarName = 'exam_controller_logo.' . $examContExt;
                        $fileTempName = $_FILES["exam_controller"]["tmp_name"];
                        if (!move_uploaded_file($fileTempName, $targetPath . '/' . $avatarName)) {
							throw new Exception("Can't upload avatar. Reason : " . $errorCode);
						}
                        #update logo
                        $st1 = $conn->prepare("UPDATE logo_settings SET exam_controller_logo=?, updated_by=? WHERE id=?");
                        $st1->bind_param("sii", $avatarName, $user_data['id'], $ls_id);

                        if($st1->execute() === false) {
                            throw new Exception("Can't update Exam Controller Logo. Reason : " . $st1->error);
                        }
                        $st1->close();
                    }
                    #result logo
                    if(is_uploaded_file($_FILES['result_logo']['tmp_name'])) {
                        $targetPath = "../logos";
                        if(!is_dir($targetPath)) {
                            mkdir($targetPath, 0777, true);
                        }
                        $avatarName = 'result_logo.' . $resLogoExt;
                        $fileTempName = $_FILES["result_logo"]["tmp_name"];
                        if (!move_uploaded_file($fileTempName, $targetPath . '/' . $avatarName)) {
							throw new Exception("Can't upload avatar. Reason : " . $errorCode);
						}
                        #update logo
                        $st1 = $conn->prepare("UPDATE logo_settings SET result_logo=?, updated_by=? WHERE id=?");
                        $st1->bind_param("sii", $avatarName, $user_data['id'], $ls_id);

                        if($st1->execute() === false) {
                            throw new Exception("Can't update Result Logo. Reason : " . $st1->error);
                        }
                        $st1->close();
                    }

                    if($conn->commit()) {
                        $addRes['status'] = 'success';
                        $addRes['message'] = "Success, Logo Setting is updated.";
                    }
                } catch (Exception $th) {
                    $error[] = $th->getMessage();
                    $addRes['status'] = 'error';
                    $addRes['error'] = $error;
                }

            } else {
                $addRes['status'] = 'error';
                $addRes['error'] = $error;
            }

            echo json_encode($addRes);
            break;
    }
}