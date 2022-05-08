<?php
    require_once("./../assets/config_provider.php");
    require_once("./../entity/user.php");
    require_once("./../entity/drug_template.php");
    require_once("./../utils/session_util.php");
    require_once("./../utils/server_util.php");
    require_once("./../service/drug_svc.php");
    require_once("./../service/drug_template_svc.php");
    require_once("./../utils/db_util.php");

    session_start();

    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
    $session = new SessionManager($configuration);
    $drug_template_svc = new DrugTemplateService($db);

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

    <div class="container mb-5">
        <div id="title-holder">
            <h1><?php echo $locale->getProperty("page.title.add.drug", "Add Drug"); ?></h1>
        </div>

        <div id="drug-selection-component">
          <input id="drug-search-input" class="form-control" type="text" placeholder="<?php echo $locale->getProperty("table.register.drug.search", "Find a drug..."); ?>" onkeyup="onDrugSearchChange()">
          <table class="table table-hover">
              <thead>
              <tr>
                  <th scope="col"><?php echo $locale->getProperty("table.register.drug.name", "Name"); ?></th>
                  <th scope="col"><?php echo $locale->getProperty("table.register.drug.manufacturer", "Manufacturer"); ?></th>
                  <th scope="col"><?php echo $locale->getProperty("table.register.drug.activeingredient", "Active ingredient"); ?></th>
                  <th scope="col"><?php echo $locale->getProperty("table.register.drug.package", "Package"); ?></th>
                  <th scope="col"><?php echo $locale->getProperty("table.register.drug.leaflet", "Leaflet"); ?></th>
                  <th scope="col"><?php echo $locale->getProperty("table.register.drug.select", "Select"); ?></th>
              </tr>
              </thead>
              <tbody id="selected-product-rows-outlet">
                  <tr>
                      <td><?php echo $locale->getProperty("table.register.drug.nodrugs", "No drugs, type something..."); ?></td>
                  </tr>
              </tbody>
          </table>
        </div>

        <?php
            if(isPost()) {
                $drugTemplateId = $_POST[$configuration->getProperty('form.register.drug.fieldname.template.id', 'drug-template-id')];
                $drugPrice = $_POST[$configuration->getProperty('form.register.drug.fieldname.price', 'drug-price')];
                $drugExpiryDt = $_POST[$configuration->getProperty('form.register.drug.fieldname.expiry.date', 'drug-expiry-date')];
                $drugInitialQuantity = $_POST[$configuration->getProperty('form.register.drug.fieldname.quantity.initial', 'drug-initial-quantity')];
                $householdId = $session->getCurrentUser()->getHouseholdId();

                $drugSvc = new DrugService($db);

                $isAddSuccess = $drugSvc->add($drugTemplateId, $drugPrice, $drugExpiryDt, $drugInitialQuantity, $householdId);

                if ($isAddSuccess) {
                  header('Location: ./dashboard.php');
                } else {
                  echo 'Server error';
                }
            }
        ?>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
            <input 
              id="drug-template-id-input"
              type="hidden" 
              name="<?php echo $configuration->getProperty('form.register.drug.fieldname.template.id', 'drug-template-id'); ?>"
              value="-1"
            >
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

    <script type="text/javascript">

      const drugTemplates = <?php echo $drug_template_svc->getAllAsJson(); ?>;

      const drugMap = {};
      drugTemplates.forEach(d => {
        const key = `${d.name} ${d.manufacturer}`;
        drugMap[key] = d;
      });

      function onDrugSearchChange() {
        const searchString = document.getElementById('drug-search-input').value;
        let matchedDrugRowsHtmls = [];
        Object.keys(drugMap).forEach(key => {
            if (key.search(searchString) !== -1) {
              matchedDrugRowsHtmls = [...matchedDrugRowsHtmls, `<tr><td>${drugMap[key].name}</td><td>${drugMap[key].manufacturer}</td><td>${drugMap[key].active_ingredient}</td><td>${drugMap[key].package}</td><td><a class="btn btn-warning" href="${drugMap[key].leaflet}" role="button">See leaflet</a></td><td><button type="button" class="btn btn-success" onclick="onSelectClick(${drugMap[key].drug_template_id})">Select</button></td></tr>`]
            }
        });

        document.getElementById('selected-product-rows-outlet').innerHTML = searchString === '' ? '<tr><td>Type something...</td></tr>' : matchedDrugRowsHtmls.join('');
      }

      function onSelectClick(drugTemplateId) {
        document.getElementById('drug-template-id-input').value = drugTemplateId;
        console.log(document.getElementById('drug-template-id-input').value);
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    
</body>
</html>