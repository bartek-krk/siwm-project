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
      * Resolves join codes to proper format and locales.
      */
     class JoinCodeResolver {
         public static function resolve($locale, $joinCode) {
             $pattern = $locale->getProperty(
                 'form.register.household.joincode.message.pattern', 
                 'Your join code is: <b>%s</b>. IMPORTANT: Please, retain it for further registrations!'
                );
             return sprintf($pattern, $joinCode);
         }
     }

     /**
      * Wrapper class to transfer registration status and associated data.
      */
     class RegisterHouseholdResponse {
        private $isSuccess;
        private $joinCode;
        private $errorCode;

        public function __construct($isSuccess, $joinCode, $errorCode) {
            $this->isSuccess = $isSuccess;
            $this->joinCode = $joinCode;
            $this->errorCode = $errorCode;
        }

        public function isSuccess() {
            return $this->isSuccess;
        }

        public function getJoinCode() {
            return $this->joinCode;
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
            $joinCode = RegisterHouseholdService::generateJoinCode();

            $template = 'INSERT INTO HOUSEHOLD(name, join_code) VALUES ("%s", "%s")';
            $sql = sprintf($template, $this->name, $joinCode);

            try {
                $res = $this->db->executeQuery($sql);
                if (!$res) {
                    throw new Exception('SQL error');
                }
            } catch (Exception $e) {
                return new RegisterHouseholdResponse(false, NULL, HouseholdRegistrationErrorCode::INTERNAL_SERVER_ERROR);
            }

            return new RegisterHouseholdResponse(true, $joinCode, HouseholdRegistrationErrorCode::SUCCESS);
        }

        public static function generateJoinCode() {
            $length = 10;
            $allowed_chars = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890';
            $token = '';
    
            for ($i=0; $i < $length; $i++) { 
                $char = substr($allowed_chars, rand(0, strlen($allowed_chars)-1), 1);
                $token = $token.$char;
            }
    
            return $token;
    
        }

     }

?>