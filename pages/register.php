<?php
    require_once("./../assets/config_provider.php");
    require_once("./../utils/server_util.php");
    require_once("./../service/register_user_svc.php");
    require_once("./../utils/db_util.php");
    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
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
      <h1>
        <?php echo $locale->getProperty('page.title.register.user', 'Register user'); ?>
      </h1>
        <?php if(isPost()) { ?>
            <?php
                $request = new RegisterUserService(
                  $db,
                  $_POST[$configuration->getProperty('form.register.user.fieldname.username', 'username')],
                  $_POST[$configuration->getProperty('form.register.user.fieldname.firstname', 'firstname')],
                  $_POST[$configuration->getProperty('form.register.user.fieldname.lastname', 'lastname')],
                  $_POST[$configuration->getProperty('form.register.user.fieldname.householdcode', 'householdcode')],
                  $_POST[$configuration->getProperty('form.register.user.fieldname.password', 'password')]
                );
                $response = $request->execute();
            ?>
            <div class="alert <?php echo $response->isSuccess() ? "alert-success" : "alert-danger" ?>" role="alert">
                <?php echo UserRegistrationErrorCodeMessageResolver::resolve($locale, $response->getErrorCode()); ?>
            </div>
        <?php } ?>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
            <div class="form-group">
              <label for="username-input"><?php echo $locale->getProperty('form.register.user.label.username', 'Username'); ?></label>
              <input type="text" class="form-control" id="username-input" name="<?php echo $configuration->getProperty('form.register.user.fieldname.username', 'username'); ?>" placeholder="<?php echo $locale->getProperty('form.register.user.placeholder.username', 'Enter username...'); ?>">
              <small id="username-help" class="form-text text-muted"><?php echo $locale->getProperty('form.register.user.hint.username', 'A username will be a unique identifier used to login.'); ?></small>
            </div>
            <div class="form-group">
              <label for="fname-input"><?php echo $locale->getProperty('form.register.user.label.firstname', 'First name'); ?></label>
              <input type="text" class="form-control" id="fname-input" name="<?php echo $configuration->getProperty('form.register.user.fieldname.firstname', 'firstname'); ?>" placeholder="<?php echo $locale->getProperty('form.register.user.placeholder.firstname', 'Enter first name...'); ?>">
            </div>
            <div class="form-group">
              <label for="lname-input"><?php echo $locale->getProperty('form.register.user.label.lastname', 'Last name'); ?></label>
              <input type="text" class="form-control" id="lname-input" name="<?php echo $configuration->getProperty('form.register.user.fieldname.lastname', 'lastname'); ?>" placeholder="<?php echo $locale->getProperty('form.register.user.placeholder.lastname', 'Enter last name...'); ?>">
            </div>
            <div class="form-group">
              <label for="householdcode-input"><?php echo $locale->getProperty('form.register.user.label.householdcode', 'Household registry code'); ?></label>
              <input type="text" class="form-control" id="householdcode-input" name="<?php echo $configuration->getProperty('form.register.user.fieldname.householdcode', 'householdcode'); ?>" placeholder="<?php echo $locale->getProperty('form.register.user.placeholder.householdcode', 'Household registry code...'); ?>">
            </div>
            <div class="form-group">
              <label for="passwd_input"><?php echo $locale->getProperty('form.register.user.label.password', 'Password'); ?></label>
              <input type="password" class="form-control" id="passwd-input" name="<?php echo $configuration->getProperty('form.register.user.fieldname.password', 'password'); ?>" placeholder="<?php echo $locale->getProperty('form.register.user.placeholder.password', 'Enter password...'); ?>">
              <small id="password-help" class="form-text text-muted"><?php echo $locale->getProperty('form.register.user.hint.password', 'Usage of at least one capital letter, digit and special character is recommended.'); ?></small>
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