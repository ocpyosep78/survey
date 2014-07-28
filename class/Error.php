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
 * Description of Error
 *
 * @author rintintin
 */
class Error {

    private $info;
    private $ip;

    function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
    }

    function __construct1($info) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $this->setInfo($info);
        $this->setIp($ip);
    }

    function __construct2($info, $ip) {
        $this->setInfo($info);
        $this->setIp($ip);
    }

    // store in db function
    function writeLog() {
        $date = get_current_date();

        $filename = ROOT_DIR . 'log/log_error' . $date;

        if (!file_exists($filename)) {
            $fp = fopen($filename, "wa");
            fclose($fp);
        }

        $data = get_current_time() . " " .
                $this->getIp() . " " .
                $this->getInfo() . PHP_EOL;

        try {
            file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);
        } catch (Exception $ex) {
            print_r("<pre>");
            print_r($ex->getMessage());
            print_r($ex->getTrace());
            print_r("</pre>");
            die();
        }
    }

    public function setInfo($info) {
        $this->info = $info;
        return $this;
    }

    public function getInfo() {
        return $this->info;
    }

    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    public function getIp() {
        return $this->ip;
    }

}
