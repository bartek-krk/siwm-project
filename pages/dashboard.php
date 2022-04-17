<?php
    require_once("./../assets/config_provider.php");
    require_once("./../entity/user.php");
    require_once("./../entity/drug.php");
    require_once("./../utils/session_util.php");
    require_once("./../service/drug_svc.php");
    require_once("./../utils/db_util.php");

    session_start();

    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
    $session = new SessionManager($configuration);
    $drugSvc = new DrugService($db);
    $drugs = $drugSvc->getByHouseholdId($session->getCurrentUser()->getHouseholdId());
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title><?php echo $locale->getProperty("browser.displayName", "Strona"); ?></title>
</head>
<body>
    <?php require_once("./../assets/components/navbar.php") ?>

    <div class="container">
        <div id="title-holder">
            <h1><?php echo $locale->getProperty("page.title.dashboard", "Dashboard"); ?></h1>
        </div>
        
        <div class="overflow-auto">
            <h2>Drugs</h2>
            <a href="./add_drug.php" class="btn btn-success" role="button">
                <?php echo 'Add new'; ?>
            </a>
            <table class="table table-hover">
                <tr>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.name', 'Name'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.price', 'Price'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.expiry.date', 'Expiry date'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.measurement.unit', 'Measurement unit'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.initial.quantity', 'Initial quantity'); ?></th>
                    <th><?php echo $locale->getProperty('dashboard.drugs.table.header.taking.that', 'I\'m taking that drug'); ?></th>
                </tr>
                <?php foreach($drugs as $d) { ?>
                    <tr>
                        <td><?php echo $d->getName(); ?></td>
                        <td><?php echo $d->getPrice() ?></td>
                        <td><?php echo $d->getExpiryDate() ?></td>
                        <td><?php echo $d->getQuantityType() ?></td>
                        <td><?php echo $d->getInitialQuantity() ?></td>
                        <td>
                            <form action="" method="get">
                                <input type="hidden" name="id" value="<?php echo $d->getId(); ?>">
                                <button type="submit" class="btn btn-primary"><?php echo $locale->getProperty('dashboard.drugs.table.header.taking.that', 'I\'m taking that drug'); ?></button>
                            </form>
                        </td>
                <?php } ?>
            </table>
        </div>
    </div>

    <?php require_once("./../assets/components/footer.php") ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    
</body>
</html>