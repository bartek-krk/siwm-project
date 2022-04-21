<?php

    class HistoryLog {
        private $firstName;
        private $lastName;
        private $drugName;
        private $dosageDate;

        public function __construct($firstName, $lastName, $drugName, $dosageDate){
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->drugName = $drugName;
            $this->dosageDate = $dosageDate;
        }

        public function getFirstName(){
            return $this->firstName;
        }
        public function getLastName(){
            return $this->lastName;
        }
        public function getDrugName(){
            return $this->drugName;
        }
        public function getDoseDate(){
            return $this->doseDate;
        }
    }

?>