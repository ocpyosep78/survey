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

    private $gender = 0;
    private $birthYear = "";
    private $EGN_WEIGHTS = array(2, 4, 8, 5, 10, 9, 7, 3, 6);

    /* Check if EGN is valid */
    /* See: http://www.grao.bg/esgraon.html */
    function egn_valid($egn) {
        if (strlen($egn) != 10)
            return false;
        $year = substr($egn, 0, 2);
        $mon = substr($egn, 2, 2);
        $day = substr($egn, 4, 2);
        if ($mon > 40) {
            if (!checkdate($mon - 40, $day, $year + 2000))
                return false;
        } else
        if ($mon > 20) {
            if (!checkdate($mon - 20, $day, $year + 1800))
                return false;
        } else {
            if (!checkdate($mon, $day, $year + 1900))
                return false;
        }
        $checksum = substr($egn, 9, 1);
        $egnsum = 0;
        for ($i = 0; $i < 9; $i++)
            $egnsum += substr($egn, $i, 1) * $this->EGN_WEIGHTS[$i];
        $valid_checksum = $egnsum % 11;
        if ($valid_checksum == 10)
            $valid_checksum = 0;
        if ($checksum == $valid_checksum)
            return true;
    }

    /* Return array with EGN info */
    function egn_parse($egn) {
        if (!egn_valid($egn))
            return false;
        $ret = array();
        $ret["year"] = substr($egn, 0, 2);
        $ret["month"] = substr($egn, 2, 2);
        $ret["day"] = substr($egn, 4, 2);
        if ($ret["month"] > 40) {
            $ret["month"] -= 40;
            $ret["year"] += 2000;
        } else
        if ($ret["month"] > 20) {
            $ret["month"] -= 20;
            $ret["year"] += 1800;
        } else {
            $ret["year"] += 1900;
        }

        $ret["sex"] = substr($egn, 8, 1) % 2;

        $this->birthYear = $ret["year"];
        $this->gender = 1;

        if (!$ret["sex"]) {
            $this->gender = 0;
        }
        return $ret;
    }

    // get ldap attribute
    function getLdapEgnInfo() {
        $ldapAttributeValue = "";

        // ldap connecting: must be a valid LDAP server!
        $ds = ldap_connect("ds.uni-sofia.bg");

        // try ldap bind
        if ($ds) {
            // set ldap bind variables
            $ldaprdn = 'uid=schedule,ou=System,dc=uni-sofia,dc=bg';
            $ldappass = 'Ahchit7chu';

            $ldapbind = ldap_bind($ds, $ldaprdn, $ldappass);

            if ($ldapbind) {
                // data array 
                $array = array('supersonalid');
                $sr = ldap_search($ds, "ou=People,dc=uni-sofia,dc=bg", "(uid=" . $this->getUsername() . ")", $array, 0, 0, 0);
                $info = ldap_get_entries($ds, $sr);
//                $ldapAttributeValue = egnDecode($info[0]['supersonalid'][0]);
                $this->egn_parse($info[0]['supersonalid'][0]);

                ldap_close($ds);
            }
        } else {
            $error = new Error("LDAP server unavailable");
            $error->writeLog();
        }
        $egnArray = array(
            "gender" => $this->gender,
            "birthYear" => $this->birthYear
        );
        return $egnArray;
    }

    function getGender() {
        $gender = NULL;
        if (isset($this->getLdapEgnInfo()['gender'])) {
            $gender = $this->getLdapEgnInfo()['gender'];
        }
        return $gender;
    }
}
