<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<?php
// set global var user
global $user;
if (isset($_SESSION['session_survey'])) {
    unset($_SESSION['session_survey']);
}
if (isset($_SESSION['session_question'])) {
    unset($_SESSION['session_question']);
}
if (isset($_SESSION['session_groups'])) {
    unset($_SESSION['session_groups']);
}
if (isset($_SESSION['session_answers'])) {
    unset($_SESSION['session_answers']);
}
if (isset($_SESSION['surveyCreatorViewSurveyId'])) {
    unset($_SESSION['surveyCreatorViewSurveyId']);
}
if (isset($_SESSION['session_message'])) {
    $session_message = unserialize($_SESSION['session_message']);
} else {
    $session_message = array('title' => '', 'text' => '');
}
?>
<div class="ac">
    <div class="accordion">
        <?php
        // check if can create surveys
        if ($user->getAdmin() == 1) {
            ?>
            <h3 class="no-float ac"><?php echo SURVEY_ROLE_PAGE_ADMIN_TITLE; ?></h3>
            <div>
                <?php
                    echo SURVEY_ROLE_PAGE_ADMIN_INFO;
                ?>
                <div class="action no-margin ac">
                    <br/>
                    <a class="button button-green" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php echo ROOT_DIR; ?>?page=admin_system"><?php echo BTN_ENTER; ?></a>
                    <a class="button button-red" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php print_r(ROOT_DIR . '?funct=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
                </div>
                <br/><br/><br/>
            </div>
            <?php
        }
        // check if user can create surveys
        if ($user->getCanAsk() == 1) {
            ?>
            <h3 class="no-float ac"><?php echo SURVEY_ROLE_PAGE_CAN_ASK_TITLE; ?></h3>
            <div>
                <?php
                    echo SURVEY_ROLE_PAGE_CAN_ASK_INFO;
                ?>
                <div class="action no-margin ac">
                    <br/>
                    <a class="button button-green" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php echo ROOT_DIR; ?>?page=admin_survey"><?php echo BTN_ENTER; ?></a>
                    <a class="button button-red" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php print_r(ROOT_DIR . '?funct=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
                </div>
                <br/><br/><br/>
            </div>
            <?php
        }
        // check if can vote
        if ($user->getCanVote() == 1) {
            ?>
            <h3 class="no-float ac"><?php echo SURVEY_ROLE_PAGE_CAN_VOTE_TITLE; ?></h3>
            <div>
                <?php
                    echo SURVEY_ROLE_PAGE_CAN_VOTE_INFO;
                ?>
                <div class="action no-margin ac">
                    <br/>
                    <a class="button button-green" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php echo ROOT_DIR; ?>?page=user_survey"><?php echo BTN_ENTER; ?></a>
                    <a class="button button-red" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php print_r(ROOT_DIR . '?funct=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
                </div>
                <br/><br/><br/>
            </div>
            <?php
        }
        ?>
    </div>
</div>