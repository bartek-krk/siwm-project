<?php

        class HistoryService{
            private $db;

            public function __construct($db) {
                $this->db = $db;
            }

            public function getDrugHistory($household_id){
                
                $template = 'SELECT * FROM DOSAGE d JOIN D_USER u ON u.user_id = d.user_id JOIN DRUG drug ON drug.drug_id = d.drug_id JOIN DRUG_TEMPLATE dt ON dt.drug_template_id=drug.drug_template_id WHERE d.dosage_date > DATE_SUB(NOW(), INTERVAL 48 HOUR) AND u.household_id ="%s" ORDER BY d.dosage_date DESC';
                $sql = sprintf($template,$household_id);

                    $res = $this->db->executeQuery($sql);
                    $historyObjectsArray = [];
                    if ($res){
                        while($row = mysqli_fetch_assoc($res)){
                            $history_log = new HistoryLog(
                                $row['first_name'],
                                $row['last_name'],
                                $row['name'],
                                $row['dosage_date']
                            );
                            array_push($historyObjectsArray, $history_log);
                        }
                    }
                return $historyObjectsArray;
            }
        }

?>