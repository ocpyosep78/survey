<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<div class="ac">
    <?php
    global $user;

    if (isset($_SESSION['surveyUserViewSurveyId'])) {
        $survey_id = $_SESSION['surveyUserViewSurveyId'];
        $survey = new Survey();
        $survey->get_from_db($survey_id);
    } else {
        logout();
        die();
    }
    if ($survey->getId() == "103") {
        ?>
        <div class="ac info_box" style="background-size: cover;">
            <img src="<?php echo ROOT_DIR . 'images/projectLogo.png'; ?>" style="width: 100%;" />
        </div>
        <div class="ac info_box" style="background-size: cover; background-color: #e3c0a0;">
            <img src="<?php echo ROOT_DIR . 'images/studentsLogo.png'; ?>" style="width: 100%;" />
        </div>
        <?php
    }
    ?>
    <div class="ac info_box box_green">
        <h3>
            <b><?php echo SURVEY_PAGE_SURVEY_TITLE; ?></b>
        </h3>
        <h4>
            <?php echo $survey->getTitle(); ?>
        </h4>
    </div>
    <?php
    $survey_questions = get_survey_questions($survey->getId());
    if (!empty($survey_questions)) {
        ?>
        <div class="accordion">
            <?php
            foreach ($survey_questions as $question_id) {
                $question = new Question();
                $question->get_from_db($question_id);
                if ($question->getType() == 1) {
                    ?>
                </div>
                <h3 class="no-float ac"><?php print_r($question->getTitle()); ?></h3>
                <div class="al">
                    <?php
                    $answers = get_survey_answers($question->getId());
                    foreach ($answers as $answer_id) {
                        $answer = new Answer ();
                        $answer->get_from_db($answer_id);
                        ?>
                        <p>&emsp;&emsp;&emsp;<?php print_r($answer->getValue()); ?></p>
                        <?php
                    }
                    ?>
                </div>
                <div class="accordion">
                    <?php
                } elseif ($question->getType() == 0) {
                    ?>
                    <h3 class="no-float ac"><?php
                        print_r($question->getTitle());
                        ?></h3>
                    <div>
                        <?php
                        // check if question is answered
                        $user_id = $user->getId();
                        $has_answered = FALSE;
                        $user_answers_by_question = get_user_answers_by_question($user_id, $question_id);
                        if (!empty($user_answers_by_question)) {
                            $has_answered = TRUE;
                        }
                        $answers = get_survey_answers($question->getId());
                        if (!empty($answers)) {
                            ?>
                            <form id="formQuestion<?php print_r($question->getId()); ?>" class="form ac" action="<?php echo ROOT_DIR . '?page=survey&funct=survey_submit' ?>" method="POST">
                                <div class="ac">
                                    <section class="clearfix prefix_2">
                                        <?php
                                        foreach ($answers as $answer_id) {
                                            $answer = new Answer();
                                            $answer->get_from_db($answer_id);
                                            $vote = new Vote();
                                            $vote_id = 0;
                                            $user_vote_by_answer = array();
                                            $user_vote_by_answer = get_user_vote_by_answer($user_id, $answer_id);
                                            if (!empty($user_vote_by_answer)) {
                                                $vote_id = $user_vote_by_answer[0];
                                                $vote->get_from_db($vote_id);
                                            }
                                            ?>
                                            <label for = "formSurvey<?php print_r($question->getId()); ?>Answer<?php print_r($answer->getId()); ?>"><?php print_r($answer->getValue()); ?>
                                                <small><?php print_r($answer->getDescription()); ?></small>
                                            </label>
                                            <input 
                                                id="formSurvey<?php print_r($question->getId()); ?>Answer<?php print_r($answer->getId()); ?>" 
                                                <?php
                                                if ($answer->getType() == "radio") {
                                                    print 'name="formSurvey' . $question->getId() . 'Answer" ';
                                                } else {
                                                    print 'name="formSurvey' . $question->getId() . 'Answer' . $answer->getId() . 'Type' . $answer->getType() . '" ';
                                                }
                                                ?>
                                                type="<?php print $answer->getType(); ?>"
                                                value="<?php
                                                if (($answer->getType() == "radio") || ($answer->getType() == "checkbox")) {
                                                    print_r($answer_id);
                                                } elseif (($answer->getType() == "text") && ($has_answered == TRUE)) {
                                                    print_r($vote->getValue());
                                                }
                                                ?>"
                                                <?php
                                                if ($has_answered == TRUE) {
                                                    if ((($answer->getType() == "radio") || ($answer->getType() == "checkbox")) && !empty($user_vote_by_answer)) {
                                                        ?>
                                                        checked="checked"
                                                        <?php
                                                    }
                                                    ?>
                                                    disabled="disabled"
                                                    <?php
                                                }
                                                ?>
                                                />
                                            <br/><br/>
                                            <?php
                                        }
                                        ?>
                                    </section>
                                </div>
                                <?php if ($has_answered == FALSE) { ?>
                                    <br/>
                                    <div class="action no-margin ac" style="padding-left: 20px;">
                                        <input id="formSurvey<?php print_r($question->getId()); ?>Submit"
                                               class="button button-green"
                                               name="formSurveySubmit"
                                               type="submit"
                                               value="<?php echo BTN_SUBMIT; ?>"/>
                                        <input id="formSurvey<?php print_r($question->getId()); ?>Reset"
                                               class="button button-orange"
                                               name="formSurveyReset"
                                               type="reset"
                                               value="<?php echo BTN_RESET; ?>"/>
                                        <input type="hidden"
                                               name="formSurvey"
                                               value="formSurvey<?php print_r($question->getId()); ?>Submit">
                                        <a class="button button-red fl" 
                                           style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" 
                                           href="<?php print_r(ROOT_DIR . '?page=user_survey'); ?>"><?php echo BTN_CANCEL; ?></a>
                                    </div>
                                <?php } ?>
                            </form>
                            <br/><br/>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                // check if text or question
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>
