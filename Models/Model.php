<?php
namespace FlowFinder\Models;
use FlowFinder\Core\Db;
use Exception;

class Model extends Db
{

	// table de la base de données
	protected $table;

	// Instance de db
	private $db;

	public function requete(string $sql, ?array $attributs = null){
		// On récupère l'instance de dB
		$this->db = Db::getInstance();

		// On vérifie si on a ades attributs
		if($attributs !== null){
			//Requête préparée
			$query = $this->db->prepare($sql);
			$query->execute($attributs);
			return $query;
		}else{
			//Requête simple

			return $this->db->query($sql);
		}
	}
	
	public function requete_insert(string $sql, array $attributs){
		/*
		* Cette fonction retourne false si la query c'est mal passé
		* cette fonction retourne l'id de l'enregistrement inséré
		* ou 
		*/ 
		// On récupère l'instance de dB
		$this->db = Db::getInstance();

		$statment = $this->db->prepare($sql);
		$ret = $statment->execute($attributs);

		if($ret !== true)
		{
			throw new Exception("Exception lors de l'insertion d'un enregistrement: code '". $statment->errorCode() ."': details: '" . $statment->errorInfo()[2] . "'");
		}
		
		return true;
	}
	
	public function getLastInsertedId()
	{
		return $this->db->lastInsertId();
	}


}