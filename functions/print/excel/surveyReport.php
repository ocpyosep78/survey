<?php

// get survey id
$survey_id = $_GET['survey_id'];

try {
    $db = new PDO('mysql' . ':host=' . 'localhost' . ';dbname=' . 'survey', 'survey', 'survey');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("SET NAMES utf8");
} catch (Exception $e) {
    error($e->getMessage());
    return;
}

// include the base files
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/functions/functions.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/BaseObject.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Error.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Info.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Answer.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Group.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Survey.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/SurveyFunctions.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/User.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Vote.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Message.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/Question.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/class/QuestionFunctions.php';

$survey = new Survey();
$survey->get_from_db($survey_id);

// get voted users
$voted_users = $survey->get_voted_users();
// get survey questions
$survey_questions = $survey->get_questions();

$alphas = range('A', 'Z');

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Sofia');

if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SU Survey")
        ->setLastModifiedBy("SU Survey")
        ->setTitle("SU Survey - Survey report")
        ->setSubject("SU Survey - Survey report")
        ->setDescription("SU Survey - Survey report")
        ->setKeywords("Sofia University,Survey,System,Results")
        ->setCategory("Survey Report");

// set sheet id
$sheetId = 0;

// set sheets
foreach ($survey_questions as $survey_question_id) {
    $question = new Question();
    $question->get_from_db($survey_question_id);
    $question_title = $question->getTitle();
    $question_answers = $question->get_answers();

    // Add question title
    $objPHPExcel->setActiveSheetIndex($sheetId)->mergeCells('A1:N1');
    $objPHPExcel->setActiveSheetIndex($sheetId)
            ->setCellValue('A1', $question_title);

    // list answers
    $columnId = 1;
    $alphabetIteration = 0;
    foreach ($question_answers as $question_answer_id) {
        $answer = new Answer();
        $answer->get_from_db($question_answer_id);

        if ($columnId < 26) {
            $cell = $alphas[$columnId] . "2";
        }
        $cell_value = $answer->getValue();

        // Add some data
        $objPHPExcel->setActiveSheetIndex($sheetId)
                ->setCellValue($cell, $cell_value);

        $objPHPExcel->getActiveSheet()->getColumnDimension($alphas[$columnId])->setWidth(15);
        $objPHPExcel->getActiveSheet()
                ->getStyle($cell)
                ->getAlignment()
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        
        $columnId++;
    }

    // list users by question
    $row_id = 3;
    foreach ($voted_users as $voted_user_id) {
        $user = new User();
        $user->get_from_db($voted_user_id);

        $cell = 'A' . $row_id;

        $user_number = $row_id - 2;
        $cell_value = 'User' . $user_number;

        // Add some data
        $objPHPExcel->setActiveSheetIndex($sheetId)
                ->setCellValue($cell, $cell_value);

        // list votes by user
        $columnId = 1;
        foreach ($question_answers as $question_answer_id) {
            $vote = new Vote();
            $vote_id_array = $vote->get_by_user_and_answer($voted_user_id, $question_answer_id);
            $cell_value = "";

            $answer = new Answer();
            $answer->get_from_db($question_answer_id);

            if (!empty($vote_id_array)) {
                $vote_id = $vote_id_array[0];
                $vote->get_from_db($vote_id);

                if (($answer->getType() == "radio") || ($answer->getType() == "checkbox")) {
                    $cell_value = 1;
                } elseif ($answer->getType() == "text") {
                    $cell_value = $vote->getValue();
                }
            }

            // fill in cell
            if ($columnId < 26) {
                $cell = $alphas[$columnId] . $row_id;
            }

            // Add some data
            $objPHPExcel->setActiveSheetIndex($sheetId)
                    ->setCellValue($cell, $cell_value);
            $objPHPExcel->getActiveSheet()
                    ->getStyle($cell)
                    ->getAlignment()
                    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            // increase column number
            $columnId++;
        }
        // increase row number
        $row_id++;
    }

    // create new sheet
    $objPHPExcel->createSheet(NULL, $sheetId);

    // Rename worksheet
    $question_number = $sheetId + 1;
    $objPHPExcel->getActiveSheet()->setTitle("Question " . $question_number);

    // increase sheet id
    $sheetId++;
}
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $survey->getTitle() . '.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
