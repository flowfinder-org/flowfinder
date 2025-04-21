<?php

use FlowFinder\Helper\Helper;

define('DS', DIRECTORY_SEPARATOR);

define('APP_ROOT', realpath(dirname(__FILE__) . DS . '..'));

define('MULTILINGUE_ACTIF', false);

define('TIMEZONE_AFFICHAGE', 'Europe/Paris');

define('ASSETS_VERSION_FOR_NOCACHE', "0"); // ceci est ajouté en suffix a chaque fichier js, et css par la methode ak->asset pour forcer le rafraichissement 

define('MAXMIND_GEOIP_ACCOUND_ID', '');
define('MAXMIND_GEOIP_LICENSE_KEY', '');

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    define('BASE_URL', "https://" . $_SERVER['HTTP_HOST']);
}else{
    define('BASE_URL', "http://" . $_SERVER['HTTP_HOST']);
}

// durée de vie de la session et du cookie
define('PHP_SESSION_COOKIE_LIFETIME', 3600 * 24 * 30); // 30 jours
define('PHP_SESSION_GC_MAXLIFETIME', 3600 * 24 * 30); // 30 jours
define('PHP_SESSION_SAVEPATH', ""); // utilise la valeur configuré dans php.ini

//Information de connexion à la db
define('DBHOST', 'localhost');
define('DBNAME', 'flowfinder_local');
define('DBUSER', 'flowfinder_local_user');
define('DBPASS', 'flowfinder_local_password');

define('FORCE_HTTPS', false);

define('USER_FILES_FOLDER_PRIVATE', APP_ROOT . DS . '_USERS_FILES_PRIVATE' );
define('USER_FILES_FOLDER', APP_ROOT . DS . 'public' . DS . '_USERS_FILES' );
define('USER_FILES_URL', '/_USERS_FILES');
