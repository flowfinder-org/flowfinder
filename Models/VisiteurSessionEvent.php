<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class VisiteurSessionEvent extends Model
{
    public function __construct() {}


    public function insert($id_collection, $id_visiteur_session, $event_name, $event_value)
    {
        $this->requete_insert("INSERT INTO visiteur_sessions_events(id_visiteur_session, id_collection, event_name, event_value, date_event) VALUES(?, ?, ?, ?, UTC_TIMESTAMP());", [$id_visiteur_session, $id_collection, $event_name, $event_value]);
    }

}
