<?php
    require_once("./../assets/config_provider.php");
    require_once("./../entity/user.php");
    require_once("./../utils/session_util.php");
    require_once("./../utils/server_util.php");
    require_once("./../service/drug_svc.php");
    require_once("./../utils/db_util.php");

    session_start();

    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
    $session = new SessionManager($configuration);

    if (!$session->isUserLoggedIn()) {
        header('Location: ./../index.php');
    }
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
            <h1><?php echo $locale->getProperty("page.title.add.drug", "Add Drug"); ?></h1>
        </div>

        <?php
            if(isPost()) {
                $drugName = $_POST[$configuration->getProperty('form.register.drug.fieldname.name', 'drug-name')];
                $drugPrice = $_POST[$configuration->getProperty('form.register.drug.fieldname.price', 'drug-price')];
                $drugExpiryDt = $_POST[$configuration->getProperty('form.register.drug.fieldname.expiry.date', 'drug-expiry-date')];
                $drugQuantityType = $_POST[$configuration->getProperty('form.register.drug.fieldname.quantity.type', 'drug-quantity-type')];
                $drugInitialQuantity = $_POST[$configuration->getProperty('form.register.drug.fieldname.quantity.initial', 'drug-initial-quantity')];
                $householdId = $session->getCurrentUser()->getHouseholdId();

                $drugSvc = new DrugService($db);

                echo $drugSvc->add($drugName, $drugPrice, $drugExpiryDt, $drugQuantityType, $drugInitialQuantity, $householdId);
            }
        ?>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
            <div class="form-group">
              <label for="drug-name-input"><?php echo $locale->getProperty('form.register.drug.label.name', 'Drug\'s name'); ?></label>
              <input 
                type="text" 
                class="form-control" 
                id="drug-name-input" 
                name="<?php echo $configuration->getProperty('form.register.drug.fieldname.name', 'drug-name'); ?>" 
                placeholder="<?php echo $locale->getProperty('form.register.drug.placeholder.name', 'Enter drug\'s name...'); ?>"
              >
              <small 
                id="name-help" 
                class="form-text text-muted">
                <?php echo $locale->getProperty('form.register.drug.hint.name', 'Drug\'s name contains its characteristic features.'); ?>
              </small>
            </div>
            <div class="form-group">
              <label for="drug-price-input"><?php echo $locale->getProperty('form.register.drug.label.price', 'Drug\'s price'); ?></label>
              <input 
                type="number" 
                step="0.01" 
                class="form-control" 
                id="drug-price-input" 
                name="<?php echo $configuration->getProperty('form.register.drug.fieldname.price', 'drug-price'); ?>" 
                placeholder="<?php echo $locale->getProperty('form.register.drug.placeholder.price', 'Enter drug\'s price...'); ?>"
              >
            </div>

            <div class="form-group">
              <label for="drug-expiry-date-input"><?php echo $locale->getProperty('form.register.drug.label.expiry.date', 'Drug\'s expiry date'); ?></label>
              <input 
                type="date" 
                class="form-control" 
                id="drug-expiry-date-input" 
                name="<?php echo $configuration->getProperty('form.register.drug.fieldname.expiry.date', 'drug-expiry-date'); ?>" 
              >
            </div>

            <div class="form-group">
              <label for="drug-quantity-type-input"><?php echo $locale->getProperty('form.register.drug.label.quantity.type', 'Drug\'s measurement unit'); ?></label>
              <input 
                type="text" 
                class="form-control" 
                id="drug-quantity-type-input" 
                name="<?php echo $configuration->getProperty('form.register.drug.fieldname.quantity.type', 'drug-quantity-type'); ?>" 
                placeholder="<?php echo $locale->getProperty('form.register.drug.placeholder.quantity.type', 'Enter drug\'s measurement unit...'); ?>"
              >
              <small 
                id="quantity-type-help" 
                class="form-text text-muted">
                <?php echo $locale->getProperty('form.register.drug.hint.quantity.type', 'Drug\'s measurement unit e.g. tablets, milliliters etc.'); ?>
              </small>
            </div>

            <div class="form-group">
              <label for="drug-initial-quantity-input"><?php echo $locale->getProperty('form.register.drug.label.quantity.initial', 'Initial drug\'s quantity'); ?></label>
              <input 
                type="number" 
                step="0.01" 
                class="form-control" 
                id="drug-initial-quantity-input" 
                name="<?php echo $configuration->getProperty('form.register.drug.fieldname.quantity.initial', 'drug-initial-quantity'); ?>" 
                placeholder="<?php echo $locale->getProperty('form.register.drug.placeholder.quantity.initial', 'Enter drug\'s initial quantity...'); ?>"
              >
              <small 
                id="initial-quantity-help" 
                class="form-text text-muted">
                <?php echo $locale->getProperty('form.register.drug.hint.quantity.initial', 'Initial quantity that package/bottle holds.'); ?>
              </small>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><?php echo $locale->getProperty("button.common.submit", "Submit"); ?></button>
            </div>
        </form>
    </div>

    <?php require_once("./../assets/components/footer.php") ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    
</body>
</html>