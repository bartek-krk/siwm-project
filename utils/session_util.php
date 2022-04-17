<?php

    class SessionManager {

        private $config;
        
        public function __construct($config) {
            $this->config = $config;
        }

        public function isUserLoggedIn() {
            if ($_SESSION != NULL) {
                $_boolean = $_SESSION[$this->config->getProperty('session.assoc.loggedin', 'is_logged_in')];
                return $_boolean != NULL ? $_boolean : false;
            } else {
                return false;
            }
        }

        public function getCurrentUser() {
            if ($_SESSION != NULL) {
                $_boolean = $_SESSION[$this->config->getProperty('session.assoc.user', 'user_details')];
                return $_boolean != NULL ? $_boolean : NULL;
            } else {
                return NULL;
            }
        }
    }

?>