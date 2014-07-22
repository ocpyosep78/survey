<?php

// logout
function logout() {
    if (!isset($_SESSION)) {
        session_start();
    }

// ensures anything dumped out will be caught
    ob_start();

// destroy all session vars
    session_destroy();

// clear out the output buffer
    while (ob_get_status()) {
        ob_end_clean();
    }

// redirected to url
    header('Location: ' . ROOT_DIR);

// end sripting
    die();
}

// set custom error handler
set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}

// write info log
function info($info) {
    // set connection var
    global $db;
    $info = "";

    // escape injection string info var
    try {
        $info .= stripslashes($info);
    } catch (Exception $exc) {
//        echo $exc->getTraceAsString();
    }

    // get user ip
    $ip = $_SERVER['REMOTE_ADDR'];
    // get current time
    $time = date("Y-m-d H:i:s");

    // set sql query
    $sql = "INSERT INTO info_log
            (info, ip, time)
            VALUES ('$info', '$ip', '$time');";

    $db->exec($sql);
}

// write error log
function error($error) {

    // set connection var
    global $db;
    $error = "";

    // escape injection string error var
    try {
        $error .= stripslashes($error);
    } catch (Exception $exc) {
        $error .= $exc->getTraceAsString();
    }

    // get user ip
    $ip = $_SERVER['REMOTE_ADDR'];
    // get current time
    $time = date("Y-m-d H:i:s");

    // set sql query
    $sql = "INSERT INTO error_log
            (info, ip, time)
            VALUES ('$error', '$ip', '$time');";

    $db->exec($sql);
}

// get available surveys by time
function get_available_by_time_surveys() {
    // set connection var
    global $db;

    // get current time
    $time = date("Y-m-d H:i:s");

    //  query to get all vote survey_ids for session user
    $sql = "SELECT id
            FROM surveys
            WHERE is_active = '1' AND status = '1' AND available_from < '$time' AND available_due > '$time';";
    $surveys_data = array();
    $surveys = array();
    foreach ($db->query($sql) as $key => $value) {
        $surveys_data[$key] = $value;
        foreach ($surveys_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $surveys[] = $subvalue;
            }
        }
    }

    return $surveys;
}

// get survey staff groups
function get_survey_staff_groups($survey_id) {
    // set connection var
    global $db;

    //query to get staff groups
    $sql = "SELECT staff_groups
            FROM surveys
            WHERE is_active = '1' AND id = '$survey_id';";

    $groups_data = array();
    $groups = array();
    $survey_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    if (!empty($groups[0])) {
        try {
            $survey_groups = unserialize($groups[0]);
        } catch (ErrorException $e) {
            $e->getMessage();
            $error = "Survey: '$survey_groups' staff groups: " . $e;
            error($error);
        }
    }

    return $survey_groups;
}

// get survey student groups
function get_survey_student_groups($survey_id) {
    // set connection var
    global $db;

    //query to get student groups str
    $sql = "SELECT student_groups
            FROM surveys
            WHERE is_active = '1' AND id = '$survey_id';";

    $groups_data = array();
    $groups = array();
    $survey_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    if (!empty($groups[0])) {
        try {
            $survey_groups = unserialize($groups[0]);
        } catch (ErrorException $e) {
            $e->getMessage();
            $error = "Survey: '$survey_groups' student groups: " . $e;
            error($error);
        }
    }

    return $survey_groups;
}

// get survey local groups
function get_survey_local_groups($survey_id) {
    // set connection var
    global $db;

    //query to get local groups str
    $sql = "SELECT local_groups
            FROM surveys
            WHERE is_active = '1' AND id = '$survey_id';";

    $groups_data = array();
    $groups = array();
    $survey_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    if (!empty($groups[0])) {
        try {
            $survey_groups = unserialize($groups[0]);
        } catch (ErrorException $e) {
            $e->getMessage();
            $error = "Survey: '$survey_groups' staff groups: " . $e;
            error($error);
        }
    }

    return $survey_groups;
}

// get available surveys by user
function get_available_by_user_surveys($user_id) {
    // get available by time
    $available_by_time_surveys = array();
    if (get_available_by_time_surveys()) {
        $available_by_time_surveys = get_available_by_time_surveys();
    }

    // get user groups
    $user_staff_groups = get_user_staff_groups($user_id);
    $user_student_groups = get_user_student_groups($user_id);
    $user_local_groups = get_user_local_groups($user_id);

    // set available_by_user_surveys
    $available_by_user_surveys = array();
    foreach ($available_by_time_surveys as $survey_id) {
        // check whether is already voted
        // get survey groups
        $survey_staff_groups = get_survey_staff_groups($survey_id);
        $survey_student_groups = get_survey_student_groups($survey_id);
        $survey_local_groups = get_survey_local_groups($survey_id);

        // get common groups
        $staff_groups = array_intersect($user_staff_groups, $survey_staff_groups);
        $student_groups = array_intersect($user_student_groups, $survey_student_groups);
        $local_groups = array_intersect($user_local_groups, $survey_local_groups);

        // get all available surveys
        if (!empty($staff_groups) || !empty($student_groups) || !empty($local_groups)) {
            array_push($available_by_user_surveys, $survey_id);
        }
    }

    return $available_by_user_surveys;
}

// get all associated survey answers
function get_survey_answers($question_id) {
    // set connection var
    global $db;

    //query to get all associated survey answers
    $sql = "SELECT id
            FROM answers
            WHERE is_active = '1' AND question = '$question_id';";

    $answers_data = array();
    $answers = array();
    foreach ($db->query($sql) as $key => $value) {
        $answers_data[$key] = $value;
        foreach ($answers_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $answers[] = $subvalue;
            }
        }
    }

    return $answers;
}

// get all associated survey questions
function get_survey_questions($survey_id) {
    // set connection var
    global $db;

    //query to get all associated survey answers
    $sql = "SELECT id
            FROM questions
            WHERE is_active = '1' AND survey = '$survey_id';";

    $questions_data = array();
    $questions = array();
    foreach ($db->query($sql) as $key => $value) {
        $questions_data[$key] = $value;
        foreach ($questions_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $questions[] = $subvalue;
            }
        }
    }

    return $questions;
}

// get surveys by creator's user_id
function get_surveys_by_creator($user_id) {
    // set connection var
    global $db;

    //query to get all associated surveys
    $sql = "SELECT id
            FROM surveys
            WHERE is_active = '1' AND created_by = '$user_id';";

    $surveys_data = array();
    $surveys = array();

    foreach ($db->query($sql) as $key => $value) {
        $surveys_data[$key] = $value;
        foreach ($surveys_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $surveys[] = $subvalue;
            }
        }
    }

    return $surveys;
}

// get votes by user
function get_voted_surveys_by_user($user_id) {
    // set connection var
    global $db;

    // query to get all vote survey_ids for user
    $sql = "SELECT survey_id
            FROM votes
            WHERE is_active='1' AND user_id='$user_id'";

    $votes_data = array();
    $votes_survey = array();
    $votes_survey_unique = array();
    foreach ($db->query($sql) as $key => $value) {
        $votes_data[$key] = $value;
        foreach ($votes_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $votes_survey[] = $subvalue;
            }
        }
    }

    $votes_survey_unique = array_unique($votes_survey);

    return $votes_survey_unique;
}

// get voted survey answers by user
function get_vote_by_user_and_survey($user_id, $survey_id) {
    // set connection var
    global $db;

    // query to get all vote answer_ids for user
    $sql = "SELECT id
            FROM votes
            WHERE is_active='1' AND survey_id='$survey_id' AND user_id='$user_id'";

    $votes_data = array();
    $votes = array();
    foreach ($db->query($sql) as $key => $value) {
        $votes_data[$key] = $value;
        foreach ($votes_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $votes[] = $subvalue;
            }
        }
    }

    return $votes;
}

// get votes by answer
function get_votes_by_answer($answer_id) {
    // set connection var
    global $db;

    //query to get all associated surveys
    $sql = "SELECT id
            FROM votes
            WHERE is_active = '1' AND answer_id = '$answer_id';";

    $answers_data = array();
    $answers = array();

    foreach ($db->query($sql) as $key => $value) {
        $answers_data[$key] = $value;
        foreach ($answers_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $answers[] = $subvalue;
            }
        }
    }

    return $answers;
}

// get session answers
function get_session_answers() {
    $session_answers = array();
    if (isset($_SESSION['session_answers'])) {
        $session_answers = unserialize($_SESSION['session_answers']);
    } else {
        $_SESSION['session_answers'] = serialize($session_answers);
    }
    return $session_answers;
}

