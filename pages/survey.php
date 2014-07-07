<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<div class="ac">
    <?php
    global $user;
    $available_by_user_surveys = get_available_by_user_surveys($user->getId());
    if (!empty($available_by_user_surveys)) {
        ?>
        <div class="ac info_box box_green">
            <h4>
                <?php echo SURVEY_PAGE_AVAILABLE_SURVEYS; ?>
            </h4>
        </div>
        <div class="accordion">
            <?php
            foreach ($available_by_user_surveys as $survey_id) {
                $survey = new Survey();
                $survey->get_from_db($survey_id);
                ?>
                <h3 class="no-float ac"><?php print_r($survey->getQuestion()); ?></h3>
                <div>
                    <?php
                    $answers = get_survey_answers($survey->getId());
                    if (!empty($answers)) {
                        ?>
                        <form id="formSurvey<?php print_r($survey->getId()); ?>" class="form ac" action="<?php echo ROOT_DIR . '?page=survey&funct=survey_submit' ?>" method="POST">
                            <div class="ac">
                                <section class="clearfix prefix_2">
                                    <?php
                                    // get diagram data
                                    $diagram_answer_values = "";
                                    $diagram_answer_votes = "";

                                    foreach ($answers as $answer_id) {
                                        $answer = new Answer();
                                        $answer->get_from_db($answer_id);

                                        $diagram_answer_values .= ", '" . $answer->getValue() . "'";
                                        $diagram_answer_votes .= ", " . count(get_votes_by_answer($answer->getId()));
                                        ?>
                                        <label for = "formSurvey<?php print_r($survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>"><?php print_r($answer->getValue()); ?>
                                            <small><?php print_r($answer->getDescription()); ?></small>
                                        </label>
                                        <input id="formSurvey<?php print_r($survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>" 
                                        <?php
                                        if ($answer->getType() == "radio") {
                                            print 'name="formSurvey' . $survey->getId() . 'Answer" ';
                                        } else {
                                            print 'name="formSurvey' . $survey->getId() . 'Answer' . $answer->getId() . 'Type' . $answer->getType() . '" ';
                                        }
                                        ?>type="<?php print $answer->getType(); ?>" value="<?php $answer->getType() == "text" ? print_r("") : print_r($answer->getId()); ?>"/>
                                        <br/><br/>
                                        <div id="formSurvey<?php print_r($survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>Results" class="jsHide" onload="this.style.display='none';">
                                            <p class="al prefix_1">
                                                <?php if ($answer->getType() != 'text') { ?>
                                                    <span><?php echo SURVEY_PAGE_VOTES; ?> </span>
                                                <?php } else { ?>
                                                    <br/>
                                                    <span><?php echo SURVEY_PAGE_ANSWERS; ?> </span>
                                                <?php } ?>
                                                <span>
                                                    <b><?php print_r(count(get_votes_by_answer($answer->getId()))); ?></b>
                                                </span>
                                            </p>
                                            <br/>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac" style="padding-left: 20px;">
                                <input id="formSurvey<?php print_r($survey->getId()); ?>Submit"
                                       class="button button-green"
                                       name="formSurveySubmit"
                                       type="submit"
                                       value="<?php echo BTN_SUBMIT; ?>"/>
                                <input id="formSurvey<?php print_r($survey->getId()); ?>Reset"
                                       class="button button-orange"
                                       name="formSurveyReset"
                                       type="reset"
                                       value="<?php echo BTN_RESET; ?>"/>
                                <input type="hidden"
                                       name="formSurvey"
                                       value="formSurvey<?php print_r($survey->getId()); ?>Submit">
                                <a class="button button-red fl" 
                                   style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" 
                                   href="<?php print_r(ROOT_DIR . '?page=survey_role'); ?>"><?php echo BTN_CANCEL; ?></a>
                            </div>
                        </form>
                        <script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jsapi.js"></script>
                        <script type="text/javascript">
                            google.load("visualization", "1", {packages: ["corechart"]});
                            google.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                        [   '<?php echo SURVEY_PAGE_VOTES; ?>'
            <?php echo $diagram_answer_values; ?>
                                        ],
                                        [   ''
            <?php echo $diagram_answer_votes; ?>
                                        ]
                                ]);
                                        var options = {
                                    title: '<?php echo SURVEY_PAGE_DIAGRAM; ?>',
                                    vAxis: {title: '<?php echo SURVEY_PAGE_ANSWERS; ?>', titleTextStyle: {color: '#000'}},
                                    hAxis: {title: '<?php echo SURVEY_PAGE_VOTERS; ?>', titleTextStyle: {color: '#000'}}
                                };

                                var chart = new google.visualization.BarChart(document.getElementById(<?php print_r('"survey' . $survey->getId() . 'Chart"'); ?>));
                                chart.draw(data, options);
                            }
                        </script>
                        <br/><br/>
                        <div class="container_6 prefix_2 grid_5">
                            <div id="<?php print_r('survey' . $survey->getId() . 'Chart'); ?>" style="width: 400px; height: 200px;"></div>
                        </div>
                        <br/><br/>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
    // list voted surveys
    $surveys_votes = get_voted_surveys_by_user($user->getId());
    if (!empty($surveys_votes)) {
        ?>
        <br/>
        <div class="ac info_box box_green">
            <h4>
                <?php echo SURVEY_PAGE_FILLED_SURVEYS; ?>
            </h4>
        </div>
        <div class="accordion">
            <?php
            foreach ($surveys_votes as $survey_id) {
                $survey = new Survey();
                $survey->get_from_db($survey_id);
                ?>
                <h3 class="no-float ac"><?php print_r($survey->getQuestion()); ?></h3>
                <div>
                    <?php
                    $answers = get_survey_answers($survey->getId());
                    if (!empty($answers)) {
                        // get diagram data
                        $diagram_answer_values = "";
                        $diagram_answer_votes = "";
                        ?>
                        <form id="formSurvey<?php print_r($survey->getId()); ?>Results" class="form ac">
                            <div class="ac">
                                <section class="clearfix prefix_2">
                                    <?php
                                    // iterate survey answers 
                                    foreach ($answers as $answer_id) {
                                        $answer = new Answer();
                                        $answer->get_from_db($answer_id);

                                        $diagram_answer_values .= ", '" . $answer->getValue() . "'";
                                        $diagram_answer_votes .= ", " . count(get_votes_by_answer($answer->getId()));
                                        ?>
                                        <label for = "formSurveyAnswer<?php print_r($answer->getId()); ?>"><?php print_r($answer->getValue()); ?>
                                            <small><?php print_r($answer->getDescription()); ?></small>
                                        </label>
                                        <?php if ($answer->getType() != 'text') { ?>
                                            <span class="grid_1 ar"><?php echo SURVEY_PAGE_DIAGRAM; ?> </span>
                                        <?php } else { ?>
                                            <br/>
                                            <span class="grid_1 ar"><?php echo SURVEY_PAGE_VOTERS; ?> </span>
                                        <?php } ?>
                                        <span class="grid_1 al">
                                            <b><?php print_r(count(get_votes_by_answer($answer->getId()))); ?></b>
                                        </span>
                                        <br/><br/><br/>
                                        <?php
                                    }
                                    ?>
                                </section>
                            </div>
                        </form>
                        <script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jsapi.js"></script>
                        <script type="text/javascript">
                            google.load("visualization", "1", {packages: ["corechart"]});
                            google.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                        [   '<?php echo SURVEY_PAGE_VOTERS; ?>'
											<?php echo $diagram_answer_values; ?>
                                        ],
                                        [   ''
											<?php echo $diagram_answer_votes; ?>
                                        ]
                                ]);
                                        var options = {
                                    title: '<?php echo SURVEY_PAGE_DIAGRAM; ?>',
                                    vAxis: {title: '<?php echo SURVEY_PAGE_ANSWERS; ?>', titleTextStyle: {color: '#000'}},
                                    hAxis: {title: '<?php echo SURVEY_PAGE_VOTERS; ?>', titleTextStyle: {color: '#000'}}
                                };

                                var chart = new google.visualization.BarChart(document.getElementById(<?php print_r('"survey' . $survey->getId() . 'Chart"'); ?>));
                                chart.draw(data, options);
                            }

                            // if JS not enable
                            document.getElementById("formSurvey<?php print_r($survey->getId()); ?>Results").style.display = "none";
                        </script>
                        <br/><br/>
                        <div class="container_6 prefix_2 grid_5">
                            <div id="<?php print_r('survey' . $survey->getId() . 'Chart'); ?>" style="width: 400px; height: 200px;"></div>
                        </div>
                        <br/><br/>
                        <?php
                    }
                    ?>
                </div>    
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>