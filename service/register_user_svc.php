<?php

    /**
     * This file contains utility classes for handling user register requests and
     * database operations.
     * 
     * @author Bartosz Lukasik
     */

    require_once('./../utils/vaildators.php');

    /**
     * Error codes for registration failures.
     */
    abstract class UserRegistrationErrorCode {
        const SUCCESS = 0;
        const NONEXISTENT_HOUSEHOLD = 1;
        const INVALID_PASSWORD = 2;
        const INTERNAL_SERVER_ERROR = 3;
    }

    /**
     * Utility class for mapping error codes to their respective human-readible error messages.
     */
    class UserRegistrationErrorCodeMessageResolver {
        public static function resolve($locale, $errorCode) {
            switch ($errorCode) {
                case UserRegistrationErrorCode::SUCCESS:
                    return $locale->getProperty(
                        'form.register.user.message.success', 
                        'Account created successfully - navigate to login!'
                    );

                case UserRegistrationErrorCode::NONEXISTENT_HOUSEHOLD:
                    return $locale->getProperty(
                        'form.register.user.message.error.household', 
                        'Account created successfully - household not found!'
                    );

                case UserRegistrationErrorCode::INVALID_PASSWORD:
                    return $locale->getProperty(
                        'form.register.user.message.error.password', 
                        'Account not created - given password does not comply with the guidelines!'
                    );

                case UserRegistrationErrorCode::INTERNAL_SERVER_ERROR:
                    return $locale->getProperty(
                        'form.register.user.message.error.internal', 
                        'Account not created - internal server error!'
                    );

                default:
                    return $locale->getProperty(
                        'form.register.user.message.error.unknown', 
                        'Account created successfully - unknown error!'
                    );
            }
        }
    }

    /**
     * Wrapper class to transfer registration status.
     */
    class RegisterUserResponse {

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
     * A service class responsible for handling DB operations for registering new users.
     */
    class RegisterUserService {

        private $db;

        private $username;
        private $firstName;
        private $lastName;
        private $householdCode;
        private $password;

        public function __construct($db, $username, $firstName, $lastName, $householdCode, $password) {
            $this->db = $db;
            $this->username = escapeString($username);
            $this->firstName = escapeString($firstName);
            $this->lastName = escapeString($lastName);
            $this->householdCode = escapeString($householdCode);
            $this->password = escapeString($password);
        }

        public function execute() {
            $hhIdTemplate = 'SELECT household_id FROM HOUSEHOLD WHERE join_code="%s"';
            $hhIdSql = sprintf($hhIdTemplate, $this->householdCode);
            $hhResponse = $this->db->executeQuery($hhIdSql);

            $hhId = mysqli_num_rows($hhResponse) == 1 ? mysqli_fetch_assoc($hhResponse)['household_id'] : -1;
            
            $householdExists = $hhId != -1;
            if (!$householdExists) {
                return new RegisterUserResponse(false, UserRegistrationErrorCode::NONEXISTENT_HOUSEHOLD);
            }

            $passwordCorrect = isValidPassword($this->password);
            if (!$passwordCorrect) {
                return new RegisterUserResponse(false, UserRegistrationErrorCode::INVALID_PASSWORD);
            }

            $template = 'INSERT INTO D_USER(username, password_hash, first_name, last_name, household_id) VALUES ("%s", "%s", "%s", "%s", %d)';
            $sql = sprintf(
                $template,
                $this->username,
                password_hash($this->password, PASSWORD_DEFAULT),
                $this->firstName,
                $this->lastName,
                $hhId
            );

            $this->db->executeQuery($sql);

            return new RegisterUserResponse(true, UserRegistrationErrorCode::SUCCESS);
        }

    }

?>