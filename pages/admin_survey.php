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
if (isset($_SESSION['session_survey'])) {
    unset($_SESSION['session_survey']);
}
if (isset($_SESSION['session_question'])) {
    unset($_SESSION['session_question']);
}
if (isset($_SESSION['session_answers'])) {
    unset($_SESSION['session_answers']);
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
            <a class="button button-blue" style="color: #fff; width: 230px; margin: 2px 5px 0px 10px;" href="<?php print_r(ROOT_DIR . '?page=survey_edit'); ?>"><?php echo MY_SURVEYS_PAGE_CREATE_SURVEY ?></a>
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
                                    <small><?php echo MY_SURVEYS_PAGE_GROUP_INFO; ?></small>
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