// get session surveys
function get_session_survey() {
    $session_survey = new Survey();
    if (isset($_SESSION['session_survey'])) {
        $session_survey = unserialize($_SESSION['session_survey']);
    } elseif (isset($_SESSION['surveyCreatorViewSurveyId'])) {

        // get session survey
        $session_survey->get_from_db($_SESSION['surveyCreatorViewSurveyId']);
        $_SESSION['session_survey'] = serialize($session_survey);

        if (!isset($_SESSION['session_groups'])) {
            $session_groups = array(
                'type' => '',
                'student' => array(),
                'staff' => array(),
                'staff_departments' => array(),
                'local' => array());

            $surveyStudentGroups = unserialize($session_survey->getStudentGroups());
            if (is_array($surveyStudentGroups)) {
                $session_groups['student'] = $surveyStudentGroups;
            }

            $surveyStaffGroups = unserialize($session_survey->getStaffGroups());
            if (is_array($surveyStaffGroups)) {
                $session_groups['staff_departments'] = $surveyStaffGroups;
            }

            $surveyLocalGroups = unserialize($session_survey->getLocalGroups());
            if (is_array($surveyLocalGroups)) {
                $session_groups['local'] = $surveyLocalGroups;
            }

            $_SESSION['session_groups'] = serialize($session_groups);
        }
    } else {
        if (!isset($_SESSION['session_groups'])) {
            $session_groups = array(
                'type' => '',
                'student' => array(),
                'staff' => array(),
                'staff_departments' => array(),
                'local' => array());
            $_SESSION['session_groups'] = serialize($session_groups);
        }
        $_SESSION['session_survey'] = serialize($session_survey);
    }
    return $session_survey;
}

// get session surveys
function get_session_question() {
    $session_question = new Question();
    if (isset($_SESSION['session_question'])) {
        $session_question = unserialize($_SESSION['session_question']);
    } elseif (isset($_SESSION['surveyCreatorViewQuestionId'])) {
        // get session question
        $session_question->get_from_db($_SESSION['surveyCreatorViewQuestionId']);
        $_SESSION['session_question'] = serialize($session_question);
    } else {
        $_SESSION['session_question'] = serialize($session_question);
    }
    return $session_question;
}

// get session user
function admin_get_session_user() {
    $session_user = new User();
    if (isset($_SESSION['session_user'])) {
        $session_user = unserialize($_SESSION['session_user']);
    } else {
        $url_query = get_url_query();
        if (isset($url_query['user_id'])) {
            $session_user->get_from_db($url_query['user_id']);
        }
        $_SESSION['session_user'] = serialize($session_user);
    }
    return $session_user;
}

// get group by name
function get_group_by_name($group_name) {
    // set connection var
    global $db;

    //query to get all associated groups
    $sql = "SELECT id
            FROM groups
            WHERE   is_active = '1'
                    AND local = '0'
                    AND name = '$group_name'
            ORDER BY name ASC;";

    $groups_data = array();
    $groups = array();

    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    return $groups;
}

// get session group
function get_session_group() {
    $group = new Group;
    if (isset($_SESSION['group'])) {
        $group = unserialize($_SESSION['group']);
    } else {
        // set empty group obj
        if (isset($_SESSION['group_id'])) {
            $group->get_from_db($_SESSION['group_id']);
        }
        $_SESSION['group'] = serialize($group);
    }
    return $group;
}

// get session groups
function get_session_groups() {
    $session_groups = array();
    if (isset($_SESSION['session_groups'])) {
        $session_groups = unserialize($_SESSION['session_groups']);
    } else {
        // set array of groups
        $session_groups = array();
        $_SESSION['session_groups'] = serialize($session_groups);
    }
    return $session_groups;
}

// get susi groups
function get_susi_groups() {
    // set connection var
    global $db;

    //query to get all associated groups
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND local = '0'
            ORDER BY name ASC;";

    $groups_data = array();
    $groups = array();

    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    return $groups;
}

// get local groups
function get_local_groups() {
    // set connection var
    global $db;

    //query to get all associated groups
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND local = '1'
            ORDER BY name ASC;";

    $groups_data = array();
    $groups = array();

    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    return $groups;
}

// get local groups by creator
function get_local_groups_by_creator($user_id) {
    // set connection var
    global $db;

    //query to get all associated groups
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND local = '1' AND created_by = '$user_id'
            ORDER BY name ASC;";

    $groups_data = array();
    $groups = array();

    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    return $groups;
}

// get user staff groups
function get_user_staff_groups($user_id) {
    // set connection var
    global $db;

    //query to get staff groups
    $sql = "SELECT staff_groups
            FROM users
            WHERE is_active = '1' AND id = '$user_id';";

    $groups_data = array();
    $groups = array();
    $user_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        if (isset($groups_data[$key][0])) {
            $groups = $groups_data[$key][0];
        }
    }

    if ($groups != "") {
        try {
            $user_groups = unserialize($groups);
        } catch (ErrorException $e) {
            $e->getMessage();
            $error = "User: '$user_id' student_groups: " . $e->getMessage();
            error($error);
        }
    }

    return $user_groups;
}

// get user student groups
function get_user_student_groups($user_id) {
    // set connection var
    global $db;

    //query to get student groups str
    $sql = "SELECT student_groups
            FROM users
            WHERE is_active = '1' AND id = '$user_id';";

    $groups_data = array();
    $groups = "";
    $user_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        if (isset($groups_data[$key][0])) {
            $groups = $groups_data[$key][0];
        }
    }

    if ($groups != "") {
        try {
            $user_groups = unserialize($groups);
        } catch (ErrorException $e) {
            $e->getMessage();
            $error = "User: '$user_id' student_groups: " . $e->getMessage();
            error($error);
        }
    }

    return $user_groups;
}

// get user local groups
function get_user_local_groups($user_id) {
    // set connection var
    global $db;

    //query to get local groups str
    $sql = "SELECT local_groups
            FROM users
            WHERE is_active = '1' AND id = '$user_id';";

    $groups_data = array();
    $groups = array();
    $user_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        if (isset($groups_data[$key][0])) {
            $groups = $groups_data[$key][0];
        }
    }

    if ($groups != "") {
        try {
            $user_groups = unserialize($groups);
        } catch (ErrorException $e) {
            $e->getMessage();
            $error = "User: '$user_id' student_groups: " . $e->getMessage();
            error($error);
        }
    }

    return $user_groups;
}

// page select
function select_page($page) {
    switch ($page) {
        case 'home':
            require_once ROOT_DIR . 'pages/home.php';
            break;
        case 'survey_role':
            require_once ROOT_DIR . 'pages/survey_role.php';
            break;
        case 'survey':
            require_once ROOT_DIR . 'pages/survey.php';
            break;
        case 'survey_contact':
            require_once ROOT_DIR . 'pages/survey_contact.php';
            break;
        case 'survey_group':
            require_once ROOT_DIR . 'pages/survey_group.php';
            break;
        case 'survey_edit':
            require_once ROOT_DIR . 'pages/survey_edit.php';
            break;
        case 'help':
            require_once ROOT_DIR . 'pages/help.php';
            break;
        case 'admin_system':
            require_once ROOT_DIR . 'pages/admin_system.php';
            break;
        case 'admin_system_user_edit':
            require_once ROOT_DIR . 'pages/admin_system_user_edit.php';
            break;
        case 'admin_survey':
            require_once ROOT_DIR . 'pages/admin_survey.php';
            break;
        case 'user_survey':
            require_once ROOT_DIR . 'pages/user_survey.php';
            break;
        case 'survey_add_answer':
            require_once ROOT_DIR . 'pages/survey_add_answer.php';
            break;
        case 'help_page':
            require_once ROOT_DIR . 'pages/help.php';
            break;
        default :
            require_once ROOT_DIR . 'pages/home.php';
            break;
    }
}

// get users from db
function get_user_by_username($username) {
    // set connection var
    global $db;

    $sql = "SELECT id
            FROM users
            WHERE is_active = '1' and username = '$username'";

    $user_data = array();
    $user = array();
    foreach ($db->query($sql) as $key => $value) {
        $user_data[$key] = $value;
        foreach ($user_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $user[] = $subvalue;
            }
        }
    }

    return $user;
}

// get users from db
function get_users($maxresults) {
    // set connection var
    global $db;

    $sql = "SELECT id
            FROM users
            WHERE is_active = '1'";
    if (is_int($maxresults) && $maxresults > 0) {
        $sql += "LIMIT 0 , '$maxresults'";
    }

    $users_data = array();
    $users = array();
    foreach ($db->query($sql) as $key => $value) {
        $users_data[$key] = $value;
        foreach ($users_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $users[] = $subvalue;
            }
        }
    }

    return $users;
}

// get URL query string
function get_url_query() {
    $query = array();

    // get the URL query string
    $query_str = $_SERVER['QUERY_STRING'];

    // parse the URL query string to array
    parse_str($query_str, $query);

    return $query;
}

