<?php

/*
 * Copyright 2014 ttt.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Description of UserFunctions
 *
 * @author ttt
 */
class UserFunctions extends User {

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

    function getGender() {
        $gender = 0;
        return $gender;
    }
    
    function getBirthYear() {
        $birthYear = 1990;
        return $birthYear;
    }
}
