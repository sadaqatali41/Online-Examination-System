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

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle('Admit Card');
$pdf->SetSubject('Student Admit Card');
$pdf->SetKeywords('Card, Student, Admit Card');

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

$html .= '<tr>';
$html .= '<td colspan="3">';
$html .= '<img src="../logos/university_logo.jpg" alt="Logo" />';
$html .= '</td>';
$html .= '<td>';
$params = $pdf->serializeTCPDFtagParameters(array('Test', 'QRCODE,L', '', '', 50, 35, $style, 'N'));
$html .= '<tcpdf method="write2DBarcode" params="' . $params . '" />';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr style="font-size: 18px; background-color: #dff0d8; font-weight: bold;">';
$html .= '<th style="text-align: center;">Center Code: 1234</th>';
$html .= '<th colspan="2" style="text-align: center;">Student Admit Card</th>';
$html .= '<th><span style="font-style: italic;">R.No.</span> 125670</th>';
$html .= '</tr>';
$html .= '</thead>';
#tbody
$html .= '<tbody>';
$html .= '<tr>';
$html .= '<th><b>First Name :</b></th>';
$html .= '<td>Enayat</td>';
$html .= '<th><b>Last Name :</b></th>';
$html .= '<td>Ali</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th><b>Gender :</b></th>';
$html .= '<td>Male</td>';
$html .= '<th><b>Curse Name :</b></th>';
$html .= '<td>B-Tech</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th><b>Mobile No :</b></th>';
$html .= '<td>896078890</td>';
$html .= '<th><b>Email Address :</b></th>';
$html .= '<td>ali890@gmail.com</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th><b>Center Name :</b></th>';
$html .= '<td>AMU</td>';
$html .= '<th><b>Valid For :</b></th>';
$html .= '<td>Field of Study as Under</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th><b>Exam Date :</b></th>';
$html .= '<td>2024-02-29</td>';
$html .= '<th><b>Exam Time :</b></th>';
$html .= '<td>11 AM to 02 PM</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th>';
$html .= '<span style="text-decoration: underline;color: green;">Allocated Exam Center:-</span><br>';
$html .= '</th>';
$html .= '<td>';
$html .= '<img src="../../students/4/avatar.png" height="200" width="200" alt="Logo" />';
$html .= '</td>';
$html .= '<td style="vertical-align: middle; text-align:center;">Student Signature</td>';
$html .= '<td>';
$html .= '<img src="../logos/exam_controller_logo.jpg" height="200" width="200" alt="Logo" />';
$html .= '</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th colspan="4">';
$html .= '<p>Candidate having valid Admit Card of the allotted Examination Centre only is permitted to undertake the examination. Note : Please bring alongwith you the following...</p>';
$html .= '<span>1. A recent passport size photograph for Exam.</span><br>';
$html .= '<span>2. Origional Photo ID Proof (Aadhar Card, Driving License, Voter ID Card, Passport, Institution ID Card or Pan Card).</span>';
$html .= '</th>';
$html .= '</tr>';
$html .= '<tr style="font-size: 18px; background-color: #f2dede; text-align: center; font-weight: bold;">';
$html .= '<th colspan="4">INSTRUCTIONS TO THE CANDIDATE</th>';
$html .= '</tr>';
$html .= '<tr>
            <td colspan="4">
                <ol type="1">
                    <li>Please report 15 minutes in advance before the commencement of the examination at the alloted Examination Centre.</li>
                    <li>Candidates will not be permitted to enter the examination centre after 30 minutes of the commencement of the examination and will not be permitted to leave the Examination Hall before the end of the examination.</li>
                    <li>Online Calculator is Available For Calculations.</li>
                    <li>Communication devices like Cellphones are not allowed inside the examination hall.</li>
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
$pdf->Output('example_050.pdf', 'I');