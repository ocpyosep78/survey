<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<script type="text/javascript">
    // define function to catch close btn click
    function closeTab() {
        document.getElementById('formSurveyAddSubelementCancelBtn').onclick = function() {
            window.close();
        };
    }

    // execute functions
    $(document).ready(function() {
        closeTab();
    });
</script>

<div class="ac">
    <h3 class="no-float ac">
        <?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_SUBELEMENTS_ADD_SUBELEMENT_TITLE; ?>
    </h3>
    <div class="ac">
        <section>
            <form id="formSurveyAddAnswer" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_add_answer&amp;funct=add_survey_answer'; ?>" method="POST">
                <div class="ac">
                    <section class="clearfix prefix_2">
                        <label for="formSurveyAddAnswer"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_NAME; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_INFO; ?></small>
                        </label>
                        <input id="formSurveyAddAnswer" name="formSurveyAddAnswer" type="text" required="required" />
                        <br/>
                        <label for="formSurveyAddAnswerDescription"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_DESCRIPTION; ?>
                            <small><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_DESCRIPTION_INFO; ?></small>
                        </label>
                        <input id="formSurveyAddAnswerDescription" name="formSurveyAddAnswerDescription" type="text" />
                        <br/>
                        <label for="formSurveyAddAnswerType"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_INFO; ?></small>
                        </label>
                        <select id="formSurveyAddAnswerType" name="formSurveyAddAnswerType" required="required">
                            <option value="null"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_NULL; ?></option>
                            <option value="text"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_TEXT; ?></option>
                            <option value="checkbox"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_CHECKBOX; ?></option>
                            <option value="radio"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_RADIO; ?></option>
                            <option value="textbox"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_TEXTBOX; ?></option>
                        </select>
                        <br/>
                    </section>
                </div>
                <h3 class="no-float ac" style="color: red;">
                    <?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_AFTER; ?>
                </h3>
                <br/>
                <div class="action no-margin ac ui-widget" style="padding-left: 50px;">
                    <input 
                        id="formSurveyAddAnswerSubmit" 
                        class="button button-green" 
                        name="formSurveyAddAnswerSubmit" 
                        title="<?php echo SURVEY_SUBELEMENT_CONFIRM; ?>"
                        type="submit" 
                        value="<?php echo BTN_SUBMIT; ?>" />
                    <input 
                        id="formSurveyAddAnswerReset" 
                        class="button button-orange" 
                        name="formSurveyAddAnswerReset" 
                        title="<?php echo SURVEY_SUBELEMENT_CLEAR; ?>"
                        type="reset" 
                        value="<?php echo BTN_RESET; ?>" />
                    <input id="formSurveyAddAnswerNew" class="button button-green" name="formSurveyAddAnswerNew" type="hidden" value="formSurveyAddAnswerNew" />
                    <input 
                        id="formSurveyAddSubelementCancelBtn" 
                        class="button button-red fl dn jsShow" 
                        title="<?php echo SURVEY_SUBELEMENT_CLOSE_WINDOW; ?>"
                        type="button" 
                        value="<?php echo BTN_CANCEL; ?>" />
                    <a 
                        id="formSurveyAddSubelementCancelLink" 
                        class="button button-red fl dn" 
                        style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" 
                        href="<?php isset($_SERVER['HTTP_REFERER']) ? print_r($_SERVER["HTTP_REFERER"]) : print_r(ROOT_DIR . '?page=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
                </div>
            </form>
        </section>
    </div>
</div>