<?php

    class DrugTemplateService {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function getAll() {
            $sql = 'SELECT * FROM DRUG_TEMPLATE';

            $res = $this->db->executeQuery($sql);

            $drug_templates = [];
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $d = new DrugTemplate(
                        $row['drug_template_id'],
                        $row['name'],
                        $row['manufacturer'],
                        $row['active_ingredient'],
                        $row['package'],
                        $row['leaflet'],
                    );
                    array_push($drug_templates, $d);
                }
            }

            return $drug_templates;
        }

        public function getAllAsJson() {
            $drug_templates = $this->getAll();
            $drug_template_jsons = [];
            foreach ($drug_templates as $t) {
                array_push($drug_template_jsons, $t->getAsJson());
            }
            return sprintf('[%s]', implode(',', $drug_template_jsons));
        }
    }

?>