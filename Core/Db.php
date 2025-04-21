<?php

namespace FlowFinder\Core;

use PDO;
use PDOException;
class Db extends PDO
{
	//Instance unique de la classe
	private static $instance;

	private function __construct()
	{
		// Dsn de connexion
		$_dsn = 'mysql:dbname='. DBNAME . ';host='. DBHOST .';charset=utf8mb4';;

		// on appelle le constructeur de la classe pdo
		try{
			parent::__construct($_dsn, DBUSER, DBPASS);

			$this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8mb4');
			$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

	public static function getInstance():PDO
	{
		if(self::$instance === null){
			self::$instance = new self();
		}
		return self::$instance;
	}

}