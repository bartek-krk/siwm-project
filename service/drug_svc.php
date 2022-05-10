<?php

    class AddDrugResponse {
        private $isSuccess;

        public function __construct($isSuccess) {
            $this->isSuccess = $isSuccess;
        }

        public function isSuccess() {
            return $this->isSuccess;
        }
    }

    class DiscardDrugResponse {
        private $isSuccess;

        public function __construct($isSuccess) {
            $this->isSuccess = $isSuccess;
        }

        public function isSuccess() {
            return $this->isSuccess;
        }
    }

    class DrugService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function add($drugTemplateId, $drugPrice, $drugExpiryDt, $drugInitialQuantity, $householdId) {
            $template = 'INSERT INTO DRUG(drug_template_id, price, expiry_dt, initial_quantity, household_id) VALUES (%d, %f, "%s", %f, %d)';
            $sql = sprintf($template, $drugTemplateId, $drugPrice, $drugExpiryDt, $drugInitialQuantity, $householdId);
            
            try {
                $res = $this->db->executeQuery($sql);
                return new AddDrugResponse($res ? true : false);
            } catch (Exception $e) {
                return new AddDrugResponse(false);
            }
        }

        public function getByHouseholdId($householdId) {
            $template = 'SELECT * FROM DRUG d JOIN DRUG_TEMPLATE dt ON d.drug_template_id=dt.drug_template_id WHERE d.household_id=%d AND d.is_discarded=FALSE';
            $sql = sprintf($template, $householdId);
            
            $res = $this->db->executeQuery($sql);
            
            $drugs = [];
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $d = new Drug(
                        $row['drug_id'],
                        $row['name'],
                        $row['price'],
                        $row['expiry_dt'],
                        $row['initial_quantity'],
                        $row['household_id']
                    );
                    array_push($drugs, $d);
                }
            }

            return $drugs;
        }

        public function getById($id) {
            $template = 'SELECT * FROM DRUG d JOIN DRUG_TEMPLATE dt ON d.drug_template_id=dt.drug_template_id WHERE d.drug_id=%d';
            $sql = sprintf($template, $id);
            
            $res = $this->db->executeQuery($sql);
            
            if ($res) {
                $row = mysqli_fetch_assoc($res);
                if ($row) {
                    return new Drug(
                        $row['drug_id'],
                        $row['name'],
                        $row['price'],
                        $row['expiry_dt'],
                        $row['initial_quantity'],
                        $row['household_id']
                    );
                }
            }

            return NULL;
        }

        public function discard($id) {
            $template = 'UPDATE DRUG d SET d.is_discarded=TRUE WHERE d.drug_id=%d';
            $sql = sprintf($template, $id);
            $res = $this->db->executeQuery($sql);
            return new DiscardDrugResponse($res ? true : false);
        }
    }

?>