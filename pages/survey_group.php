<?php
$session_group = get_session_group();
?>
<div class="ac">
    <div class="ac">
        <form id="formSurveyGroup" class="form ac" action="<?php echo ROOT_DIR; ?>?page=survey_group&amp;funct=group_funct" method="POST">
            <div class="ac">
                <section class="clearfix prefix_2">
                    <label for="formSurveyGroupName"><?php echo SURVEY_GROUP_PAGE_GROUP_NAME; ?>
                        <em>*</em>
                        <small><?php echo SURVEY_GROUP_PAGE_GROUP_NAME_INFO; ?></small>
                    </label>
                    <input id="formSurveyGroupName" name="formSurveyGroupName" type="text" required="required" value="<?php echo $session_group->getName(); ?>" />
                    <br/>
                    <label for="formSurveyGroupAbbreviation"><?php echo SURVEY_GROUP_PAGE_GROUP_ABBREVIATION; ?>
                        <em>*</em>
                        <small><?php SURVEY_GROUP_PAGE_GROUP_ABBREVIATION_INFO; ?></small>
                    </label>
                    <input id="formSurveyGroupAbbreviation" name="formSurveyGroupAbbreviation" type="text" required="required" value="<?php echo $session_group->getAbbreviation(); ?>" />
                    <br/>
                    <label for="formSurveyGroupDescription"><?php echo SURVEY_GROUP_PAGE_GROUP_INFO; ?>
                        <em>*</em>
                        <small><?php echo SURVEY_GROUP_PAGE_GROUP_INFO_INFO; ?></small>
                    </label>
                    <input id="formSurveyGroupDescription" name="formSurveyGroupDescription" type="text" required="required" value="<?php echo $session_group->getDescription(); ?>" />
                    <?php
                    // list the session answers
                    $session_group_users_ids = $session_group->getMembersArray();
                    foreach ($session_group_users_ids as $user_id) {
                        $user = new User();
                        $user->get_from_db($user_id);
                        ?>
                        <span class="grid_3 al">
                            <b><?php echo SURVEY_GROUP_PAGE_VIEW_USER_USERNAME; ?></b> <?php print_r($user->getUsername()); ?>
                            <br/>
                            <small>
                                <b><?php echo SURVEY_GROUP_PAGE_VIEW_USER_GIVENNAME; ?></b> <?php print_r($user->getGivenname()); ?>
                            </small>
                        </span>
                        <span class="grid_1">
                            <a id="deleteGroupUser" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_group&amp;funct=delete_session_group_user&amp;user_id=<?php echo $user->getId(); ?>">
                                <span class="delete"></span>
                            </a>
                        </span>
                        <br/>
                        <?php
                    }
                    ?>
                </section>
            </div>
            <br/>
            <div class="action no-margin ac ui-widget" style="padding-left: 40px;">
                <input id="formSurveyGroupCreate" class="button button-green" name="formSurveyGroupCreate" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                <input id="formSurveyGroupReset" class="button button-orange" name="formSurveyGroupReset" type="submit" value="<?php echo BTN_RESET; ?>" />
                <input name="formSurveyGroupFunction" value="<?php print_r($session_group->getId()); ?>" type="hidden" />
                <a id="formSurveyGroupCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 10px 0px;" href="<?php echo ROOT_DIR; ?>?page=my_surveys"><?php echo BTN_CANCEL; ?></a>
            </div>
        </form>
    </div>
    <br/><br/><br/>
    <section>
        <div class="ac info_box box_green">
            <h3>
                <?php echo SURVEY_GROUP_PAGE_ADD_USER_TITLE; ?>
            </h3>
        </div>
        <div class="ac">
            <form id="formSurveyGroupUser" class="form ac" action="<?php echo ROOT_DIR; ?>?page=survey_group&amp;funct=add_session_group_user" method="POST">
                <div class="ac">
                    <section class="clearfix prefix_2">
                        <span class="grid_3">
                            <h3>
                                <?php echo SURVEY_GROUP_PAGE_ADD_USER_SEARCH; ?>
                            </h3>
                        </span>
                        <label for="formSurveyGroupUserUsername"><?php echo SURVEY_GROUP_PAGE_ADD_USER_USERNAME; ?>
                            <small><?php echo SURVEY_GROUP_PAGE_ADD_USER_USERNAME_INFO; ?></small>
                        </label>
                        <input id="formSurveyGroupUserUsername" name="formSurveyGroupUserUsername" type="text" />
                        <br/><br/><br/><br/>
                        <label for="formSurveyGroupUserEmail"><?php echo SURVEY_GROUP_PAGE_ADD_USER_EMAIL; ?>
                            <small><?php echo SURVEY_GROUP_PAGE_ADD_USER_EMAIL_INFO; ?></small>
                        </label>
                        <input id="formSurveyGroupUserEmail" name="formSurveyGroupUserEmail" type="email" />
                    </section>
                </div>
                <br/>
                <div class="action no-margin ac ui-widget" style="padding-left: 40px;">
                    <input id="formSurveyGroupUserAdd" class="button button-green" name="formSurveyGroupCreate" type="submit" value="<?php echo BTN_SUBMIT; ?>" />;
                    <input id="formSurveyGroupUserReset" class="button button-orange" name="formSurveyGroupReset" type="reset" value="<?php echo BTN_RESET; ?>" />
                    <a id="formSurveyGroupUserCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 10px 0px;" href="<?php echo ROOT_DIR; ?>?page=my_surveys"><?php echo BTN_CANCEL; ?></a>                    
                </div>
            </form>
        </div>
    </section>
</div>