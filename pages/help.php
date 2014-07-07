<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<?php
if (!isset($_SESSION['user'])) {
    ?>
    <div class="ac info_box box_green">
        <h4 class="al" style="padding: 10px">
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo HELP_PAGE_NO_SESSION_USER; ?>
        </h4>
    </div>
    <div class="accordion">
        <h3>
            <?php echo HELP_PAGE_NO_SESSION_USER_ACCESS_STAFF; ?>
        </h3>
        <?php echo HELP_PAGE_NO_SESSION_USER_ACCESS_STAFF_DESCRIPTION; ?>
        <h3>
            <?php echo HELP_PAGE_NO_SESSION_USER_ACCESS_STUDENT; ?>
        </h3>
        <?php echo HELP_PAGE_NO_SESSION_USER_ACCESS_STUDENT_DESCRIPTION; ?>
    </div>
    <?php
} else {
    global $user;
    ?>
    <div class="ac info_box box_green">
        <h4 class="al" style="padding: 10px">
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo HELP_PAGE_SESSION_USER; ?>
        </h4>
    </div>
    <div class="accordion">
        <?php if ($user->getCanVote() == '1') { 
            echo HELP_PAGE_SESSION_USER_CAN_VOTE;
        }
        if ($user->getCanAsk() == '1') {
            echo HELP_PAGE_SESSION_USER_CAN_ASK;
        }
        if ($user->getAdmin() == '1') {
            echo HELP_PAGE_SESSION_USER_ADMIN;
        }
        echo HELP_PAGE_SESSION_USER_CONTACT;
        ?>
    </div>
    <?php
}
?>