// get admin mails
function get_admin_email_data() {
    // set connection var
    global $db;

    //query to get local groups str
    $sql = "SELECT email, givenname
            FROM users
            WHERE is_active = '1' AND admin = '1';";

    $email_data = array();
    foreach ($db->query($sql) as $key => $value) {
        $email_data[$key] = $value;
    }

    return $email_data;
}

// send_mail
function send_mail($title, $text) {
    require_once ROOT_DIR . 'functions/mail/class.phpmailer.php';
    require ROOT_DIR . 'functions/mail/class.smtp.php';

    // get global user object
    global $user;
    $userEmail = nl2br("Email: " . $user->getEmail() . ",\n");
    $userTitle = nl2br($user->getTitle() . " ");
    $userName = nl2br($user->getGivenname() . ",\n");

    $userStudentGroups = "";
    $studentGroups = get_user_student_groups($user->getId());
    if (!empty($studentGroups)) {
        $userStudentGroups = nl2br("Студент в:\n");
        $i = 1;
        foreach ($studentGroups as $group_id) {
            $group = new Group();
            $group->get_from_db($group_id);
            $userStudentGroups .= nl2br($i . ". " . $group->getName() . ",\n");
            $i++;
        }
    }

    $userStaffGroups = "";
    $staffGroups = get_user_staff_groups($user->getId());
    if (!empty($staffGroups)) {
        $userStaffGroups = nl2br("Служител в:\n");
        $i = 1;
        foreach ($staffGroups as $group_id) {
            $group = new Group();
            $group->get_from_db($group_id);
            $userStaffGroups .= nl2br($i . ". " . $group->getName() . ",\n");
            $i++;
        }
    }

    // format the message
    $text .= nl2br("\n\n" . "Съобщението е изпратено от:\n") .
            $userTitle . $userName .
            $userEmail .
            $userStudentGroups .
            $userStaffGroups;

    // set message data
    $mailFrom = 'schedule@ucc.uni-sofia.bg';
    $mailFromName = 'SU Survey';

    // get admin email data
    $admin_email_data = get_admin_email_data();

    $mail = new PHPMailer;

    $mail->IsSMTP();                                            // Set mailer to use SMTP
    $mail->CharSet = 'utf-8';                                   // Set the message charset
    $mail->Host = 'mailbox.uni-sofia.bg';                       // Specify main and backup server
    $mail->Port = 465;                                          // Specify server port '465' or '587'
    $mail->SMTPAuth = true;                                     // Enable SMTP authentication
    $mail->SMTPSecure = 'ssl';                                  // secure transfer enabled REQUIRED for GMail
    $mail->Username = 'schedule@ucc.uni-sofia.bg';              // SMTP username
    $mail->Password = 'schedule';                              // SMTP password 
    $mail->From = "$mailFrom";                                  // Sender email	
    $mail->FromName = "$mailFromName";                          // Sender name

    foreach ($admin_email_data as $admin) {                     // Add a recipient
        $AddAddress = $admin['email'];
        $AddName = $admin['givenname'];
        $mail->AddAddress("$AddAddress", "$AddName");
    }

    $mail->WordWrap = 50;                                       // set line lenght
    $mail->AddAttachment(ROOT_DIR . 'images/su_logo.png', 'SU_Logo.png');    // Optional attachments name
    $mail->IsHTML(true);                                        // Set email format to HTML
    $mail->Subject = "$title";                                  // set message subject
    $mail->Body = "$text";                                      // set message body
    $mail->AltBody = "$text";                                   // set alternative message body
    $mail->SMTPDebug = 1;                                       // set smtp debug to show eror

    if (!$mail->Send()) {
        $mail_error = $mail->ErrorInfo;
        $error = "User: '" . $user->getId() . "' failed sending message: '$mail_error'";
        error($error);
        // set message cookie
        $cookie_key = 'msg';
        $cookie_value = 'Съжаляваме, за причиненото неудобство!<br/>Възникна техническа грешка, поради която съобщението не може да бъде изпратено!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location:' . ROOT_DIR . '?page=survey_role');
    }

    $info = "User: '" . $user->getId() . "' sent message '$title'";
    info($info);
}

// message submit
function message_submit() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) || !isset($_POST['formMessageSubmit']) || !isset($_POST['formMessage'])) {
        logout();
        die();
    }

    // get message data from $_POST
    $title = filter_input(INPUT_POST, 'messageTitle');
    $text = filter_input(INPUT_POST, 'messageText');
    $time_now = date("Y-m-d H:i:s");

    // send mail
    send_mail($title, $text);

    // set message object
    $message = new Message();
    $message->setIsActive(1);
    $message->setCreatedOn($time_now);
    $message->setLastEditedOn($time_now);
    $message->setUser($user->getId());
    $message->setTitle($title);
    $message->setText($text);

    // store message object in db
    $message->store_in_db();

    // set message cookie
    $cookie_key = 'msg';
    $cookie_value = 'Благодарим Ви за съобщението!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('location:' . ROOT_DIR . '?page=survey_role');
}

// survey submit
function survey_submit() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) || !isset($_POST['formSurveySubmit'])) {
        logout();
        die();
    }

    // create empty array for $_POST container
    $post = array();

    // escape mysql injections array
    foreach ($_POST as $key => $value) {
        $post[$key] = stripslashes($value);
    }

    $post_keys = array_keys($_POST);
    $substring = 'Answer';
    $pattern = '/' . $substring . '/';
    $survey_keys = preg_grep($pattern, $post_keys);


    foreach ($survey_keys as $key) {
        // get question
        preg_match_all('!\d+!', $key, $matches);
        $question_id = $matches[0][0];

        $question = new Question();
        $question->get_from_db($question_id);

        //get answer value
        $answer_value = $_POST[$key];

        //get answer id
        $answer_id = $answer_value;
        if (isset($matches[0][1])) {
            $answer_id = $matches[0][1];
        }

        // get current time
        $time_now = date("Y-m-d H:i:s");

        // create vote object
        $vote = new Vote();
        $vote->setIsActive(1);
        $vote->setCreatedOn($time_now);
        $vote->setLastEditedOn($time_now);
        $vote->setUser($user->getId());
        $vote->setSurvey($question->getSurvey());
        $vote->setQuestion($question_id);
        $vote->setAnswer($answer_id);
        $vote->setValue($answer_value);
        $vote->store_in_db();
    }

    // set message cookie
    $cookie_key = 'msg';
    $cookie_value = 'Благодарим Ви за отговорения въпрос!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('location:' . ROOT_DIR . '?page=survey');
}

// login mysql
function login_mysql($username, $password) {
    // set connection var
    global $db;

    // encode password string
    $password_hash = md5($password);

    $sql = "SELECT id
        FROM users
        WHERE is_active='1' AND username='$username' AND password='$password_hash'";

    $user_data = array();
    foreach ($db->query($sql) as $key => $value) {
        $user_data[$key] = $value;
    }

    // write info log
    $info = "Username: '$username' - try to login";
    info($info);

    if (isset($user_data[0])) {
        $info = "Username: '$username' - login success";
        info($info);

        // create user obj
        $user = new User();
        $user->get_from_db($user_data[0]['id']);

        // store user in session
        $_SESSION['user'] = serialize($user);

        // redirect to role_survey page
        $url = ROOT_DIR . '?page=survey_role';
        header('location:' . $url);
        die();
    } else {
        $error = "Username: '$username' - login fail. No such username or password";
        error($error);

        // set message cookie
        $cookie_key = 'msg';
        $cookie_value = 'Невалидно потребителско име или парола';
        setcookie($cookie_key, $cookie_value, time() + 1);

        // logout from the application
        $url = ROOT_DIR . '?funct=logout';
        header('location:' . $url);
        die();
    }
}

