<?php
class ChartService{
    private $fullHistoryObjects;
    private $drugs;

    public function __construct($fullHistoryObjects, $drugs){
        $this->fullHistoryObjects = $fullHistoryObjects;
        $this->drugs = $drugs;
    }

    public function getCurrentDrugsQuantity(){

        $arrayIdAndInitialQuantity = [];

        foreach($this->drugs as $d){
            $arrayIdAndInitialQuantity[$d->getId()] = $d->getInitialQuantity();
        }

        $arrayIdAndName = [];
        foreach($this->drugs as $d){
            $arrayIdAndName[$d->getId()] = $d->getName();
        }

        $arrayNameAndInitialQuantity = [];
        foreach($this->fullHistoryObjects as $fh){
            foreach($arrayIdAndInitialQuantity as $drugId => $initialQuantity){
                if($fh->getDrugId() == $drugId){
                    $arrayNameAndInitialQuantity[$arrayIdAndName[$drugId]] -= $fh->getDoseQuantity();
                }
            }
        }
        return $arrayNameAndInitialQuantity;
    }
}
?>