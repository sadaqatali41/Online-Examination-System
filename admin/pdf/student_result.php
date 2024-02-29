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

if (!filter_has_var(INPUT_GET, 'id') || filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) === false) {
	echo '<h2>Sorry! Parameter not found</h2>';exit;
}

// Include the main TCPDF library (search for installation path).
require_once('../class/TCPDF/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set the indentation width for bullet lists
$pdf->setListIndentWidth(4);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle('Student Result');
$pdf->SetSubject('Student Result');
$pdf->SetKeywords('Card, Student, Result');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 10, 10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();

$html = '';

if(filter_has_var(INPUT_GET, 'id') && filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) !== false) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
} else {
    echo '<h2>Not Found.</h2>';exit;
}

#student information
$stmt = $conn->prepare("SELECT st.fname, st.lname, st.gender, st.phone, st.email, st.avatar, CONCAT(st.id, st.pin) AS reg_no, ct.center_name, ct.center_code, ct.center_address, st.dob, co.course_name, rs.no_of_questions, rs.marks, rs.percentage, rs.status FROM students st INNER JOIN centers ct ON ct.id=st.center_id INNER JOIN courses co ON co.id=st.course_id INNER JOIN results rs ON rs.student_id=st.id WHERE st.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$row = $res->fetch_assoc();

#logo setting
$stlg = $conn->prepare("SELECT ls.university_logo, ls.exam_controller_logo, ls.result_logo FROM logo_settings ls WHERE 1=1");
$stlg->execute();
$reslg = $stlg->get_result();
$stlg->close();
$rowlg = $reslg->fetch_assoc();

$html .= '<table border="1" cellpadding="5">';
$html .= '<thead>';
$html .= '<tr style="background-color: #d9edf7;">';
$html .= '<th colspan="4" style="font-size: 25px; text-align: center;">ONLINE EXAMINATION SYSTEM</th>';
$html .= '</tr>';

// set style for barcode
$style = array(
    'position' => 'R',
    'border' => 0,
    // 'vpadding' => '0',
    // 'hpadding' => '0',
    'padding' => 0,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false,     #array(255,255,255)
    'module_width' => 1,    #width of a single module in points
    'module_height' => 1    #height of a single module in points
);

$qrData = 'R.No. : ' . $row['reg_no'] . "\n";
$qrData .= 'Name : ' . $row['fname'] . ' ' . $row['lname'] . "\n";
$qrData .= 'DOB : ' . $row['dob'] . "\n";
$qrData .= 'Mobile No : ' . $row['phone'] . "\n";
$qrData .= 'Email : ' . $row['email'] . "\n";
$qrData .= 'Course Name: ' . $row['course_name'] . "\n";
$qrData .= 'Exam Center : ' . $row['center_name'] . "\n";
$qrData .= 'Exam Status : ' . $row['status'];

$html .= '<tr>';
$html .= '<td colspan="3">';
$html .= '<img src="../logos/'. $rowlg['university_logo'] .'" alt="Logo" />';
$html .= '</td>';
$html .= '<td>';
$params = $pdf->serializeTCPDFtagParameters(array($qrData, 'QRCODE,L', '', '', 50, 35, $style, 'N'));
$html .= '<tcpdf method="write2DBarcode" params="' . $params . '" />';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr style="font-size: 18px; background-color: #fcf8e3; font-weight: bold;">';
$html .= '<th colspan="3" style="text-align: center;">Student Result</th>';
$html .= '<th><span style="font-style: italic;">R.No.</span> '. $row['reg_no'] .'</th>';
$html .= '</tr>';
$html .= '</thead>';
#tbody
$html .= '<tbody>';
$html .= '<tr>';
$html .= '<td><b>First Name :</b></td>';
$html .= '<td>'. $row['fname'] .'</td>';
$html .= '<td><b>Last Name :</b></td>';
$html .= '<td>'. $row['lname'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Gender :</b></td>';
$html .= '<td>'. ($row['gender'] == 'M' ? 'Male' : 'Female') .'</td>';
$html .= '<td><b>Course Name :</b></td>';
$html .= '<td>'. $row['course_name'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Mobile No :</b></td>';
$html .= '<td>'. $row['phone'] .'</td>';
$html .= '<td><b>Exam Center :</b></td>';
$html .= '<td>'. $row['center_name'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<th><b>Email Address :</b></th>';
$html .= '<td colspan="2">'. $row['email'] .'</td>';
$resultColor = 'red';
if($row['status'] === 'Selected') {
    $resultColor = 'green';
}
$html .= '<td style="color: '. $resultColor .';"><b>'. $row['status'] .'</b></td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>D.O.B :</b></td>';
$html .= '<td>'. $row['dob'] .'</td>';
$html .= '<td><b>No. of Questions :</b></td>';
$html .= '<td>'. $row['no_of_questions'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Total Marks :</b></td>';
$html .= '<td>'. $row['marks'] .'</td>';
$html .= '<td><b>Marks % :</b></td>';
$html .= '<td>'. $row['percentage'] .' %</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td colspan="4">';
$html .= '<img src="../logos/'. $rowlg['result_logo'] .'" alt="Result Logo" />';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr style="font-size: 18px; background-color: #f2dede; text-align: center; font-weight: bold;">';
$html .= '<th colspan="4">INSTRUCTIONS TO THE CANDIDATE</th>';
$html .= '</tr>';
$html .= '<tr>
            <td colspan="4">
                <ol type="1">
                    <li>Bring This Result During Counselling.</li>
                    <li>You are not Permitted without this Result sheet.</li>
                    <li>Come with 7 Passport size photo and Original Documents.</li>
                    <li>Without Original Documents, you will not get the admission.</li>
                </ol>
            </td>
        </tr>';
$html .= '</tbody>';
$html .= '</table>';

ob_end_clean();
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
$pdf->SetDisplayMode('real', 'default');
//Close and output PDF document
$pdfFileName = 'result-' . $row['fname'] . '' . $row['lname'] . '-' . date('Y-m-d-H-i-s');
$pdf->Output($pdfFileName . '.pdf', 'I');