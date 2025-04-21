<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class Collection extends Model
{
	/*
	id_collection INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(64) NOT NULL,
    traceur_uuid VARCHAR(64),
    date_creation DATETIME
	*/

	public function __construct()
	{
	}

    public function getCollection(int $id_collection)
    {
        return $this->requete("SELECT * FROM collections WHERE id_collection = ? ", [$id_collection])->fetch();
    }

    public function getCollectionParTraceurUUID(string $traceur_uuid)
    {
        return $this->requete("SELECT * FROM collections WHERE traceur_uuid = ? ", [$traceur_uuid])->fetch();
    }


    public function addCollection(string $nom, string $traceur_uuid)
    {
        $this->requete("INSERT INTO collections(date_creation, nom, traceur_uuid) VALUES(UTC_TIMESTAMP(), ?, ?)", [$nom, $traceur_uuid]);
        return $this->getLastInsertedId();
    }

}