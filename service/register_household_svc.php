<?php
    /**
     * This file contains utility classes and persistance layer services
     * for registrating new households.
     * 
     * @author Bartosz Lukasik
     */


     /**
      * Error codes for registration failures.
      */
     class HouseholdRegistrationErrorCode {
        const SUCCESS = 0;
        const INTERNAL_SERVER_ERROR = 1;
     }

     /**
      * Utility class for mapping error codes to their respective human-readible error messages.
      */
     class HouseholdRegistrationErrorCodeMessageResolver {
         public static function resolve($locale, $errorCode) {
             switch ($errorCode) {
                case HouseholdRegistrationErrorCode::SUCCESS:
                    return $locale->getProperty(
                        'form.register.household.message.success', 
                        'Household created successfully!'
                    );
                case HouseholdRegistrationErrorCode::INTERNAL_SERVER_ERROR:
                    return $locale->getProperty(
                        'form.register.household.message.error.internal', 
                        'Household not created - internal server error!'
                    );
                default:
                    return $locale->getProperty(
                        'form.register.household.message.error.unknown', 
                        'Household not created - unknown error!'
                    );
             }
         }
     }

     /**
      * Wrapper class to transfer registration status.
      */
     class RegisterHouseholdResponse {
        private $isSuccess;
        private $errorCode;

        public function __construct($isSuccess, $errorCode) {
            $this->isSuccess = $isSuccess;
            $this->errorCode = $errorCode;
        }

        public function isSuccess() {
            return $this->isSuccess;
        }

        public function getErrorCode() {
            return $this->errorCode;
        }
     }


     /**
      * A service class responsible for handling DB operations for registering new households.
      */
     class RegisterHouseholdService {

        private $db;

        private $name;

        public function __construct($db, $name) {
            $this->db = $db;
            $this->name = $name;
        }

        public function execute() {
            return new RegisterHouseholdResponse(true, HouseholdRegistrationErrorCode::SUCCESS);
        }

     }

?>