<div class="ac">
    <form id="formLogin" class="form ac" action="<?php echo ROOT_DIR; ?>?funct=login" method="POST">
        <h3 class="no-float ac"><?php echo HOME_PAGE_ENTRANCE; ?></h3>
        <div class="ac">
            <section class="clearfix prefix_2">
                <label for="username"><?php echo HOME_PAGE_USERNAME; ?> <em>*</em><small><?php echo HOME_PAGE_USERNAME_INFO; ?></small></label>
                <input id="username" name="username" type="text" required="required">
                <br>
                <label for="password"><?php echo HOME_PAGE_PASSWORD; ?> <em>*</em><small><?php echo HOME_PAGE_PASSWORD_INFO; ?></small></label>
                <input id="password" name="password" type="password" required="required">
            </section>
        </div>
        <br/>
        <div class="action no-margin ac ui-widget" style="padding-left: 35px;">
            <input id="formLoginSubmit" class="button button-green" name="submit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
            <input id="formLogin3Reset" class="button button-orange" name="reset" type="reset" value="<?php echo BTN_RESET; ?>" />
            <input id="formLoginSubmitValidate" name="formLoginSubmitValidate" type="hidden" value="formLoginSubmitValidate" />
            <a id="formSurvey3Cancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 10px 0px;" href="<?php isset($_SERVER['HTTP_REFERER']) ? print_r($_SERVER["HTTP_REFERER"]) : print_r(ROOT_DIR . '?page=logout'); ?>"><?php echo BTN_CANCEL; ?></a>
        </div>
    </form>
</div>