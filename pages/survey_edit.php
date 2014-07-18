<?php
// get session vars
global $user;
$session_survey = new Survey();
$session_question = new Question();
$session_groups = array();
$session_answers = array();

$session_survey = get_session_survey();
$session_question = get_session_question();
$session_groups = get_session_groups();
$session_answers = get_session_answers();

?>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo ROOT_DIR; ?>js/jquery-ui.js"></script>

<div class="ac">
    <div class="accordion">
        <h3 class="no-float ac" id="survey_data"><?php echo SURVEY_QUESTION_PAGE_SURVEY_DATA; ?></h3>
        <div class="ac">
            <form id="formSurvey" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&amp;funct=survey_funct'; ?>" method="POST">
                <div class="ac">
                    <section class="clearfix prefix_2">
                        <label for="formSurveyQuestion"><?php echo SURVEY_QUESTION_PAGE_SURVEY_NAME; ?> <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_NAME_INFO; ?></small>
                        </label>
                        <input id="formSurveyQuestion" 
                               name="formSurveyQuestion" 
                               type="text" 
                               required="required" 
                               value="<?php echo $session_survey->getTitle(); ?>" />
                        <br/>
                        <label for="formSurveyFromHour"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_TIME; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_TIME_INFO; ?></small>
                        </label>
                        <input id="formSurveyFromHour" name="formSurveyFromHour" type="time" value="<?php ((substr($session_survey->getAvailableFrom(), 11, 5) != "00:00") and ( $session_survey->getAvailableFrom() != null)) ? print substr($session_survey->getAvailableFrom(), 11, 5) : print date("H:i"); ?>" required="required" />
                        <br/>
                        <label for="formSurveyFromDate"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_DATE; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_FROM_DATE_INFO; ?></small>
                        </label>
                        <input id="formSurveyFromDate" name="formSurveyFromDate" type="date" value="<?php ((substr($session_survey->getAvailableFrom(), 0, 10) != "0000-00-00") and ( $session_survey->getAvailableFrom() != null)) ? print substr($session_survey->getAvailableFrom(), 0, 10) : print date("Y-m-d"); ?>" required="required" />
                        <br/>
                        <label for="formSurveyDueHour"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_TIME; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_TIME_INFO; ?></small>
                        </label>
                        <input id="formSurveyDueHour" name="formSurveyDueHour" type="time" value="<?php ((substr($session_survey->getAvailableDue(), 11, 5) != "00:00") and ( $session_survey->getAvailableDue() != null)) ? print substr($session_survey->getAvailableDue(), 11, 5) : print date("H:i"); ?>" required="required" />
                        <br/>
                        <label for="formSurveyDueDate"><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_DATE; ?>
                            <em>*</em>
                            <small><?php echo SURVEY_QUESTION_PAGE_SURVEY_ACTIVE_DUE_DATE_INFO; ?></small>
                        </label>
                        <input id="formSurveyDueDate" name="formSurveyDueDate" type="date" value="<?php ((substr($session_survey->getAvailableDue(), 0, 10) != "0000-00-00") and ( $session_survey->getAvailableDue() != null)) ? print substr($session_survey->getAvailableDue(), 0, 10) : print date("Y-m-d"); ?>" required="required" />
                        <div class="clearfix">
                            <span class="grid_3">
                                <h3>
                                    <?php echo MY_SURVEYS_PAGE_STATUS_SURVEY; ?>
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
                        $session_group_student = $session_groups['student'];
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
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_session_group&amp;session_group_type=student&amp;session_group_id=<?php echo $key; ?>">
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
                        $session_group_staff = $session_groups['staff'];
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
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_session_group&amp;session_group_type=staffs&amp;session_group_id=<?php echo $key; ?>">
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
                        $session_group_staff_departments = $session_groups['staff_departments'];
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
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_session_group&amp;session_group_type=staff_departments&amp;session_group_id=<?php echo $key; ?>">
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
                        $session_group_local = $session_groups['local'];
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
                                            <a id="deleteSurveyAnswer" class="button fl" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_session_group&amp;session_group_type=local&amp;session_group_id=<?php echo $key; ?>">
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
                    if ($session_groups['type'] == '') {
                        ?>
                        <form id="formSurveyAddGroup" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&funct=add_survey_group_type'; ?>" method="POST">
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
                                <a id="formSurveyAddGroupCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_group_type"><?php echo BTN_CANCEL; ?></a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_groups['type'] == 'student') {
                        $susi_student_groups = get_susi_student_groups();
                        ?>
                        <form id="formSurveyAddGroupSusiStudent" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&funct=add_survey_group_susi_student'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    <?php
                                    echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STUDENTS;
                                    ?>
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupSusiStudentGroup"><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STUDENTS_FACULTIES; ?> <em>*</em>
                                        <small><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STUDENTS_CHOICE_FACULTIES; ?></small>
                                    </label>
                                    <select id="formSurveyAddGroupSusiStudentGroup" name="formSurveyAddGroupSusiStudentGroup[]" multiple="multiple" required="required">
                                        <option value="0" selected="selected"><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STUDENTS_DEFAULT_OPTION; ?></option>
                                        <?php
                                        foreach ($susi_student_groups as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" 
                                                    <?php in_array($group->getId(), $session_groups['student']) ? print_r('selected="selected"') : print_r(''); ?>><?php echo $group->getName(); ?></option>
                                                    <?php
                                                }
                                                ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupSusiStudentSubmit" class="button button-green" name="formSurveyAddGroupSusiStudentSubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                                <input id="formSurveyAddGroupSusiStudentReset" class="button button-orange" name="formSurveyAddGroupSusiStudentReset" type="reset" value="<?php echo BTN_CANCEL; ?>" />
                                <input id="formSurveyAddGroupSusiStudent" class="button button-green" name="formSurveyAddGroupSusiStudent" type="hidden" value="formSurveyAddGroupStudentSusi" />
                                <a id="fformSurveyAddGroupSusiStudentCancel" 
                                   class="button button-red fl" 
                                   style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" 
                                   href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_group_type"><?php echo BTN_DELETE; ?></a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_groups['type'] == 'staff') {
                        $susi_staff_faculties = get_susi_staff_faculties();
                        ?>
                        <form id="formSurveyAddGroupSusiStaffFaculty" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&funct=add_survey_group_susi_staff_faculty'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    <?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STAFF; ?>
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupSusiStaffFaculty"><?php EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STAFF_FACULTIES; ?> <em>*</em>
                                        <small><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STAFF_CHOICE_FACULTIES; ?></small>
                                    </label>
                                    <select id="formSurveyAddGroupSusiStaffFaculty" name="formSurveyAddGroupSusiStaffFacultyGroup[]" multiple="multiple" required="required">
                                        <option value="0" selected="selected"><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_STAFF_DEFAULT_OPTION; ?></option>
                                        <?php
                                        foreach ($susi_staff_faculties as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" <?php in_array($group->getId(), $session_groups['staff']) ? print_r('selected="selected"') : print_r(''); ?>><?php echo $group->getName(); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupSusiStaffFacultySubmit" class="button button-green" name="formSurveyAddGroupSusiStaffFacultySubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                                <input id="formSurveyAddGroupSusiStaffFacultyReset" class="button button-orange" name="formSurveyAddGroupSusiStaffFacultyReset" type="reset" value="<?php echo BTN_CANCEL; ?>" />
                                <input id="formSurveyAddGroupSusiStaffFaculty" class="button button-green" name="formSurveyAddGroupSusiStaffFaculty" type="hidden" value="formSurveyAddGroupSusiStaffFaculty" />
                                <a id="formSurveyAddGroupSusiStaffFacultyCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_group_type"><?php echo BTN_DELETE; ?></a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_groups['type'] == 'staff_departments') {
                        $session_group_staff = $session_groups['staff'];
                        ?>
                        <form id="formSurveyAddGroupSusiStaffFacultyDepartment" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&funct=add_survey_group_susi_staff_faculty_department'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    <?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_SUB_GROUP_STAFF; ?>
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupSusiStaffFacultyDepartment"><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_SUB_GROUP_STAFF_FACULTIES; ?> <em>*</em>
                                        <small><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_SUB_GROUP_STAFF_CHOICE_FACULTIES; ?></small>
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
                                                <option value="<?php echo $subgroup->getId(); ?>" <?php in_array($subgroup->getId(), $session_groups['staff_departments']) ? print_r('selected="selected"') : print_r(''); ?>>&nbsp;&nbsp; <?php echo $subgroup->getName(); ?></option>
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
                                <input id="formSurveyAddGroupSusiStaffFacultyDepartmentSubmit" class="button button-green" name="formSurveyAddGroupSusiStaffFacultyDepartmentSubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                                <input id="formSurveyAddGroupSusiStaffFacultyDepartmentReset" class="button button-orange" name="formSurveyAddGroupSusiStaffFacultyDepartmentReset" type="reset" value="<?php echo BTN_CANCEL; ?>" />
                                <input id="formSurveyAddGroupSusiStaffFacultyDepartment" class="button button-green" name="formSurveyAddGroupSusiStaffFacultyDepartment" type="hidden" value="formSurveyAddGroupSusiStaffFacultyDepartment" />
                                <a id="formSurveyAddGroupSusiStaffFacultyDepartmentCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_group_type"><?php echo BTN_DELETE; ?></a>
                            </div>
                        </form>
                        <?php
                    } elseif ($session_groups['type'] == 'local') {
                        $local_groups = get_local_groups_by_creator($user->getId());
                        ?>
                        <form id="formSurveyAddGroupLocal" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&funct=add_survey_group_local'; ?>" method="POST">
                            <div class="ac">
                                <hr/>
                                <h4>
                                    <?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_LOCAL; ?>
                                </h4>
                                <hr/>
                                <section class="clearfix prefix_2">
                                    <label for="formSurveyAddGroupLocal"><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_LOCAL_GROUPS; ?>
                                        <small><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_LOCAL_LOCAL_GROUPS; ?></small>
                                    </label>
                                    <select id="formSurveyAddGroupLocal" name="formSurveyAddGroupLocalGroup[]" multiple="multiple">
                                        <option value="0" selected="selected"><?php echo EDIT_SURVEY_PAGE_YOU_CHOOSE_GROUP_LOCAL_DEFAULT_OPTION; ?></option>
                                        <?php
                                        foreach ($local_groups as $group_id) {
                                            $group = new Group;
                                            $group->get_from_db($group_id);
                                            ?>
                                            <option value="<?php echo $group->getId(); ?>" <?php in_array($group->getId(), $session_groups['local']) ? print_r('selected="selected"') : print_r(''); ?>><?php echo $group->getName(); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <br/><br/><br/>
                                </section>
                            </div>
                            <br/>
                            <div class="action no-margin ac ui-widget" style="padding-left: 25px;">
                                <input id="formSurveyAddGroupLocalSubmit" class="button button-green" name="formSurveyAddGroupLocalSubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                                <input id="formSurveyAddGroupLocalReset" class="button button-orange" name="formSurveyAddGroupLocalReset" type="reset" value="<?php echo BTN_CANCEL; ?>" />
                                <input id="formSurveyAddGroupLocal" class="button button-green" name="formSurveyAddGroupLocal" type="hidden" value="formSurveyAddGroupLocal" />
                                <a id="formSurveyAddGroupLocalCancel" class="button button-red fl" style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;;" href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_group_type"><?php echo BTN_DELETE; ?></a>
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
            <?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_TITLE; ?>
        </h3>
        <div class="ac">
            <section>
                <form id="formSurveyAddElement" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&amp;funct=add_survey_element'; ?>" method="POST">
                    <div class="ac">
                        <section class="clearfix prefix_2">
                            <label for="formSurveyAddElementTitle"><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TITLE; ?>
                                <em>*</em>
                                <small><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TITLE_INFO; ?></small>
                            </label>
                            <input id="formSurveyAddElementTitle" name="formSurveyAddElementTitle" type="text" required="required" value="<?php echo $session_question->getTitle(); ?>" />
                            <br />
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TYPE; ?>
                                    </h3>
                                </span>
                                <label for="formSurveyAddElementTypeQuestion"><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TYPE_QUESTION_TITLE; ?>
                                    <em>*</em>
                                    <small><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TYPE_QUESTION_INFO; ?></small>
                                </label>
                                <input id="formSurveyAddElementTypeQuestion"
                                       name="formSurveyAddElementType"
                                       type="radio"
                                       value="0"
                                       required="required"
                                       <?php
                                       ($session_question->getType() == '0') ? print_r('checked="checked"') : print_r('');
                                       ?> />
                                <br /><br /><br />
                                <label for="formSurveyAddElementTypeTextbox"><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TYPE_TEXTBOX_TITLE; ?>
                                    <em>*</em>
                                    <small><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_ELEMENT_TYPE_TEXTBOX_INFO; ?></small>
                                </label>
                                <input id="formSurveyAddElementTypeTextbox"
                                       name="formSurveyAddElementType"
                                       type="radio"
                                       value="1"
                                       required="required"
                                       <?php
                                       ($session_question->getType() == '1') ? print_r('checked="checked"') : print_r('');
                                       ?> />
                            </div>

                        </section>
                    </div>
                    <br/>
                    <div class="action no-margin ac ui-widget" style="padding-left: 20px;">
                        <input id="formSurveyAddElementSubmit" class="button button-green" name="formSurveyAddElementSubmit" type="submit" value="<?php echo BTN_SUBMIT; ?>" />
                        <input id="formSurveyAddElementReset" class="button button-orange" name="formSurveyAddElementReset" type="reset" value="<?php echo BTN_RESET; ?>" />
                        <input id="formSurveyAddElementNew" class="button button-green" name="formSurveyAddElementNew" type="hidden" value="formSurveyAddElementNew" />
                        <a 
                            id="formSurveyAddElementCancel" 
                            class="button button-red fl" 
                            style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" 
                            href="<?php isset($_SERVER['HTTP_REFERER']) ? print_r($_SERVER["HTTP_REFERER"]) : print_r(ROOT_DIR . '?page=admin_survey'); ?>"><?php echo BTN_CANCEL; ?></a>
                    </div>
                </form>
                <!-- add sub element -->
                <form id="formSurveyAddSubElement" class="form ac" action="<?php echo ROOT_DIR . '?page=survey_edit&amp;funct=add_survey_element'; ?>" method="POST">
                    <div class="ac">
                        <section class="clearfix prefix_2">
                            <div class="clearfix">
                                <span class="grid_3">
                                    <h3>
                                        <?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_SUBELEMENTS_TITLE; ?>
                                    </h3>
                                </span>
                            </div>
                            <?php
                            if (!empty($session_answers)) {
                                ?>
                                <div class="clearfix">
                                    <?php
                                    foreach ($session_answers as $key => $answer) {
                                        if (($answer->getType() == "text") || ($answer->getType() == "radio") || ($answer->getType() == "checkbox")) {
                                            ?>
                                            <label for="answer<?php print_r($key); ?>Text"><?php print_r($answer->getValue()); ?>:
                                                <small><?php print_r($answer->getDescription()); ?></small>
                                            </label>
                                            <input id="answer<?php print_r($key); ?>Text" 
                                                   name="answer<?php print_r($key); ?>Text" 
                                                   type="<?php print_r($answer->getType()); ?>" 
                                                   disabled="disabled" />
                                            <a id="deleteSurveyAnswer" 
                                               class="button fl" 
                                               href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_session_answer&amp;answer_id=<?php echo $key; ?>">
                                                <span class="delete"></span>
                                            </a>
                                            <br>
                                            <?php
                                        } elseif ($answer->getType() == "textbox") {
                                            ?>
                                            <label for="answer<?php print_r($key); ?>Text"><?php echo EDIT_SURVEY_PAGE_ADD_ELEMENT_SUBELEMENTS_TEXTBOX_TITLE; ?>:
                                            </label>
                                            <textarea id="answer<?php print_r($key); ?>Text" 
                                                      name="answer<?php print_r($key); ?>Text" 
                                                      disabled="disabled" /><?php echo $answer->getValue(); ?></textarea>

                                            <a id="deleteSurveyAnswer" 
                                               class="button fl al"
                                               href="<?php echo ROOT_DIR; ?>?page=survey_edit&amp;funct=delete_session_answer&amp;answer_id=<?php echo $key; ?>">
                                                <span class="delete"></span>
                                            </a>
                                            <br/><br/>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </section>
                        <div class="ac">
                            <div class="action no-margin ac ui-widget">
                                <input 
                                    id="formSurveyAddSubElementInput"
                                    class="button button-blue dn jsShow"
                                    type="button" 
                                    style="margin-left: 290px;"
                                    value="<?php echo BTN_ADD; ?>"
                                    onclick='javascript:window.open("<?php print_r(ROOT_DIR . '?page=survey_add_answer'); ?>", "Add new answer", "width=900, height=500");' />
                                <a 
                                    id="formSurveyAddSubElementA"
                                    class="button button-blue jsHide" 
                                    style="color: #fff; width: 230px; margin: 2px 5px 0px 10px;" 
                                    target="_blank"
                                    href="<?php print_r(ROOT_DIR . '?page=survey_add_answer'); ?>"><?php echo BTN_ADD; ?></a>
                            </div>
                            <br/>
                        </div>
                    </div>
                </form>
                <!-- add sub element ends -->
            </section>
            <br/><br/><br/>
        </div>
    </div>
</div>

<!-- survey elements -->
<div class="ac">
    <br />
    <div class="ac info_box box_green">
        <h3>
            <b><?php echo EDIT_SURVEY_PAGE_SURVEY_ELEMENTS; ?></b>
        </h3>
    </div>
    <?php
    $survey_questions = get_survey_questions($session_survey->getId());
    if (!empty($survey_questions)) {
        ?>
        <div class="accordion">
            <?php
            foreach ($survey_questions as $question_id) {
                $question = new Question();
                $question->get_from_db($question_id);
                if ($question->getType() == 1) {
                    ?>
                </div>
                <div class="clearfix">
                    <h3 class="no-float ac"><?php print_r($question->getTitle()); ?></h3>

                    <?php
                    $answers = get_survey_answers($question->getId());
                    foreach ($answers as $answer_id) {
                        $answer = new Answer ();
                        $answer->get_from_db($answer_id);
                        if ($answer->getType() == "textbox") {
                            ?>
                            <div  class="al">
                                &emsp;&emsp;&emsp;<?php print_r($answer->getValue()); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <div class="al">
                        <form class="form ac" 
                              action="<?php echo ROOT_DIR . '?page=survey_edit&amp;funct=edit_survey_element' ?>" 
                              method="POST"
                              style="padding-bottom: 50px">
                            <div class="action no-margin ac prefix_1" style="margin-bottom: 20px;">
                                <input id="formSurvey<?php print_r($question->getId()); ?>Submit"
                                       class="button button-orange"
                                       name="formElementEdit"
                                       type="submit"
                                       value="<?php echo BTN_EDIT; ?>"
                                       style="margin-left: 56px;" />
                                <input type="hidden"
                                       name="formElementId"
                                       value="<?php print_r($question->getId()); ?>">
                                <a class="button button-red fl" 
                                   style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" 
                                   href="<?php print_r(ROOT_DIR . '?page=survey_edit&amp;funct=delete_question&amp;question_id=' . $question->getId()); ?>"><?php echo BTN_DELETE; ?></a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="accordion">
                    <?php
                } elseif ($question->getType() == 0) {
                    ?>
                    <h3 class="no-float ac"><?php print_r($question->getTitle()); ?></h3>
                    <div>
                        <?php
                        $answers = get_survey_answers($question->getId());
                        if (!empty($answers)) {
                            ?>
                            <form id="formSurvey<?php print_r($question->getId()); ?>" 
                                  class="form ac" 
                                  action="<?php echo ROOT_DIR . '?page=survey_edit&amp;funct=edit_survey_element' ?>" 
                                  method="POST">
                                <div class="ac">
                                    <section class="clearfix prefix_2">
                                        <?php
                                        foreach ($answers as $answer_id) {
                                            $answer = new Answer();
                                            $answer->get_from_db($answer_id);
                                            if (($answer->getType() == "text") || ($answer->getType() == "radio") || ($answer->getType() == "checkbox")) {
                                                ?>
                                                <label for = "formSurvey<?php print_r($session_survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>"><?php print_r($answer->getValue()); ?>
                                                    <small><?php print_r($answer->getDescription()); ?></small>
                                                </label>
                                                <input id="formSurvey<?php print_r($session_survey->getId()); ?>Answer<?php print_r($answer->getId()); ?>" 
                                                <?php
                                                if ($answer->getType() == "radio") {
                                                    print 'name="formSurvey' . $session_survey->getId() . 'Answer" ';
                                                } else {
                                                    print 'name="formSurvey' . $session_survey->getId() . 'Answer' . $answer->getId() . 'Type' . $answer->getType() . '" ';
                                                }
                                                ?>type="<?php print $answer->getType(); ?>" 
                                                       value="<?php $answer->getType() == "text" ? print_r("") : print_r($answer->getId()); ?>"
                                                       disabled="disabled" />
                                                <br/><br/>
                                                <?php
                                            } elseif ($answer->getType() == "textbox") {
                                                ?>
                                            </section>
                                            <section class="clearfix">
                                                <div class="al">
                                                    &emsp;&emsp;&emsp;<?php print_r($answer->getValue()); ?>
                                                </div>
                                                <br/><br/>
                                            </section>
                                            <section class="clearfix prefix_2">
                                                <?php
                                            }
                                        }
                                        ?>
                                    </section>
                                </div>
                                <br/>
                                <div class="action no-margin ac prefix_1">
                                    <input id="formQuestion<?php print_r($question->getId()); ?>Submit"
                                           class="button button-orange"
                                           name="formSurveyEdit"
                                           type="submit"
                                           value="<?php echo BTN_EDIT; ?>"
                                           style="margin-left: 30px;" />
                                    <input type="hidden"
                                           name="formElementId"
                                           value="<?php print_r($question->getId()); ?>">
                                    <a class="button button-red fl" 
                                       style="color: #fff; width: 230px; margin: 2px 0px 0px 10px;" 
                                       href="<?php print_r(ROOT_DIR . '?page=survey_edit&amp;funct=delete_question&amp;question_id=' . $question->getId()); ?>"><?php echo BTN_DELETE; ?></a>
                                </div>
                            </form>
                            <br/><br/>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                // check if text or question
            }
            ?>
        </div>
        <?php
    }
    ?>
</div>