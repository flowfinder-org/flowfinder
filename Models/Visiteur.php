<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class Visiteur extends Model
{
	/*
	id_visiteur INT AUTO_INCREMENT PRIMARY KEY,
    id_collection INT NOT NULL,
    visiteur_uuid VARCHAR(255) NOT NULL UNIQUE,
    date_creation DATETIME,
    INDEX (visitor_uuid)
	*/

	public function __construct()
	{
	}

    public function getVisiteurParId(int $id_visiteur)
    {
        return $this->requete("SELECT * FROM visiteurs WHERE id_visiteur = ?", [$id_visiteur])->fetch();
    }

    public function getVisiteurParCollectionIdEtUUID(int $id_collection, string $visiteur_uuid)
    {
        return $this->requete("SELECT * FROM visiteurs WHERE visiteur_uuid = ? AND id_collection = ? ", [$visiteur_uuid, $id_collection])->fetch();
    }

    public function addVisiteurToCollection(int $id_collection, string $visiteur_uuid)
    {
        $this->requete("INSERT INTO visiteurs(date_creation, id_collection, visiteur_uuid) VALUES(UTC_TIMESTAMP(), ?, ?)", [$id_collection, $visiteur_uuid]);
        return $this->getLastInsertedId();
    }

}