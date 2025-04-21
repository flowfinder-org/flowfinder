<?php

namespace FlowFinder\Helper;

use FlowFinder\Core\Afficheur;
use FlowFinder\Helper\Helper;
use FlowFinder\Models\Theme;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\Collection;
use FlowFinder\Models\CollectionUtilisateur;
use FlowFinder\Models\PageConfiguration;
use FlowFinder\Models\Element;

class HelperIntegration
{

    private static $snippet_template = "<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        tool_id: 'flowfinder_analytics',
        traceur_uuid: '[[TRACEUR_UUID]]',
        endpoint: '". BASE_URL ."/Api/Integration/Web/Endpoint'
    });
</script>
<script src=\"". BASE_URL ."/ressources/js/integration_web.js\"></script>";

    public static function get_default_snippet_analytics_create_it_if_necessary($utilisateur_id)
    {
        $collectionUtilisateurModel = new CollectionUtilisateur();
        $collectionsUtilisateur =  $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($utilisateur_id);

        $collectionModel = new Collection();

        if($collectionsUtilisateur == null || count($collectionsUtilisateur) == 0)
        {
            // pas encore de collection pour cet utilisateur ?
            // on va en créer une
            $collection_id = $collectionModel->addCollection("default", HelperIntegration::createUUIDCollection($utilisateur_id));

            $collection_utilisateur_id = $collectionUtilisateurModel->addCollectionUtilisateur($utilisateur_id, $collection_id);
            
            // maintenant on récupere à nouveau les collections de l'utilisateur
            $collectionsUtilisateur =  $collectionUtilisateurModel->getIdsCollectionsPourUtilisateur($utilisateur_id);
            if($collectionsUtilisateur == null || count($collectionsUtilisateur) == 0)
            {
                throw new \Exception("Impossible de créer une collection par défaut pour l'utilisateur ". $utilisateur_id . ". Une Exception à été levée.");
            }
        }

        $collection = $collectionModel->getCollection($collectionsUtilisateur[0]->id_collection);
        $collection_uuid = $collection->traceur_uuid;
        
        $snippet = str_replace("[[TRACEUR_UUID]]", $collection_uuid, HelperIntegration::$snippet_template);
        
        return $snippet;
    }

    public function GetEnsembleDeConstructionFormulaires(int $id_collection, string $forms_info_json)
    {
        // premierement on retrouve le proprietaire de la collection car on ne retournera uniquement que des formulaire pour lequel le proprietaire est valide

        $collectionUtilisateurModel = new CollectionUtilisateur();
        $collectionsUtilisateur =  $collectionUtilisateurModel->getParIdCollection($id_collection);
        $id_utilisateur_proprietaire = $collectionsUtilisateur[0]->id_utilisateur; // la requete renvoie par ordre de creation et le premier utilisateur lié a la collection en est le proprietaire
        $pageConfigurationModel = new PageConfiguration();
        
        // on va extraire les formulaires
        $ensemble_construction = []; 
        $ensemble_construction["forms"] = [];
        $forms_info = json_decode($forms_info_json);
        foreach($forms_info as $form_info)
        {
            $json_configuration_page = $pageConfigurationModel->select_pageconfiguration($form_info->id_page_configuration, $id_utilisateur_proprietaire, $form_info->type_page);
            $html_formulaire = "";
            if($json_configuration_page!= null && $json_configuration_page!= false)
            {
                //verifier proprietaire
                $usersModel = new Utilisateur;
                $utilisateur = $usersModel->chercheUtilisateurParId($id_utilisateur_proprietaire);

                // on recherche le design sous lequel le formulaire va apparaitre
                $design_affichage = "integre";
                $config = json_decode($json_configuration_page->json_configuration_page);
                if(isset($config->Design) && isset($config->Design->Select))
                {
                    $design_affichage = $config->Design->Select;
                }
                
                $elements = new Element();
                $json_elements = $elements->select_all_elements($id_utilisateur_proprietaire, $form_info->type_page, $form_info->id_page_configuration);
                $afficheur = new Afficheur;
                $afficheur->config_affichage->template = 'template_pure';
                $afficheur->config_affichage->view = 'client/public/tunnel_public';
                $html_formulaire = $afficheur->return_rendu([
                    'id_utilisateur' => $id_utilisateur_proprietaire,
                    'id_utilisateur_proprietaire_tunnel' => $id_utilisateur_proprietaire,
                    'numero_tunnel' => $form_info->id_page_configuration,
                    'json_configuration_page' => $json_configuration_page,
                    'json_elements' => $json_elements,
                    "photo_disponible" => $utilisateur->photo_disponible == 1,
                    "photo_url" => USER_FILES_URL . "/" . $id_utilisateur_proprietaire . "/logo_200.jpg",
                    "integration_web" => true
                ]);   
            }

            array_push ($ensemble_construction["forms"], [
                "html" => $html_formulaire,
                "id_page_configuration" => $form_info->id_page_configuration,
                "type_page" => $form_info->type_page,
                "id_collection" => $form_info->id_collection,
                "type_page" => $form_info->type_page,
                "seconds_delay" => $form_info->seconds_delay,
                "inject_into_elem_id" => $form_info->inject_into_elem_id,
                "design_affichage" => $design_affichage
            ]);
        }
        

        // on jecte directement le contenu des fichier js et css requis, on ne peut pas renvoyer les url car not client peuvent vouloir cacher les url flowfinder
        $ensemble_construction["js_requires"] = 
        [
            file_get_contents(APP_ROOT . DS . 'public' . DS . 'ressources' . DS . 'js' . DS . "public_page_integration.js"),
            file_get_contents(APP_ROOT . DS . 'public' . DS . 'ressources' . DS . 'js' . DS . "public_page_templates.js")
        ];

        $ensemble_construction["css_requires"] = 
        [
            file_get_contents(APP_ROOT . DS . 'public' . DS . 'ressources' . DS . 'css' . DS . "flowfinder-pagepublic.css"),
            file_get_contents(APP_ROOT . DS . 'public' . DS . 'ressources' . DS . 'css' . DS . "flowfinder-pagepublic-integration.css")
        ];

        $ensemble_construction["themes"] = 
        [
            "1" => HelperIntegration::getTheme($id_utilisateur_proprietaire, "portail", "perso"),
            "2" => HelperIntegration::getTheme($id_utilisateur_proprietaire, "tunnel", "perso"),
            "3" => HelperIntegration::getTheme($id_utilisateur_proprietaire, "sondage", "perso"),
            "4" => HelperIntegration::getTheme($id_utilisateur_proprietaire, "feedback", "perso"),
            "5" => HelperIntegration::getTheme($id_utilisateur_proprietaire, "formulaire", "perso")
        ];
        

        return $ensemble_construction;

    }

    private static function getTheme($id_utilisateur, $theme_page_type, $theme_nom)
    {
        $Theme = new Theme();
        $result = $Theme->selectcsstheme($id_utilisateur, $theme_page_type, $theme_nom);
        // Vérifier si des résultats ont été trouvés
        if ($result) {
            // Il existe
            return $result;
            exit();
        } else {
            //chargé le thème par default
            $theme_nom = "default";
            $result = $Theme->selectcsstheme(0, $theme_page_type, $theme_nom);
            return $result;
            exit();
        }
    }

    private static function createUUIDCollection($utilisateur_id){
        
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return "gcol-" . $utilisateur_id . "-" . $uuid; // on ajoute le utilisateur_id pour éviter tou risque de collision MD5 entre plusieurs de nos clients
    }

    public static function createUUIDVisiteur(){
        
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return "glav-" . $uuid;
    }

    public static function createUUIDVisiteurSession($id_visiteur){
        
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return "glas-" . $id_visiteur . "-" .$uuid; // on ajoute le viiteur_id pour éviter tou risque de collision MD5 entre plusieurs visiteur, sinon nos clients pourraient se retrouver avec des données d'autres visiteurs
    }

}