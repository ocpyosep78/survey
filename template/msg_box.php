<div class="ac msg_box">
    <h3>
        <?php
        // display message text
        if (isset($_COOKIE['msg'])) {
            echo $_COOKIE['msg'];
        }
        ?>
    </h3>
</div>