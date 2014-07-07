<div class="ac info_box box_blue">
    <h4>
        Здравейте,
        <?php
        if (isset($user)) {
            echo '<br />';
            echo $user->getTitle() . '&nbsp;';
            echo $user->getGivenname();
            $user_id = $user->getId();
            $user_staff_groups = get_user_staff_groups($user_id);
            if (!empty($user_staff_groups)) {
                echo "<br/><b>" . STAFF_PERSON . ":</b><br/>";
                foreach ($user_staff_groups as $group_id) {
                    $group = new Group();
                    $group->get_from_db($group_id);
                    echo $group->getName() . '<br/>';
                }
            }
            $user_student_groups = get_user_student_groups($user_id);
            if (!empty($user_student_groups)) {
                echo "<b>" . STUDENT_PERSON . ":</b><br/>";
                foreach ($user_student_groups as $group_id) {
                    $group = new Group();
                    $group->get_from_db($group_id);
                    echo $group->getName() . '<br/>';
                }
            }
            $user_local_groups = get_user_local_groups($user_id);
            if (!empty($user_local_groups)) {
                echo "<b>" . MEMBER_PERSON . ":</b><br/>";
                foreach ($user_local_groups as $group_id) {
                    $group = new Group();
                    $group->get_from_db($group_id);
                    echo $group->getName() . '<br/>';
                }
            }
        } else {
            ?>
            <br>
            <?php echo HELLO_BOX_SIDE_DESCRIPTION; ?>
            <br>
            <?php echo HELLO_BOX_PLEASE_IDENTIFY;
        }
        ?>
    </h4>
</div>