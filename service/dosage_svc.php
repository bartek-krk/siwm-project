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
        private $drugSvc;

        public function __construct($db, $drugSvc) {
            $this->db = $db;
            $this->drugSvc = $drugSvc;
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

        public function getRemainingQuantity($id) {
            $sumDosagesTemplate = 'SELECT SUM(d.quantity) AS DOSAGE_SUM FROM DOSAGE d WHERE d.drug_id=%d';
            $sumDosagesSql = sprintf($sumDosagesTemplate, $id);

            $initialQuantity = $this->drugSvc->getById($id)->getInitialQuantity();

            $res = $this->db->executeQuery($sumDosagesSql);

            $dosageSum = $res ? mysqli_fetch_assoc($res)['DOSAGE_SUM'] : 0;

            return $initialQuantity - $dosageSum;
        }
    }
?>