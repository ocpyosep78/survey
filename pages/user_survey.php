<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<?php
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
if (isset($_SESSION['survey_id'])) {
    unset($_SESSION['survey_id']);
}
if (isset($_SESSION['group'])) {
    unset($_SESSION['group']);
}
if (isset($_SESSION['group_id'])) {
    unset($_SESSION['survey_id']);
}
if (isset($_SESSION['session_group'])) {
    unset($_SESSION['session_group']);
}
if (isset($_SESSION['group_users'])) {
    unset($_SESSION['group_users']);
}
?>
<div class="ac info_box box_green">
    <h4>
        <?php echo USER_SURVEY_HOMEPAGE_AVAILABLE_SURVEYS; ?>
    </h4>
</div>
<div class="ac">
    <div class="accordion">
        <?php
        $surveys_by_user = get_available_by_user_surveys($user->getId());
        if (!empty($surveys_by_user)) {
            foreach ($surveys_by_user as $survey_id) {
                $survey = new Survey();
                $survey->get_from_db($survey_id);
                ?>
                <h3 class="no-float ac"><?php print_r($survey->getTitle()); ?></h3>
                <div>
                    <div class="ac">
                        <div class="action no-margin ac">
                            <form id="formSurvey<?php print_r($survey->getId()); ?>"
                                  class="form ac prefix_2" 
                                  action="./?page=user_survey&amp;funct=survey_funct" 
                                  method="POST">
                                <input id="formSurveyView" 
                                       class="button button-green" 
                                       name="formSurveyUserView" 
                                       type="submit"
                                       value="<?php echo BTN_SURVEY_VIEW; ?>"
                                       style="margin-left: 50px;" />
                                <input name="formSurveyFunction" value="<?php print_r($survey->getId()); ?>" type="hidden" />
                            </form>
                            <br />
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>