// login ldap
function login_ldap($username, $password) {
    // ldap connecting
    // must be a valid LDAP server!
    $ds = ldap_connect("ds.uni-sofia.bg");

    // try ldap bind
    if ($ds) {
        try {
            // binding to ldap server
            $user_dn = "uid=$username,ou=People,dc=uni-sofia,dc=bg";
            $userbind = ldap_bind($ds, $user_dn, $password);
            // verify binding
            if ($userbind) {

                header('Content-Type: text/html; charset=utf-8');

                // set ldap bind variables
                $ldaprdn = 'uid=survey,ou=People,dc=uni-sofia,dc=bg';
                $ldappass = 'fee2noh7Sh';

                // binding to ldap server
                $ldapbind = ldap_bind($ds, $ldaprdn, $ldappass);

                // verify binding
                if ($ldapbind) {

                    // data array 
                    $array = array("displayname", "mail", "title", "suscientifictitle", "suscientificdegree", "suFaculty", "suDepartment", "suStudentFaculty", "ou", "objectclass");
                    //$array = array("displayname", "mail", "title");
                    $sr = ldap_search($ds, "ou=People,dc=uni-sofia,dc=bg", "(uid=$username)", $array, 0, 0, 0);

                    $pass = md5($password);
                    $email = "";
                    $givenname = "";
                    $title = "";
                    $staff_groups = "";
                    $student_groups = "";
                    $staff_groups_id = array();
                    $student_groups_id = array();
                    $student_groups_array = array();
                    $staff_groups_array = array();

                    $info = ldap_get_entries($ds, $sr);

                    for ($i = 0; $i < count($info); $i++) {
                        if (isset($info[$i]['mail'])) {
                            $email = $info[$i]['mail'][0];
                        }
                        if (isset($info[$i]['displayname'])) {
                            $givenname = $info[$i]['displayname'][0];
                        }
                        if (isset($info[$i]['title'])) {
                            $title .= $info[$i]['title'][0];
                        }
                        if (isset($info[$i]['suscientifictitle'])) {
                            $title .= " " . $info[$i]['suscientifictitle'][0];
                        }
                        if (isset($info[$i]['suscientificdegree'])) {
                            $title .= " " . $info[$i]['suscientificdegree'][0];
                        }
                        if (isset($info[$i]['objectclass'])) {
                            if (in_array("suStudentPerson", $info[$i]['objectclass']) && !in_array("suFacultyPerson", $info[$i]['objectclass'])) {
                                if (isset($info[$i]['sustudentfaculty'])) {
                                    foreach ($info[$i]['sustudentfaculty'] as $student_group) {
                                        if (!is_int($student_group)) {
                                            array_push($student_groups_array, $student_group);
                                        }
                                    }
                                } elseif (isset($info[$i]['sufaculty'])) {
                                    foreach ($info[$i]['sufaculty'] as $student_group) {
                                        if (!is_int($student_group)) {
                                            array_push($student_groups_array, $student_group);
                                        }
                                    }
                                }
                            }
                            if (in_array("suStaffPerson", $info[$i]['objectclass']) || in_array("suFacultyPerson", $info[$i]['objectclass'])) {
                                if (isset($info[$i]['sufaculty'])) {
                                    foreach ($info[$i]['sufaculty'] as $staff_group) {
                                        if (!is_int($staff_group) && !in_array($staff_group, $student_groups_array)) {
                                            array_push($staff_groups_array, $staff_group);
                                        }
                                    }
                                }
                                if (isset($info[$i]['sudepartment'])) {
                                    foreach ($info[$i]['sudepartment'] as $staff_group) {
                                        if (!is_int($staff_group)) {
                                            array_push($staff_groups_array, $staff_group);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // get the ids of the staff groups
                    foreach ($staff_groups_array as $staff_group_name) {
                        $staff_group_ids = get_group_by_name($staff_group_name);
                        if (!empty($staff_group_ids)) {
                            foreach ($staff_group_ids as $group_id) {
                                $group = new Group();
                                $group->get_from_db($group_id);
                                if ($group->getLocal() == "0" && $group->getStudent() == "0" && $group->getStaff() == "1") {
                                    array_push($staff_groups_id, $group_id);
                                }
                            }
                        }
                    }

                    // get the ids of the student groups
                    foreach ($student_groups_array as $student_group_name) {
                        $student_group_ids = get_group_by_name($student_group_name);
                        if (!empty($student_group_ids)) {
                            foreach ($student_group_ids as $group_id) {
                                $group = new Group();
                                $group->get_from_db($group_id);
                                if ($group->getLocal() == "0" && $group->getStudent() == "1" && $group->getStaff() == "0") {
                                    array_push($student_groups_id, $group_id);
                                }
                            }
                        }
                    }

                    // set common properties
                    $staff_groups = serialize($staff_groups_id);
                    $student_groups = serialize($student_groups_id);
                    $user = new User();

                    $user->setUsername($username);
                    $user->setPassword($pass);
                    $user->setLocal(0);

                    $user_exists = get_user_by_username($username);
                    $time_now = date("Y-m-d H:i:s");

                    if (!empty($user_exists)) {
                        $user->get_from_db($user_exists[0]);
                        $user->setGivenname($givenname);
                        $user->setTitle($title);
                        $user->setStaffGroups($staff_groups);
                        $user->setStudentGroups($student_groups);
                        $user->setId($user_exists[0]);
                        $user->setLastEditedOn($time_now);
                        $user->update_in_db();
                        $info = "User: id " . $user->getId() . " update in db";
                        info($info);
                    } else {
                        $user->setEmail($email);
                        $user->setCanVote(1);
                        $user->setCanAsk(0);
                        $user->setAdmin(0);
                        $user->setGivenname($givenname);
                        $user->setTitle($title);
                        $user->setStaffGroups($staff_groups);
                        $user->setStudentGroups($student_groups);
                        $user->setLocalGroups(serialize(array()));
                        $user->setIsActive(1);
                        $user->setCreatedOn($time_now);
                        $user->setLastEditedOn($time_now);
                        $user->store_in_db();
                        $info = "User: $username added in db";
                        info($info);
                    }

                    ldap_close($ds);
                }
            }
        } catch (Exception $e) {
            $error = "User: $username failed login: $e";
            error($error);
        }
    } else {
        $error = "LDAP server unenable";
        error($error);
    }
}

// login
function login() {
    // check for injection
    if (!sizeof(filter_input(INPUT_POST, 'username')) || !sizeof(filter_input(INPUT_POST, 'password'))) {

        // set message cookie
        $cookie_key = 'msg';
        $cookie_value = 'Невалиден достъп до приложението!';
        setcookie($cookie_key, $cookie_value, time() + 1);

        // escape the php file
        logout();
        die();
    } else {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        // try auth by ldap
        try {
            login_ldap($username, $password);
        } catch (Exception $exc) {
            error($exc->getTraceAsString());
        }
        // try auth by mysql
        try {
            login_mysql($username, $password);
        } catch (Exception $exc) {
            error($exc->getTraceAsString());
        }
    }
}

// get groups by creator's user_id
function get_groups_by_creator($user_id) {
    // set connection var
    global $db;

    //query to get all associated surveys
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND local = '1' AND created_by = '$user_id';";

    $groups_data = array();
    $groups = array();

    foreach ($db->query($sql) as $key => $value) {
        $groups_data[$key] = $value;
        foreach ($groups_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $groups[] = $subvalue;
            }
        }
    }

    return $groups;
}

// add survey group type
function add_survey_group_type() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyAddGroupSubmit']) or ! isset($_POST['formSurveyAddGroup'])) {
        if ($_POST['formSurveyAddGroup'] != 'formSurveyAddGroup') {
            logout();
            die();
        }
    }

    if (!isset($_POST['formSurveyAddGroupType'])) {
        $cookie_key = 'msg';
        $cookie_value = 'Моля изберете тип на анкетната група преди да натиснете добави!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_edit');
    }

    if (isset($_SESSION['session_groups'])) {
        $session_groups = unserialize($_SESSION['session_groups']);
    } else {
        $session_groups = array(
            'type' => '',
            'student' => array(),
            'staff' => array(),
            'staff_departments' => array(),
            'local' => array());
    }

    if (isset($_SESSION['survey_id'])) {
        $survey = new Survey();
        $survey->get_from_db($_SESSION['survey_id']);
        $studentGroups = unserialize($survey->getStudentGroups());
        if (is_array($studentGroups)) {
            $session_groups['student'] = $studentGroups;
        }
    }

    echo '<pre>';
    var_dump($session_groups);
    echo '</pre>';

    $session_groups['type'] = $_POST['formSurveyAddGroupType'];

    echo '<pre>';
    var_dump($session_groups);
    echo '</pre>';

    $_SESSION['session_groups'] = serialize($session_groups);

    $cookie_key = 'msg';
    $cookie_value = 'Вие избрахте тип на анкетната група.<br/>Моля изберете група(и) от дадените опции!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// get susi student groups
function get_susi_student_groups() {
    // set connection var
    global $db;

    //query to get student groups str
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND student = '1'
            ORDER BY name ASC;";

    $groups = array();
    $student_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups[$key] = $value;
        foreach ($groups[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $student_groups[] = $subvalue;
            }
        }
    }

    return $student_groups;
}

// add survey group susi students
function add_survey_group_susi_student() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyAddGroupSusiStudentSubmit']) or ! isset($_POST['formSurveyAddGroupSusiStudent'])) {
        if ($_POST['formSurveyAddGroupSusiStudent'] != 'formSurveyAddGroupSusiStudentGroup') {
            logout();
            die();
        }
    }

    $session_groups = unserialize($_SESSION['session_groups']);
    if ($_POST['formSurveyAddGroupSusiStudentGroup'][0] == '0') {
        $session_groups['student'] = get_susi_student_groups();
    } else {
        $session_groups['student'] = $_POST['formSurveyAddGroupSusiStudentGroup'];
    }

    $session_groups['type'] = '';

    $_SESSION['session_groups'] = serialize($session_groups);

    $cookie_key = 'msg';
    $cookie_value = 'Вие добавихте анкетната група студенти!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// get susi student groups
function get_susi_staff_faculties() {
    // set connection var
    global $db;

    //query to get student groups str
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND student = '0' AND parent_id = '0'
            ORDER BY name ASC;";

    $groups = array();
    $faculties_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups[$key] = $value;
        foreach ($groups[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $faculties_groups[] = $subvalue;
            }
        }
    }

    return $faculties_groups;
}

