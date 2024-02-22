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

$html .= '<table border="1">';
$html .= '<thead>';
$html .= '<tr style="background-color: #d9edf7;">';
$html .= '<th colspan="4" style="font-size: 25px; text-align: center;">ONLINE EXAMINATION SYSTEM</th>';
$html .= '</tr>';

// set style for barcode
$style = array(
    'position' => 'R',
    'border' => 0,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
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
// $pdf->write2DBarcode('Sadaqat Ali Annabi', 'QRCODE,L', null, null, 50, 50, $style, 'N');
$params = $pdf->serializeTCPDFtagParameters(array('Test', 'QRCODE,L', '', '', 50, 50, $style, 'N'));
$html .= '<tcpdf method="write2DBarcode" params="' . $params . '" />';
$html .= '</td>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '</table>';

ob_end_clean();
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
$pdf->SetDisplayMode('real', 'default');
//Close and output PDF document
$pdf->Output('example_050.pdf', 'I');