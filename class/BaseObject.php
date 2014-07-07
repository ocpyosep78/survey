<?php

// main user class
class BaseObject {

    private $id;
    private $is_active;
    private $created_on;
    private $last_edited_on;

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setIsActive($is_active) {
        if (!isset($is_active)) {
            $is_active = 1;
        }
        $this->is_active = $is_active;        
        return $this;
    }

    public function getIsActive() {
        return $this->is_active;
    }

    public function setCreatedOn($created_on) {
        if (!isset($created_on)) {
            // get current time
            $time_now = date("Y-m-d H:i:s");
            $created_on = $time_now;
        }
        $this->created_on = $created_on;
        return $this;
    }

    public function getCreatedOn() {
        return $this->created_on;
    }

    public function setLastEditedOn($last_edited_on) {
        if (!isset($last_edited_on)) {
            // get current time
            $time_now = date("Y-m-d H:i:s");
            $last_edited_on = $time_now;
        }
        $this->last_edited_on = $last_edited_on;
        return $this;
    }

    public function getLastEditedOn() {
        return $this->last_edited_on;
    }

}

?>