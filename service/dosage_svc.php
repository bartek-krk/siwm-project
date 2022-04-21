<?php

    /**
     * This file contains services to handle DB operations for Dosage entities.
     * 
     * @author Bartosz Lukasik
     */

    /**
     * Container for transferring Dosage insert query status.
     */
    class AddDosageResponse {
        private $isSuccess;

        public function __construct($isSuccess) {
            $this->isSuccess = $isSuccess;
        }

        public function isSuccess() {
            return $this->isSuccess;
        }
    }

    /**
     * Service class handling persistance operations of Dosage entities.
     */
    class DosageService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function add($userId, $drugId, $quantity) {
            $template = 'INSERT INTO DOSAGE(user_id, drug_id, quantity, dosage_date) VALUES(%d, %d, %f, NOW())';
            $sql = sprintf($template, $userId, $drugId, $quantity);
            
            try {
                $res = $this->db->executeQuery($sql);
                return new AddDosageResponse($res ? true : false);
            } catch (Exception $e) {
                return new AddDosageResponse(false);
            }
        }
    }
?>