// get susi student groups
function get_susi_staff_departments_by_faculty($faculty_susi_id) {
    // set connection var
    global $db;

    //query to get student groups str
    $sql = "SELECT id
            FROM groups
            WHERE is_active = '1' AND student = '0' AND parent_id = '$faculty_susi_id'
            ORDER BY name ASC;";

    $groups = array();
    $departments_groups = array();
    foreach ($db->query($sql) as $key => $value) {
        $groups[$key] = $value;
        foreach ($groups[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $departments_groups[] = $subvalue;
            }
        }
    }

    return $departments_groups;
}

// add survey group susi students
function add_survey_group_susi_staff_faculty() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyAddGroupSusiStaffFacultySubmit']) or ! isset($_POST['formSurveyAddGroupSusiStaffFaculty'])) {
        if ($_POST['formSurveyAddGroupSusiStaffFaculty'] != 'formSurveyAddGroupSusiStaffFaculty') {
            logout();
            die();
        }
    }

    $session_groups = unserialize($_SESSION['session_groups']);
    if ($_POST['formSurveyAddGroupSusiStaffFacultyGroup'][0] == '0') {
        $session_groups['staff'] = get_susi_staff_faculties();
    } else {
        $session_groups['staff'] = $_POST['formSurveyAddGroupSusiStaffFacultyGroup'];
    }

    $session_groups['type'] = 'staff_departments';

    $_SESSION['session_groups'] = serialize($session_groups);

    $cookie_key = 'msg';
    $cookie_value = 'Вие добавихте анкетната група служители.<br/>Можете да изберете съответна подгрупа!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// add survey group susi students
function add_survey_group_susi_staff_faculty_department() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyAddGroupSusiStaffFacultyDepartmentSubmit']) or ! isset($_POST['formSurveyAddGroupSusiStaffFacultyDepartment'])) {
        if ($_POST['formSurveyAddGroupSusiStaffFacultyDepartment'] != 'formSurveyAddGroupSusiStaffFacultyDepartment') {
            logout();
            die();
        }
    }

    $session_groups = unserialize($_SESSION['session_groups']);
    if ($_POST['formSurveyAddGroupSusiStaffFacultyDepartmentGroup'][0] == '0') {
        $session_groups['staff_departments'] = array();
    } else {
        $session_groups['staff_departments'] = $_POST['formSurveyAddGroupSusiStaffFacultyDepartmentGroup'];
    }

    $session_groups['type'] = '';
    $session_groups['staff'] = '';

    $_SESSION['session_groups'] = serialize($session_groups);

    $cookie_key = 'msg';
    $cookie_value = 'Вие добавихте анкетната група служители!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// add local group
function add_survey_group_local() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyAddGroupLocalSubmit']) or ! isset($_POST['formSurveyAddGroupLocal'])) {
        if ($_POST['formSurveyAddGroupLocal'] != 'formSurveyAddGroupLocal') {
            logout();
            die();
        }
    }

    $session_groups = unserialize($_SESSION['session_groups']);
    if ($_POST['formSurveyAddGroupLocalGroup'][0] == '0') {
        $session_groups['local'] = get_local_groups_by_creator($user->getId());
    } else {
        $session_groups['local'] = $_POST['formSurveyAddGroupLocalGroup'];
    }

    $session_groups['type'] = '';

    $_SESSION['session_groups'] = serialize($session_groups);

    $cookie_key = 'msg';
    $cookie_value = 'Вие добавихте анкетната група служители!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// delete group type
function delete_group_type() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_SESSION['session_groups'])) {
        logout();
        die();
    }

    $session_group = unserialize($_SESSION['session_groups']);
    $session_group['type'] = '';
    $_SESSION['session_groups'] = serialize($session_group);

    $cookie_key = 'msg';
    $cookie_value = 'Вие демаркирахте тип за група на анкетата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// add survey element
function add_survey_element() {
    // get global db object
    global $db;

    // get global user object
    global $user;

    $session_question = new Question();
    $session_question = get_session_question();

    // get current time
    $time_now = date("Y-m-d H:i:s");

    // protect from unauthorized access
    if ((!isset($user)) or ( !isset($_POST['formSurveyAddElementSubmit'])) or ( !isset($_POST['formSurveyAddAnswer'])) or ( !isset($_POST['formSurveyAddAnswerType']))) {
        if ($_POST['formSurveyAddElementNew'] != 'formSurveyAddElementNew') {
            logout();
            die();
        }
    }

    // set question obj
    $session_question = new Question();
    $session_question = get_session_question();
    $session_question->setTitle($_POST['formSurveyAddElementTitle']);
    $session_question->setType($_POST['formSurveyAddElementType']);
    $session_question->setCreatedOn($time_now);
    $session_question->setLastEditedOn($time_now);
    $_SESSION['session_question'] = serialize($session_question);

    // get session answers
    $session_answers = array();
    $session_answers = get_session_answers();
    if (empty($session_answers)) {
        $cookie_key = 'msg';
        $cookie_value = 'Моля, добавете поне един поделемент!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_edit');
        die();
    }

    $session_survey = new Survey();
    $session_survey = get_session_survey();

    if ($session_survey->getId() == NULL) {
        $session_survey->setStatus(0);
        $session_survey->setCreatedBy($user->getId());
        $session_survey->store_in_db();
        // get survey id
        $sql = "SELECT id
            FROM surveys
            WHERE is_active = '0'
            ORDER BY id DESC
            LIMIT 1;";

        $data = array();
        foreach ($db->query($sql) as $key => $value) {
            $data[$key] = $value;
        }

        // set id to session survey
        $survey_id = $data[0]['id'];
        $session_survey->setId($survey_id);
        $_SESSION['session_survey'] = serialize($session_survey);

        // store question in db
    } else {
        $session_survey->setLastEditedOn($time_now);
        $session_survey->update_in_db();
    }

    $session_question->setSurvey($session_survey->getId());
    $session_question->setIsActive(1);
    $session_question->setLastEditedOn($time_now);

    // check if the question exists in db
    if (!is_int($session_question->getId()) || !($session_question->getId() > 0)) {
        $session_question->setCreatedOn($time_now);
        $session_question->store_in_db();

        // get last question id
        $sql = "SELECT id
            FROM questions
            WHERE is_active = '1'
            ORDER BY id DESC
            LIMIT 1;";
        $data = array();
        foreach ($db->query($sql) as $key => $value) {
            $data[$key] = $value;
        }

        // set id to session question
        $session_question->setId($data[0]['id']);
    } else {
        $session_question->update_in_db();
    }

    // unset session question to release memory
    $question_empty = new Question();
    $_SESSION['session_question'] = serialize($question_empty);

    // store answers in db
    foreach ($session_answers as $session_answer) {
        $answer = new Answer();
        $answer = $session_answer;
        $answer->setQuestion($session_question->getId());
        $answer->setIsActive(1);
        $answer->setLastEditedOn($time_now);

        if ($answer->getId() != NULL) {
            $answer->update_in_db();
        } else {
            $answer->setCreatedOn($time_now);
            $answer->store_in_db();
        }
    }

    $session_answers = array();
    $_SESSION['session_answers'] = serialize($session_answers);
    $session_question = new Question();
    $_SESSION['session_question'] = serialize($session_question);

    $cookie_key = 'msg';
    $cookie_value = 'Вие успешно добавихте/редактирахте елемент от анкетата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// add survey element
function edit_survey_element() {
    // get global db object
    global $db;

    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) || (!isset($_POST['formElementEdit']))) {
        logout();
        die();
    }

    // get and set session answer
    $session_question = new Question();
    $session_question->get_from_db($_POST['formElementId']);

    $survey = new Survey();
    $survey->get_from_db($session_question->getSurvey());
    if ($survey->getCreatedBy() != $user->getId()) {
        if ($user->getAdmin() != 1) {
            logout();
            die();
        }
    }

    $_SESSION['session_question'] = serialize($session_question);

    // get session answers
    $session_answers = array();
    $session_answer_ids = get_survey_answers($session_question->getId());
    foreach ($session_answer_ids as $answer_id) {
        $answer = new Answer();
        $answer->get_from_db($answer_id);
        array_push($session_answers, $answer);
    }
    $_SESSION['session_answers'] = serialize($session_answers);

    $cookie_key = 'msg';
    $cookie_value = 'Вие избрахте елемент от анкетата за редакция!<br />Отидете на раздел "Добавете или редактирайте елемент към анкетата"';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// add local group
