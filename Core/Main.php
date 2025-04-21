<?php

namespace FlowFinder\Core;

/**
 * Routeur Principal
 */
class Main
{

    public function start()
    {
        //on demarre la session utilisateur cookie
        if (PHP_SESSION_COOKIE_LIFETIME != "") {
            session_set_cookie_params(PHP_SESSION_COOKIE_LIFETIME, "/");
        }
        if (PHP_SESSION_SAVEPATH != "") {
            session_save_path(PHP_SESSION_SAVEPATH);
        }
        if (PHP_SESSION_GC_MAXLIFETIME != "") {
            ini_set('session.gc_maxlifetime', PHP_SESSION_GC_MAXLIFETIME);
        }

        //on retire le dernier / eventuel de l'url
        $uri = $_SERVER['REQUEST_URI'];

        //on vérifie que uri n'est pas vide et se termine par /
        if (!empty($uri) && $uri != "/" && $uri[-1] === "/") {
            // on vire le / inutile
            $uri = substr($uri, 0, -1);

            // on redirige
            http_response_code(301);
            header('Location: ' . $uri);
        }
        // on gère les paramètres d'URL
        $params = array();
        if (isset($_GET['p'])) {
            $params = explode('/', $_GET['p']); // ce p est le resultat de la regexp du mod_rewrite appliqué par le .htaccess
            if (count($params) == 1 && $params[0] == "") {
                $params = array();
            }
        }

        //on gère les variables en GET au cas ou j'en ai besoin...
        $pos = strpos($uri, 'ank=');
        if ($pos != false) {
            $listparams = substr(urldecode($uri), $pos + 4);
            $listparams = json_decode($listparams, true);
        } else {
            $listparams = null;
        }

        // si on a des parametres on commence par vérifier si on est dans un appel vers /api
        if (count($params) > 0 && $params[0] == 'Api') {
            /*
            les API sont accessibles en HTTP, c'est la responsabilité de l'appelant de sécuriser ses appels
            */
            $folder = ucfirst($params[1]);
            $controller_in = ucfirst($params[2]);
            $api = '\\FlowFinder\\Api\\' . $folder . '\\' . $controller_in . 'Api';
            $cheminapi = APP_ROOT . DS . 'Api' . DS . $folder . DS . $controller_in . 'Api.php';

            if (file_exists($cheminapi)) {
                $api_instance = new $api();
            } else {
                error_log("chemin de controlleur API inexistant sur le disque: '" . $cheminapi . "'");
                http_response_code(404);
                echo '404';
            }

            $action = $params[3];
            if (method_exists($api_instance, $action)) {
                call_user_func_array([$api_instance, $action], [$listparams]);
            } else {
                error_log("la method de controlleur '" . $action . "' n'existe pas dans le controlleur API '" . $cheminapi . "'");
                http_response_code(404);
                echo '404';
            }
        } else {
            // on démarre la session utilisateur
            ini_set('session.name', 'ffinder_sess');
            session_start();

            // force le HTTPS si besoin
            if (FORCE_HTTPS && $_SERVER["HTTPS"] != "on") {
                header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
                exit();
            }

            // Étape 1 : gestion de la langue
            $supported_langs = ['fr', 'en', 'es', 'de', 'it'];
            $lang = 'fr'; // langue par défaut

            if (count($params) > 0 && in_array(strtolower($params[0]), $supported_langs)) {
                $lang = strtolower(array_shift($params)); // On retire le param de langue
                $_SESSION['lang'] = $lang; // On le met en session
                define('APP_LANG', $lang); // Si tu veux y accéder globalement
            } else {
                // Pas de langue dans l'URL ? Redirige avec la langue par défaut
                $uri = $_SERVER['REQUEST_URI'];
                $new_url = '/' . $lang . $uri;
                header('Location: ' . $new_url);
                exit();
            }

            $controller_in = "";
            if (count($params) > 0) {
                $controller_in = ucfirst(array_shift($params));
            }
            //on récupère le nom du controlleur instancié
            $controller = '\\FlowFinder\\Controllers\\' . $controller_in . 'Controller';
            $chemincontroller = APP_ROOT . DS . 'Controllers' . DS . $controller_in . 'Controller.php';

            // on est sur le site flowfinder classique avec un utilisateur qui veut voir 
            if ($controller_in != '' && file_exists($chemincontroller))
            {
                //on instancie le bon controller monsite/balbla donc on instancie balbla dans Controllers\balbaController.php
                $controller_instance = new $controller();
            } else {    
                // Pas de controlleur qui porte le nom de cette url ni de site d'url qui indique une volonté d'afficher un site custom alors on redirige vers la page flowfinder.com/accueil
                $controller = "\FlowFinder\Controllers\LoginController";
                $controller_instance = new $controller();
            }

            //on récupère le 2 parametres de l'url
            $action = (isset($params[0]) && trim($params[0]) != "") ? array_shift($params) : 'index';

            //on verifie maintenant seulement pour le controller tunnel poura ajouter en parametre le numéro de tunnel
            if ($controller_in == 'Tunnel' || $controller_in == 'Sondage' || $controller_in == 'Feedback' || $controller_in == 'Formulaire') {
                $position_t = strrpos($uri, '/');
                if ($position_t !== false) {
                    $reste = substr($uri, $position_t + 1);
                    $position_interrogation = strpos($reste, '?');

                    if ($position_interrogation !== false) {
                        $reste = substr($reste, 0, $position_interrogation);
                    }
                    $numerotunnel = substr($reste, 1);
                    $premier_caractere = substr($reste, 0, 1);
                    if (ctype_digit($numerotunnel) && $premier_caractere == "t") {
                        $listparams['numero_tunnel'] = $numerotunnel;
                        $listparams['type_tunnel'] = $controller_in;
                        $action = 'Loadtunnelt';
                    }
                }
            }

            if (method_exists($controller, $action)) {
                $controller_instance->action($action, $listparams);
            } else {
                error_log("la method de controlleur '" . $action . "' n'existe pas dans le controlleur '" . $chemincontroller . "'");
                http_response_code(404);
                echo '404 - page not found';
                die();
            }
        }
    }

}
