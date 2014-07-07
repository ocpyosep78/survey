<?php

// main user class
class User extends BaseObject {

    private $username;
    private $password;
    private $email;
    private $givenname;
    private $title;
    private $local;
    private $can_vote;
    private $can_ask;
    private $admin;
    private $staffGroups;
    private $studentGroups;
    private $localGroups;
    
    // get from db function
    function get_from_db($id) {
        //include connection variable
        global $db;

        // sql statement
        $sql = "SELECT *
                FROM users
                WHERE is_active='1' AND id='$id'";
        
        $user_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $user_data[$key] = $value;
        }
        
        if(isset($user_data[0])) {
            $this->setId($user_data[0]['id']);
            $this->setIsActive($user_data[0]['is_active']);
            $this->setCreatedOn($user_data[0]['created_on']);
            $this->setLastEditedOn($user_data[0]['last_edited_on']);
            $this->setUsername($user_data[0]['username']);
            $this->setPassword($user_data[0]['password']);
            $this->setLocal($user_data[0]['local']);
            $this->setEmail($user_data[0]['email']);
            $this->setGivenname($user_data[0]['givenname']);
            $this->setTitle($user_data[0]['title']);
            $this->setCanVote($user_data[0]['can_vote']);
            $this->setCanAsk($user_data[0]['can_ask']);
            $this->setAdmin($user_data[0]['admin']);
            $this->setStaffGroups($user_data[0]['staff_groups']);
            $this->setStudentGroups($user_data[0]['student_groups']);
            $this->setLocalGroups($user_data[0]['local_groups']);
        } else {
            unset($this);
        } 
    }
    
    // check if ail is taken
    function is_email_taken($email) {
        //include connection variable
        global $db;

        $sql = "SELECT id
                FROM users
                WHERE is_active='1' AND email='$email'";
        
        $user_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $user_data[$key] = $value;
        }
        
        if(isset($user_data[0])) {
            return true;
        } else {
            return false;
        }
    }
    
    // check if ail is taken
    function is_username_taken($username) {
        //include connection variable
        global $db;

        $sql = "SELECT id
                FROM users
                WHERE is_active='1' AND username='$username'";
        
        $user_data = array();
        foreach ($db->query($sql) as $key => $value) {
            $user_data[$key] = $value;
        }
        
        if(isset($user_data[0])) {
            return true;
        } else {
            return false;
        }
    }
    
    // store in db function
    function store_in_db() {
        //include connection variable
        global $db;
        
        // sql statement
        $sql = "INSERT INTO users
                    (is_active,
                    created_on,
                    last_edited_on,
                    username,
                    password,
                    local,
                    email,
                    givenname,
                    title,
                    can_vote,
                    can_ask,
                    admin,
                    staff_groups,
                    student_groups,
                    local_groups)
                VALUES ('".$this->getIsActive()         ."',
                        '".$this->getCreatedOn()        ."',
                        '".$this->getLastEditedOn()     ."',
                        '".$this->getUsername()         ."',
                        '".$this->getPassword()         ."',
                        '".$this->getLocal()            ."',
                        '".$this->getEmail()            ."',
                        '".$this->getGivenname()        ."',
                        '".$this->getTitle()            ."',
                        '".$this->getCanVote()          ."',
                        '".$this->getCanAsk()           ."',
                        '".$this->getAdmin()            ."',
                        '".$this->getStaffGroups()      ."',
                        '".$this->getStudentGroups()    ."',
                        '".$this->getLocalGroups()      ."');";
        
        try {
            $db->exec($sql);
            $info = "Store in db user:" . $this->getUsername();
            info($info);
        } catch (PDOException $e) {
            $error = "Store in db error:" . $e->getTraceAsString();
            error($error);
        }
    }
    
    // update in db function
    function update_in_db() {
        //include connection variable
        global $db;

        // sql statement
        $sql = "UPDATE users
                SET is_active           = '".$this->getIsActive()."',
                    last_edited_on      = '".$this->getLastEditedOn()."',
                    username            = '".$this->getUsername()."',
                    password            = '".$this->getPassword()."',
                    local               = '".$this->getLocal()."',
                    email               = '".$this->getEmail()."',
                    givenname           = '".$this->getGivenname()."',
                    title               = '".$this->getTitle()."',
                    can_vote            = '".$this->getCanVote()."',
                    can_ask             = '".$this->getCanAsk()."',
                    admin               = '".$this->getAdmin()."',
                    staff_groups        = '".$this->getStaffGroups()."',
                    student_groups      = '".$this->getStudentGroups()."',
                    local_groups        = '".$this->getLocalGroups()."'
                WHERE id = '".$this->getId()."';";
        
        $db->exec($sql);
    }
    
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setLocal($local) {
        $this->local = $local;
        return $this;
    }

    public function getLocal() {
        return $this->local;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setGivenname($givenname) {
        $this->givenname = $givenname;
        return $this;
    }

    public function getGivenname() {
        return $this->givenname;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setCanVote($can_vote) {
        $this->can_vote = $can_vote;
        return $this;
    }

    public function getCanVote() {
        return $this->can_vote;
    }

    public function setCanAsk($can_ask) {
        $this->can_ask = $can_ask;
        return $this;
    }

    public function getCanAsk() {
        return $this->can_ask;
    }

    public function setAdmin($admin) {
        $this->admin = $admin;
        return $this;
    }

    public function getAdmin() {
        return $this->admin;
    }

    public function setStaffGroups($staffGroups) {
        $this->staffGroups = $staffGroups;
        return $this;
    }

    public function getStaffGroups() {
        return $this->staffGroups;
    }

    public function setStudentGroups($studentGroups) {
        $this->studentGroups = $studentGroups;
        return $this;
    }

    public function getStudentGroups() {
        return $this->studentGroups;
    }
    
    public function setLocalGroups($localGroups) {
        $this->localGroups = $localGroups;
        return $this;
    }

    public function getLocalGroups() {
        return $this->localGroups;
    }
}

?>