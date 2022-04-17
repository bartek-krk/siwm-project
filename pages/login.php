<?php
    require_once("./../assets/config_provider.php");
    require_once("./../utils/server_util.php");
    require_once("./../service/authentication_svc.php");
    require_once("./../utils/db_util.php");
    require_once("./../utils/session_util.php");

    session_start();

    $configuration = new ConfigProvider("./../assets/conf.json");    
    $locale = new LocaleProvider($configuration, "./../assets/locale/labels.json");
    $db = new DbManager($configuration);
    $session = new SessionManager($configuration);
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
            <h1><?php echo $locale->getProperty("page.title.login", "Login"); ?></h1>
        </div>

        <?php
            if (isPost()) {
                $auth_svc = new AuthenticationService($db);

                $username = $_POST[$configuration->getProperty('form.login.user.fieldname.username', 'username')];
                $password = $_POST[$configuration->getProperty('form.login.user.fieldname.password', 'password')];
                
                $user = $auth_svc->isAuthenticated($username, $password);

                if ($user != NULL) {
                    $_SESSION[$configuration->getProperty('session.assoc.loggedin', 'is_logged_in')] = true;
                    $_SESSION[$configuration->getProperty('session.assoc.user', 'user_details')] = $user;
                    header('Location: ./dashboard.php');
                }
            }
        ?>

        <?php if ($user == NULL && isPost()) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $locale->getProperty('form.login.user.unauthorized', 'Invalid username or password. Try again.'); ?>
            </div>
        <?php } ?>

        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
            <div class="form-group">
              <label for="username-input"><?php echo $locale->getProperty('form.login.user.label.username', 'Username'); ?></label>
              <input type="text" class="form-control" id="username-input" name="<?php echo $configuration->getProperty('form.login.user.fieldname.username', 'username'); ?>" placeholder="<?php echo $locale->getProperty('form.login.user.placeholder.username', 'Enter username...'); ?>">
            </div>
            <div class="form-group">
              <label for="passwd_input"><?php echo $locale->getProperty('form.login.user.label.password', 'Password'); ?></label>
              <input type="password" class="form-control" id="passwd-input" name="<?php echo $configuration->getProperty('form.login.user.fieldname.password', 'password'); ?>" placeholder="<?php echo $locale->getProperty('form.login.user.placeholder.password', 'Enter password...'); ?>">
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