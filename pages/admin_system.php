<?php
global $user;
// protect from unauthorised access!
if($user->getAdmin() != 1) {
    $msg = "try for unauthorised access!";
    error($msg);
    logout();
    die();
}

$users = get_users(0);
if(isset($_SESSION['session_user'])) {
    unset($_SESSION['session_user']);
}
?>
<div>
    <h3 class="ac"><?php echo SURVEY_ADMIN_PAGE_USER_LIST; ?></h3>
    <hr />
    <style type="text/css">
        th > a,
        td > a {
            padding: 0px;
        }
    </style>
    <table class="datatable paginate sortable full">
        <thead>
            <tr>
                <th style="width: 150px;"><?php echo SURVEY_ADMIN_PAGE_USERNAME; ?></th>
                <th><?php echo SURVEY_ADMIN_PAGE_USER_GIVENNAME; ?></th>
                <th style="width: 70px;"><?php echo SURVEY_ADMIN_PAGE_USER_CAN_VOTE; ?></th>
                <th style="width: 70px;"><?php echo SURVEY_ADMIN_PAGE_USER_CAN_ASK; ?></th>
                <th style="width: 70px;"><?php echo SURVEY_ADMIN_PAGE_USER_ADMIN; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($users as $user_id) {
                $user = new User();
                $user->get_from_db($user_id);
                ?>
                <tr class="ac">
                    <td>
                        <a target="_top" href="<?php echo ROOT_DIR; ?>?page=admin_system_user_edit&user_id=<?php echo $user->getID(); ?>"><?php echo $user->getUsername(); ?></a>
                    </td>
                    <td>
                        <?php echo $user->getGivenname(); ?>
                    </td>
                    <td class="ac">
                        <a href="#" class="button">
                            <span class="<?php $user->getCanVote() == 1 ? print_r('tick') : print_r('delete'); ?>"></span>
                        </a>
                    </td>
                    <td class="ac">
                        <a href="#" class="button">
                            <span class="<?php $user->getCanAsk() == 1 ? print_r('tick') : print_r('delete'); ?>"></span>
                        </a>
                    </td>
                    <td class="ac">
                        <a href="#" class="button"s>
                            <span class="<?php $user->getAdmin() == 1 ? print_r('tick') : print_r('delete'); ?>"></span>
                        </a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<br/>
<div class="ac">
    <div class="ac">
        <div class="action no-margin ac ui-widget">
            <a class="button button-blue" style="color: #fff; width: 230px; margin: 2px 5px 0px 10px;" href="<?php print_r(ROOT_DIR . '?page=survey_user'); ?>"><?php echo SURVEY_ADMIN_PAGE_CREATE_USER; ?></a>
        </div>
    </div>
</div>


