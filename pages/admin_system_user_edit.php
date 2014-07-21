<?php
global $user;
// protect from unauthorised access!
if ($user->getAdmin() != 1) {
    $msg = "try for unauthorised access!";
    error($msg);
    logout();
    die();
}
$session_user = admin_get_session_user();
?>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<div>
    <div class="ac info_box box_green">
        <h4>
            <?php echo SURVEY_USER_PAGE_TITLE; ?>
        </h4>
    </div>
    <div class="ac">
        <form 
            id="formEditUser" 
            class="form ac" 
            action="./?page=admin_system_user_edit&amp;funct=user_funct" 
            method="POST">
            <div class="ac">
                <section class="clearfix prefix_2">
                    <label for="formSurveyUserUsername">
                        <?php echo SURVEY_USER_PAGE_USERNAME; ?>
                        <em>*</em>
                        <small><?php echo SURVEY_USER_PAGE_USERNAME_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserUsername"
                           name="formSurveyUserUsername"
                           type="text"
                           required="required"
                           value="<?php print_r($session_user->getUsername()); ?>" />
                    <br/><br/><br/>
                    <label for="formSurveyUserEmail">
                        <?php echo SURVEY_USER_PAGE_EMAIL; ?>
                        <em>*</em>
                        <small><?php echo SURVEY_USER_PAGE_EMAIL_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserEmail"
                           name="formSurveyUserEmail"
                           type="text"
                           required="required"
                           value="<?php print_r($session_user->getEmail()); ?>" />
                    <br/><br/><br/>
                    <label for="formSurveyUserGivenname">
                        <?php echo SURVEY_USER_PAGE_GIVENNAME; ?>
                        <em>*</em>
                        <small><?php echo SURVEY_USER_PAGE_GIVENNAME_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserGivenname"
                           name="formSurveyUserGivenname"
                           type="text"
                           required="required"
                           value="<?php print_r($session_user->getGivenname()); ?>" />
                    <br/><br/><br/>
                    <label for="formSurveyUserTitle">
                        <?php echo SURVEY_USER_PAGE_USER_TITLE; ?>
                        <small><?php echo SURVEY_USER_PAGE_USER_TITLE_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserTitle"
                           name="formSurveyUserTitle"
                           type="text"
                           value="<?php print_r($session_user->getTitle()); ?>" />
                    <br/><br/><br/>
                    <div class="clearfix">
                        <?php
                        $i = 1;
                        $student_groups = unserialize($session_user->getStudentGroups());
                        if (!empty($student_groups)) {
                            ?>
                            <span class="grid_3">
                                <h3>
                                    <?php echo SURVEY_USER_PAGE_USER_SUSI_STUDENT; ?>
                                </h3>
                            </span>
                            <?php
                            foreach ($student_groups as $group_id) {
                                $group = new Group();
                                $group->get_from_db($group_id);
                                ?>
                                <span class="grid_3 al">
                                    <?php print_r($i . '. ' . $group->getName()); ?>
                                </span>
                                <span class="grid_1">
                                    <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_user&amp;funct=delete_session_user_group&amp;group_type=student&amp;group_id=<?php echo $group_id; ?>">
                                        <span class="delete"></span>
                                    </a>
                                </span>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>
                    <div class="clearfix">
                        <?php
                        $i = 1;
                        $staff_groups = unserialize($session_user->getStaffGroups());
                        if (!empty($staff_groups)) {
                            ?>
                            <span class="grid_3">
                                <h3>
                                    <?php echo SURVEY_USER_PAGE_USER_SUSI_STAFF; ?>
                                </h3>
                            </span>
                            <?php
                            foreach ($staff_groups as $group_id) {
                                $group = new Group();
                                $group->get_from_db($group_id);
                                ?>
                                <span class="grid_3 al">
                                    <?php print_r($i . '. ' . $group->getName()); ?>
                                </span>
                                <span class="grid_1">
                                    <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_user&amp;funct=delete_session_user_group&amp;group_type=staff&amp;group_id=<?php echo $group_id; ?>">
                                        <span class="delete"></span>
                                    </a>
                                </span>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>
                    <div class="clearfix">
                        <?php
                        $i = 1;
                        $local_groups = unserialize($session_user->getLocalGroups());
                        if (!empty($staff_groups)) {
                            ?>
                            <span class="grid_3">
                                <h3>
                                    <?php echo SURVEY_USER_PAGE_USER_LOCAL_GROUP; ?>
                                </h3>
                            </span>
                            <?php
                            foreach ($local_groups as $group_id) {
                                $group = new Group();
                                $group->get_from_db($group_id);
                                ?>
                                <span class="grid_3 al">
                                    <?php print_r($i . '. ' . $group->getName()); ?>
                                </span>
                                <span class="grid_1">
                                    <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_user&amp;funct=delete_session_user_group&amp;group_type=local&amp;group_id=<?php echo $group_id; ?>">
                                        <span class="delete"></span>
                                    </a>
                                </span>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>
                </section>
                <hr/>
                <h3>
                    <?php echo SURVEY_USER_PAGE_USER_ROLES; ?>
                </h3>
                <hr/>
                <section class="clearfix prefix_2">
                    <label for="formSurveyUserCanVote"><?php echo SURVEY_USER_PAGE_USER_ROLES_CAN_VOTE; ?>
                        <small><?php echo SURVEY_USER_PAGE_USER_ROLES_CAN_VOTE_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserCanVote"
                           name="formSurveyUserCanVote"
                           type="checkbox"
                           value="1"
                           <?php $session_user->getCanVote() == '1' ? print_r('checked="checked"') : print_r(''); ?> />
                    <br/><br/><br/><br/>
                    <label for="formSurveyUserCanAsk"><?php echo SURVEY_USER_PAGE_USER_ROLES_CAN_ASK; ?>
                        <small><?php echo SURVEY_USER_PAGE_USER_ROLES_CAN_ASK_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserCanAsk"
                           name="formSurveyUserCanAsk"
                           type="checkbox"
                           value="1"
                           <?php $session_user->getCanAsk() == '1' ? print_r('checked="checked"') : print_r(''); ?> />
                    <br/><br/><br/><br/>
                    <label for="formSurveyUserAdmin"><?php echo SURVEY_USER_PAGE_USER_ROLES_ADMIN; ?>
                        <small><?php echo SURVEY_USER_PAGE_USER_ROLES_ADMIN_INFO; ?></small>
                    </label>
                    <input id="formSurveyUserAdmin"
                           name="formSurveyUserAdmin"
                           type="checkbox"
                           value="1"
                           <?php $session_user->getAdmin() == '1' ? print_r('checked="checked"') : print_r(''); ?> />
                </section>
                <br/>
                <div class="action no-margin ac" style="padding-left: 40px;">
                    <input  <?php if ($session_user->getId() == NULL) {
                               ?>
                            id="formSurveyUserSave"
                            name="formSurveyUserSave"
                            <?php
                        } else {
                            ?>
                            id="formSurveyUserEdit"
                            name="formSurveyUserEdit"
                            <?php
                        }
                        ?>
                        class="button button-green"
                        type="submit"
                        value="Потвърди" />
                    <input  id="formSurveyUserReset"
                            name="formSurveyUserReset"
                            class="button button-orange" 
                            type="submit"
                            value="Изчисти" />
                    <input  <?php if ($session_user->getId() == NULL) {
                            ?>
                            id="formSurveyUserCancel"
                            name="formSurveyUserCancel"
                            value="Отказ"
                            <?php
                        } else {
                            ?>
                            id="formSurveyUserRemove"
                            name="formSurveyUserRemove"
                            value="Изтрий"
                            <?php
                        }
                        ?>
                        class="button button-red"
                        type="submit" />
                    <br/><br/><br/>
                </div>
            </div>
        </form>
    </div>
    <?php
    if ($session_user->getCanAsk() == 1) {
        $surveys_by_creator = get_surveys_by_creator($session_user->getId());
        ?>
        <div class="ac info_box box_green">
            <h4>
                <?php echo SURVEY_USER_PAGE_USER_CREATED_SURVEYS; ?>
            </h4>
        </div>
        <div class="ac">
            <div class="accordion">
                <?php
                if ($surveys_by_creator != null) {
                    foreach ($surveys_by_creator as $survey_id) {
                        $survey = new Survey();
                        $survey->get_from_db($survey_id);
                        ?>
                        <h3 class="no-float ac"><?php print_r($survey->getTitle()); ?></h3>
                        <div>
                            <div class="ac">
                                <div class="action no-margin ac">
                                    <form id="formSurvey<?php print_r($survey->getId()); ?>"
                                          class="form ac prefix_2" 
                                          action="./?page=admin_surveys&amp;funct=survey_funct" 
                                          method="POST">
                                        <input id="formSurveyView" 
                                               class="button button-green" 
                                               name="formSurveyEdit" 
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
                        // close survey search
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    if ($session_user->getCanVote() == 1) {
        $surveys_votes = get_voted_surveys_by_user($session_user->getId());
        ?>
        <br/>
        <div class="ac info_box box_green">
            <h4>
                <?php echo SURVEY_USER_PAGE_USER_FILLED_SURVEYS; ?>
            </h4>
        </div>
        <div class="ac">
            <div class="accordion">
                <?php
                if ($surveys_votes != null) {
                    foreach ($surveys_votes as $survey_id) {
                        $survey = new Survey();
                        $survey->get_from_db($survey_id);
                        ?>
                        <h3 class="no-float ac"><?php print_r($survey->getTitle()); ?></h3>
                        <div>
                            <form id="formSurveyVote" class="form ac" action="<?php echo ROOT_DIR; ?>?page=survey_user&amp;funct=survey_funct" method="POST">
                                <?php
                                $votes = get_vote_by_user_and_survey($session_user->getId(), $survey->getId());
                                if ($votes != null) {
                                    ?>
                                    <div class="ac">
                                        <section class="clearfix prefix_2">
                                            <?php
                                            foreach ($votes as $vote_id) {
                                                $vote = new Vote();
                                                $vote->get_from_db($vote_id);
                                                $answer = new Answer();
                                                $answer->get_from_db($vote->getAnswer());
                                                ?>
                                                <label for = "formSurveyAnswer<?php print_r($survey->getId()); ?>"><?php print_r($answer->getValue()); ?>
                                                    <small><?php print_r($answer->getDescription()); ?></small>
                                                </label>
                                                <input id="formSurvey<?php print_r($survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>" 
                                                       name="formSurvey<?php print_r($survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>"
                                                       disabled="disabled"
                                                       type="<?php print_r($answer->getType()); ?>"
                                                       value="<?php print_r($vote->getValue()); ?>"
                                                       <?php
                                                       if ($answer->getType() == "radio" || $answer->getType() == "checkbox") {
                                                           ?>
                                                           checked="checked"
                                                           <?php
                                                       }
                                                       ?> />
                                                <br/>
                                                <?php
                                            }
                                            ?>
                                        </section>
                                    </div>
                                    <br/>
                                    <div class="action no-margin ac prefix_2">
                                        <input id="formSurveyVoteDelete" class="button button-red" name="formSurveyVoteDelete" type="submit" value="<?php echo BTN_DELETE; ?>" style="margin-left: 60px;" />
                                        <input name="formSurveyFunction" value="<?php print_r($survey->getId()); ?>" type="hidden" />
                                    </div>
                                    <?php
                                }
                                ?>
                            </form>
                            <br/><br/><br/>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>