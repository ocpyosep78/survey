<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<?php
// set global var user
global $user;
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
                    <a class="button button-green" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php echo ROOT_DIR; ?>?page=survey_admin"><?php echo BTN_ENTER; ?></a>
                    <a class="button button-red" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php print_r(ROOT_DIR . '?funct=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
                </div>
                <br/><br/><br/>
            </div>
            <?php
        }
        // check if can create surveys
        if ($user->getCanAsk() == 1) {
            ?>
            <h3 class="no-float ac"><?php echo SURVEY_ROLE_PAGE_CAN_ASK_TITLE; ?></h3>
            <div>
                <?php
                    echo SURVEY_ROLE_PAGE_CAN_ASK_INFO;
                ?>
                <div class="action no-margin ac">
                    <br/>
                    <a class="button button-green" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php echo ROOT_DIR; ?>?page=my_surveys"><?php echo BTN_ENTER; ?></a>
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
                    <a class="button button-green" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php echo ROOT_DIR; ?>?page=survey"><?php echo BTN_ENTER; ?></a>
                    <a class="button button-red" style="color: #fff; width: 80px; margin-left: 5px; margin-right: 5px;" href="<?php print_r(ROOT_DIR . '?funct=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
                </div>
                <br/><br/><br/>
            </div>
            <?php
        }
        ?>
        <h3 class="no-float ac"><?php echo SURVEY_ROLE_PAGE_CONTACT_TITLE; ?></h3>
        <div class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-1-panel-0" aria-labelledby="ui-accordion-1-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false" style="display: block; height: 399.79999923706055px;">
            <h4 class="no-float al">
                <?php echo SURVEY_ROLE_PAGE_CONTACT_INFO; ?>
            </h4>
            <br/>
            <form id="formMessage" class="form ac" action="?page=survey_role&amp;funct=message_submit" method="POST">
                <div class="ac">
                    <section class="clearfix prefix_2">
                        <label for="messageTitle"><?php echo SURVEY_ROLE_PAGE_CONTACT_MSG_TITLE; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_ROLE_PAGE_CONTACT_MSG_TITLE_INFO; ?></small>
                        </label>
                        <input id="messageTitle" name="messageTitle" type="text" required="required" value="<?php print_r($session_message['title']);    ?>" />
                        <br/><br/><br/>
                        <label for="messageText"><?php echo SURVEY_ROLE_PAGE_CONTACT_MSG_TEXT; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_ROLE_PAGE_CONTACT_MSG_TEXT_INFO; ?></small>
                        </label>
                        <textarea id="messageText" name="messageText" type="textarea" style="resize: vertical;" required="required"><?php print_r($session_message['text']);    ?></textarea>
                        <br/><br/><br/>
                    </section>
                </div>
                <br>
                <div class="action no-margin ac" style="padding-left: 25px;">
                    <input id="formMessageUser<?php $user->getId(); ?>Submit"
                           class="button button-green"
                           name="formMessageSubmit"
                           type="submit"
                           value="<?php echo BTN_SUBMIT; ?>" />
                    <input id="formMessageUser<?php $user->getId(); ?>Reset"
                           class="button button-orange fl"
                           name="formMessageReset"
                           type="reset"
                           value="<?php echo BTN_RESET; ?>" />
                    <input type="hidden"
                           name="formMessage"
                           value="formMessageSubmit">
                    <a class="button button-red fl"
                       style="color: #fff; width: 230px; margin: 2px 0 0 10px;"
                       href="?funct=logout"><?php echo BTN_EXIT; ?></a>
                </div>
            </form>
            <br><br><br>
        </div>
    </div>
</div>