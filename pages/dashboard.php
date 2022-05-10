<?php
    require_once("./../assets/config_provider.php");
    require_once("./../entity/user.php");
    require_once("./../entity/drug.php");
    require_once("./../entity/history_log.php");
    require_once("./../utils/session_util.php");
    require_once("./../service/drug_svc.php");
    require_once("./../service/history_svc.php");
    require_once("./../service/chart_svc.php");
    require_once("./../utils/db_util.php");    

    session_start();

    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
    $session = new SessionManager($configuration);
    $drugSvc = new DrugService($db);
    $historySvc = new HistoryService($db);
    $drugs = $drugSvc->getByHouseholdId($session->getCurrentUser()->getHouseholdId());
    $historyObjects = $historySvc->getDrugHistory($session->getCurrentUser()->getHouseholdId());
    $fullHistoryObjects = $historySvc->getFullDrugHistory($session->getCurrentUser()->getHouseholdId());
    $chartSvc = new ChartService($fullHistoryObjects,$drugs);
    $dataForChart = $chartSvc->getCurrentDrugsQuantity();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title><?php echo $locale->getProperty("browser.displayName", "Strona"); ?></title>
    <script>
        
window.onload = function () {
var dataForChart1 = <?php echo json_encode($dataForChart); ?>;
var dps = [];    
            for (var key in dataForChart1)
            {
                dps.push({y: dataForChart1[key], label:key});     
            }
    

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "<?php echo $locale->getProperty("dashboard.chart.title", "Current drug quantity in First Aid Kit"); ?>"
	},
	axisY: {
		title: "<?php echo $locale->getProperty("dashboard.chart.yaxis", "Drug quantity"); ?>"
	},
	data: [{        
		type: "column",  
		showInLegend: false, 
		dataPoints: dps      
	}]
});
chart.render();

}
</script>

</head>
<body>
    <?php require_once("./../assets/components/navbar.php") ?>

    <div class="container mb-5">
        <div id="title-holder">
            <h1><?php echo $locale->getProperty("page.title.dashboard", "Dashboard"); ?></h1>
        </div>
        <div class="overflow-auto">
            <h2><?php echo $locale->getProperty('dashboard.drugs', 'Drugs'); ?></h2>
            <a href="./add_drug.php" class="btn btn-success" role="button">
                <?php echo $locale->getProperty("dashboard.drugs.add", "Add new"); ?>
            </a>
            <table class="table table-hover">
                <tr>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.name', 'Name'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.price', 'Price'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.expiry.date', 'Expiry date'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.initial.quantity', 'Initial quantity'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.taking.that', 'I\'m taking that drug'); ?></th>
                </tr>
                <?php foreach($drugs as $d) { ?>
                    <?php if(strtotime($d->getExpiryDate()) > strtotime('7 day')) {
                        echo "<tr>";
                    }else if(strtotime($d->getExpiryDate()) <= strtotime('7 day') && strtotime($d->getExpiryDate()) > strtotime('0 day')){
                        echo '<tr class="table-warning">';
                    }else{
                        echo '<tr class="table-danger">';
                    }
                    ?>
                        <td><?php echo $d->getName(); ?></td>
                        <td><?php echo $d->getPrice() ?></td>
                        <td><?php echo $d->getExpiryDate() ?></td>
                        <td><?php echo $d->getInitialQuantity() ?></td>
                        <td>
                            <form action="./add_dosage.php" method="post">
                                <div class="form-group">
                                    <input 
                                        id="<?php echo printf('id-input-%d', $d->getId()); ?>" 
                                        type="hidden" 
                                        name="<?php echo $configuration->getProperty('dashboard.takingthatdrug.drug.id', 'drug-id'); ?>" 
                                        value="<?php echo $d->getId(); ?>"
                                    >
                                </div>
                                <div class="form-group">
                                    <input 
                                        id="<?php echo printf('quantity-input-%d', $d->getId()); ?>" 
                                        class="form-control"
                                        name="<?php echo $configuration->getProperty('dashboard.takingthatdrug.drug.quantity', 'drug-quantity'); ?>"
                                        type="number" 
                                        step="0.01" 
                                        placeholder=""
                                    >
                                </div>
                                <button 
                                    type="submit" 
                                    class="btn btn-primary"
                                >
                                    <?php echo $locale->getProperty('dashboard.drugs.table.header.taking.that', 'I\'m taking that drug'); ?>
                                </button>
                            </form>
                        </td>
                <?php } ?>
            </table>
            <h2><?php echo $locale->getProperty('dashboard.dosage.history', 'Dosage History'); ?></h2>
            <table class="table table-hover">
                <tr>
                <th><?php echo $locale->getProperty('form.register.user.label.firstname', 'First name'); ?></th>
                    <th><?php echo $locale->getProperty('form.register.user.label.lastname', 'Last name'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.name', 'Name'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.dosage.date', 'Dosage Date'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.dosage.quantity', 'Dosage Quantity'); ?></th>
                </tr>
                <?php foreach($historyObjects as $h) { ?>
                    <tr>
                        <td><?php echo $h->getFirstName(); ?></td>
                        <td><?php echo $h->getLastName(); ?></td>
                        <td><?php echo $h->getDrugName(); ?></td>
                        <td><?php echo $h->getDoseDate(); ?></td>
                        <td><?php echo $h->getDoseQuantity(); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div id="chartContainer" style="height: 450px; width: 100%;"></div>
    </div>

    

    <?php require_once("./../assets/components/footer.php") ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>