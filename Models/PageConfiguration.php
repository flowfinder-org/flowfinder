<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class PageConfiguration extends Model
{
    public function __construct() {}

    public function select_pageconfiguration($id_page_configuration, $id_utilisateur, $type_page)
    {
        $selectconfig = $this->requete("SELECT json_configuration_page FROM page_configuration WHERE id_page_configuration = ? AND id_utilisateur = ? AND type_page = ? ", [$id_page_configuration, $id_utilisateur, $type_page])->fetch();
        return $selectconfig;
    }

    public function select_all_listetunnel($id_utilisateur, $type_tunnel)
    {
        $selectalltunnel = $this->requete("SELECT id_page_configuration, json_configuration_page FROM page_configuration WHERE id_utilisateur = ? AND type_page = ?", [$id_utilisateur, $type_tunnel])->fetchAll();
        return $selectalltunnel;
    }

    public function create_pageconfiguration($id_page_configuration, $actif_page_configuration,  $id_utilisateur, $type_page, $json_configuration_page)
    {
        $createpageconfiguration = $this->requete("INSERT INTO page_configuration(id_page_configuration, actif_page_configuration , id_utilisateur, type_page, json_configuration_page, date_modification_page) VALUES( ?, ?, ?, ?, ?, UTC_TIMESTAMP() );", [$id_page_configuration, $actif_page_configuration, $id_utilisateur, $type_page, $json_configuration_page])->fetch();
        if ($createpageconfiguration) {
            return 'création de la configuration';
        } {
            return 'error creation config';
        }
    }

    public function update_pageconfiguration($id_page_configuration, $actif_page_configuration,  $id_utilisateur, $type_page, $json_configuration_page)
    {

        $updatepageconfig = $this->requete(
            "UPDATE page_configuration
                SET actif_page_configuration = ?, json_configuration_page = ? , date_modification_page = UTC_TIMESTAMP()
                WHERE id_utilisateur = ? AND type_page = ? AND id_page_configuration = ?",
            [$actif_page_configuration, $json_configuration_page,  $id_utilisateur, $type_page, $id_page_configuration]
        );
        if ($updatepageconfig) {
            return 'sauvegarde de la configuration';
        } {
            return 'error update';
        }
    }
}
