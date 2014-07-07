<?php

// main survey class
class Message extends BaseObject {

    private $title;
    private $text;
    private $user;

    // object constructor
    function get_from_db($id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT *
                FROM messages
                WHERE is_active='1' AND id='$id'";
        
        $message_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $message_data[$key] = $value;
        }
        
        if(isset($message_data[0])) {
            $this->setId($message_data[0]['id']);
            $this->setIsActive($message_data[0]['is_active']);
            $this->setCreatedOn($message_data[0]['created_on']);
            $this->setLastEditedOn($message_data[0]['last_edited_on']);
            $this->setUser($message_data[0]['user_id']);
            $this->setTitle($message_data[0]['title']);
            $this->setText($message_data[0]['text']);
        } else {
            unset($this);
        } 
    }
    
    // store in db function
    function store_in_db() {
        //include connection variable
        global $db;

        $isActive = parent::getIsActive();
        $createdOn = parent::getCreatedOn();
        $lastEditedOn = parent::getLastEditedOn();
        $user = $this::getUser();
        $title = $this::getTitle();
        $text = $this::getText();

        // sql statement
        $sql = "INSERT INTO messages
                (is_active, created_on, last_edited_on, user_id,
                title, text)
                VALUES ('$isActive',
                        '$createdOn',
                        '$lastEditedOn',
                        '$user',
                        '$title',
                        '$text');";
        
        $db->exec($sql);
        $info = "User: '$user' sends message '$title'";
        info($info);
    }
    
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function getText() {
        return $this->text;
    }
}

?>