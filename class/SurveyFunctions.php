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
                WHERE is_active='1' AND survey_id='". $this->getId() ."' AND user_id='$user_id'";

        $votes = array();
        foreach ($db->query($sql) as $key => $value) {
            $votes[$key] = $value['id'];
        }

        return $votes;
    }

}

?>