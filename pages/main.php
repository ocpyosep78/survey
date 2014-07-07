<?php
if ($user == null && ($page != 'home' AND $page != 'help')) {
    header('location:' . ROOT_DIR . '?funct=logout');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo SIDE_TITLE; ?></title>

        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/reset.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/grid.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/style.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/messages.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/forms.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/tables.css" />
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/jquery-ui.css" />

        <link rel="icon" type="image/x-icon" href="<?php echo ROOT_DIR; ?>images/icons/favicon.ico" />

        <!--[if lt IE 8]>
        <link rel="stylesheet" media="screen" href="<?php echo ROOT_DIR; ?>style/ie.css" />
        <![endif]-->

        <!-- jquerytools -->
        <script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery.tools.min.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery.tables.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/global.js"></script>
        <script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/functions.js"></script>

        <!--[if lt IE 9]>
        <script type="text/javascript" src="js/html5.js"></script>
        <script type="text/javascript" src="js/PIE.js"></script>
        <script type="text/javascript" src="js/IE9.js"></script>
        <script type="text/javascript" src="js/ie.js"></script>
        <![endif]-->

    </head>
    <body>
        <div id="wrapper">
            <section>
<?php /*
                <!-- language bar -->
                <div class="container_8 clearfix language_bar">
                    <section class="grid_8">
                        <ul class="action-buttons clearfix fr">
                            <li>
                                <a href="<?php echo ROOT_DIR; ?>?page=<?php echo $page; ?>&amp;lang=bg_bg" class="button no-text help">
                                    <span class="bg_flag"></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo ROOT_DIR; ?>?page=<?php echo $page; ?>&amp;lang=ru_ru" class="button no-text help">
                                    <span class="ru_flag"></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo ROOT_DIR; ?>?page=<?php echo $page; ?>&amp;lang=en_us" class="button no-text help" >
                                    <span class="us_flag"></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo ROOT_DIR; ?>?page=<?php echo $page; ?>&amp;lang=de_de" class="button no-text help">
                                    <span class="de_flag"></span>
                                </a>
                            </li>
                        </ul>
                    </section>
                </div>
                <!-- language bar ends -->
*/ 
?>
                <div class="container_8 clearfix">
                    <!-- Main Section -->
                    <section class="main-section grid_8">
                        <div class="main-content grid_8 alpha">
                            <header class="ac">
                                <ul class="action-buttons clearfix fr">
                                    <li>
                                        <a href="<?php echo ROOT_DIR; ?>?page=help" title="<?php echo BTN_HELP; ?>" class="button button-gray no-text help" <?php $page == 'admin_survey' ? print_r('rel="#overlay"') : print_r('target="_top"') ?> >
                                            <span class="help"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php isset($user) ? print_r(ROOT_DIR . '?page=survey_role') : print_r(ROOT_DIR . '?page=home'); ?>" title="<?php echo BTN_HOME; ?>" class="button button-gray no-text help">
                                            <span class="home"></span>
                                        </a>
                                    </li>
                                    <?php if(isset($_SESSION['user'])) { ?>
                                    <li>
                                        <a href="<?php print_r(ROOT_DIR . '?funct=logout'); ?>" title="<?php echo BTN_EXIT; ?>" class="button button-gray no-text help">
                                            <span class="delete"></span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <h2>
                                    <?php echo SIDE_TITLE; ?>
                                </h2>
                            </header>
                            <section>
                                <?php
								// include info boxes
                                require_once ROOT_DIR . 'template/hello_box.php';
                                require_once ROOT_DIR . 'template/info_box.php';
                                require_once ROOT_DIR . 'template/msg_box.php';
                                // select page to include
                                
								//echo 'ok';
								//die();
								
								select_page($page);
                                ?>
                                <br/><br/><br/>
                                <div class="clearfix al">
                                    <h4>
                                        <?php echo REQUIRED_FIELDS; ?>
                                    </h4>
                                </div>
                            </section>
                        </div>
                    </section>
                    <!-- Main Section End -->
                </div>
                <div id="push"></div>
            </section>
        </div>
        <?php
        require_once 'template/footer.php';
        ?>
    </body>
</html>
