<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class PageFormDonnees extends Model
{
    public function __construct() {}


    public function select_all_elements($id_utilisateur, $id_page_configuration)
    {
        $selectelement = $this->requete("SELECT json_formdonnees, date_soumission FROM page_formdonnees WHERE id_utilisateur = ? AND id_page_configuration = ? ORDER BY date_soumission DESC ", [$id_utilisateur, $id_page_configuration])->fetchAll();
        return $selectelement;
    }

    public function insertion_donnees($id_utilisateur, $type_page, $id_page_configuration, $json_formdonnees)
    {
        $this->requete_insert("INSERT INTO page_formdonnees(id_utilisateur, type_page, id_page_configuration, json_formdonnees, date_soumission) VALUES(?, ?, ?, ?, UTC_TIMESTAMP());", [$id_utilisateur, $type_page, $id_page_configuration, $json_formdonnees]);
        return $this->getLastInsertedId();
    }
}
