<?php
    require_once('./../entity/user.php');

    /**
     * Handles credential checks.
     */
    class AuthenticationService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        /**
         * Returns a {@link User} entity class if user is authenticated,
         * otherwise returns NULL.
         */
        public function isAuthenticated($username, $password) {
            $template = 'SELECT u.user_id, u.username, u.password, u.first_name, u.last_name, u.household_id from D_USER u WHERE u.username="%s"';
            $sql = sprintf($template, $username, $password);
            
            try {
                $res = $this->db->executeQuery($sql);
                if ($res) {
                    $row = mysqli_fetch_assoc($res);
                    if ($row) {
                        $user = new User(
                            $row['user_id'],
                            $row['username'],
                            $row['first_name'],
                            $row['last_name'],
                            $row['household_id']
                        );
                        $db_password_hash = $row['password'];
                        return password_verify($password, $db_password_hash) ? $user : NULL;
                    } else {
                        return NULL;
                    }
                } else {
                    throw new Exception('SQL error or requested entity not found');
                }
            } catch (Exception $e) {
                return NULL;
            }
        }
    }

?>