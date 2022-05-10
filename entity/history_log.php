<?php

    class HistoryLog {
        private $firstName;
        private $lastName;
        private $drugName;
        private $dosageDate;
        private $dosageQuantity;
        private $drugId;

        public function __construct($drugId, $firstName, $lastName, $drugName, $dosageDate, $dosageQuantity){
            $this->drugId = $drugId;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->drugName = $drugName;
            $this->dosageDate = $dosageDate;
            $this->dosageQuantity = $dosageQuantity;
        }

        public function getDrugId(){
            return $this->drugId;
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
            return $this->dosageDate;
        }
        public function getDoseQuantity(){
            return $this->dosageQuantity;
        }
    }

?>