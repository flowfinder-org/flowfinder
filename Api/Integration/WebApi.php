<?php

namespace FlowFinder\Api\Integration;

use FlowFinder\Api\Api;
use FlowFinder\Helper\Helper;
use FlowFinder\Helper\HelperIntegration;
use FlowFinder\Helper\UserAgentParser;
use FlowFinder\Models\Collection;
use FlowFinder\Models\Visiteur;
use FlowFinder\Models\VisiteurSession;
use FlowFinder\Models\Declencheur;
use FlowFinder\Models\FormDonnees;
use FlowFinder\Models\VisiteurSessionFormDonnee;
use FlowFinder\Models\VisiteurSessionEvent;
use FlowFinder\Models\CollectionUtilisateur;

/*
* Point par lequel les sites de nos clients interagissent avec les collections
* http://localhost:8099/Api/Integration/Web/Endpoint
*/

class WebApi extends Api
{

    public function Endpoint()
    {
        header("Access-Control-Allow-Origin: *");
        // cette ligne n'est pas super, elle permet à n'importe qui d'envoyer des stats dans n'importe quel compte,
        // il faudrait faire un tri en s'inspirant du code ci-dessous;
        /*$allowed_domains = array(
            'http://domain1.com',
            'http://domain2.com',
        );
        if (in_array($http_origin, $allowed_domains))
        {  
            header("Access-Control-Allow-Origin: $http_origin");
        }*/

        if(!isset($_POST["action"]) || !($_POST["action"] == "init" || $_POST["action"] == "mark" || $_POST["action"] == "rec" || $_POST["action"] == "get_forms" || $_POST["action"] == "save_form"|| $_POST["action"] == "track_event"))
        {
            echo json_encode(["success" => "false", "raison" => "parameter action is not defined"]);
            exit();  
        }
        $action = $_POST["action"];

        if(!isset($_POST["traceur_uuid"]))
        {
            echo json_encode(["success" => "false", "raison" => "parameter traceur_uuid is not defined"]);
            exit();  
        }

        if($action == 'init')
        {
            // on va chercher la collection lié à ce traceur_uuid
            $collectionModel = new Collection();
            $collection = $collectionModel->getCollectionParTraceurUUID($_POST["traceur_uuid"]);
            if($collection == null || $collection == false)
            {
                echo json_encode(["success" => "false", "raison" => "le traceur_uuid ne correspond pas à une collection"]);
                exit();  
            }
            
            $visiteur_uuid = null;
            if(!isset($_POST['visiteur_uuid']) || $_POST["visiteur_uuid"] == "")
            {
                $visiteur_uuid = HelperIntegration::createUUIDVisiteur();
            }
            else
            {
                $visiteur_uuid = $_POST['visiteur_uuid'];
            }

            // nous avons maintenant un visiteur_uuid, celui-ci est-il déjà enregistré pour la collection lié au traceur_uuid en cours ?
            $visiteurModel = new Visiteur();
            $visiteur = $visiteurModel->getVisiteurParCollectionIdEtUUID($collection->id_collection, $visiteur_uuid);
            
            if($visiteur == null || $visiteur == false)
            {
                $visiteurModel->addVisiteurToCollection($collection->id_collection, $visiteur_uuid);
                $visiteur = $visiteurModel->getVisiteurParCollectionIdEtUUID($collection->id_collection, $visiteur_uuid);
            }


            // Si le visiteur n'a pas de session existante, en générer une nouvelle
            $visiteur_session_uuid = null;
            if (!isset($_POST['visiteur_session_uuid']) || $_POST['visiteur_session_uuid'] == "") {
                $visiteur_session_uuid = HelperIntegration::createUUIDVisiteurSession($visiteur->id_visiteur); // Générer un UUID pour la session
            } else {
                $visiteur_session_uuid = $_POST['visiteur_session_uuid']; // Utiliser celui passé dans la requête si existant
            }
            $visiteurSessionModel = new VisiteurSession();


            $url = null;
            if(isset($_POST['url']))
            {
                $url = $_POST['url'];
                if (strlen($url) > 1024) { $url = substr($url, 0, 1024 - 6) . "[...]"; }
            }
            $user_ip = $this->get_user_ip();
            if (strlen($user_ip) > 64) { $user_ip = substr($user_ip, 0, 64 - 6) . "[...]"; }

            $user_agent = $this->get_user_agent();
            if (strlen($user_agent) > 2048) { $user_agent = substr($user_agent, 0, 2048 - 6) . "[...]"; }

            // Récupérer les paramètres UTM depuis la requête
            $utm_source = isset($_POST['utm_source']) && trim($_POST['utm_source']) != "" ? $_POST['utm_source'] : null;
            if ($utm_source != null && strlen($utm_source) > 64) { $utm_source = substr($utm_source, 0, 64 - 6) . "[...]"; }

            $utm_medium = isset($_POST['utm_medium']) && trim($_POST['utm_medium']) != "" ? $_POST['utm_medium'] : null;
            if ($utm_medium != null && strlen($utm_medium) > 64) { $utm_medium = substr($utm_medium, 0, 64 - 6) . "[...]"; }

            $utm_campaign = isset($_POST['utm_campaign']) && trim($_POST['utm_campaign']) != "" ? $_POST['utm_campaign'] : null;
            if ($utm_campaign != null && strlen($utm_campaign) > 64) { $utm_campaign = substr($utm_campaign, 0, 64 - 6) . "[...]"; }

            $utm_term = isset($_POST['utm_term']) && trim($_POST['utm_term']) != "" ? $_POST['utm_term'] : null;
            if ($utm_term != null && strlen($utm_term) > 128) { $utm_term = substr($utm_term, 0, 128 - 6) . "[...]"; }

            $utm_content = isset($_POST['utm_content']) && trim($_POST['utm_content']) != "" ? $_POST['utm_content'] : null;
            if ($utm_content != null && strlen($utm_content) > 64) { $utm_content = substr($utm_content, 0, 64 - 6) . "[...]"; }

            $fbclid = isset($_POST['fbclid']) && trim($_POST['fbclid']) != "" ? ('fb.1.' . $_SERVER['REQUEST_TIME'] . "000." . $_POST['fbclid']) : null;
            if ($fbclid != null && strlen($fbclid) > 255) { $fbclid = substr($fbclid, 0, 255 - 6) . "[...]"; }
            
            // et le referer
            $referer = isset($_POST['referer']) && trim($_POST['referer']) != "" ? $_POST['referer'] : null;
            if ($referer != null && strlen($referer) > 1024) { $referer = substr($referer, 0, 1024 - 6) . "[...]"; }

            // Récupérer les info sur l'écran
            $screen_width = isset($_POST['screen_width']) && is_numeric($_POST['screen_width']) != "" ? (int)$_POST['screen_width'] : 0;
            $screen_height = isset($_POST['screen_height']) && is_numeric($_POST['screen_height']) != "" ? (int)$_POST['screen_height'] : 0;
            $window_width = isset($_POST['window_width']) && is_numeric($_POST['window_width']) != "" ? (int)$_POST['window_width'] : 0;
            $window_height = isset($_POST['window_height']) && is_numeric($_POST['window_height']) != "" ? (int)$_POST['window_height'] : 0;
            $pixel_ratio = isset($_POST['pixel_ratio']) && is_numeric($_POST['pixel_ratio']) != "" ? (float)$_POST['pixel_ratio'] : 0;
            $orientation = isset($_POST['orientation']) && ($_POST['orientation'] === 'portrait' || $_POST['orientation'] === 'landscape') ? $_POST['orientation'] : '';

            $maxSequenceIdRecord = $visiteurSessionModel->getVisiteurSessionMaxSequenceIdx($visiteur->id_visiteur, $visiteur_session_uuid);
            $visiteur_session_seq_idx = 0;
            if($maxSequenceIdRecord != null && $maxSequenceIdRecord != false && $maxSequenceIdRecord->max_sequence_index !== null)
            {
                $visiteur_session_seq_idx = $maxSequenceIdRecord->max_sequence_index + 1;
            }

            $userAgentObj = new UserAgentParser($user_agent);

            $visiteurSessionModel->addVisiteurSession(
                $visiteur->id_visiteur, $visiteur_session_uuid, $visiteur_session_seq_idx, $url, 
                $user_agent, $userAgentObj->os_family, $userAgentObj->os_version, $userAgentObj->device_type, $userAgentObj->browser_family, $userAgentObj->browser_version, $userAgentObj->device_brand, $userAgentObj->device_model,
                $user_ip,
                $utm_source, $utm_medium, $utm_campaign, $utm_term, $utm_content, $fbclid, $referer, 
                $screen_width, $screen_height, $window_width, $window_height, $pixel_ratio, $orientation
            );


            // on envoie l'url pour télécharger et déclencher rrweb uniquement si on a un déclencheur qui correspond à la page
            // et on va demander les déclencheur pour les formulaires et sondage en même temps
            $rrweb_script_url = null;
            $forms_declencheurs = [];
            $regexp_delimiter = "/"; // utilisé ci-dessous
            if($url != null && trim($url) != "")
            {
                $declencheurModel = new Declencheur();

                $declencheurs_enregistrement = $declencheurModel->SelectAllDeclencheursPourCollectionParTypeDeclencheur($collection->id_collection, 2);
                // pour l'instant il n'y a qu'un declenheur d'enregistrement par collection
                if($declencheurs_enregistrement != null && count($declencheurs_enregistrement) > 0)
                {
                    // est ce que l'url de la page en cours match la regexp
                    $regexp_with_delimiter = $regexp_delimiter . $declencheurs_enregistrement[0]->url_regexp . $regexp_delimiter;
                    if($declencheurs_enregistrement[0]->url_regexp != "" && ($declencheurs_enregistrement[0]->url_regexp == "*" || preg_match($regexp_with_delimiter, $url) === 1))
                    {
                        $rrweb_script_url = $this->getRrwebScriptUrl();
                    }
                }

                $forms_declencheurs_non_filtre = $declencheurModel->SelectAllDeclencheursPourCollectionParTypeDeclencheur($collection->id_collection, 1);
                if($forms_declencheurs_non_filtre != null && count($forms_declencheurs_non_filtre) > 0)
                {
                    foreach($forms_declencheurs_non_filtre as $dec)
                    {
                        $regexp_with_delimiter = $regexp_delimiter . $dec->url_regexp . $regexp_delimiter;
                        if($dec->url_regexp == "*" || preg_match($regexp_with_delimiter, $url) === 1)
                        {
                            array_push($forms_declencheurs, $dec);
                        }
                    }
                }
            }

            $this->getRrwebScriptUrl();

            echo json_encode([
                "success" => "true", 
                "visiteur_uuid" => $visiteur_uuid, 
                "visiteur_session_uuid" => $visiteur_session_uuid,
                "visiteur_session_seq_idx" => $visiteur_session_seq_idx,
                "rrweb_script_url" => $rrweb_script_url,
                "declencheurs" => $forms_declencheurs
            ]);
            exit();
        }
        else if($action == 'mark')
        {
            if(isset($_POST['visiteur_session_uuid']) && isset($_POST['visiteur_session_seq_idx']))
            {
                // y'a til vraiement une session visiteur qui porte cet uuid ?

                $visiteurSessionModel = new VisiteurSession();
                $visiteurSession = $visiteurSessionModel->getVisiteurSessionParUUIDEtSequenceIndex($_POST['visiteur_session_uuid'], $_POST['visiteur_session_seq_idx']);
                if($visiteurSession != null && $visiteurSession != false)
                {
                    $visiteurSessionModel->markAlive($visiteurSession->id_visiteur_session);
                }   
            }
            
            echo json_encode(["success" => "true"]);
            exit();
        }
        else if($action == 'track_event')
        {
            if(isset($_POST['visiteur_session_uuid']) && isset($_POST['visiteur_session_seq_idx']) && isset($_POST["event_name"]) && trim($_POST["event_name"]) != "" )
            {
                // y'a til vraiement une session visiteur qui porte cet uuid ?

                $visiteurSessionModel = new VisiteurSession();
                $visiteurSession = $visiteurSessionModel->getVisiteurSessionParUUIDEtSequenceIndex($_POST['visiteur_session_uuid'], $_POST['visiteur_session_seq_idx']);
                if($visiteurSession != null && $visiteurSession != false)
                {
                    // par securité on vérifie que le traceur_uuid est valide, pour éviter les DDOS de bots
                    // on en profite pour retrouver le id_utilisateur (propriétaire de la collection) et le id_collection
                    
                    $collection = null;
                    if(isset($_POST["traceur_uuid"]))
                    {
                        $collectionModel = new Collection();
                        $collection = $collectionModel->getCollectionParTraceurUUID($_POST["traceur_uuid"]);
                        if($collection == null || $collection == false)
                        {
                            echo json_encode(["success" => "false", "raison" => "le traceur_uuid ne correspond pas à une collection"]);
                            exit();  
                        }
                    }
                    
                    // et le session_uuid doit aussi être valide, on en profite pour retrouver le visiteur->id_visiteur
                    $visiteurSession = null;
                    if(isset($_POST['visiteur_session_uuid']) && isset($_POST['visiteur_session_seq_idx']))
                    {
                        // y'a til vraiement une session visiteur qui porte cet uuid ?
                        $visiteurSessionModel = new VisiteurSession();
                        $visiteurSession = $visiteurSessionModel->getVisiteurSessionParUUIDEtSequenceIndex($_POST['visiteur_session_uuid'], $_POST['visiteur_session_seq_idx']);
                        if($visiteurSession == null || $visiteurSession == false)
                        {
                            echo json_encode(["success" => "false", "raison" => "le visiteur_session_uuid ne correspond pas à une session de cette collection"]);
                            exit();  
                        }   
                    }

                    $eventName = mb_substr(trim($_POST["event_name"]), 0, 31);
                    $eventValue = "";
                    if(isset($_POST["event_value"]) && trim($_POST["event_value"]) != "")
                    {
                        $eventValue = mb_substr(trim($_POST["event_value"]), 0, 255);
                    }


                    $visiteurSessionEventsModel = new VisiteurSessionEvent();
                    $visiteurSessionEventsModel->insert($collection->id_collection, $visiteurSession->id_visiteur_session, $eventName, $eventValue);
                }   
            }
            
            echo json_encode(["success" => "true"]);
            exit();
        }
        else if($action == "rec")
        {
            // par securité on vérifie que le traceur_uuid est valide, pour éviter les DDOS de bots
            // on en profite pour retrouver le id_utilisateur (propriétaire de la collection) et le id_collection
            $collection = null;
            if(isset($_POST["traceur_uuid"]))
            {
                $collectionModel = new Collection();
                $collection = $collectionModel->getCollectionParTraceurUUID($_POST["traceur_uuid"]);
                if($collection == null || $collection == false)
                {
                    echo json_encode(["success" => "false", "raison" => "le traceur_uuid ne correspond pas à une collection"]);
                    exit();  
                }
            }
            // et le session_uuid doit aussi être valide, on en profite pour retrouver le visiteur->id_visiteur

            $visiteurSession = null;
            if(isset($_POST['visiteur_session_uuid']) && isset($_POST['visiteur_session_seq_idx']))
            {
                // y'a til vraiement une session visiteur qui porte cet uuid ?
                $visiteurSessionModel = new VisiteurSession();
                $visiteurSession = $visiteurSessionModel->getVisiteurSessionParUUIDEtSequenceIndex($_POST['visiteur_session_uuid'], $_POST['visiteur_session_seq_idx']);
                if($visiteurSession == null || $visiteurSession == false)
                {
                    echo json_encode(["success" => "false", "raison" => "le visiteur_session_uuid ne correspond pas à une session de cette collection"]);
                    exit();  
                }   
            }
            
            $event_timestamp = $_POST["event_timestamp"];
            
            // stockage des données de la session coté serveur dans le dossier de session
            $visiteur_session_folder_path = USER_FILES_FOLDER_PRIVATE . DS . 'collections' . DS . $collection->id_collection . DS . 'visiteur_sessions' . DS . $visiteurSession->id_visiteur_session;
            if(!is_dir($visiteur_session_folder_path))
            {
                mkdir($visiteur_session_folder_path, 0770, true);
            }

            $is_compressed_gzip = strlen($_POST["event"]) > 500; // on compress si l'event est plus que 500 characters
            
            // on stock les données de cet event rrweb dans un fichier qui porte le numero du timestamp
            $json_event_filename = $visiteur_session_folder_path . DS . $event_timestamp . ".json";
            if($is_compressed_gzip)
            {
                $json_event_filename .= ".gz";
            }
            
            // parfois on a des appel web en doublon, pour éviter de tels cas nous allons déjà créer sur le disque une fichier vide qui servira de lock,
            // ensuite on va le remplir. Evidement, il faut commencer par vérifier qu'il n'y a pas de fichier existant avec le même nom avant de commencer.

            $deja_sur_disque = false;
            if(file_exists($json_event_filename))
            {
                $deja_sur_disque = true;
            }

            if(!$deja_sur_disque)
            {

                // on commence par ecrire un fichier vide, sa présence sur le disque servira de lock au cas ou un autre thread php tente de faire le même enregistrement en doublon
                $json_event_file_handle_temp = fopen($json_event_filename, 'w');
                if ($json_event_file_handle_temp) {
                    // Ajoute l'événement suivi d'une nouvelle ligne pour faciliter la lecture
                    fwrite($json_event_file_handle_temp, "");
                    
                    fclose($json_event_file_handle_temp);
                }

                // maintenant on peut travailler sans s'inquéter qu'un autre thread écrive dans le même fichier
                $json_event_file_handle = fopen($json_event_filename, 'w');
                if ($json_event_file_handle) {
                    // Ajoute l'événement suivi d'une nouvelle ligne pour faciliter la lecture
                    if($is_compressed_gzip)
                    {
                        fwrite($json_event_file_handle, gzencode($_POST['event'], 6));
                    }
                    else
                    {
                        fwrite($json_event_file_handle, $_POST['event']);
                    }
                    
                    fclose($json_event_file_handle);
                } else {
                    http_response_code(500);
                    echo json_encode(["status" => "error", "message" => "impossible d'enregistrer les données de rrweb."]);
                    exit();
                }            
    
                // on indique dans le fichier timestamps.txt a la base du dossier ce nouveau timestamps reçu
                
                $timestamps_filename = $visiteur_session_folder_path . DS . "timestamps.txt"; // Remplacez par le chemin de votre fichier
                $timestamps_handle = fopen($timestamps_filename, 'a');
                if ($timestamps_handle) {
                    // Écrit le timestamp suivi d'une nouvelle ligne
                    if($is_compressed_gzip)
                    {
                        fwrite($timestamps_handle, $event_timestamp . " gz" . PHP_EOL);
                    }
                    else
                    {
                        fwrite($timestamps_handle, $event_timestamp . " fl" . PHP_EOL);
                    }
                    fclose($timestamps_handle);
                } else {
                    http_response_code(500);
                    echo json_encode(["status" => "error", "message" => "impossible d'enregistrer l'index de timestamp."]);
                }
            }

            echo json_encode(["success" => "true"]);
            exit();
        }
        else if($action == 'get_forms')
        {
            if(!isset($_POST["forms_info"]))
            {
                echo json_encode(["success" => "false", "raison" => "parameter forms_info is not defined"]);
                exit();  
            }
            
            // on va chercher la collection lié à ce traceur_uuid
            $collectionModel = new Collection();
            $collection = $collectionModel->getCollectionParTraceurUUID($_POST["traceur_uuid"]);
            if($collection == null || $collection == false)
            {
                echo json_encode(["success" => "false", "raison" => "le traceur_uuid ne correspond pas à une collection"]);
                exit();  
            }

            $forms_info = $_POST["forms_info"];
            $helperIntegration = new HelperIntegration();
            $ensembleConstructionFormulaires = $helperIntegration->GetEnsembleDeConstructionFormulaires($collection->id_collection, $forms_info);

            echo json_encode(["success" => "true", "ensemble_construction_formulaires" => $ensembleConstructionFormulaires]);
            exit();  

        }
        else if($action == 'save_form')
        {
            // par securité on vérifie que le traceur_uuid est valide, pour éviter les DDOS de bots
            // on en profite pour retrouver le id_utilisateur (propriétaire de la collection) et le id_collection
            
            $collection = null;
            if(isset($_POST["traceur_uuid"]))
            {
                $collectionModel = new Collection();
                $collection = $collectionModel->getCollectionParTraceurUUID($_POST["traceur_uuid"]);
                if($collection == null || $collection == false)
                {
                    echo json_encode(["success" => "false", "raison" => "le traceur_uuid ne correspond pas à une collection"]);
                    exit();  
                }
            }
            
            if($_POST["id_collection"] != $collection->id_collection)
            {
                echo json_encode(["success" => "false", "raison" => "le id_collection ne correspond pas à la collection de ce traceur_uuid"]);
                exit();  
            }

            // et le session_uuid doit aussi être valide, on en profite pour retrouver le visiteur->id_visiteur
            $visiteurSession = null;
            if(isset($_POST['visiteur_session_uuid']) && isset($_POST['visiteur_session_seq_idx']))
            {
                // y'a til vraiement une session visiteur qui porte cet uuid ?
                $visiteurSessionModel = new VisiteurSession();
                $visiteurSession = $visiteurSessionModel->getVisiteurSessionParUUIDEtSequenceIndex($_POST['visiteur_session_uuid'], $_POST['visiteur_session_seq_idx']);
                if($visiteurSession == null || $visiteurSession == false)
                {
                    echo json_encode(["success" => "false", "raison" => "le visiteur_session_uuid ne correspond pas à une session de cette collection"]);
                    exit();  
                }   
            }

            // on retrouve le proprietaire de la collection, cela est requis historiquement par la depednece à la table formdonnees enver utilisateur_id (qui est le propriétaire du form)
            $collectionUtilisateurModel = new CollectionUtilisateur();
            $collectionsUtilisateur =  $collectionUtilisateurModel->getParIdCollection($collection->id_collection);
            $id_utilisateur_proprietaire = $collectionsUtilisateur[0]->id_utilisateur; // la requete renvoie par ordre de creation et le premier utilisateur lié a la collection en est le proprietaire

            $evenement = new FormDonnees();
            $id_formdonnees = $evenement->insertion_donnees($id_utilisateur_proprietaire, $_POST["type_page"], $_POST["id_page_configuration"], $_POST["json_values"]); // le type est forcé sur 2 [tunnel] car cette methode n'est appelé que par les formulaires inséeré avec "insert_tunnel"

            $visiteurSessionFormdonneesModel = new VisiteurSessionFormDonnee();
            $visiteurSessionFormdonneesModel->insert($collection->id_collection, $visiteurSession->id_visiteur_session, $id_formdonnees);

            // On envoie un email au gestionnaire du compte
            $formdonnees = json_decode($_POST["json_values"]);

            $type_page_nom = "formulaire";
            switch ($_POST["type_page"]) {
                case 1:
                    $type_page_nom = "portail";
                    break;
    
                case 2:
                    $type_page_nom = "tunnel";
                    break;
    
                case 3:
                    $type_page_nom = "sondage";
                    break;
    
                case 4:
                    $type_page_nom = "feedback";
                    break;
    
                case 5:
                    $type_page_nom = "formulaire";
                    break;
                }

            $date_soumission = (new Helper())->retourneDateUTCVersTimezoneAffichageFormatJourLong(gmdate("Y-m-d\TH:i:s\Z"));
            $date_soumission .= " à " . (new Helper())->retourneHeureUTCVersTimezoneAffichage(gmdate("Y-m-d\TH:i:s\Z"));
            $mail_content_html = "<br/>Date de soummission : " . $date_soumission . "<br/>Numero de " . $type_page_nom . " : " .  $_POST["id_page_configuration"] . "<br/>";
            $mail_content_text = "\nDate de soumission :" . $date_soumission . "\nNumero de " . $type_page_nom . " : " .  $_POST["id_page_configuration"] . "\n";
            foreach ($formdonnees as $kvPairIndex) {
                $kvPair = $kvPairIndex;
                if (is_array($kvPair->value)) {
                    $mail_content_text .= "\n  " . $kvPair->label . " : " . implode(';', $kvPair->value);
                    $mail_content_html .= "<br/> " . $kvPair->label . " : " . implode(';', $kvPair->value);
                } else {
                    $mail_content_text .= "\n  " . $kvPair->label . " : " . $kvPair->value;
                    $mail_content_html .= "<br/> " . $kvPair->label . " : " . $kvPair->value;
                }
            }

            echo json_encode(["success" => "true"]);
            exit();
        }

    }

    private function getRrwebScriptUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $base_url = $protocol . "://" . $host;
        $rrweb_url = $base_url . "/ressources/js/rrweb/rrweb-record.min.js" . "?version=" . ASSETS_VERSION_FOR_NOCACHE; // Remplacez par le chemin réel sur votre serveur
        return $rrweb_url;
    }

    private function get_user_ip()
    {
        $ip = "";
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    private function get_user_agent()
    {
        $userAgent = "";
        if(isset($_SERVER['HTTP_USER_AGENT']))
        {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $userAgent = strlen($userAgent) > 2042 ? substr($userAgent, 0 ,2042). "..." : $userAgent;
        }
        
        return $userAgent;
    }

}

?>