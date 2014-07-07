<?php

// main survey class
class Answer extends BaseObject {

    private $survey;
    private $value;
    private $description;
    private $type;

    // object constructor
    function get_from_db($id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT *
                FROM answers
                WHERE is_active='1' AND id='$id'";
        
        $answer_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $answer_data[$key] = $value;
        }
        
        if(isset($answer_data[0])) {
            $this->setId($answer_data[0]['id']);
            $this->setIsActive($answer_data[0]['is_active']);
            $this->setCreatedOn($answer_data[0]['created_on']);
            $this->setLastEditedOn($answer_data[0]['last_edited_on']);
            $this->setSurvey($answer_data[0]['survey_id']);
            $this->setValue($answer_data[0]['value']);
            $this->setDescription($answer_data[0]['description']);
            $this->setType($answer_data[0]['type']);
        } else {
            unset($this);
        } 
    }
    
    // store in db function
    function store_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "INSERT INTO answers
                (is_active, created_on, last_edited_on, survey_id,
                value, description, type)
                VALUES ('".$this->getIsActive()."',
                        '".$this->getCreatedOn()."',
                        '".$this->getLastEditedOn()."',
                        '".$this->getSurvey()."',
                        '".$this->getValue()."',
                        '".$this->getDescription()."',
                        '".$this->getType()."');";
        
        $db->exec($sql);
    }
    
    // update in db function
    function update_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "UPDATE answers
                SET is_active = '".$this->getIsActive()."',
                    last_edited_on = '".$this->getLastEditedOn()."',
                    survey_id = '".$this->getSurvey()."',
                    value = '".$this->getValue()."',
                    type = '".$this->getType()."',
                    description = '".$this->getDescription()."'
                WHERE id = '".$this->getId()."';";
        
        $db->exec($sql);
    }
    
    public function setSurvey($survey) {
        $this->survey = $survey;
        return $this;
    }

    public function getSurvey() {
        return $this->survey;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function getValue() {
        return $this->value;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }
    
    public function getVotes() {
        // set connection var
        global $db;

        //query to get all associated surveys
        $sql = "SELECT id
                FROM votes
                WHERE is_active = '1' AND answer_id = '".$this->getId()."';";

        $votes_data = array();
        $votes = array();

        foreach ($db->query($sql) as $key => $value) {
            $votes_data[$key] = $value;
            foreach ($votes_data[$key] as $subkey => $subvalue) {
                if (is_int($subkey)) {
                    $votes[] = $subvalue;
                }
            }
        }

        return $votes;
    }
}

?>