function add_survey_answer() {
    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyAddAnswerSubmit']) or ! isset($_POST['formSurveyAddAnswer']) or ! isset($_POST['formSurveyAddAnswerType'])) {
        if ($_POST['formSurveyAddAnswerNew'] != 'formSurveyAddAnswerNew') {
            logout();
            die();
        }
    }

    // set empty answer obj
    $session_answers = get_session_answers();

    $answer = new Answer();
    $answer->setValue($_POST['formSurveyAddAnswer']);
    $answer->setDescription($_POST['formSurveyAddAnswerDescription']);
    $answer->setType($_POST['formSurveyAddAnswerType']);

    if ($answer->getType() == 'null') {
        $cookie_key = 'msg';
        $cookie_value = 'Моля, изберете тип на отговора за анкетната, за да го добавите!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_add_answer');
        die();
    }

    array_push($session_answers, $answer);

    $_SESSION['session_answers'] = serialize($session_answers);

    $cookie_key = 'msg';
    $cookie_value = 'Вие добавихте поделемент в анкетата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_add_answer');
}

function delete_question() {
    // get global user object
    global $db;

    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) || (!isset($_SESSION['session_survey']))) {
        logout();
        die();
    }

    $question = new Question();
    $question->get_from_db($_GET['question_id']);
    $survey = new Survey();
    $survey->get_from_db($question->getSurvey());

    if ($survey->getCreatedBy() != $user->getId()) {
        if ($user->getAdmin() != 1) {
            logout();
            die();
        }
    }

    $question->setIsActive(0);
    $question->update_in_db();

    $cookie_key = 'msg';
    $cookie_value = 'Вие успешно изтрихте елемент от анкетата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
    die();
}

// delete session answer
function delete_session_answer($session_answer_id) {
    // get global user object
    global $db;

    // get global user object
    global $user;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_SESSION['session_answers'])) {
        logout();
        die();
    }

    $answer = new Answer();

    // get session answers
    $session_answers = get_session_answers();
    if (isset($session_answers[$session_answer_id])) {
        $answer = $session_answers[$session_answer_id];
        if ($answer->getId() != 'null') {
            $answer_id = $answer->getId();
            $sql = "UPDATE answers
                SET is_active = '0'
                WHERE is_active = '1' AND id = '$answer_id';";
            $db->exec($sql);
        }
        unset($session_answers[$session_answer_id]);
    }
    $_SESSION['session_answers'] = serialize($session_answers);

    $cookie_key = 'msg';
    $cookie_value = 'Вие успешно изтрихте поделемент от анкетата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// delete session groups
function delete_session_group() {
    // get global user object
    global $user;

//    var_dump($_SESSION);
//    die();
    // protect from unauthorized access
    if (!isset($user) or ! isset($_SESSION['session_groups'])) {
        logout();
        die();
    }

    // get the URL query string
    $query_str = $_SERVER['QUERY_STRING'];

    // parse the URL query string to array
    $query = array();
    parse_str($query_str, $query);

    if (!isset($query['session_group_type']) || !isset($query['session_group_id'])) {
        $cookie_key = 'msg';
        $cookie_value = 'Неоторизиран достъп!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        logout();
        die();
    }

    $session_group_type = $query['session_group_type'];
    $session_group_id = $query['session_group_id'];
    $session_groups = unserialize($_SESSION['session_groups']);

    if (isset($session_groups[$session_group_type][$session_group_id])) {
        unset($session_groups[$session_group_type][$session_group_id]);
    }

    $_SESSION['session_groups'] = serialize($session_groups);

    $cookie_key = 'msg';
    $cookie_value = 'Вие успешно изтрихте група за анкетата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_edit');
}

