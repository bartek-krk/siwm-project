<?php

    require_once('./../assets/config_provider.php');

    class ReqisterUserRequest {
        private $username;
        private $password;
        private $firstName;
        private $lastName;
        private $householdRegisterCode;

        public function __construct($serverPostRequest) {
            $configuration = new ConfigProvider("./../assets/conf.json");    
            $this->username = $serverPostRequest[$configuration->getProperty('form.register.user.fieldname.username', 'username')];
            $this->password = $serverPostRequest[$configuration->getProperty('form.register.user.fieldname.password', 'password')];
            $this->firstName = $serverPostRequest[$configuration->getProperty('form.register.user.fieldname.firstname', 'firstname')];
            $this->lastName = $serverPostRequest[$configuration->getProperty('form.register.user.fieldname.lastname', 'lastname')];
            $this->householdRegisterCode = $serverPostRequest[$configuration->getProperty('form.register.user.fieldname.householdcode', 'householdcode')];
        }

        public function stringify() {
            return sprintf(
                'Username: %s, Password: %s, FirstName: %s, LastName: %s, HHcode: %s',
                $this->username,
                $this->password,
                $this->firstName,
                $this->lastName,
                $this->householdRegisterCode
            );
        }
    }

    class ReqisterHouseholdRequest {
        private $name;

        public function __construct($serverPostRequest) {
            $configuration = new ConfigProvider("./../assets/conf.json");    
            $this->name = $serverPostRequest[$configuration->getProperty('form.register.household.fieldname.name', 'household-name')];
        }

        public function stringify() {
            return sprintf(
                'Name: %s',
                $this->name
            );
        }
    }

?>