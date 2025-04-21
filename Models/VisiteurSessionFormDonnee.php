<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class VisiteurSessionFormDonnee extends Model
{
    public function __construct() {}


    public function insert($id_collection, $id_visiteur_session, $id_formdonnees)
    {
        $this->requete_insert("INSERT INTO visiteur_sessions_formdonnees(id_visiteur_session, id_formdonnees, id_collection) VALUES(?, ?, ?);", [$id_visiteur_session, $id_formdonnees, $id_collection]);
    }

}
