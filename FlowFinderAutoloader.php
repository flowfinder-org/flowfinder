<?php

/*
 * @author FlowFinder <contact@flowfinder.org>
 * @copyright 2022-3032 FlowFinder.org
 * @license  AGPL 3.0
 * International Registered FlowFinder.org
 */

/**
 * Permet d'inserer automatiquement les classes lorsqu'elles sont demand�es dans les fichiers php
 */

namespace FlowFinder;
class FlowFinderAutoloader
{
	static function register()
	{
		spl_autoload_register([
			__CLASS__,
			'autoload_func'
		]);
	}

	static function autoload_func($class)
	{
		// remplacer le namespace -> FlowFinder par rien puis enlever le \
		$class= str_replace(__NAMESPACE__ . '\\','', $class);

		// on modifie les \ par des /
		$class = str_replace('\\', '/', $class);

		$fichier = APP_ROOT . '/' . $class . '.php';
		
		if (file_exists($fichier)){
			require_once $fichier;

		}
	}

}