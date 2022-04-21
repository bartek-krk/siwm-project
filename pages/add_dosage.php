<?php
    require_once("./../assets/config_provider.php");
    require_once("./../entity/user.php");
    require_once("./../entity/drug.php");
    require_once("./../utils/session_util.php");
    require_once("./../utils/server_util.php");
    require_once("./../utils/db_util.php");
    require_once("./../service/drug_svc.php");
    require_once("./../service/dosage_svc.php");

    session_start();

    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
    $session = new SessionManager($configuration);
    $drug_svc = new DrugService($db);
    $dosage_svc = new DosageService($db);

    if (!$session->isUserLoggedIn()) {
        header('Location: ./../index.php');
    }

    if (isPost()) {
        $drugId = $_POST[$configuration->getProperty('dashboard.takingthatdrug.drug.id', 'drug-id')];
        $drugQuantity = $_POST[$configuration->getProperty('dashboard.takingthatdrug.drug.quantity', 'drug-quantity')];

        $drug = $drug_svc->getById($drugId);

        if ($drug->getHouseholdId() == $session->getCurrentUser()->getHouseholdId()) {
            $isAddSuccess = $dosage_svc->add($session->getCurrentUser()->getId(), $drugId, $drugQuantity)->isSuccess();
            if ($isAddSuccess) {
                header('Location: ./dashboard.php');
            } else {
                echo 'Adding dosage failed';
            }
        } else {
            echo('403 FORBIDDEN - drug does not belong to the user');
            http_response_code(403);
            exit;
        }
        
    } else {
        header('Location: ./dashboard.php');
    }

?>