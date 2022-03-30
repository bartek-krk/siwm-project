<?php
    class ConfigProvider {

        private $jsonDict;

        public function __construct($path) {
            $string = file_get_contents($path);
            $this->jsonDict = json_decode($string, true);
        }

        public function getProperty($key, $default) {
            $fetchedProperty = $this->jsonDict[$key];
            return $fetchedProperty == NULL ? $default : $fetchedProperty;
        }
    }

    class LocaleProvider {

        private $jsonDict;
        private $configuration;

        public function __construct($configuration, $path) {
            $this->configuration = $configuration;
            $string = file_get_contents($path);
            $this->jsonDict = json_decode($string, true);
        }

        public function getProperty($key, $default) {
            $lang = $_COOKIE[$this->configuration->getProperty("cookie.language.name", "homefirstaidkit_chosenapplicationlanguage")];
            $fetchedProperty = $this->jsonDict[$key];
            $fetchedLabel = $fetchedProperty == NULL ? $default : $fetchedProperty[$lang];
            return $fetchedLabel == NULL ? $default : $fetchedLabel;
        }
    }

    $configuration = new ConfigProvider(dirname(__FILE__)."/conf.json");    
    $locale = new LocaleProvider($configuration, dirname(__FILE__)."/locale/labels.json");

    $langCookieName = $configuration->getProperty("cookie.language.name", "homefirstaidkit_chosenapplicationlanguage");
    if (!isset($_COOKIE[$langCookieName])) {
        setcookie($langCookieName, "en", time() + (86400*30), "/");
    }

?>