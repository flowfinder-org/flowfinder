<?php

namespace FlowFinder\Core;

use FlowFinder\Helper\Helper;

class Afficheur
{
    public $config_affichage;

    public function __construct()
    {
        $this->config_affichage = new Helper;
        $this->config_affichage->lang = $this->recupereLaLocaleDeLUtilisateur();
        $this->config_affichage->template = "template_pure";
    }

    private function recupereLaLocaleDeLUtilisateur()
    {
        if (MULTILINGUE_ACTIF) {
            if (isset($_SESSION["utilisateur"])) {
                if ($_SESSION["utilisateur"]["id_lang"] == 1)
                    return 'fr';
                else if ($_SESSION["utilisateur"]["id_lang"] == 2)
                    return 'en';
            } else {
                // pas de session ? on essaie de mettre la locale indiqué par le navigateur
                $locale = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
                if ($locale == "fr" || $locale == "en")
                    return $locale;
            }
            return 'fr';
        }
        return 'fr';
    }

    public function rendu($contenu)
    {
        //on defini ak pour pouvoir utiliser les méthodes du Helper ecrit traduit ect..
        $ak = $this->config_affichage;

        //on defini l'id du body de la page directement ici pour être tranquil après
        $ak->idbody = str_replace("/", "-", $ak->view);

        //Template de page
        require APP_ROOT . '/Views/' . $ak->template . '.php';
    }

    public function return_rendu(array $donnees = [])
    {
        //on defini ak pour pouvoir utiliser les méthodes du Helper ecrit traduit ect..
        $ak = $this->config_affichage;

        //on defini l'id du body de la page directement ici pour être tranquil après
        $ak->idbody = str_replace("/", "-", $ak->view);

        //on extrait le contenu des données
        extract($donnees);

        //on fait un buffer de sortie
        ob_start();
        //on va stocker le buffer dans la variable contenu

        //on insert la bonne views
        require APP_ROOT . '/Views/' . $ak->view . '.php';
        $rendu = ob_get_clean();

        //Transfère le buffer dans default->$contenu
        return $rendu;
    }

    public function return_json($donnees)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($donnees);
    }
}
