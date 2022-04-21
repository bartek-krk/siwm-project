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

    class DrugService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function add($drugName, $drugPrice, $drugExpiryDt, $drugQuantityType, $drugInitialQuantity, $householdId) {
            $template = 'INSERT INTO DRUG(name, price, expiry_dt, quantity_type, initial_quantity, household_id) VALUES ("%s", %f, "%s", "%s", %f, %d)';
            $sql = sprintf($template, $drugName, $drugPrice, $drugExpiryDt, $drugQuantityType, $drugInitialQuantity, $householdId);
            
            try {
                $res = $this->db->executeQuery($sql);
                return new AddDrugResponse($res ? true : false);
            } catch (Exception $e) {
                return new AddDrugResponse(false);
            }
        }

        public function getByHouseholdId($householdId) {
            $template = 'SELECT * FROM DRUG d WHERE d.household_id=%d';
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
                        $row['quantity_type'],
                        $row['initial_quantity'],
                        $row['household_id']
                    );
                    array_push($drugs, $d);
                }
            }

            return $drugs;
        }
    }

?>