<?php
// get global var page
global $page;

// show proper page message
switch ($page) {
    case 'home':
        ?>

        <?php
        break;
    case 'survey_role':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_ROLE; ?>
            </h4>
        </div>
        <?php
        break;
    case 'survey':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_ATTENTION; ?>
                <br/>
                <?php echo INFO_BOX_SURVEY_FILL_QUESTION; ?>
                <br/>
                <?php echo INFO_BOX_SURVEY_FILLED_QUESTIONS; ?>
            </h4>
        </div>
        <?php
        break;
    case 'my_surveys':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_MY_SURVEYS_HERE_YOU_CAN ?>
                <ul class="no-float al">
                    <li>
                        <?php echo INFO_BOX_MY_SURVEYS_TO_VIEW_SURVEY_STATISTICS; ?>
                    </li>
                </ul>
            </h4>
        </div>
        <?php
        break;
    case 'survey_admin':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_ADMIN; ?>
            </h4>
        </div>
        <?php
        break;
    case 'survey_contact':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_CONTACT; ?>
            </h4>
        </div>
        <?php
        break;
    case 'survey_group':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_GROUP; ?>
            </h4>
        </div>
        <?php
        break;
    case 'survey_user':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_USER; ?>
            </h4>
        </div>
        <?php
        break;
    case 'survey_question':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_QUESTION_HERE_YOU_CAN; ?>
                <ul class="no-float al">
                    <li>
                        <?php echo INFO_BOX_SURVEY_QUESTION_TO_MAKE_SURVEY; ?>
                    </li>
                    <li>
                        <?php echo INFO_BOX_SURVEY_QUESTION_TO_EDIT_SURVEY; ?>
                    </li>
                </ul>
            </h4>
        </div>
        <?php
        break;
    case 'survey_edit':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_SURVEY_QUESTION_HERE_YOU_CAN; ?>
                <ul class="no-float al">
                    <li>
                        <?php echo INFO_BOX_SURVEY_QUESTION_TO_MAKE_SURVEY; ?>
                    </li>
                    <li>
                        <?php echo INFO_BOX_SURVEY_QUESTION_TO_EDIT_SURVEY; ?>
                    </li>
                </ul>
            </h4>
        </div>
        <?php
        break;
    case 'survey_add_answer':
        ?>
        <div class="ac info_box box_orange">
            <?php echo SURVEY_EDIT_SURVEY_PAGE_ADD_SUBELEMENT; ?>
        </div>
        <?php
        break;
    case 'user_survey':
        ?>
        <div class="ac info_box box_orange">
            <?php echo SURVEY_ROLE_PAGE_CAN_VOTE_INFO; ?>
        </div>
        <?php
        break;
    case 'help':
        ?>
        <div class="ac info_box box_orange">
            <h4>
                <?php echo INFO_BOX_HELP; ?>
            </h4>
        </div>
        <?php
        break;
    default :
        ?>

        <?php
        break;
}
?>