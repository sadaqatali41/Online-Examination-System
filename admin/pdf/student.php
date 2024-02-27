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
$pdf->SetTitle('Student Details');
$pdf->SetSubject('Student Student Details');
$pdf->SetKeywords('Card, Student, Student Details');

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
$stmt = $conn->prepare("SELECT st.fname, st.lname, st.gender, st.phone, st.email, st.avatar, st.pin, st.address, CONCAT(st.id, st.pin) AS reg_no, st.country, c.name AS country_nm, st.state, s.name AS state_nm, st.city, cs.name AS city_nm, ct.center_name, ct.center_code, ct.center_address, st.dob, co.course_name, es.exam_date, es.start_time, es.end_time FROM students st INNER JOIN countries c ON c.id=st.country INNER JOIN states s ON s.id=st.state INNER JOIN cities cs ON cs.id=st.city INNER JOIN centers ct ON ct.id=st.center_id INNER JOIN courses co ON co.id=st.course_id LEFT JOIN exam_schedule es ON es.course_id=st.course_id AND es.for_year=YEAR(CURRENT_DATE) WHERE st.id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$row = $res->fetch_assoc();

#logo setting
$stlg = $conn->prepare("SELECT ls.university_logo, ls.exam_controller_logo FROM logo_settings ls WHERE 1=1");
$stlg->execute();
$reslg = $stlg->get_result();
$stlg->close();
$rowlg = $reslg->fetch_assoc();

#educational qualifications
$sted = $conn->prepare("SELECT 'X' AS type, hs.roll_no, hs.board_name, hs.school_name, hs.yop, hs.percent FROM highschool hs WHERE hs.student_id=?
UNION ALL
SELECT 'XII' AS type, im.roll_no, im.board_name, im.college_name AS school_name, im.yop, im.percent FROM intermediate im WHERE im.student_id=?
UNION ALL
SELECT gd.course_name AS type, gd.enroll_no AS roll_no, gd.branch_name AS board_name, gd.institute_name AS school_name, gd.yop, gd.aggregate_percent AS percent FROM graduation gd WHERE gd.student_id=?");
$sted->bind_param('iii', $id, $id, $id);
$sted->execute();
$resed = $sted->get_result();
$sted->close();

$html .= '<table border="1" cellpadding="5">';
$html .= '<tbody>';
$html .= '<tr style="background-color: #dff0d8; font-weight: bold;">';
$html .= '<th colspan="7" style="font-size: 18px; text-align: center;">ONLINE EXAMINATION SYSTEM</th>';
$html .= '</tr>';

// set style for barcode
$style = [
    'position' => 'R',
    'border' => 0,
    // 'vpadding' => '0',
    // 'hpadding' => '0',
    'padding' => 0,
    'fgcolor' => array(0,0,0),
    'bgcolor' => false,     #array(255,255,255)
    'module_width' => 1,    #width of a single module in points
    'module_height' => 1    #height of a single module in points
];

$qrData = 'R.No. : ' . $row['reg_no'] . "\n";
$qrData .= 'Name : ' . $row['fname'] . ' ' . $row['lname'] . "\n";
$qrData .= 'DOB : ' . $row['dob'] . "\n";
$qrData .= 'Mobile No : ' . $row['phone'] . "\n";
$qrData .= 'Email : ' . $row['email'] . "\n";
$qrData .= 'Course : ' . $row['course_name'] . "\n";
while($rowed = $resed->fetch_assoc()) {
    $qrData .= $rowed['type'] . ' % : ' . $rowed['percent'] . ', YOP : ' . $rowed['yop'] . "\n";
}
$resed->data_seek(0);

$html .= '<tr>';
$html .= '<td colspan="5">';
$html .= '<img src="../logos/'. $rowlg['university_logo'] .'" alt="Logo" />';
$html .= '</td>';
$html .= '<td colspan="2">';
$params = $pdf->serializeTCPDFtagParameters(array($qrData, 'QRCODE,L', '', '', 50, 35, $style, 'N'));
$html .= '<tcpdf method="write2DBarcode" params="' . $params . '" />';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr style="font-size: 18px; background-color: #fcf8e3; font-style: italic; color: #337ab7;">';
$html .= '<td colspan="5" style="text-align: center; font-weight: bold;">Personal Details</td>';
$html .= '<td colspan="2">R.No. '. $row['reg_no'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>First Name</b></td>';
$html .= '<td>'. $row['fname'] .'</td>';
$html .= '<td><b>Last Name</b></td>';
$html .= '<td>'. $row['lname'] .'</td>';
$html .= '<td><b>Mobile No</b></td>';
$html .= '<td colspan="2">'. $row['phone'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Gender</b></td>';
$html .= '<td>'. ($row['gender'] == 'M' ? 'Male' : 'Female') .'</td>';
$html .= '<td><b>Course</b></td>';
$html .= '<td>'. $row['course_name'] .'</td>';
$html .= '<td><b>Email</b></td>';
$html .= '<td colspan="2">'. $row['email'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Country</b></td>';
$html .= '<td>'. $row['country_nm'] .'</td>';
$html .= '<td><b>State</b></td>';
$html .= '<td>'. $row['state_nm'] .'</td>';
$html .= '<td><b>City</b></td>';
$html .= '<td colspan="2">'. $row['city_nm'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td><b>Pin</b></td>';
$html .= '<td>'. $row['pin'] .'</td>';
$html .= '<td><b>Center</b></td>';
$html .= '<td>'. $row['center_name'] .'</td>';
$html .= '<td><b>DOB</b></td>';
$html .= '<td colspan="2">'. $row['dob'] .'</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td colspan="2"><b>Permanent Address</b></td>';
$html .= '<td colspan="5">'. $row['address'] .'</td>';
$html .= '</tr>';

$styleForQualification = 'background-color: #fcf8e3;'; 
$styleForQualification .= 'text-align: center;';
$styleForQualification .= 'font-size: 18px;';
$styleForQualification .= 'font-style: italic;';
$styleForQualification .= 'color: #3c763d;';

$html .= '<tr style="'. $styleForQualification .'">';
$html .= '<td colspan="7"><b>Educational Qualification</b></td>';
$html .= '</tr>';

$html .= '<tr style="font-size: 10px; font-weight: bold;">';
$html .= '<th>Course</th>';
$html .= '<th>Roll No</th>';
$html .= '<th>Board/Branch</th>';
$html .= '<th colspan="2">College/Institution</th>';
$html .= '<th>YOP</th>';
$html .= '<th>Percent</th>';
$html .= '</tr>';
while($rowed = $resed->fetch_assoc()) {
    $html .= '<tr style="font-size: 10px;">';
    $html .= '<td>'. $rowed['type'] .'</td>';
    $html .= '<td>'. $rowed['roll_no'] .'</td>';
    $html .= '<td>'. $rowed['board_name'] .'</td>';
    $html .= '<td colspan="2">'. $rowed['school_name'] .'</td>';
    $html .= '<td>'. $rowed['yop'] .'</td>';
    $html .= '<td>'. $rowed['percent'] .'</td>';
    $html .= '</tr>';
}
$html .= '</tbody>';
$html .= '</table>';

ob_end_clean();
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
$pdf->SetDisplayMode('real', 'default');
//Close and output PDF document
$pdfFileName = 'studentInfo' . $row['fname'] . '' . $row['lname'] . '-' . date('Y-m-d-H-i-s');
$pdf->Output($pdfFileName . '.pdf', 'I');