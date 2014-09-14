<?php

/*
 * Copyright 2014 rintintin.
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
 * Description of QuestionFunctions
 *
 * @author rintintin
 */
class QuestionFunctions extends Question {

    // get voted users
    function getVotedUsers() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT user_id
                FROM votes
                WHERE is_active='1' AND question='" . $this->getId() . "'";

        $votes_data = array();
        $index = 0;
        foreach ($db->query($sql) as $key => $value) {
            $votes_data[$index] = $value['user_id'];
            $index++;
        }
        $voted_users = array_unique($votes_data);
        return $voted_users;
    }

    // get voted users birth years
    function getVotedUsersBirthYear() {
        $votedUsersBirthYear = array();
        return $votedUsersBirthYear;
    }
}
