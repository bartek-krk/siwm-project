<?php
class ChartService{
    private $fullHistoryObjects;
    private $drugs;

    public function __construct($fullHistoryObjects, $drugs){
        $this->fullHistoryObjects = $fullHistoryObjects;
        $this->drugs = $drugs;
    }

    public function getCurrentDrugsQuantity(){

        $arrayIdAndQuantity = [];
        foreach($this->drugs as $d){
            $arrayIdAndQuantity[$d->getId()] = (float) $d->getInitialQuantity();
        }

        $arrayIdAndName = [];
        foreach($this->drugs as $d){
            $arrayIdAndName[$d->getId()] = $d->getName();
        }

        foreach($this->fullHistoryObjects as $fh){
            $arrayIdAndQuantity[$fh->getDrugId()] = $arrayIdAndQuantity[$fh->getDrugId()] - $fh->getDoseQuantity();
        }

        $arrayNameAndInitialQuantity = [];
        foreach($arrayIdAndQuantity as $id => $q){
            $arrayNameAndInitialQuantity[$arrayIdAndName[$id]] = $q;
        }

        return $arrayNameAndInitialQuantity;
    }
}
?>