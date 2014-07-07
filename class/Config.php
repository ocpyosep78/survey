<?php
// main side configs
class Config implements arrayaccess {

    private static $defaults = array(   "db" => "survey",
                                        "db_host" => "localhost",
                                        "db_username" => "survey",
                                        "db_password" => "survey",
                                        "db_driver" => "mysql");
    
    private $configs = array();

    function __construct() {
        foreach (self::$defaults as $key => $name)
            $this->configs[$key] = $name;
    }

    public function offsetSet($offset, $value) {
        
    }

    public function offsetExists($offset) {
        
    }

    public function offsetUnset($offset) {
        
    }

    public function offsetGet($offset) {
        return isset($this->configs[$offset]) ? $this->configs[$offset] : null;
    }

}
?>
    