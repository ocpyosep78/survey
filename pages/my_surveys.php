<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<?php
global $user;
if (isset($_SESSION['survey_id'])) {
    unset($_SESSION['survey_id']);
}
if (isset($_SESSION['group'])) {
    unset($_SESSION['group']);
}
if (isset($_SESSION['group_id'])) {
    unset($_SESSION['survey_id']);
}
if (isset($_SESSION['answers'])) {
    unset($_SESSION['answers']);
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
        <?php echo MY_SURVEYS_PAGE_MY_SURVEYS; ?>
    </h4>
</div>
<div class="ac">
    <div class="ac">
        <div class="action no-margin ac ui-widget">
            <a class="button button-blue" style="color: #fff; width: 230px; margin: 2px 5px 0px 10px;" href="<?php print_r(ROOT_DIR . '?page=survey_question'); ?>"><?php echo MY_SURVEYS_PAGE_CREATE_SURVEY ?></a>
        </div>
        <br/>
    </div>
</div>
<div class="ac">
    <div class="accordion">
        <?php
        $surveys_by_creator = get_surveys_by_creator($user->getId());
        if (!empty($surveys_by_creator)) {
            foreach ($surveys_by_creator as $survey_id) {
                $survey = new Survey();
                $survey->get_from_db($survey_id);
                ?>
                <h3 class="no-float ac"><?php print_r($survey->getQuestion()); ?></h3>
                <div>
                    <form id="formSurvey<?php print_r($survey->getId()); ?>" class="form ac" action="/?page=my_surveys&funct=survey_funct" method="POST">
                        <?php
                        if (get_survey_answers($survey->getId()) != null) {
                            ?>
                            <div class="ac">
                                <section class="clearfix prefix_2">
                                    <?php
                                    $answers = get_survey_answers($survey->getId());
                                    foreach ($answers as $answer_id) {
                                        $answer = new Answer();
                                        $answer->get_from_db($answer_id);
                                        ?>
                                        <label for = "formSurveyAnswer<?php print_r($answer->getId()); ?>"><?php print_r($answer->getValue()); ?>
                                            <small><?php print_r($answer->getDescription()); ?></small>
                                        </label>
                                        <input id="formSurvey<?php print_r($survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>" 
                                        <?php
                                        if ($answer->getType() == "radio") {
                                            print 'name="formSurvey' . $survey->getId() . 'Answer" ';
                                        } else {
                                            print 'name="formSurvey' . $survey->getId() . 'Answer' . $answer->getId() . 'Type' . $answer->getType() . '" ';
                                        }
                                        ?>type="<?php print $answer->getType(); ?>" value="<?php $answer->getType() == "text" ? print_r("") : print_r($answer->getId()); ?>" disabled="disabled" />
                                        <br/><br/><br/>
                                        <p class="al prefix_1">
                                            <?php if ($answer->getType() != 'text') { ?>
                                                <span>Гласoвe: </span>
                                            <?php } else { ?>
                                                <br/>
                                                <span>Отговори: </span>
                                            <?php } ?>
                                            <span>
                                                <b><?php print_r(count(get_votes_by_answer($answer->getId()))); ?></b>
                                            </span>
                                        </p>
                                        <?php
                                        if ($answer->getType() == 'text') {
                                            $i = 1;
                                            foreach (get_votes_by_answer($answer->getId()) as $vote) {
                                                ?>
                                                <p class="al suffix_1">
                                                    <?php
														$vote_answer_value = "";
														if(isset($vote['answer_value'])) {
															$vote_answer_value = $vote['answer_value'];
														}
														echo $i . ". " . $vote_answer_value;
													?>
                                                </p>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        ?>
                                        <br/>
                                        <?php
                                    }
                                    ?>
                                </section>
                                <section class="clearfix prefix_2">
                                    <span class="grid_3">
                                        <h3>
                                            <?php echo MY_SURVEYS_PAGE_STATUS_SURVEY; ?>
                                        </h3>
                                    </span>
                                    <label for="formSurveyStatusActive">
                                        <?php echo MY_SURVEYS_PAGE_ACTIVE_SURVEY; ?> <em>*</em>
                                        <small>
                                            <?php echo MY_SURVEYS_PAGE_ACTIVE_SURVEY_INFO; ?>
                                        </small>
                                    </label>
                                    <input id="surveyNewRequesStatusActive"
                                           disabled="disabled"
                                           name="formSurveyStatus"
                                           type="radio"
                                           value="1"
                                           required="required"
                                           <?php
                                           ($survey->getStatus() == '1') ? print_r('checked="checked"') : print_r('');
                                           ?> />
                                    <br/><br/><br/>
                                    <label for="formSurveyStatusIncctive">
                                        <?php echo MY_SURVEYS_PAGE_UNACTIVE_SURVEY; ?> <em>*</em>
                                        <small>
                                            <?php echo MY_SURVEYS_PAGE_UNACTIVE_SURVEY_INFO; ?>
                                        </small>
                                    </label>
                                    <input id="surveyNewRequesStatusInactive"
                                           disabled="disabled"
                                           name="formSurveyStatus"
                                           type="radio"
                                           value="0"
                                           required="required"
                                           <?php
                                           ($survey->getStatus() == '0') ? print_r('checked="checked"') : print_r('');
                                           ?> />
                                </section>
                            </div>
                            <br/><br/>    
                            <div class="action no-margin ac" style="padding-left: 25px;">

                                <input class="button button-green" name="formSurveyPrint" value="<?php echo BTN_PRINT; ?>" type="submit" />

                                <input class="button button-orange" name="formSurveyEdit" value="<?php echo BTN_EDIT; ?>" type="submit" />
                                <input class="button button-red" name="formSurveyRemove" value="<?php echo BTN_DELETE; ?>" type="submit" />
                                <input name="formSurveyFunction" value="<?php print_r($survey->getId()); ?>" type="hidden" />
                            </div>
                            <?php
                        }
                        ?>
                    </form>
                    <br/><br/><br/>
                </div>
                <?php
                // close survey search
            }
        }
        ?>
    </div>
</div>
<br/>
<div class="ac info_box box_green">
    <h4>
        <?php echo MY_SURVEYS_PAGE_MY_GROUPS; ?>
    </h4>
</div>
<div class="ac">
    <div class="ac">
        <div class="action no-margin ac ui-widget">
            <a class="button button-blue" style="color: #fff; width: 230px; margin: 2px 5px 0px 10px;" href="<?php print_r(ROOT_DIR . '?page=survey_group'); ?>"><?php echo MY_SURVEYS_PAGE_CREATE_GROUP; ?></a>
        </div>
        <br/>
    </div>
</div>
<div class="ac">
    <div class="accordion">
        <?php
        $groups_by_creator = get_groups_by_creator($user->getId());
        if ($groups_by_creator != null) {
            foreach ($groups_by_creator as $group_id) {
                $group = new Group();
                $group->get_from_db($group_id);
                ?>
                <h3 class="no-float ac"><?php print_r($group->getAbbreviation()); ?></h3>
                <div>
                    <div class="ac">
                        <h4>
                            <?php print_r($group->getName()); ?>
                        </h4>
                        <hr/>
                    </div>
                    <form id="formSurvey<?php print_r($group->getId()); ?>View" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_group&funct=group_funct' ?>" method="POST">
                        <div class="ac">
                            <section class="clearfix prefix_2">
                                <label for="formGroup<?php print_r($group->getId()); ?>Description"><?php echo MY_SURVEYS_PAGE_GROUP_INFO_LABEL; ?>
                                    <small>Информация за групата</small>
                                </label>
                                <textarea id="formGroup<?php print_r($group->getId()); ?>Description" class="al" rows="5" disabled="disabled" style="resize: vertical;"><?php print_r($group->getDescription()); ?></textarea>
                            </section>
                        </div>
                        <br/>
                        <div class="action no-margin ac" style="padding-left: 25px;">
                            <input class="button button-green" name="formSurveyGroupPrint" value="<?php echo BTN_PRINT; ?>" type="submit" />
                            <input class="button button-orange" name="formSurveyGroupEdit" value="<?php echo BTN_EDIT; ?>" type="submit" />
                            <input class="button button-red" name="formSurveyGroupRemove" value="<?php echo BTN_DELETE; ?>" type="submit" />
                            <input name="formSurveyGroupFunction" value="<?php print_r($group->getId()); ?>" type="hidden" />
                        </div>
                    </form>
                </div>
                <?php
                // close group search
            }
        }
        ?>
    </div>
</div>
