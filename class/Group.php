<?php

// main survey class
class Group extends BaseObject {

    private $name;
    private $abbreviation;
    private $local;
    private $staff;
    private $student;
    private $created_by;
    private $parent;
    private $susiId;
    private $description;
    private $members;

    // object constructor
    function get_from_db($id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT *
                FROM groups
                WHERE is_active='1' AND id='$id'";

        $group_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $group_data[$key] = $value;
        }

        if(isset($group_data[0])) {
            $this->setId($group_data[0]['id']);
            $this->setIsActive($group_data[0]['is_active']);
            $this->setCreatedOn($group_data[0]['created_on']);
            $this->setLastEditedOn($group_data[0]['last_edited_on']);
            $this->setCreatedBy($group_data[0]['created_by']);
            $this->setName($group_data[0]['name']);
            $this->setAbbreviation($group_data[0]['abbreviation']);
            $this->setLocal($group_data[0]['local']);
            $this->setStaff($group_data[0]['staff']);
            $this->setStudent($group_data[0]['student']);
            $this->setParent($group_data[0]['parent_id']);
            $this->setSusiId($group_data[0]['susi_id']);
            $this->setDescription($group_data[0]['description']);
            $this->setMembers($group_data[0]['members']);
        } else {
            unset($this);
        }
    }

    // store in db function
    function store_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "INSERT INTO groups
                (is_active, created_on, last_edited_on, name,
                abbreviation, local, staff, student, created_by,
                parent_id, susi_id, description, members)
                VALUES ('".$this->getIsActive()."',
                        '".$this->getCreatedOn()."',
                        '".$this->getLastEditedOn()."',
                        '".$this->getName()."',
                        '".$this->getAbbreviation()."',
                        '".$this->getLocal()."',
                        '".$this->getStaff()."',
                        '".$this->getStudent()."',
                        '".$this->getCreatedBy()."',
                        '".$this->getParent()."',
                        '".$this->getSusiId()."',
                        '".$this->getDescription()."',
                        '".$this->getMembers()."');";

        $group_id = NULL;
        try { 
            $db->exec($sql);
            $group_id = $db->lastInsertId();
            $info = "Group: " . $group_id . " created";
            info($info);
        } catch(PDOExecption $e) {
            $error = "Fail store survey group in db: $e";
            error($error);
        }
        
        return  $group_id;
    }

    // update in db function
    function update_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "UPDATE groups
                SET is_active           = '".$this->getIsActive()."',
                    last_edited_on      = '".$this->getLastEditedOn()."',
                    name                = '".$this->getName()."',
                    abbreviation        = '".$this->getAbbreviation()."',
                    local               = '".$this->getLocal()."',
                    staff               = '".$this->getStaff()."',
                    student             = '".$this->getStudent()."',
                    created_by          = '".$this->getCreatedBy()."',
                    parent_id           = '".$this->getParent()."',
                    susi_id             = '".$this->getSusiId()."',
                    description         = '".$this->getDescription()."',
                    members             = '".$this->getMembers()."'
                WHERE id = '".$this->getId()."';";

        $db->exec($sql);
    }

    function getMembersArray() {
        $members_array = array();
        if(is_array(unserialize($this->getMembers()))) {
            $members_array = unserialize($this->getMembers());
        }
        return $members_array;
    }

    public function setCreatedBy($created_by) {
        $this->created_by = $created_by;
        return $this;
    }

    public function getCreatedBy() {
        return $this->created_by;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setLocal($local) {
        $this->local = $local;
        return $this;
    }

    public function getLocal() {
        return $this->local;
    }

    public function setStaff($staff) {
        $this->staff = $staff;
        return $this;
    }

    public function getStaff() {
        return $this->staff;
    }

    public function setStudent($student) {
        $this->student = $student;
        return $this;
    }

    public function getStudent() {
        return $this->student;
    }

    public function setAbbreviation($abbreviation) {
        $this->abbreviation = $abbreviation;
        return $this;
    }

    public function getAbbreviation() {
        return $this->abbreviation;
    }

    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setSusiId($susiId) {
        $this->susiId = $susiId;
        return $this;
    }

    public function getSusiId() {
        return $this->susiId;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setMembers($members) {
        $this->members = $members;
        return $this;
    }

    public function getMembers() {
        return $this->members;
    }
}

?>