// survey function
function survey_funct() {
    // get global user object
    global $user;

    // set connection var
    global $db;

    // get current time
    $time_now = date("Y-m-d H:i:s");

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyFunction'])) {
        logout();
        die();
    }

    // set empty survey
    $session_survey = new Survey();
    $session_survey = get_session_survey();

    $survey_id = $_POST['formSurveyFunction'];
    if ($survey_id != "") {
        $session_survey->get_from_db($survey_id);
    }

    // get the function
    $function = '';

    foreach ($_POST as $key => $post) {
        if ($post != $survey_id) {
            $function = substr($key, 10);
        }
    }

    if ($function == 'Print') {
        $_SESSION['survey_id'] = $survey_id;
        header('location: ' . ROOT_DIR . '?print=survey_print');
        die();
    } elseif ($function == 'Remove') {
        if ($session_survey->getId() != NULL) {
            //query to delete survey
            $session_survey->setIsActive(0);
            $session_survey->update_in_db();
        }
        $cookie_key = 'msg';
        $cookie_value = 'Вие успешно изтрихте Ваша анкета!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=admin_survey');
        die();
    } elseif ($function == 'Reset') {
        if (isset($_SESSION['session_survey'])) {
            unset($_SESSION['session_survey']);
        }
        if (isset($_SESSION['session_groups'])) {
            unset($_SESSION['session_groups']);
        }
        if (isset($_SESSION['session_answers'])) {
            unset($_SESSION['session_answers']);
        }
        if (isset($_SESSION['session_question'])) {
            unset($_SESSION['session_question']);
        }
        header('location: ' . ROOT_DIR . '?page=survey_edit');
        die();
    } elseif ($function == 'Edit') {
        // check if post a survey id and asign
        if (!isset($_POST['formSurveyFunction'])) {
            // or go back
            $cookie_key = 'msg';
            $cookie_value = 'Не е избрана анкета!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=admin_survey');
            die();
        }

        $session_survey->get_from_db(intval($_POST['formSurveyFunction']));
        // check for illegal access
        if (($session_survey->getCreatedBy() != $user->getId()) &&
                ($user->getAdmin() != 1)) {
            error('Опит за неоторизиран достъп!');
            $cookie_key = 'msg';
            $cookie_value = 'Опит за неоторизиран достъп!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=admin_survey');
            die();
        }

        $_SESSION['session_survey'] = serialize($session_survey);
        $session_groups = array();
        $session_groups['type'] = '';
        $session_groups['student'] = get_survey_student_groups($session_survey->getId());
        $session_groups['staff'] = get_survey_staff_groups($session_survey->getId());
        $session_groups['local'] = get_survey_local_groups($session_survey->getId());
        $_SESSION['session_groups'] = serialize($session_groups);

        $cookie_key = 'msg';
        $cookie_value = 'Вие избрахте анкета за редакция!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_edit');
        die();
    } elseif ($function == 'Save') {
        // check for answers
        $session_answers = array();
        $session_answers = get_session_answers();


        $available_from = $_POST['formSurveyFromDate'] . " " . $_POST['formSurveyFromHour'] . ":00";
        $available_due = $_POST['formSurveyDueDate'] . " " . $_POST['formSurveyDueHour'] . ":00";
        $title = $_POST['formSurveyTitle'];
        $status = $_POST['formSurveyStatus'];

        $session_survey->setIsActive(1);
        $session_survey->setCreatedOn($time_now);
        $session_survey->setLastEditedOn($time_now);
        $session_survey->setAvailableFrom($available_from);
        $session_survey->setAvailableDue($available_due);
        $session_survey->setTitle(htmlspecialchars($title));
        $session_survey->setStatus($status);
        $_SESSION['session_survey'] = serialize($session_survey);

        // check for groups
        $session_groups = array();
        $session_groups = get_session_groups();
        if (empty($session_groups['student']) &&
                empty($session_groups['staff']) &&
                empty($session_groups['staff_departments']) &&
                empty($session_groups['local'])) {
            $cookie_key = 'msg';
            $cookie_value = 'Моля, добавете поне една анкетна група!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=survey_edit');
            die();
        }

        if (isset($session_groups['staff_departments']) && is_array($session_groups['staff_departments'])) {
            if (is_array($session_groups['staff'])) {
                $session_groups['staff'] = array_merge($session_groups['staff'], $session_groups['staff_departments']);
            } else {
                $session_groups['staff'] = $session_groups['staff_departments'];
            }
        }

        $session_survey->setStudentGroups(serialize($session_groups['student']));
        $session_survey->setStaffGroups(serialize($session_groups['staff']));
        $session_survey->setLocalGroups(serialize($session_groups['local']));

        if ($session_survey->getId() != NULL) {
            $session_survey->update_in_db();
            $_SESSION['session_survey'] = serialize($session_survey);

            $cookie_key = 'msg';
            $cookie_value = 'Вие успешно добавихте/редактирахте анкета!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=survey_edit');
            die();
        } else {
            $cookie_key = 'msg';
            $cookie_value = 'Моля, добавете поне един елемент към анкетата!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=survey_edit');
            die();
        }
        unset($_SESSION['session_groups']);
    } elseif ($function == 'VoteDelete') {
        if (!isset($_SESSION['session_user']) || !isset($_SESSION['session_user'])) {
            logout();
            die();
        }

        $survey_id = $_POST['formSurveyFunction'];
        $session_user = new User();
        $session_user = unserialize($_SESSION['session_user']);
        $user_id = $session_user->getId();
        $time_now = date("Y-m-d H:i:s");

        $sql = "UPDATE votes
                SET is_active = '0'
                    last_edited_ob = '$time_now'
                WHERE   is_active = '1'
                        AND user_id = '$user_id'
                        AND survey_id = '$survey_id'";

        try {
            $db->exec($sql);
            $info = "Delete vote in db for user:" . $session_user->getId() . " for survey: $survey_id";
            info($info);
        } catch (PDOException $e) {
            $error = "Delete vote in db error:" . $e->getTraceAsString();
            error($error);
        }

        $cookie_key = 'msg';
        $cookie_value = 'Вие успешно изтрихте вот на потребителя!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_user');
        die();
    } elseif ($function == 'UserView') {
        $survey_id = $_POST['formSurveyFunction'];

        $_SESSION['surveyUserViewSurveyId'] = $survey_id;

        var_dump($_SESSION);
        header('Location: ' . ROOT_DIR . '?page=survey');
        die();
    } elseif ($function == 'UserVote') {
        $survey_id = $_POST['formSurveyFunction'];

        $_SESSION['surveyUserViewSurveyId'] = $survey_id;

        var_dump($_SESSION);
        header('Location: ' . ROOT_DIR . '?page=survey');
        die();
    } elseif ($function == 'PrintExcel') {
        // get global user object
        global $user;

        // get survey id
        $survey_id = $_POST['formSurveyFunction'];

        // check if the user is the surveyCreator or systemAdmin
        $survey = new Survey();
        $survey->get_from_db($survey_id);

        if ((intval($survey->getCreatedBy()) != $user->getId()) && ($user->getAdmin() != 1)) {
            logout();
            die();
        }

        header('Location: ' . ROOT_DIR . 'functions/print/excel/surveyReport.php?survey_id=' . $survey_id);

        die();
    } elseif ($function == 'UserVoteDelele') {
        // get global user object
        global $user;

        // secure the function
        if ($user->getAdmin() != 1) {
            logout();
            die();
        }

        $user_id = $_GET['user_id'];
        $survey_id = $_POST['formSurveyFunction'];

        $surveyFunctions = new SurveyFunctions();
        $surveyFunctions->get_from_db($survey_id);
        $surveyVotes = array();
        $surveyVotes = $surveyFunctions->getVotesByUser($user_id);

        $user = new User();
        $user->get_from_db($user_id);

        if (!empty($surveyVotes)) {
            foreach ($surveyVotes as $surveyVoteId) {
                $surveyVote = new Vote();
                $surveyVote->get_from_db($surveyVoteId);
                $surveyVote->setIsActive(0);
                $surveyVote->update_in_db();
            }
            $cookieKey = 'msg';
            $cookieValue = 'Гласуването на съответния потребител беше успешно изтрито!';
            setcookie($cookieKey, $cookieValue, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=admin_system_user_edit');
            die();
        }

        $cookieKey = 'msg';
        $cookieValue = 'Няма налично гласуването за съответния потребител!';
        setcookie($cookieKey, $cookieValue, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=admin_system_user_edit');
        die();
    }
    die();
}

// delete session group user
function delete_session_group_user() {
    // protect from unauthorized access
    if (!isset($_SESSION['user']) or ! isset($_SESSION['group'])) {
        logout();
        die();
    }

    // get the URL query string
    $query_str = $_SERVER['QUERY_STRING'];

    // parse the URL query string to array
    $query = array();
    parse_str($query_str, $query);

    if (!isset($query['user_id'])) {
        $cookie_key = 'msg';
        $cookie_value = 'Невалиден адрес!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=my_surveys');
    }

    $session_group = new Group;
    $session_group = unserialize($_SESSION['group']);
    $users = $session_group->getMembersArray();
    if (($key = array_search($query['user_id'], $users)) !== false) {
        unset($users[$key]);
    }
    $session_group->setMembers(serialize($users));
    $_SESSION['group'] = serialize($session_group);

    $cookie_key = 'msg';
    $cookie_value = 'Вие успешно изтрихте потребител от групата!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('Location: ' . ROOT_DIR . '?page=survey_group');
}

// survey function
function group_funct() {
    // get global user object
    global $user;

    // set connection var
    global $db;

    // protect from unauthorized access
    if (!isset($user) or ! isset($_POST['formSurveyGroupFunction'])) {
        logout();
        die();
    }

    $group_id = $_POST['formSurveyGroupFunction'];
    $function = '';

    foreach ($_POST as $key => $post) {
        if ($post != $group_id) {
            $function = substr($key, 15);
        }
    }

    if ($function == 'Print') {
        $_SESSION['group_id'] = $group_id;
        header('location: ' . ROOT_DIR . '?print=group_print');
    } elseif ($function == 'Edit') {
        $_SESSION['group_id'] = $group_id;
        $cookie_key = 'msg';
        $cookie_value = 'Редакция на анкетна група!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=survey_group');
        die();
    } elseif ($function == 'Reset') {
        if (isset($_SESSION['group'])) {
            unset($_SESSION['group']);
        }
        if (isset($_SESSION['group_id'])) {
            unset($_SESSION['group_id']);
        }
        $cookie_key = 'msg';
        $cookie_value = 'Създаване на нова група!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=survey_group');
        die();
    } elseif ($function == 'Remove') {
        $group = new Group();
        $group->get_from_db($group_id);
        $group->setIsActive(0);
        $group->update_in_db();
        $cookie_key = 'msg';
        $cookie_value = 'Вие успешно изтрихте Ваша група!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=my_surveys');
    } elseif ($function == 'Create') {
        echo $function;

        if (!isset($_SESSION['group'])) {
            $error = "Unauthorized try for group creating";
            error($error);
            logout();
        }

        $groupName = filter_input(INPUT_POST, 'formSurveyGroupName');
        $groupDescription = filter_input(INPUT_POST, 'formSurveyGroupDescription');
        $groupAbbreviation = filter_input(INPUT_POST, 'formSurveyGroupAbbreviation');

        $time_now = date("Y-m-d H:i:s");
        $session_group = unserialize($_SESSION['group']);
        $group = new Group();
        $group = clone $session_group;

        $group->setCreatedBy(intval($user->getId()));
        $group->setIsActive(1);
        $group->setCreatedOn($time_now);
        $group->setLastEditedOn($time_now);
        $group->setLocal(1);
        $group->setStaff(0);
        $group->setStudent(0);
        $group->setName($groupName);
        $group->setDescription($groupDescription);
        $group->setAbbreviation($groupAbbreviation);

        $group_id = $group->store_in_db();

        if ($group_id != NULL) {
            $members = unserialize($group->getMembers());
            foreach ($members as $member_id) {
                $member = new User();
                $member->get_from_db($member_id);
                $local_groups = unserialize($member->getLocalGroups());

                if (is_array($local_groups)) {
                    array_push($local_groups, $group_id);
                } else {
                    $local_groups = array($group_id);
                }

                $member->setLocalGroups(serialize($local_groups));
                $member->update_in_db();
            }
        } else {
            $cookie_key = 'msg';
            $cookie_value = 'Извиняваме се за неудобството, Вашата група нв беше създадена! Опитайте пак по-късно.';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('location: ' . ROOT_DIR . '?page=my_surveys');
        }

        var_dump($_SESSION);
        unset($_SESSION['group']);
        $cookie_key = 'msg';
        $cookie_value = 'Вашата група беше успешно създадена!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=survey_group');
    }
    die();
}

