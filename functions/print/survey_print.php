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
if(!isset($_SESSION['survey_id'])) {
    header('location: /?page=my_surveys');
    die();
}

global $user;

$survey = new Survey();

$survey->get_from_db($_SESSION['survey_id']);
$answers = get_survey_answers($survey->getId());

$groups = get_survey_staff_groups($survey->getId());
foreach (get_survey_student_groups($survey->getId()) as $group_id) {
    array_push($groups, $group_id);
}
foreach (get_survey_local_groups($survey->getId()) as $group_id) {
    array_push($groups, $group_id);
}

//-------------------------------------------------

// Include the main TCPDF library (search for installation path).
require_once( ROOT_DIR . 'functions/print/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
//		$image_file = K_PATH_IMAGES.'logo_example.jpg';
//		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('freeserif', 'B', 20);
		// Title
		$this->Cell(0, 15, 'СУ Анкета 2014', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
$pdf->SetFont('freeserif', '', 18);

$pdf->Ln(5);

$txt = "Резултати от анкета:";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set font
$pdf->SetFont('freeserif', '', 16);

$txt = "\"". $survey->getQuestion() ."\"";
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

$pdf->Ln(5);

// set cell padding
$pdf->setCellPaddings(1, 1, 1, 1);

// set cell margins
$pdf->setCellMargins(0, 0, 0, 0);

// set font
$pdf->SetFont('freeserif', 'B', 14);

$txt = 'Отговори';
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set color for background
$pdf->SetFillColor(225, 225, 225);

// set font
$pdf->SetFont('freeserif', '', 12);

// Multicell test
$txt = '№';
$pdf->MultiCell(10, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Отговор';
$pdf->MultiCell(140, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Тип';
$pdf->MultiCell(30, '', $txt, 1, 'C', 1, 1, '', '', true);

// set color for background
$pdf->SetFillColor(255, 255, 255);

$number_answer = 1;
foreach ($answers as $answer_id) {
    $answer = new Answer();
    $answer->get_from_db($answer_id);
    $txt = $number_answer;
    $pdf->MultiCell(10, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = $answer->getValue();
    $pdf->MultiCell(140, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = $answer->getType();
    $pdf->MultiCell(30, '', $txt, 1, 'C', 1, 1, '', '', true);
    $number_answer++;
}

$pdf->Ln(5);

// set font
$pdf->SetFont('freeserif', 'B', 14);

$txt = 'Групи';
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set color for background
$pdf->SetFillColor(225, 225, 225);

// set font
$pdf->SetFont('freeserif', '', 12);

// Multicell test
$txt = '№';
$pdf->MultiCell(10, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Име';
$pdf->MultiCell(40, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Информация';
$pdf->MultiCell(100, '', $txt, 1, 'C', 1, 0, '', '', true);
$txt = 'Членове';
$pdf->MultiCell(30, '', $txt, 1, 'C', 1, 1, '', '', true);

// set color for background
$pdf->SetFillColor(255, 255, 255);

$number_groups = 1;
foreach ($groups as $group_id) {
    $group = new Group();
    $group -> get_from_db($group_id);
    $txt = $number_groups;
    $pdf->MultiCell(10, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = $group->getAbbreviation();
    $pdf->MultiCell(40, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = $group->getDescription();
    $pdf->MultiCell(100, '', $txt, 1, 'C', 1, 0, '', '', true);
    $txt = count($group->getMembersArray());
    $pdf->MultiCell(30, '', $txt, 1, 'C', 1, 1, '', '', true);
    $number_groups++;
}

$pdf->Ln(5);

// set font
$pdf->SetFont('freeserif', 'B', 14);

$txt = 'Статистика';
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

// set font
$pdf->SetFont('freeserif', 'B', 12);

// set color for background
$pdf->SetFillColor(215, 215, 215);
// Vertical alignment
$pdf->MultiCell(20, 10, '', 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');

$results_width = 140;
$result_field_width = $results_width / count($answers);

// set color for background
$pdf->SetFillColor(225, 225, 225);
for($i = 1; $i < (count($answers) + 1); $i++ ) {
    $pdf->MultiCell($result_field_width, 10, 'Отг. '.$i , 1, 'C', 1, 0, '', '', true, 0, false, true, 10, 'M');
}

// set color for background
$pdf->SetFillColor(235, 235, 235);
$pdf->MultiCell(20, 10, 'Всички', 1, 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');

$number_group = 1;
foreach ($groups as $group_id) {
    $group = new Group();
    $group ->get_from_db($group_id);
    
    // set color for background
    $pdf->SetFillColor(225, 225, 225);
    $pdf->MultiCell(20, '', 'Група '.$number_group, 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
    
    // set color for background
    $pdf->SetFillColor(255, 255, 255);
    $group_votes = 0;
    foreach ($answers as $answer_id) {
        $answer = new Answer();
        $answer->get_from_db($answer_id);
        
        $votes = get_votes_by_answer($answer_id);
        
        $group_answer_votes = 0;
        foreach ($votes as $vote_id) {
            $vote = new Vote();
            $vote->get_from_db($vote_id);
            if(in_array( $vote->getUser() , $group->getMembersArray() )) {
                $group_answer_votes++;
            }
        }
        
        $group_votes += $group_answer_votes;
        $txt = $group_answer_votes;
        $pdf->MultiCell($result_field_width, '', $txt, 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
        $number_answer++;
    }
    
    $pdf->MultiCell(20, '', "$group_votes", 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');
    
    $number_group++;
}

// set color for background
$pdf->SetFillColor(225, 225, 225);
$pdf->MultiCell(20, '', "Всички", 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');

// set color for background
$pdf->SetFillColor(255, 255, 255);
foreach ($answers as $answer_id) {
    $answer = new Answer();
    $answer->get_from_db($answer_id);
    $txt = count(get_votes_by_answer($answer->getId()));
    $pdf->MultiCell($result_field_width, '', $txt , 1, 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
}

// set color for background
$pdf->SetFillColor(240, 240, 240);
$pdf->MultiCell(20, '', 'Общо', 1, 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');

// ---------------------------------------------------------

ob_end_clean();

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('survey_print.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

unset($_SESSION['survey_id']);

