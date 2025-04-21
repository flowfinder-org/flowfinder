<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class CollectionUtilisateur extends Model
{
	/*
	id_collection_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    id_collection INT NOT NULL,
    id_utilisateur INT NOT NULL,
    added_at DATETIME
	*/

	public function __construct()
	{
	}

    public function getCollectionUtilisateurParCollectionIdEtUtilisateurId(int $id_utilisateur, int $id_collection)
    {
        return $this->requete("SELECT * FROM collections_utilisateurs WHERE id_collection = ? AND id_utilisateur = ?", [$id_collection, $id_utilisateur])->fetch();
    }

    public function getIdsCollectionsPourUtilisateur(int $id_utilisateur)
    {
        // le order by ASC est important car on utilise la plus ancienne collection comme valeure par défaut 
        return $this->requete("SELECT id_collection FROM collections_utilisateurs WHERE id_utilisateur = ? ORDER BY date_ajout ASC", [$id_utilisateur])->fetchAll();
    }

    public function getParIdCollection(int $id_collection)
    {
        // le order by ASC est important car on utilise la plus ancienne collection comme valeure par défaut 
        return $this->requete("SELECT * FROM collections_utilisateurs WHERE id_collection = ? ORDER BY date_ajout ASC", [$id_collection])->fetchAll();
    }

    public function addCollectionUtilisateur(int $id_utilisateur, int $id_collection)
    {
        $this->requete("INSERT INTO collections_utilisateurs(date_ajout, id_utilisateur, id_collection) VALUES(UTC_TIMESTAMP(), ?, ?)", [$id_utilisateur, $id_collection]);
        return;
    }
}