<?php

// main survey class
class SurveyFunctions extends Survey {

    // get votes
    function getVotesByUser($user_id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT id
                FROM votes
                WHERE is_active='1' AND survey_id='" . $this->getId() . "' AND user_id='$user_id'";

        $votes = array();
        foreach ($db->query($sql) as $key => $value) {
            $votes[$key] = $value['id'];
        }

        return $votes;
    }

    function getStudentGroupsArray() {
        $studentGroupsArray = array();
        try {
            $studentGroupsArray = unserialize(parent::getStudentGroups());
        } catch (Exception $exc) {
            $error = new Error($exc->getMessage());
            $error->writeLog();
        }
        return $studentGroupsArray;
    }

    function getStaffGroupsArray() {
        $staffGroupsArray = array();
        try {
            $staffGroupsArray = unserialize(parent::getStaffGroups());
        } catch (Exception $exc) {
            $error = new Error($exc->getMessage());
            $error->writeLog();
        }
        return $staffGroupsArray;
    }

    function getLocalGroupsArray() {
        $localGroupsArray = array();
        try {
            $localGroupsArray = unserialize(parent::getLocalGroups());
        } catch (Exception $exc) {
            $error = new Error($exc->getMessage());
            $error->writeLog();
        }
        return $localGroupsArray;
    }

    function getAllGroupsArray() {
        return array_merge($this->getStudentGroupsArray(), $this->getStaffGroupsArray(), $this->getLocalGroupsArray());
    }
}

?>