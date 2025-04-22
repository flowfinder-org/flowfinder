<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Models\CollectionUtilisateur;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\PageConfiguration;
use FlowFinder\Models\VisiteurSession;
use FlowFinder\Models\Visiteur;
use FlowFinder\Models\Collection;
use FlowFinder\Helper\GeoIpLocator;


class SessionsReplaysController extends Controller
{

    public function list(?array $params = null)
    {
        $id_utilisateur = $this->verif_authentification();

        $this->afficheur->config_affichage->template = 'template_connected';
        $this->afficheur->config_affichage->view = 'pages/sessionsreplays/list';

        /* pour le panneau menu_gauche */
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->chercheUtilisateurParId($id_utilisateur);

        //statut paiement pour redirection vers la page de page/tarif formule 
        $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;

        //trouver formule en cours
        $NumeroFormule = 1;

        //Partie qui devra être transféré dans un controller menu gauche afin de ne pas à avoir à tout répéter
        $ModelTunnel = new PageConfiguration;
        $listeTunnel = $ModelTunnel->select_all_listetunnel($id_utilisateur, 1);
        $listeSondage = $ModelTunnel->select_all_listetunnel($id_utilisateur, 3);
        $listeFeedback = $ModelTunnel->select_all_listetunnel($id_utilisateur, 4);
        $listeFormulaire = $ModelTunnel->select_all_listetunnel($id_utilisateur, 5);


        /*
        Calcul les geoip , cette appel fait la mise à jour de toutes les GeoIP, même celles des autres comptes clients, il faudrait adapter ceci
        */
        GeoIpLocator::CalculGeoIpManquantes();

        /*
        * On hache les IP pour être compatible avec la RGPD
        */
        $visiteurSessionModel = new VisiteurSession();
        $visiteurSessionModel->hashIPNotYetHashed();

        /*
        Préparation du filtrage
        */
        if (!isset($params["filtre_date_start"]) || !$this->isValidDateString($params["filtre_date_start"])) {
            $params["filtre_date_start"] = date('Y-m-d', strtotime('-7 days'));
        }
        if (!isset($params["filtre_date_end"]) || !$this->isValidDateString($params["filtre_date_end"])) {
            $params["filtre_date_end"] = date('Y-m-d');;
        }

        if (!isset($params["filtre_utm_campaign"]) || $params["filtre_utm_campaign"] == "aucun") {
            $params["filtre_utm_campaign"] = "";
        }

        // on extrait tous les utm_campaign de ce compte pour la période donnée
        $query_utm_campaign = "
        SELECT DISTINCT vs.utm_campaign, 
        YEAR(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')) as annee, 
        WEEK(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris'), 3),
        WEEKDAY(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')),
        HOUR(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')) 
        FROM collections_utilisateurs cu 
        LEFT JOIN collections c on c.id_collection = cu.id_collection
        LEFT JOIN visiteurs v on v.id_collection = c.id_collection
        LEFT JOIN visiteur_sessions vs on vs.id_visiteur = v.id_visiteur
        WHERE cu.id_utilisateur = " . $id_utilisateur . " AND vs.date_creation BETWEEN '" . $params["filtre_date_start"] . " 00:00' AND '" . $params["filtre_date_end"] . " 23:59:59'
        ORDER BY 
            YEAR(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')) DESC, 
            WEEK(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris'), 3) DESC, 
            WEEKDAY(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')) ASC
        ;
        ";
        $results_utm_campaign = (new Collection())->requete($query_utm_campaign)->fetchAll();
        $utm_campaigns = ["aucun" => 1]; // on utilisae un tableau associatif pour accélérer la recherche isset() dans le if suivant
        foreach ($results_utm_campaign as $result_utm_campaign) {
            if ($result_utm_campaign->utm_campaign != null && trim($result_utm_campaign->utm_campaign) != "" && !isset($utm_campaigns[$result_utm_campaign->utm_campaign])) {
                $utm_campaigns[$result_utm_campaign->utm_campaign] = 1;
            }
        }
        /*
        On va afficher les sessions des visiteurs de la collection par défault dont cet utilisateur est propriétaire
        */
        //$visiteursSessions = $visiteurSessionModel->getVisiteursSessionsPourUtilisateur($id_utilisateur, 0, 100);
        $visiteursSessions = $visiteurSessionModel->getVisiteursSessionsPourUtilisateurFiltre($id_utilisateur, $params["filtre_utm_campaign"], $params["filtre_date_start"] . " 00:00", $params["filtre_date_end"] . " 23:59:59");

        return  $this->afficheur->return_rendu([
            
            /* pour le panneau menu_gauche */
            "id_utilisateur" => $id_utilisateur,
            "prenom" => $utilisateur->prenom,
            "nom" => $utilisateur->nom,    
            "Numero_Formule" => $NumeroFormule,
            'listetunnel' => $listeTunnel,
            'listesondage' => $listeSondage,
            'listefeedback' => $listeFeedback,
            'listeformulaire' => $listeFormulaire,
            "affiche_lien_demande_creation_compte_preprempli" => false,
            "conversation_encours" => false,
                
            /* pour les stats */
            "utm_campaigns" => $utm_campaigns,
            "filtre_utm_campaign" => $params["filtre_utm_campaign"] != "" ? $params["filtre_utm_campaign"] : "aucun",
            "filtre_date_start" => $params["filtre_date_start"],
            "filtre_date_end" => $params["filtre_date_end"],
            "visiteurs_session" => $visiteursSessions,
            "statut_paiement" => $utilisateur->statut_paiement
        ]);
    }

    public function player(?array $params = null)
    {
        $id_utilisateur = $this->verif_authentification();

        $this->afficheur->config_affichage->template = 'template_connected';
        $this->afficheur->config_affichage->view = 'pages/sessionsreplays/player';


        /* pour le panneau menu_gauche */
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->chercheUtilisateurParId($id_utilisateur);

        //statut paiement pour redirection vers la page de page/tarif formule 
        $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;
        
        //trouver formule en cours
        $NumeroFormule = 1;

        //Partie qui devra être transféré dans un controller menu gauche afin de ne pas à avoir à tout répéter
        $ModelTunnel = new PageConfiguration;
        $listeTunnel = $ModelTunnel->select_all_listetunnel($id_utilisateur, 1);
        $listeSondage = $ModelTunnel->select_all_listetunnel($id_utilisateur, 3);
        $listeFeedback = $ModelTunnel->select_all_listetunnel($id_utilisateur, 4);
        $listeFormulaire = $ModelTunnel->select_all_listetunnel($id_utilisateur, 5);

        /* pour le player */
        $id_visiteur_session = $params["id_visiteur_session"];

        // on va devoir retrouver le la collection de cette session pour savoir ou elle est stockée
        // et retourver aussi son utilisateur_id propriétaire pour nous assurer de droit d'accès
        $visiteurSession = null;
        $visiteurSessionModel = new VisiteurSession();
        $visiteurSession = $visiteurSessionModel->getVisiteurSessionParId($id_visiteur_session);
        if($visiteurSession == null || $visiteurSession == false)
        {
            echo json_encode(["success" => "false", "raison" => "le visiteur_session_id ne correspond pas à une session"]);
            exit();
        }
    

        $sessionSequences = $visiteurSessionModel->getVisiteurSessionsParSessionUUID($visiteurSession->visiteur_session_uuid);

        $visiteurModel = new Visiteur();
        $visiteur = $visiteurModel->getVisiteurParId($visiteurSession->id_visiteur);
        if($visiteur == null || $visiteur == false)
        {
            echo json_encode(["success" => "false", "raison" => "le visiteur_id de cette visiteur_session n'existe pas'"]);
            exit();
        }

        $collectionUtilisateurModel = new CollectionUtilisateur();
        $collectionUtilisateur = $collectionUtilisateurModel->getCollectionUtilisateurParCollectionIdEtUtilisateurId($id_utilisateur, $visiteur->id_collection);
        if($collectionUtilisateur == null || $collectionUtilisateur == false)
        {
            echo json_encode(["success" => "false", "raison" => "l'utilisateur courant n'a pas de droit d'accès à la collection contenant cette visiteur_session"]);
            exit();  
        }

        $visiteur_session_folder_path = USER_FILES_FOLDER_PRIVATE . DS . 'collections' . DS . $collectionUtilisateur->id_collection . DS . 'visiteur_sessions' . DS . $visiteurSession->id_visiteur_session;
        if(!is_dir($visiteur_session_folder_path))
        {
            echo json_encode(["success" => "false", "raison" => "impossible de charger les données de la session visiteur (erreur 001)"]);
            exit();  
        }

        // on lit dans le fichier timestamps.txt a la base du dossier les fichier a charger
        $timestamps_filename = $visiteur_session_folder_path . DS . "timestamps.txt"; // Remplacez par le chemin de votre fichier
        $timestamps_handle = fopen($timestamps_filename, 'r');
        if (!$timestamps_handle) 
        {
            echo json_encode(["success" => "false", "raison" => "impossible de charger les données de la session visiteur (erreur 002)"]);
            exit();  
        }

        // le contenu des fichiers est dejà encodé en json, on doit les combiner dans un tableau mais on
        // ne peut pas decoder pui ré-encoder le json, c'est beaucoup trop gourmand en CPU , on va donc 
        // faire le tableau json à la main en concaténant "[" "," et "]"
        $visiteursSessionsEventsJSONEncoded = "["; 
        $firstTimestampFileRead = true;
        while (($line = fgets($timestamps_handle)) !== false) 
        {
            $line = trim($line); // Supprimer les espaces et les sauts de ligne
            if (!empty($line)) {
                $line_parts = explode(' ', $line);
                $timestamp = $line_parts[0];
                $compression_mode = $line_parts[1];
                $json_event_filename = $visiteur_session_folder_path . DS . $timestamp . ".json";
                
                // on ajoute une virgule pour separer les item deja encodé en json
                if($firstTimestampFileRead)
                {
                    $firstTimestampFileRead = false;
                }
                else
                {
                    $visiteursSessionsEventsJSONEncoded .= ',';
                }

                if($compression_mode == "fl") // fl pour flat
                {    
                    if(!file_exists($json_event_filename)) 
                    {
                        echo json_encode(["success" => "false", "raison" => "impossible de charger les données pour le timestamp '" . $timestamp . "' (erreur 003)"]);
                        exit();  
                    }
                    $visiteursSessionsEventsJSONEncoded .= file_get_contents($json_event_filename);
                }
                else if($compression_mode == "gz")
                {
                    $json_event_filename .= ".gz";
                    if(!file_exists($json_event_filename)) 
                    {
                        echo json_encode(["success" => "false", "raison" => "impossible de charger les données pour le timestamp '" . $timestamp . "' (erreur 004)"]);
                        exit();  
                    }
                    $visiteursSessionsEventsJSONEncoded .= gzdecode(file_get_contents($json_event_filename));
                }
            }
        }
        fclose($timestamps_handle);
    
        $visiteursSessionsEventsJSONEncoded .= "]";


        return  $this->afficheur->return_rendu([
            /* pour le panneau menu_gauche */
            "id_utilisateur" => $id_utilisateur,
            "prenom" => $utilisateur->prenom,
            "nom" => $utilisateur->nom,    
            "Numero_Formule" => $NumeroFormule,
            'listetunnel' => $listeTunnel,
            'listesondage' => $listeSondage,
            'listefeedback' => $listeFeedback,
            'listeformulaire' => $listeFormulaire,
            "affiche_lien_demande_creation_compte_preprempli" => false,
            "conversation_encours" => false,
                
            /* pour les stats */
            "visiteur_session" => $visiteurSession,
            "session_sequences" => $sessionSequences,
            "visiteurs_session_events_json_encoded" => $visiteursSessionsEventsJSONEncoded
        ]);
    }

    function isValidDateString($date) {
        // Tenter de créer un objet DateTime à partir du format
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        
        // Vérifier si la conversion a été réussie et si la date correspond au format
        return $d && $d->format('Y-m-d') === $date;
    }
}
