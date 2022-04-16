<?php
    function resolveLocaleChangeUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../assets/locale/set_lang.php' : $_SERVER['REQUEST_URI'].'/../assets/locale/set_lang.php';
    }

    function resolveIndexUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../index.php' : $_SERVER['REQUEST_URI'].'/../index.php';
    }

    function resolveRegisterUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../pages/register.php' : $_SERVER['REQUEST_URI'].'/../pages/register.php';
    }

    function resolveRegisterHouseholdUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../pages/register_household.php' : $_SERVER['REQUEST_URI'].'/../pages/register_household.php';
    }

    function resolveLoginUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../pages/login.php' : $_SERVER['REQUEST_URI'].'/../pages/login.php';
    }

    function resolveLogoutUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../pages/logout.php' : $_SERVER['REQUEST_URI'].'/../pages/logout.php';
    }

    function resolveDashboardUri() {
        $requestUri = $_SERVER['REQUEST_URI'];
        $isPagesUri = strpos($requestUri, 'pages');
        return $isPagesUri ? $_SERVER['REQUEST_URI'].'/../../pages/dashboard.php' : $_SERVER['REQUEST_URI'].'/../pages/dashboard.php';
    }

    $isLoggedIn = $session->isUserLoggedIn();
?>

<style>
    .nav-right {
        margin-left: auto;
    }

    .nav-left {
        margin-right: auto;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    
    <ul class="navbar-nav nav-left">
        <li class="nav-item">
            <a class="mx-2 navbar-brand pull-left" href="<?php echo $isLoggedIn ? resolveDashboardUri() : resolveIndexUri(); ?>"><?php echo $locale->getProperty("navbar.brand", "Strona"); ?></a>
        </li>
    </ul>
    

    <ul class="navbar-nav nav-right align-items-center">
        <?php if($isLoggedIn) { ?>
            <li class="nav-item">
                <div class="navbar-brand mx-3">
                    <?php echo sprintf($locale->getProperty('navbar.greeting.template', 'Hello, %s!'), $session->getCurrentUser()->getFormattedName()); ?>
                </div>
            </li>
            <li class="nav-item">
                <a href="<?php echo resolveLogoutUri(); ?>" class="btn btn-danger" role="button">
                    <?php echo $locale->getProperty('navbar.label.logout', 'Logout'); ?>
                </a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a href="<?php echo resolveRegisterUri(); ?>" class="btn btn-success mx-1" role="button">
                    <?php echo $locale->getProperty('navbar.label.register.user', 'Register user'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo resolveRegisterHouseholdUri(); ?>" class="btn btn-success mx-1" role="button">
                    <?php echo $locale->getProperty('navbar.label.register.household', 'Register household'); ?>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo resolveLoginUri(); ?>" class="btn btn-secondary mx-1" role="button">
                    <?php echo $locale->getProperty('navbar.label.login', 'Login'); ?>
                </a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <form action="<?php echo resolveLocaleChangeUri() ?>" method="get">
                <input type="hidden" name="lang" value="en">
                <button type="submit" class="btn"><img src="https://img.icons8.com/color/48/000000/usa.png" alt="en-US"></button>
            </form>
        </li>
        <li class="nav-item">
            <form action="<?php echo resolveLocaleChangeUri() ?>" method="get">
                <input type="hidden" name="lang" value="pl">
                <button type="submit" class="btn"><img src="https://img.icons8.com/color/48/000000/poland.png" alt="pl-PL"></button>
            </form>
        </li>
    </ul>

</nav>
