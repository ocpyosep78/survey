<?php

// main survey class
class Question extends BaseObject {

    private $survey;
    private $title;
    private $type;

    // object constructor
    function get_from_db($id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT *
                FROM questions
                WHERE is_active='1' AND id='$id'";
        
        $question_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $question_data[$key] = $value;
        }
        
        if(isset($question_data[0])) {
            $this->setId($question_data[0]['id']);
            $this->setIsActive($question_data[0]['is_active']);
            $this->setCreatedOn($question_data[0]['created_on']);
            $this->setLastEditedOn($question_data[0]['last_edited_on']);
            $this->setSurvey($question_data[0]['survey']);
            $this->setTitle($question_data[0]['title']);
            $this->setType($question_data[0]['type']);
        } else {
            unset($this);
        } 
    }
    
    // store in db function
    function store_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "INSERT INTO questions
                (is_active, created_on, last_edited_on,
                survey, title, type)
                VALUES ('".$this->getIsActive()."',
                        '".$this->getCreatedOn()."',
                        '".$this->getLastEditedOn()."',
                        '".$this->getSurvey()."',
                        '".$this->getTitle()."',
                        '".$this->getType()."');";
        $question_id = NULL;
        try { 
            $db->exec($sql);
            $question_id = $db->lastInsertId();
            $info = "Question: " . $question_id . " created";
            info($info);
        } catch(PDOExecption $e) {
            $error = "Fail store question in db: $e";
            error($error);
        }
        return  $question_id;
    }
    
    // update in db function
    function update_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "UPDATE questions
                SET is_active = '".$this->getIsActive()."',
                    last_edited_on = '".$this->getLastEditedOn()."',
                    title = '".$this->getTitle()."',
                    survey = '".$this->getSurvey()."',
                    type = '".$this->getType()."'
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
    
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }
    
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getType() {
        return $this->type;
    }

}

?>