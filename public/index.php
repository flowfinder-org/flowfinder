<?php
/*
 * @author FlowFinder <contact@flowfinder.org>
 * @copyright 2022-3032 FlowFinder.org
 * @license  AGPL 3.0
 */

 //on defini la constance du dossier racine

require_once dirname(__DIR__) . '/Core/Config.php';
use FlowFinder\Core\Main;
//on importe Autoloader FlowFinder

require_once APP_ROOT.'/FlowFinderAutoloader.php';
use FlowFinder\FlowFinderAutoloader;
FlowFinderAutoloader::register();

// j'instancie Main (qui va router les infos)
$flowfinder = new Main();

// je démarre l'appli FlowFinder
$flowfinder->start();
