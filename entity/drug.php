<?php
    class Drug {
        public $id;
        public $drugName;
        public $drugPrice;
        public $drugExpiryDt;
        public $drugInitialQuantity;
        public $householdId;

        public function __construct($id, $drugName, $drugPrice, $drugExpiryDt, $drugInitialQuantity, $householdId) {
            $this->id = $id;
            $this->drugName = $drugName;
            $this->drugPrice = $drugPrice;
            $this->drugExpiryDt = $drugExpiryDt;
            $this->drugInitialQuantity = $drugInitialQuantity;
            $this->householdId = $householdId;
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->drugName;
        }

        public function getPrice() {
            return $this->drugPrice;
        }

        public function getExpiryDate() {
            return $this->drugExpiryDt;
        }

        public function getInitialQuantity() {
            return $this->drugInitialQuantity;
        }

        public function getHouseholdId() {
            return $this->householdId;
        }
    }
?>