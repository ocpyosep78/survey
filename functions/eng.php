<?php

/*
 * Copyright 2014 ttt.
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

$gender = 0;
$birthYear = "";

# Информация, проверка и генератор за единни граждански номера (ЕГН)
# Версия 1.50 (30-Sep-2006)
#
# За контакти:
#   E-mail: georgi@unixsol.org
#   WWW   : http://georgi.unixsol.org/
#   Source: http://georgi.unixsol.org/programs/egn.php
#
# Copyright (c) 2006 Georgi Chorbadzhiyski
# All rights reserved.
#
# Redistribution and use of this script, with or without modification, is
# permitted provided that the following conditions are met:
#
# 1. Redistributions of this script must retain the above copyright
#    notice, this list of conditions and the following disclaimer.
#
#  THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED
#  WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
#  MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO
#  EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
#  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
#  PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
#  OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
#  WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
#  OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
#  ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
#
error_reporting(E_ALL);

$EGN_WEIGHTS = array(2, 4, 8, 5, 10, 9, 7, 3, 6);

/* Check if EGN is valid */
/* See: http://www.grao.bg/esgraon.html */
function egn_valid($egn) {
    global $EGN_WEIGHTS;
    if (strlen($egn) != 10)
        return false;
    $year = substr($egn, 0, 2);
    $mon = substr($egn, 2, 2);
    $day = substr($egn, 4, 2);
    if ($mon > 40) {
        if (!checkdate($mon - 40, $day, $year + 2000))
            return false;
    } else
    if ($mon > 20) {
        if (!checkdate($mon - 20, $day, $year + 1800))
            return false;
    } else {
        if (!checkdate($mon, $day, $year + 1900))
            return false;
    }
    $checksum = substr($egn, 9, 1);
    $egnsum = 0;
    for ($i = 0; $i < 9; $i++)
        $egnsum += substr($egn, $i, 1) * $EGN_WEIGHTS[$i];
    $valid_checksum = $egnsum % 11;
    if ($valid_checksum == 10)
        $valid_checksum = 0;
    if ($checksum == $valid_checksum)
        return true;
}

/* Return array with EGN info */

function egn_parse($egn) {
    global $gender;
    global $birthYear;

    global $EGN_REGIONS;
    global $MONTHS_BG;
    if (!egn_valid($egn))
        return false;
    $ret = array();
    $ret["year"] = substr($egn, 0, 2);
    $ret["month"] = substr($egn, 2, 2);
    $ret["day"] = substr($egn, 4, 2);
    if ($ret["month"] > 40) {
        $ret["month"] -= 40;
        $ret["year"] += 2000;
    } else
    if ($ret["month"] > 20) {
        $ret["month"] -= 20;
        $ret["year"] += 1800;
    } else {
        $ret["year"] += 1900;
    }

    $ret["sex"] = substr($egn, 8, 1) % 2;
    $birthYear = $ret["year"];
    $gender = 1;
    if (!$ret["sex"]) {
        $gender = 0;
    }
    return $ret;
}

header("Content-type: text/html; charset=UTF-8");

$egn = preg_replace("/[^0-9]/", "", @$_GET["egn"]);
if (isset($_GET["egn"])) {
    egn_parse($egn);
}
var_dump($gender);
echo '<br>';
var_dump($birthYear);
