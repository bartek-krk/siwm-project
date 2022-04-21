<?php

    /**
     * Entity class representing a service user.
     */
    class User {
        private $id;
        private $username;
        private $firstName;
        private $lastName;
        private $householdId;

        public function __construct($id, $username, $firstName, $lastName, $householdId) {
            $this->id = $id;
            $this->username = $username;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->householdId = $householdId;
        }

        public function getId() {
            return $this->id;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getFormattedName() {
            return sprintf('%s %s', $this->firstName, $this->lastName);
        }

        public function getHouseholdId() {
            return $this->householdId;
        }
    }

?>