<?php

// main survey class
class Survey extends BaseObject {

    private $created_by;
    private $available_from;
    private $available_due;
    private $title;
    private $staffGroups;
    private $studentGroups;
    private $localGroups;
    private $status;

    // object constructor
    function get_from_db($id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT *
                FROM surveys
                WHERE is_active='1' AND id='$id'";

        $survey_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $survey_data[$key] = $value;
        }

        if (isset($survey_data[0])) {
            $this->setId($survey_data[0]['id']);
            $this->setIsActive($survey_data[0]['is_active']);
            $this->setCreatedOn($survey_data[0]['created_on']);
            $this->setLastEditedOn($survey_data[0]['last_edited_on']);
            $this->setCreatedBy($survey_data[0]['created_by']);
            $this->setAvailableFrom($survey_data[0]['available_from']);
            $this->setAvailableDue($survey_data[0]['available_due']);
            $this->setTitle($survey_data[0]['title']);
            $this->setStaffGroups($survey_data[0]['staff_groups']);
            $this->setStudentGroups($survey_data[0]['student_groups']);
            $this->setLocalGroups($survey_data[0]['local_groups']);
            $this->setStatus($survey_data[0]['status']);
        } else {
            unset($this);
        }
    }

    // store in db function
    function store_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "INSERT INTO surveys
                (is_active, created_on, last_edited_on, created_by,
                available_from, available_due, title,
                staff_groups, student_groups, local_groups, status)
                VALUES ('" . $this->getIsActive() . "',
                        '" . $this->getCreatedOn() . "',
                        '" . $this->getLastEditedOn() . "',
                        '" . $this->getCreatedBy() . "',
                        '" . $this->getAvailableFrom() . "',
                        '" . $this->getAvailableDue() . "',
                        '" . $this->getTitle() . "',
                        '" . $this->getStaffGroups() . "',
                        '" . $this->getStudentGroups() . "',
                        '" . $this->getLocalGroups() . "',
                        '" . $this->getStatus() . "');";
        $survey_id = NULL;
        try {
            $db->exec($sql);
            $survey_id = $db->lastInsertId();
            $info = "Survey: " . $survey_id . " created";
            info($info);
        } catch (PDOExecption $e) {
            $error = "Fail store survey in db: $e";
            error($error);
        }

        return $survey_id;
    }

    // update in db function
    function update_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "UPDATE surveys
                SET is_active = '" . $this->getIsActive() . "',
                    last_edited_on = '" . $this->getLastEditedOn() . "',
                    available_from = '" . $this->getAvailableFrom() . "',
                    available_due = '" . $this->getAvailableDue() . "',
                    title = '" . $this->getTitle() . "',
                    status = '" . $this->getStatus() . "',
                    staff_groups = '" . $this->getStaffGroups() . "',
                    student_groups = '" . $this->getStudentGroups() . "',
                    local_groups = '" . $this->getLocalGroups() . "'
                WHERE id = '" . $this->getId() . "';";

        $db->exec($sql);
    }

    // get voted users
    function get_voted_users() {
        //include connection variable
        global $db;
        
        // sql statement
        $sql = "SELECT user_id
                FROM votes
                WHERE is_active='1' AND survey_id='" . $this->getId() . "'";

        $votes_data = array();
        $index = 0;
        foreach ($db->query($sql) as $key => $value) {
            $votes_data[$index] = $value['user_id'];
            $index++;
        }
        $voted_users = array_unique($votes_data);
        return $voted_users;
    }
    
    // get survey questions
    function get_questions() {
        //include connection variable
        global $db;
        
        // sql statement
        $sql = "SELECT id
                FROM questions
                WHERE is_active='1' AND type='0' AND survey='" . $this->getId() . "'";

        $questions = array();
        $index = 0;
        foreach ($db->query($sql) as $key => $value) {
            $questions[$index] = $value['id'];
            $index++;
        }
        return $questions;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
        return $this;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }

    public function setAvailableFrom($available_from) {
        $this->available_from = $available_from;
        return $this;
    }

    public function getAvailableFrom() {
        return $this->available_from;
    }

    public function setAvailableDue($available_due) {
        $this->available_due = $available_due;
        return $this;
    }

    public function getAvailableDue() {
        return $this->available_due;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setGroups($groups) {
        $this->groups = $groups;
        return $this;
    }

    public function getGroups() {
        return $this->groups;
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStaffGroups($staffGroups) {
        $this->staffGroups = $staffGroups;
        return $this;
    }

    public function getStaffGroups() {
        return $this->staffGroups;
    }

    public function setStudentGroups($studentGroups) {
        $this->studentGroups = $studentGroups;
        return $this;
    }

    public function getStudentGroups() {
        return $this->studentGroups;
    }

    public function setLocalGroups($localGroups) {
        $this->localGroups = $localGroups;
        return $this;
    }

    public function getLocalGroups() {
        return $this->localGroups;
    }

}

?>