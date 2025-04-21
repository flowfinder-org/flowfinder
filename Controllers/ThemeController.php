<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\Theme;
use Exception;

class ThemeController extends Controller
{
    public function index()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
    }

    public function save_theme()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }

        if (isset($_POST["themepage_type"])) {
            $theme_page_type = $_POST["themepage_type"];
        }

        $stylecss = json_decode($_POST['stylecss'], true);
        if ($stylecss !== null && $theme_page_type !== null) {
            $id_utilisateur = $id_utilisateur = $_SESSION["utilisateur"]["id"];
            $theme_nom = "perso";

            $Theme = new Theme();

            //verifier si il existe deja
            $result = $Theme->selectthemepage($id_utilisateur, $theme_page_type);
            // Vérifier si des résultats ont été trouvés
            if ($result) {
                // Il existe deja
                $Theme->updatetheme($id_utilisateur, $theme_page_type, $theme_nom, $stylecss);
            } else {
                //il n'existe pas il faut le créer
                $Theme->createtheme($id_utilisateur, $theme_page_type, $theme_nom, $stylecss);
            }
        } else {
            throw new Exception("Imossible de sauver les données du theme: $ POST['stylecss'] ou $ POST['themepage_type'] non valide !");
        }
    }

    public function charge_theme()
    {
        if (isset($_POST["themenom"]) && isset($_POST["themepage_type"])) {
            $theme_nom = $_POST["themenom"];
            $theme_page_type = $_POST["themepage_type"];

            //$stylecss = json _decode($_POST['stylecss'], true);
            if ($theme_page_type !== null) {
                if ($theme_nom == 'default') {
                    //prend le theme créer par flowfinder
                    $id_utilisateur = 0;
                }
                if ($_POST["themepublic"] == "true") {
                    $id_utilisateur = $_POST["id_proprietaire_theme"];
                }else{
                    if ($_SESSION["utilisateur"]["id"]) {
                        $id_utilisateur = $_SESSION["utilisateur"]["id"];
                    }
                }

                $Theme = new Theme();
                $result = $Theme->selectcsstheme($id_utilisateur, $theme_page_type, $theme_nom);
                // Vérifier si des résultats ont été trouvés
                if ($result) {
                    // Il existe
                    return json_encode($result);
                    exit();
                } else {
                    //chargé le thème par default
                    $theme_nom = "default";
                    $result = $Theme->selectcsstheme(0, $theme_page_type, $theme_nom);
                    return json_encode($result);
                    exit();
                }
            } else {
                throw new Exception("Imossible de charger les données du theme: $ POST['themepage_type'] non valide !");
            }
        }
    }
}
