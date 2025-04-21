<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class Element extends Model
{
    public function __construct() {}

    public function select_element($id_element, $id_utilisateur, $type_page, $id_page_configuration)
    {
        $selectlien = $this->requete("SELECT * FROM page_elements WHERE id_element = ? AND id_utilisateur = ? AND type_page = ? AND id_page_configuration = ?", [$id_element, $id_utilisateur, $type_page, $id_page_configuration])->fetch();
        return $selectlien;
    }

    public function select_all_elements($id_utilisateur, $type_page, $id_page_configuration)
    {
        $selectelement = $this->requete("SELECT id_element, donnee_json_element FROM page_elements WHERE id_utilisateur = ? AND type_page = ? AND id_page_configuration = ?", [$id_utilisateur, $type_page, $id_page_configuration])->fetchAll();
        return $selectelement;
    }

    public function create_element($id_element, $id_utilisateur, $type_page, $actif_element, $titre_element, $donnee_json_element, $id_page_configuration)
    {
        $createlien = $this->requete("INSERT INTO page_elements(id_element, id_utilisateur, type_page, actif_element,titre_element,donnee_json_element, date_modification_element, id_page_configuration) VALUES(?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), ?);", [$id_element, $id_utilisateur, $type_page, $actif_element, $titre_element, $donnee_json_element, $id_page_configuration])->fetch();
        if ($createlien) {
            return 'création du lien';
        } {
            return 'error creation';
        }
    }

    public function delete_element($id_element, $id_utilisateur, $type_page, $id_page_configuration)
    {
        $this->requete('DELETE FROM page_elements WHERE id_element = ? AND id_utilisateur= ? AND type_page = ? AND id_page_configuration = ?', [$id_element, $id_utilisateur, $type_page, $id_page_configuration]);
    }

    public function update_element($id_element, $id_utilisateur, $type_page, $actif_element, $titre_element, $donnee_json_element, $id_page_configuration)
    {
        $updatelien = $this->requete(
            "UPDATE page_elements
                SET actif_element = ?, titre_element = ?,  donnee_json_element = ?, date_modification_element = UTC_TIMESTAMP()
                WHERE id_utilisateur = ? AND id_element = ? AND type_page = ? AND id_page_configuration = ?",
            [$actif_element, $titre_element, $donnee_json_element, $id_utilisateur, $id_element, $type_page, $id_page_configuration]
        );
        if ($updatelien) {
            return 'sauvegarde';
        } {
            return 'error update';
        }
    }
}
