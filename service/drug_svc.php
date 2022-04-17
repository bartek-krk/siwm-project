<?php

    class DrugService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function add($drugName, $drugPrice, $drugExpiryDt, $drugQuantityType, $drugInitialQuantity, $householdId) {
            $template = 'INSERT INTO DRUG(name, price, expiry_dt, quantity_type, initial_quantity, household_id) VALUES ("%s", %f, "%s", "%s", %f, %d)';
            $sql = sprintf($template, $drugName, $drugPrice, $drugExpiryDt, $drugQuantityType, $drugInitialQuantity, $householdId);
            return $sql;
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