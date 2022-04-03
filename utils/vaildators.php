<?php
    function escapeString($s) {
        return $s;
    }

    function isValidPassword($pwd) {
        return preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", $pwd);
    }
?>