// add temp user to session group
function add_session_group_user() {
    // set connection var
    global $db;

    // protect from unauthorized access
    if (!isset($_SESSION['user']) or ! isset($_SESSION['group'])) {
        logout();
        die();
    }

    if (!isset($_POST['formSurveyGroupUserUsername']) || !isset($_POST['formSurveyGroupUserEmail']) || (($_POST['formSurveyGroupUserUsername'] == '') && ($_POST['formSurveyGroupUserEmail'] == ''))) {
        $cookie_key = 'msg';
        $cookie_value = 'Моля, въведете Потребителско Име или Email за търсене на потребителя!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_group');
        die();
    }

    $username = $_POST['formSurveyGroupUserUsername'];
    $email = $_POST['formSurveyGroupUserEmail'];

    $sql = "SELECT id
            FROM users
            WHERE ( username =  '$username'
                    OR email =  '$email')
            AND is_active =  '1';";

    $users = array();
    $new_group_user = null;
    foreach ($db->query($sql) as $key => $value) {
        $users[$key] = $value;
        foreach ($users[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $new_group_user = $subvalue;
            }
        }
    }

    if ($new_group_user != null) {
        $group = new Group();
        $group = unserialize($_SESSION['group']);
        $session_group = new Group;
        $session_group = unserialize($_SESSION['group']);
        $users = $session_group->getMembersArray();
        if (!in_array($new_group_user, $users)) {
            array_push($users, $new_group_user);
            $session_group->setMembers(serialize($users));
            $_SESSION['group'] = serialize($session_group);
            $cookie_key = 'msg';
            $cookie_value = 'Вие успешно добавихте потребител към групата!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=survey_group');
        } else {
            $cookie_key = 'msg';
            $cookie_value = 'Този потребител е вече добавен към групата!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('Location: ' . ROOT_DIR . '?page=survey_group');
        }
    } else {
        $cookie_key = 'msg';
        $cookie_value = 'Няма открит потребител с тези данни!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('Location: ' . ROOT_DIR . '?page=survey_group');
    }
}

// survey function
function user_funct() {
    // get global user object
    global $user;

    // set connection var
    global $db;

    // protect from unauthorized access
    if (!isset($user) or ( $user->getAdmin() != '1' or ! isset($_SESSION['session_user']))) {
        logout();
        die();
    }

    $session_user = new User();
    $session_user = unserialize($_SESSION['session_user']);

    $function = end($_POST);
    $function = substr(array_search(end($_POST), $_POST), 14);

    if ($function == 'Reset') {
        if (isset($_SESSION['session_user'])) {
            unset($_SESSION['session_user']);
        }
        header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
        die();
    } elseif ($function == 'Remove') {
        $session_user->setIsActive(0);
        $session_user->update_in_db();
        $cookie_key = 'msg';
        $cookie_value = 'Вие успешно изтрихте потребител от системата!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
        die();
    } elseif ($function == 'Cancel') {
        if (isset($_SESSION['session_user'])) {
            unset($_SESSION['session_user']);
        }
        header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
        die();
    } elseif ($function == 'Edit') {
        $session_user->setUsername($_POST['formSurveyUserUsername']);
        $session_user->setEmail($_POST['formSurveyUserEmail']);
        $session_user->setGivenname($_POST['formSurveyUserGivenname']);
        $session_user->setTitle($_POST['formSurveyUserTitle']);
        if (isset($_POST['formSurveyUserCanVote'])) {
            $session_user->setCanVote($_POST['formSurveyUserCanVote']);
        } else {
            $session_user->setCanVote(0);
        }
        if (isset($_POST['formSurveyUserCanAsk'])) {
            $session_user->setCanAsk($_POST['formSurveyUserCanAsk']);
        } else {
            $session_user->setCanAsk(0);
        }
        if (isset($_POST['formSurveyUserAdmin'])) {
            $session_user->setAdmin($_POST['formSurveyUserAdmin']);
        } else {
            $session_user->setAdmin(0);
        }
        $session_user->update_in_db();
        $_SESSION['session_user'] = serialize($session_user);

        $cookie_key = 'msg';
        $cookie_value = 'Вие успешно редактирахте потребител на системата!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
        die();
    } elseif ($function == 'Save') {
        $session_user->setUsername($_POST['formSurveyUserUsername']);
        if ($session_user->is_username_taken($session_user->getUsername())) {
            $_SESSION['session_user'] = serialize($session_user);
            $cookie_key = 'msg';
            $cookie_value = 'Потребителското име вече е заето!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
            die();
        }
        $session_user->setEmail($_POST['formSurveyUserEmail']);
        if ($session_user->is_email_taken($session_user->getEmail())) {
            $_SESSION['session_user'] = serialize($session_user);
            $cookie_key = 'msg';
            $cookie_value = 'Email адресът е вече зает!';
            setcookie($cookie_key, $cookie_value, time() + 1);
            header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
            die();
        }
        $session_user->setGivenname($_POST['formSurveyUserGivenname']);
        $session_user->setTitle($_POST['formSurveyUserTitle']);
        $session_user->setIsActive(1);
        if (isset($_POST['formSurveyUserCanVote'])) {
            $session_user->setCanVote($_POST['formSurveyUserCanVote']);
        } else {
            $session_user->setCanVote(0);
        }
        if (isset($_POST['formSurveyUserCanAsk'])) {
            $session_user->setCanAsk($_POST['formSurveyUserCanAsk']);
        } else {
            $session_user->setCanAsk(0);
        }
        if (isset($_POST['formSurveyUserAdmin'])) {
            $session_user->setAdmin($_POST['formSurveyUserAdmin']);
        } else {
            $session_user->setAdmin(0);
        }
        $session_user->store_in_db();
        if (isset($_SESSION['session_user'])) {
            unset($_SESSION['session_user']);
        }
        $cookie_key = 'msg';
        $cookie_value = 'Вие успешно добавихте потребител на системата!';
        setcookie($cookie_key, $cookie_value, time() + 1);
        header('location: ' . ROOT_DIR . '?page=admin_system_user_edit');
        die();
    }
    die();
}

// delete user's group
function delete_session_user_group() {
    // get global user object
    global $user;

    // get url query
    $query = get_url_query();

    // protect from unauthorized access
    if (!isset($user)
            or ( $user->getAdmin() != '1'
            or ! isset($_SESSION['session_user']))
            or ( !isset($query["page"])
            and ! isset($query["group_type"])
            and ! isset($query["group_id"]))) {
        logout();
        die();
    }

    $session_user = new User();
    $session_user = unserialize($_SESSION['session_user']);
    $group_id = $query["group_id"];

    if ($query["group_type"] == "staff") {
        $user_staff_groups = array();
        if (is_array(unserialize($session_user->getStaffGroups()))) {
            $user_staff_groups = unserialize($session_user->getStaffGroups());
        }
        if (($key = array_search($group_id, $user_staff_groups)) !== false) {
            unset($user_staff_groups[$key]);
        }
        $session_user->setStaffGroups(serialize($user_staff_groups));
    } elseif ($query["group_type"] == "student") {
        $user_student_groups = array();
        if (is_array(unserialize($session_user->getStudentGroups()))) {
            $user_student_groups = unserialize($session_user->getStudentGroups());
        }
        if (($key = array_search($group_id, $user_student_groups)) !== false) {
            unset($user_student_groups[$key]);
        }
        $session_user->setStudentGroups(serialize($user_student_groups));
    } elseif ($query["group_type"] == "local") {
        $user_local_groups = array();
        if (is_array(unserialize($session_user->getLocalGroups()))) {
            $user_local_groups = unserialize($session_user->getLocalGroups());
        }
        if (($key = array_search($group_id, $user_local_groups)) !== false) {
            unset($user_local_groups[$key]);
        }
        $session_user->setLocalGroups(serialize($user_local_groups));
    }

    $_SESSION['session_user'] = serialize($session_user);
    $cookie_key = 'msg';
    $cookie_value = 'Вие успешно премахнахте група от този потребител!';
    setcookie($cookie_key, $cookie_value, time() + 1);
    header('location: ' . ROOT_DIR . '?page=survey_user');
    die();
}

// question check if answered by user
function get_user_answers_by_question($user_id, $question_id) {
    // set connection var
    global $db;

    //  query to get all vote survey_ids for session user
    $sql = "SELECT id
            FROM votes
            WHERE is_active = '1' AND user_id = '$user_id' AND question = '$question_id';";

    $votes_data = array();
    $votes = array();
    foreach ($db->query($sql) as $key => $value) {
        $votes_data[$key] = $value;
        foreach ($votes_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $votes[] = $subvalue;
            }
        }
    }

    return $votes;
}

// get answer by user and question
function get_user_vote_by_answer($user_id, $answer_id) {
    // set connection var
    global $db;

    //  query to get all vote survey_ids for session user
    $sql = "SELECT id
            FROM votes
            WHERE is_active = '1' AND user_id = '$user_id' AND answer_id = '$answer_id';";

    $votes_data = array();
    $votes = array();
    foreach ($db->query($sql) as $key => $value) {
        $votes_data[$key] = $value;
        foreach ($votes_data[$key] as $subkey => $subvalue) {
            if (is_int($subkey)) {
                $votes[] = $subvalue;
            }
        }
    }

    return $votes;
}

?>
