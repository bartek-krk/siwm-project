<?php

    class DrugTemplate {
        private $id;
        private $name;
        private $manufacturer;
        private $active_ingredient;
        private $package;
        private $leaflet;

        public function __construct($id, $name, $manufacturer, $active_ingredient, $package, $leaflet) {
            $this->id = $id;
            $this->name = $name;
            $this->manufacturer = $manufacturer;
            $this->active_ingredient = $active_ingredient;
            $this->package = $package;
            $this->leaflet = $leaflet;
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getManufacturer() {
            return $this->manufacturer;
        }
        
        public function getActiveIngredient() {
            return $this->active_ingredient;
        }

        public function getPackage() {
            return $this->package;
        }

        public function getLeaflet() {
            return $this->leaflet;
        }

        public function getAsJson() {
            return sprintf(
                '{"drug_template_id": %d, "name": "%s", "manufacturer": "%s", "active_ingredient": "%s", "package": "%s", "leaflet": "%s"}',
                $this->id,
                $this->name,
                $this->manufacturer,
                $this->active_ingredient,
                $this->package,
                $this->leaflet
            );
        }
    }

?>