<?php

namespace FlowFinder\Models;

use FlowFinder\Models\Model;

class Theme extends Model
{

    public function __construct() {}

    public function createtheme($id_utilisateur, $theme_page, $theme_nom, $theme_css)
    {
        $this->requete("INSERT INTO theme(id_utilisateur, theme_page, theme_nom, theme_css, date_modification) VALUES(?, ?, ?, ?, UTC_TIMESTAMP());", [$id_utilisateur, $theme_page, $theme_nom, $theme_css]);
    }

    public function selectthemepage($id_utilisateur, $theme_page)
    {
        $selectthemepage = $this->requete("SELECT * FROM theme WHERE id_utilisateur = ? AND theme_page = ?", [$id_utilisateur, $theme_page])->fetch();
        return $selectthemepage;
    }

    public function selectcsstheme($id_utilisateur, $theme_page, $theme_nom)
    {
        $selectcsstheme = $this->requete("SELECT theme_css FROM theme WHERE id_utilisateur = ? AND theme_page = ? AND theme_nom = ?", [$id_utilisateur, $theme_page, $theme_nom])->fetch();
        return $selectcsstheme;
    }

    public function updatetheme($id_utilisateur, $theme_page, $theme_nom, $theme_css)
    {
        $this->requete(
            "UPDATE theme
                SET theme_nom = ?, theme_css = ?, date_modification = UTC_TIMESTAMP()  
                WHERE id_utilisateur = ? AND theme_page = ?",
            [$theme_nom, $theme_css, $id_utilisateur, $theme_page]
        );
    }
}
