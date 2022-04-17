<?php
    class Drug {
        public $id;
        public $drugName;
        public $drugPrice;
        public $drugExpiryDt;
        public $drugQuantityType;
        public $drugInitialQuantity;
        public $householdId;

        public function __construct($id, $drugName, $drugPrice, $drugExpiryDt, $drugQuantityType, $drugInitialQuantity, $householdId) {
            $this->id = $id;
            $this->drugName = $drugName;
            $this->drugPrice = $drugPrice;
            $this->drugExpiryDt = $drugExpiryDt;
            $this->drugQuantityType = $drugQuantityType;
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

        public function getQuantityType() {
            return $this->drugQuantityType;
        }

        public function getInitialQuantity() {
            return $this->drugInitialQuantity;
        }
    }
?>