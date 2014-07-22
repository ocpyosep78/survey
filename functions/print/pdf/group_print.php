<?php

// set session
if(!isset($_SESSION)) {
    session_start();
}

// protect from unauthorized access
if(!isset($_SESSION['user'])) {
    logout();
    die();
}

// protect from error access
if(!isset($_SESSION['group_id'])) {
    header('location: /?page=my_surveys');
    die();
}

global $user;

$group = new Group();
$group->get_from_db($_SESSION['group_id']);
$users = $group->getMembersArray();

//-------------------------------------------------

// Include the main TCPDF library (search for installation path).
require_once( ROOT_DIR . 'functions/print/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'logo_example.jpg';
		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('freeserif', 'B', 20);
		// Title
		$this->Cell(0, 15, '"СУ Анкета 2014', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('freeserif', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'стр. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SU Survey');
$pdf->SetTitle('Sofia University');
$pdf->SetSubject('Print Survey');
$pdf->SetKeywords('SU Survey, PDF, print, results');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/bul.php')) {
	require_once(dirname(__FILE__).'/lang/bul.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// add a page
$pdf->AddPage();

// ---------------------------------------------------------

// set font
$pdf->SetFont('freeserif', 'B', 12);

// set some text to print
$txt = <<<EOD
         Софийски университет "Св. Климент Охридски"
EOD;

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// ---------------------------------------------------------

// set font
$pdf->SetFont('freeserif', 'B', 18);

$pdf->Ln(5);

$txt = "Преглед на група:";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set font
$pdf->SetFont('freeserif', '', 16);

$txt = "\"". $group->getName() ."\"";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set font
$pdf->SetFont('freeserif', 'B', 14);

$pdf->Ln(5);

$txt = "Информация:";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set font
$pdf->SetFont('freeserif', '', 12);

$txt = $group->getDescription();
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);


$pdf->Ln(5);

// set cell padding
$pdf->setCellPaddings(1, 1, 1, 1);

// set cell margins
$pdf->setCellMargins(0, 0, 0, 0);

// set font
$pdf->SetFont('freeserif', 'B', 14);

$txt = 'Членове';
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set color for background
$pdf->SetFillColor(245, 245, 245);

// set font
$pdf->SetFont('freeserif', '', 12);

// Multicell test
$txt = '№';
$pdf->MultiCell(10, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Име';
$pdf->MultiCell(100, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Email';
$pdf->MultiCell(70, '', $txt, 1, 'C', 1, 1, '', '', true);

// set color for background
$pdf->SetFillColor(255, 255, 255);

$number_user = 1;
foreach ($users as $user_id) {
    $user = new User();
    $user->get_from_db($user_id);
    $txt = $number_user;
    $pdf->MultiCell(10, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = $user->getGivenname();
    $pdf->MultiCell(100, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = $user->getEmail();
    $pdf->MultiCell(70, '', $txt, 1, 'C', 1, 1, '', '', true);
    $number_user++;
}

ob_end_clean();

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('survey_print.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

//unset($_SESSION['group_id']);

