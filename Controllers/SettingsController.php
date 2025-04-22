<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\CollectionUtilisateur;
use FlowFinder\Models\Declencheur;
use FlowFinder\Helper\HelperIntegration;

class SettingsController extends Controller
{
    public function index()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }

        $id_utilisateur = $this->verif_authentification();
        //on defini déja les routes
        //$this->afficheur->config_affichage->template = 'template_connected';
        $this->afficheur->config_affichage->template = 'template_default-noindex';
        $this->afficheur->config_affichage->view = 'pages/settings';

        // A chaque rechargement de la page on met à jour certaines données
        $usersModel = new Utilisateur;
        $utilisateur = $usersModel->chercheUtilisateurParId($id_utilisateur);

        $base_url = "/settings";
        
        $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;
        
        //  la methode crée la collection par default qui est lié à l'utilisateur si celui-ci n'en a pas encore une
        $snippet_analytics = HelperIntegration::get_default_snippet_analytics_create_it_if_necessary($id_utilisateur);
        
        // on va trouver la collection attribué a cet utilisateur (pour l'instant 1 utilisateur = 1 collection)
        // et en extraire les déclencheurs
        $forms_declencheurs = [];
        $collectionUtilisateurModel = new CollectionUtilisateur();
        $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
        
        $declencheurModel = new Declencheur();
        $forms_declencheurs = $declencheurModel->SelectAllDeclencheursPourCollectionParTypeDeclencheur($collectionUtilisateur[0]->id_collection, 1);
        if($forms_declencheurs == null)
        {
            $forms_declencheurs = [];
        }

        $declencheurs_enregistrement_regexp = "*";
        $declencheurs_enregistrement = $declencheurModel->SelectAllDeclencheursPourCollectionParTypeDeclencheur($collectionUtilisateur[0]->id_collection, 2);
        // pour l'instant il n'y a qu'un declenheur d'enregistrement par collection
        if($declencheurs_enregistrement == null || count($declencheurs_enregistrement) == 0)
        {
            $declencheurModel->AjouteDeclencheur($collectionUtilisateur[0]->id_collection, 2, 0, 0, $declencheurs_enregistrement_regexp, 0, "");
        }
        else
        {
            $declencheurs_enregistrement_regexp = $declencheurs_enregistrement[0]->url_regexp;
        }
        

        return  $this->afficheur->return_rendu([
            "base_url" => $base_url,
            "forms_declencheurs" => $forms_declencheurs,
            "declencheurs_enregistrement_regexp" => $declencheurs_enregistrement_regexp,
            "snippet_analytics" => $snippet_analytics,
            "photo_disponible" => $utilisateur->photo_disponible == 1,
            "photo_url" => USER_FILES_URL . "/" . $id_utilisateur . "/logo_200.jpg"
        ]);
    }

    public function enregistre_declencheur_enregistrement()
    {
        if(!$this->verif_authentification(false))
        {
            return;
        }

        if(!isset($_POST["regexp"]))
        {
            return;
        }

        $id_utilisateur = $this->verif_authentification();

        $collectionUtilisateurModel = new CollectionUtilisateur();
        $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
        if($collectionUtilisateur != null && count($collectionUtilisateur) > 0)
        {
            $declencheurModel = new Declencheur();
            $declencheurs_enregistrement = $declencheurModel->SelectAllDeclencheursPourCollectionParTypeDeclencheur($collectionUtilisateur[0]->id_collection, 2);
            // pour l'instant il n'y a qu'un declenheur d'enregistrement par collection
            if($declencheurs_enregistrement != null && count($declencheurs_enregistrement) > 0)
            {
                $declencheurModel->UpdateDeclencheur($collectionUtilisateur[0]->id_collection, $declencheurs_enregistrement[0]->id_declencheur, 0, 0, $_POST["regexp"], 0, "");
            }
            else
            {
                $this->afficheur->return_json(array("success" => "error"));
                die();    
            }

            $this->afficheur->return_json(array("success" => "ok"));
            
        }
        else
        {
            $this->afficheur->return_json(array("success" => "error"));
        }

    }

    public function AfficheAjouteDeclencheurPopup()
    {
        $this->afficheur->config_affichage->view = 'pages/settings/popup_ajoute_declencheur';
        $this->afficheur->config_affichage->template = 'template_pure';
        
        return  $this->afficheur->return_rendu([]);
    }

    
    public function AfficheModifieDeclencheurPopup()
    {
        $this->afficheur->config_affichage->view = 'pages/settings/popup_modifie_declencheur';
        $this->afficheur->config_affichage->template = 'template_pure';
        //$this->afficheur->config_affichage->lang = 'en';
     
        $id_utilisateur = $this->verif_authentification();

        if (isset($_POST["id_declencheur"])) {
            
            $collectionUtilisateurModel = new CollectionUtilisateur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            if($collectionUtilisateur != null && count($collectionUtilisateur) > 0)
            {
                $declencheurModel = new Declencheur();
                $declencheur = $declencheurModel->SelectDeclencheurParIdEtIdCollection($_POST["id_declencheur"], $collectionUtilisateur[0]->id_collection);
                if($declencheur != null && $declencheur !== false )
                {
                    return  $this->afficheur->return_rendu([
                        "declencheur" => $declencheur
                    ]);
                }
            }

            
        } else {
            die();
        }
    }

    
    public function EnregistreAjouteDeclencheurPopup()
    {
        $id_utilisateur = $this->verif_authentification();

        if (isset($_POST["type_page"])) {
            
            $collectionUtilisateurModel = new CollectionUtilisateur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            $declencheurModel = new Declencheur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            if($collectionUtilisateur != null && count($collectionUtilisateur) > 0)
            {
                $declencheurModel = new Declencheur();
                $declencheurModel->AjouteDeclencheur($collectionUtilisateur[0]->id_collection, 1, $_POST["type_page"], $_POST["id_page_configuration"], $_POST["url_regexp"] == "" ? "*" : $_POST["url_regexp"], trim($_POST["seconds_delay"]) == "" ? 0 : $_POST["seconds_delay"], trim($_POST["inject_into_elem_id"]));
            }
            else
            {
                $this->afficheur->return_json(array("success" => "no"));
                return;
            }
            
            $this->afficheur->return_json(array("success" => "yes"));
            return;
        } else {
            $this->afficheur->return_json(array("success" => "no"));
            return;
        }
    }

    public function SupprimeDeclencheur()
    {
        $id_utilisateur = $this->verif_authentification();

        if (isset($_POST["id_declencheur"])) {
            
            $collectionUtilisateurModel = new CollectionUtilisateur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            $declencheurModel = new Declencheur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            if($collectionUtilisateur != null && count($collectionUtilisateur) > 0)
            {
                $declencheurModel = new Declencheur();
                $declencheurModel->SupprimeDeclencheur($collectionUtilisateur[0]->id_collection, $_POST["id_declencheur"]);
            }
            else
            {
                $this->afficheur->return_json(array("success" => "no"));
                return;
            }
            
            $this->afficheur->return_json(array("success" => "yes"));
            return;
        } else {
            $this->afficheur->return_json(array("success" => "no"));
            return;
        }
    }

    public function EnregistreModifieDeclencheurPopup()
    {
        $id_utilisateur = $this->verif_authentification();
        
        if (isset($_POST["id_declencheur"])) {
            $collectionUtilisateurModel = new CollectionUtilisateur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            $declencheurModel = new Declencheur();
            $collectionUtilisateur = $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($id_utilisateur);
            if($collectionUtilisateur != null && count($collectionUtilisateur) > 0)
            {
                $declencheurModel = new Declencheur();
                $declencheurModel->UpdateDeclencheur($collectionUtilisateur[0]->id_collection, $_POST["id_declencheur"], $_POST["type_page"], $_POST["id_page_configuration"], $_POST["url_regexp"] == "" ? "*" : $_POST["url_regexp"] , trim($_POST["seconds_delay"]) == "" ? 0 : $_POST["seconds_delay"], trim($_POST["inject_into_elem_id"]));
            }
            else
            {
                $this->afficheur->return_json(array("success" => "no"));
                return;
            }
            
            $this->afficheur->return_json(array("success" => "yes"));
            return;
        } else {
            $this->afficheur->return_json(array("success" => "no"));
            return;
        }
    }
}


