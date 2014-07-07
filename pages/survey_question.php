<?php
// get session vars
global $user;
$session_survey = get_session_survey();
$session_group = array('type' => '', 'student' => array(), 'staff' => array(), 'staff_departments' => array(), 'local' => array());
if (isset($_SESSION['session_group'])) {
    $session_group['type'] = unserialize($_SESSION['session_group'])['type'];
    $session_group['student'] = unserialize($_SESSION['session_group'])['student'];
    $session_group['staff'] = unserialize($_SESSION['session_group'])['staff'];
    $session_group['staff_departments'] = unserialize($_SESSION['session_group'])['staff_departments'];
    $session_group['local'] = unserialize($_SESSION['session_group'])['local'];
}
?>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>
<div class="ac">
    <div class="accordion">
        <h3 class="no-float ac" id="survey_data"><?php echo SURVEY_QUESTION_PAGE_SURVEY_DATA; ?></h3>
        <div class="ac">
            <form id="formSurvey" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&amp;funct=survey_funct'; ?>" method="POST">
                <div class="ac">
                    <section class="clearfix prefix_2">
                        <label for="formSurveyQuestion"><?php echo SURVEY_QUESTION_PAGE_SURVEY_NAME; ?> <em>*</em><small><?php echo SURVEY_QUESTION_PAGE_SURVEY_NAME_INFO; ?></small></label>
                        <input id="formSurveyQuestion" name="formSurveyQuestion" type="text" required="required" value="<?php echo $session_survey->getQuestion(); ?>" />
                        <br/>
                        <label for="formSurveyFromHour"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_TIME; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_TIME_INFO; ?></small>
                        </label>
                        <input id="formSurveyFromHour" name="formSurveyFromHour" type="time" value="<?php ((substr($session_survey->getAvailableFrom(), 11, 5) != "00:00") and ($session_survey->getAvailableFrom() != null)) ? print substr($session_survey->getAvailableFrom(), 11, 5) : print date("H:i"); ?>" required="required" />
                        <br/>
                        <label for="formSurveyFromDate"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_DATE; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_DATE_INFO; ?></small>
                        </label>
                        <input id="formSurveyFromDate" name="formSurveyFromDate" type="date" value="<?php ((substr($session_survey->getAvailableFrom(), 0, 10) != "0000-00-00") and ($session_survey->getAvailableFrom() != null)) ? print substr($session_survey->getAvailableFrom(), 0, 10) : print date("Y-m-d"); ?>" required="required" />
                        <br/>
                        <label for="formSurveyDueHour"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_TIME; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_TIME_INFO; ?></small>
                        </label>
                        <input id="formSurveyDueHour" name="formSurveyDueHour" type="time" value="<?php ((substr($session_survey->getAvailableDue(), 11, 5) != "00:00") and ($session_survey->getAvailableDue() != null)) ? print substr($session_survey->getAvailableDue(), 11, 5) : print date("H:i"); ?>" required="required" />
                        <br/>
                        <label for="formSurveyDueDate"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_DATE; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_DATE_INFO; ?></small>
                        </label>
                        <input id="formSurveyDueDate" name="formSurveyDueDate" type="date" value="<?php ((substr($session_survey->getAvailableDue(), 0, 10) != "0000-00-00") and ($session_survey->getAvailableDue() != null)) ? print substr($session_survey->getAvailableDue(), 0, 10) : print date("Y-m-d"); ?>" required="required" />
                        <div class="clearfix">
                            <span class="grid_3">
                                <h3>
                                    Статус на анкетата
                                </h3>
                            </span>
                            <label for="formSurveyStatusActive"><?php echo SURVEY_QUESTION_PAGE_ACTIVE_SURVEY; ?>
                                <em>*</em>
                                <small><?php echo SURVEY_QUESTION_PAGE_ACTIVE_SURVEY_INFO; ?></small>
                            </label>
                            <input id="surveyNewRequesStatusActive"
                                   name="formSurveyStatus"
                                   type="radio"
                                   value="1"
                                   required="required"
                                   <?php (($session_survey->getStatus() == '1') || ($session_survey->getStatus() == null)) ? print_r('checked="checked"') : print_r(''); ?> />
                            <br/><br/><br/>
                            <label for="formSurveyStatusIncctive"><?php echo SURVEY_QUESTION_PAGE_UNACTIVE_SURVEY; ?>
                                <em>*</em>
                                <small><?php echo SURVEY_QUESTION_PAGE_UNACTIVE_SURVEY_INFO; ?></small>
                            </label>
                            <input id="surveyNewRequesStatusInactive"
                                   name="formSurveyStatus"
                                   type="radio"
                                   value="0"
                                   required="required"
                                   <?php
                                        ($session_survey->getStatus() == '0') ? print_r('checked="checked"') : print_r('');
                                    ?> />
                        </div>
                        <?php
                        // list session group students
                        $session_group_student = $session_group['student'];
                        if (!empty($session_group_student)) {
                            ?>
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo SURVEY_QUESTION_PAGE_STUDENT_GROUP; ?>
                                    </h3>
                                </span>
                                <?php
                                $i = 1;
                                foreach ($session_group_student as $key => $group_id) {
                                    $group = new Group();
                                    $group->get_from_db($group_id);
                                    if ($group->getName() != '') {
                                        ?>
                                        <span class="grid_3 al">
                                            <?php print_r($i . '. ' . $group->getName()); ?>
                                        </span>
                                        <span class="grid_1">
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_session_group&amp;session_group_type=student&amp;session_group_id=<?php echo $key; ?>">
                                                <span class="delete"></span>
                                            </a>
                                        </span>
                                        <?php
                                    }
                                    $i++;
                                }
                                ?>
                            </div>
                            <?php
                        }
                        
                        // list session group staff
                        $session_group_staff = $session_group['staff'];
                        if (!empty($session_group_staff)) {
                            ?>
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo SURVEY_QUESTION_PAGE_STAFF_GROUP; ?>
                                    </h3>
                                </span>
                                <?php
                                $i = 1;
                                foreach ($session_group_staff as $key => $group_id) {
                                    $group = new Group();
                                    $group->get_from_db($group_id);
                                    if ($group->getName() != '') {
                                        ?>
                                        <span class="grid_3 al">
                                            <?php print_r($i . '. ' . $group->getName()); ?>
                                        </span>
                                        <span class="grid_1">
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_session_group&amp;session_group_type=staffs&amp;session_group_id=<?php echo $key; ?>">
                                                <span class="delete"></span>
                                            </a>
                                        </span>
                                        <?php
                                    }
                                    $i++;
                                }
                                ?>
                            </div>
                            <?php
                        }
                        
                        // list session group staff departments
                        $session_group_staff_departments = $session_group['staff_departments'];
                        if (!empty($session_group_staff_departments)) {
                            ?>
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo SURVEY_QUESTION_PAGE_STAFF_GROUP; ?>
                                    </h3>
                                </span>
                                <?php
                                $i = 1;
                                foreach ($session_group_staff_departments as $key => $group_id) {
                                    $group = new Group();
                                    $group->get_from_db($group_id);
                                    if ($group->getName() != '') {
                                        ?>
                                        <span class="grid_3 al">
                                            <?php print_r($i . '. ' . $group->getName()); ?>
                                        </span>
                                        <span class="grid_1">
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_session_group&amp;session_group_type=staff_departments&amp;session_group_id=<?php echo $key; ?>">
                                                <span class="delete"></span>
                                            </a>
                                        </span>
                                        <?php
                                    }
                                    $i++;
                                }
                                ?>
                            </div>
                            <?php
                        }
                        
                        // list session group local
                        $session_group_local = $session_group['local'];
                        if (!empty($session_group_local)) {
                            ?>
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo SURVEY_QUESTION_PAGE_LOCAL_GROUP; ?>
                                    </h3>
                                </span>
                                <?php
                                $i = 1;
                                foreach ($session_group_local as $key => $group_id) {
                                    $group = new Group();
                                    $group->get_from_db($group_id);
                                    if ($group->getName() != '') {
                                        ?>
                                        <span class="grid_3 al">
                                            <?php print_r($i . '. ' . $group->getName()); ?>
                                        </span>
                                        <span class="grid_1">
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_session_group&amp;session_group_type=local&amp;session_group_id=<?php echo $key; ?>">
                                                <span class="delete"></span>
                                            </a>
                                        </span>
                                        <?php
                                    }
                                    $i++;
                                }
                                ?>
                            </div>
                            <?php
                        }

                        // list session answers
                        $session_answers = get_session_answers();
                        if (!empty($session_answers)) {
                            ?>
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo SURVEY_QUESTION_PAGE_ANSWERS; ?>
                                    </h3>
                                </span>
                                <?php
                                foreach ($session_answers as $key => $answer) {
									$answer_value = $answer->getValue();
                                    if (!empty($answer_value)) {
                                        ?>
                                        <label for="answer<?php print_r($key); ?>Text"><?php print_r($answer_value); ?>:
                                            <small><?php print_r($answer->getDescription()); ?></small>
                                        </label>
                                        <input id="answer<?php print_r($key); ?>Text" name="answer<?php print_r($key); ?>Text" type="<?php print_r($answer->getType()); ?>" disabled="disabled" />
                                        <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_session_answer&amp;answer_id=<?php echo $key; ?>">
                                            <span class="delete"></span>
                                        </a>
                                        <br>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </section>
                </div>
                <br/>
                <div class="action no-margin ac ui-widget" style="padding-left: 20px;">
                    <input id="formSurveyCreate" class="button button-green" name="formSurveyCreate" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                    <input id="formSurveyReset" class="button button-orange" name="formSurveyReset" type="submit" value="<?php echo BTN_RESET; ?>" />
                    <input id="formSurveyRemove" class="button button-red" name="formSurveyRemove" type="submit" value="<?php echo BTN_DELETE; ?>" />
                    <input name="formSurveyFunction" value="<?php print_r($session_survey->getId()); ?>" type="hidden" />
                </div>
            </form>
            <br/><br/><br/>
        </div>
        <h3 class="no-float ac" id="survey_add_group">
            <?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_TITLE; ?>
        </h3>
        <div class="ac">
            <section>
                <div class="ac">
                    <?php
                    if ($session_group['type'] == '') {
                        ?>
                        <form id="formSurveyAddGroup" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&funct=add_survey_group_type'; ?>" method="POST">
                            <div class="ac">
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupStudent"><?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_STUDENTS_NAME; ?>
                                        <small><?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_STUDENTS_INFO; ?></small>
                                    </label>
                                    <input id="formSurveyAddGroupStudent" name="formSurveyAddGroupType" type="radio" value="student" />
                                    <br/><br/><br/>
                                    <label for="formSurveyAddGroupStaff"><?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_STAFF_NAME; ?>
                                        <small><?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_STAFF_INFO; ?></small>
                                    </label>
                                    <input id="formSurveyAddGroupStaff" name="formSurveyAddGroupType" type="radio" value="staff" />
                                    <br/><br/><br/>
                                    <label for="formSurveyAddGroupLocal"><?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_LOCAL_NAME; ?>
                                        <small><?php echo SURVEY_QUESTION_PAGE_ADD_GROUP_LOCAL_INFO; ?></small>
                                    </label>
                                    <input id="formSurveyAddGroupLocal" name="formSurveyAddGroupType" type="radio" value="local" />
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 20px;">
                                <input id="formSurveyAddGroupSubmit" class="button button-green" name="formSurveyAddGroupSubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                                <input id="formSurveyAddGroupReset" class="button button-orange" name="formSurveyAddGroupReset" type="reset" value="<?php echo BTN_RESET; ?>" />
                                <input id="formSurveyAddGroup" class="button button-green" name="formSurveyAddGroup" type="hidden" value="formSurveyAddGroup" />
                                <a id="formSurveyAddGroupCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_group_type"><?php echo BTN_CANCEL; ?></a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_group['type'] == 'student') {
                        $susi_student_groups = get_susi_student_groups();
                        ?>
                        <form id="formSurveyAddGroupSusiStudent" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&funct=add_survey_group_susi_student'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    Изберете група студенти
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupSusiStudentGroup">Факултети <em>*</em>
                                        <small>Изберете факултет за групата студенти<br/>
                                            По подразбиране е избран университетът, т.е. всички факултети
                                        </small>
                                    </label>
                                    <select id="formSurveyAddGroupSusiStudentGroup" name="formSurveyAddGroupSusiStudentGroup[]" multiple="multiple" required="required">
                                        <option value="0" selected="selected">СУ "Св. Климент Охридски"</option>
                                        <?php
                                        foreach ($susi_student_groups as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" <?php in_array($group->getId(), $session_group['student']) ? print_r('selected="selected"') : print_r(''); ?>><?php echo $group->getName(); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupSusiStudentSubmit" class="button button-green" name="formSurveyAddGroupSusiStudentSubmit" type="submit" value="Добави" />
                                <input id="formSurveyAddGroupSusiStudentReset" class="button button-orange" name="formSurveyAddGroupSusiStudentReset" type="reset" value="Изчисти" />
                                <input id="formSurveyAddGroupSusiStudent" class="button button-green" name="formSurveyAddGroupSusiStudent" type="hidden" value="formSurveyAddGroupStudentSusi" />
                                <a id="fformSurveyAddGroupSusiStudentCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_group_type">Отказ</a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_group['type'] == 'staff') {
                        $susi_staff_faculties = get_susi_staff_faculties();
                        ?>
                        <form id="formSurveyAddGroupSusiStaffFaculty" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&funct=add_survey_group_susi_staff_faculty'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    Изберете група служители от факултетите в СУ
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupSusiStaffFaculty">Факултети <em>*</em>
                                        <small>Изберете факултет за групата служители<br/>
                                            По подразбиране е избран университетът, т.е. всички факултети и звена
                                        </small>
                                    </label>
                                    <select id="formSurveyAddGroupSusiStaffFaculty" name="formSurveyAddGroupSusiStaffFacultyGroup[]" multiple="multiple" required="required">
                                        <option value="0" selected="selected">СУ "Св. Климент Охридски"</option>
                                        <?php
                                        foreach ($susi_staff_faculties as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" <?php in_array($group->getId(), $session_group['staff']) ? print_r('selected="selected"') : print_r(''); ?>><?php echo $group->getName(); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupSusiStaffFacultySubmit" class="button button-green" name="formSurveyAddGroupSusiStaffFacultySubmit" type="submit" value="Добави" />
                                <input id="formSurveyAddGroupSusiStaffFacultyReset" class="button button-orange" name="formSurveyAddGroupSusiStaffFacultyReset" type="reset" value="Изчисти" />
                                <input id="formSurveyAddGroupSusiStaffFaculty" class="button button-green" name="formSurveyAddGroupSusiStaffFaculty" type="hidden" value="formSurveyAddGroupSusiStaffFaculty" />
                                <a id="formSurveyAddGroupSusiStaffFacultyCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_group_type">Отказ</a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_group['type'] == 'staff_departments') {
                        $session_group_staff = $session_group['staff'];
                        ?>
                        <form id="formSurveyAddGroupSusiStaffFacultyDepartment" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&funct=add_survey_group_susi_staff_faculty_department'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    Изберете група служители от отделите на факултетите в СУ
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupSusiStaffFacultyDepartment">Отдели <em>*</em>
                                        <small>Изберете отдел/и от факултет/ите за групата служители<br/>
                                            По подразбиране са избрани целите факултети и звена
                                        </small>
                                    </label>
                                    <select id="formSurveyAddGroupSusiStaffFacultyDepartment" name="formSurveyAddGroupSusiStaffFacultyDepartmentGroup[]" multiple="multiple" required="required">
                                        <?php
                                        foreach ($session_group_staff as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" selected="selected"><b><?php echo $group->getName(); ?></b></option>
                                            <?php
                                            $session_group_staff_department = get_susi_staff_departments_by_faculty($group->getSusiId());
                                            foreach ($session_group_staff_department as $subgroup_id) {
                                                $subgroup = new Group;
                                                $subgroup->get_from_db($subgroup_id);
                                                ?>
                                                <option value="<?php echo $subgroup->getId(); ?>" <?php in_array($subgroup->getId(), $session_group['staff_departments']) ? print_r('selected="selected"') : print_r(''); ?>>&nbsp;&nbsp; <?php echo $subgroup->getName(); ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupSusiStaffFacultyDepartmentSubmit" class="button button-green" name="formSurveyAddGroupSusiStaffFacultyDepartmentSubmit" type="submit" value="Добави" />
                                <input id="formSurveyAddGroupSusiStaffFacultyDepartmentReset" class="button button-orange" name="formSurveyAddGroupSusiStaffFacultyDepartmentReset" type="reset" value="Изчисти" />
                                <input id="formSurveyAddGroupSusiStaffFacultyDepartment" class="button button-green" name="formSurveyAddGroupSusiStaffFacultyDepartment" type="hidden" value="formSurveyAddGroupSusiStaffFacultyDepartment" />
                                <a id="formSurveyAddGroupSusiStaffFacultyDepartmentCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_group_type">Отказ</a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_group['type'] == 'local') {
                        $local_groups = get_local_groups_by_creator($user->getId());
                        ?>
                        <form id="formSurveyAddGroupLocal" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&funct=add_survey_group_local'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    Изберете Ваша локална група
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupLocal">Локални групи
                                        <small>Изберете локална група създадена от Вас</small>
                                    </label>
                                    <select id="formSurveyAddGroupLocal" name="formSurveyAddGroupLocalGroup[]" multiple="multiple">
                                        <option value="0" selected="selected">Всички</option>
                                        <?php
                                        foreach ($local_groups as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" <?php in_array($group->getId(), $session_group['local']) ? print_r('selected="selected"') : print_r(''); ?>><?php echo $group->getName(); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupLocalSubmit" class="button button-green" name="formSurveyAddGroupLocalSubmit" type="submit" value="Добави" />
                                <input id="formSurveyAddGroupLocalReset" class="button button-orange" name="formSurveyAddGroupLocalReset" type="reset" value="Изчисти" />
                                <input id="formSurveyAddGroupLocal" class="button button-green" name="formSurveyAddGroupLocal" type="hidden" value="formSurveyAddGroupLocal" />
                                <a id="formSurveyAddGroupLocalCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_question&amp;funct=delete_group_type">Отказ</a>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                    <br/>
                </div>
            </section>
            <br/><br/><br/>
        </div>
        <h3 class="no-float ac" id="survey_add_answer">
            <?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TITLE; ?>
        </h3>
        <div class="ac">
            <section>
                <form id="formSurveyAddAnswer" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_question&amp;funct=add_survey_answer'; ?>" method="POST">
                    <div class="ac">
                        <section class="clearfix prefix_2">
                            <label for="formSurveyAddAnswer"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_NAME; ?>
                                <em>*</em>
                                <small><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_INFO; ?></small>
                            </label>
                            <input id="formSurveyAddAnswer" name="formSurveyAddAnswer" type="text" required="required" />
                            <br/>
                            <label for="formSurveyAddAnswerDescription"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_DESCRIPTION; ?>
                                <small><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_DESCRIPTION_INFO; ?></small>
                            </label>
                            <input id="formSurveyAddAnswerDescription" name="formSurveyAddAnswerDescription" type="text" />
                            <br/>
                            <label for="formSurveyAddAnswerType"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE; ?>
                                <em>*</em>
                                <small><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_INFO; ?></small>
                            </label>
                            <select id="formSurveyAddAnswerType" name="formSurveyAddAnswerType" required="required">
                                <option value="null"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_NULL; ?></option>
                                <option value="text"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_TEXT; ?></option>
                                <option value="checkbox"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_CHECKBOX; ?></option>
                                <option value="radio"><?php echo SURVEY_QUESTION_PAGE_ADD_ANSWER_TYPE_RADIO; ?></option>
                            </select>
                            <br/>
                        </section>
                    </div>
                    <br/>
                    <div class="action no-margin ac ui-widget" style="padding-left: 20px;">
                        <input id="formSurveyAddAnswerSubmit" class="button button-green" name="formSurveyAddAnswerSubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                        <input id="formSurveyAddAnswerReset" class="button button-orange" name="formSurveyAddAnswerReset" type="reset" value="<?php echo BTN_RESET; ?>" />
                        <input id="formSurveyAddAnswerNew" class="button button-green" name="formSurveyAddAnswerNew" type="hidden" value="formSurveyAddAnswerNew" />
                        <a id="formSurveyAddAnswerCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" href="<?php isset($_SERVER['HTTP_REFERER']) ? print_r($_SERVER["HTTP_REFERER"]) : print_r(ROOT_DIR . '?page=logout'); ?>"><?php echo BTN_CANCEL; ?></a>;
                    </div>
                </form>
            </section>
            <br/><br/><br/>
        </div>
    </div>
</div>
