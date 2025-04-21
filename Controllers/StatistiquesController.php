<?php

namespace FlowFinder\Controllers;

use FlowFinder\Helper\TunnelsTraceursCalculateur;
use FlowFinder\Core\Controller;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\PageConfiguration;
use FlowFinder\Models\Collection;
use FlowFinder\Helper\HelperIntegration;

class StatistiquesController extends Controller
{
    public function index($params)
    {
        $id_utilisateur = $this->verif_authentification();


        // on vérifie si on a déjà enreistré des sessions, si ce n'est pas le cas on affiche le tutoriel d'inclusion du code de traçage

        $contient_des_sessions_query = "SELECT 1 as contient_des_sessions
        FROM  visiteur_sessions vs 
        LEFT JOIN visiteurs v on vs.id_visiteur = v.id_visiteur 
        LEFT JOIN collections c on v.id_collection = c.id_collection
        LEFT JOIN collections_utilisateurs cu on c.id_collection = cu.id_collection
        WHERE cu.id_utilisateur = ?
        LIMIT 1;";
        $contient_des_sessions_result = (new Collection())->requete($contient_des_sessions_query, [$id_utilisateur])->fetch();
        if($contient_des_sessions_result != null && $contient_des_sessions_result != false && $contient_des_sessions_result->contient_des_sessions == 1)
        {
            return $this->statistiques($params);
           //return $this->affiche_tutoriel();
        }
        else
        {
            return $this->affiche_tutoriel();
        }

        
    }

    private function statistiques($params)
    {
        $id_utilisateur = $this->verif_authentification();

        $this->afficheur->config_affichage->template = 'template_connected';
        $this->afficheur->config_affichage->view = 'pages/statistiques';

        /* pour le panneau menu_gauche */
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->chercheUtilisateurParId($id_utilisateur);

        //statut paiement pour redirection vers la page de page/tarif formule 
        $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;

        //trouver formule en cours
        $NumeroFormule = 1;

        $ModelTunnel = new PageConfiguration();
        $listeTunnel = $ModelTunnel->select_all_listetunnel($id_utilisateur, 1);
        $listeSondage = $ModelTunnel->select_all_listetunnel($id_utilisateur, 3);
        $listeFeedback = $ModelTunnel->select_all_listetunnel($id_utilisateur, 4);
        $listeFormulaire = $ModelTunnel->select_all_listetunnel($id_utilisateur, 5);

        /* pour les stats */

        $order_by_journalier = "";
        $order_by_journalier_extract_filtres = "";
        if (!isset($params["type_vue"])) {
            $params["type_vue"] = "top_en_haut";
        }

        if (!isset($params["filtre_date_start"]) || !$this->isValidDateString($params["filtre_date_start"])) {
            $params["filtre_date_start"] = date('Y-m-d', strtotime('-7 days'));
        }
        if (!isset($params["filtre_date_end"]) || !$this->isValidDateString($params["filtre_date_end"])) {
            $params["filtre_date_end"] = date('Y-m-d');;
        }

        if ($params["type_vue"] == "top_en_haut") {
            $order_by_journalier = "
                SUM(vs.seconds_active) DESC";
            $order_by_journalier_extract_filtres = "1";
        } else if ($params["type_vue"] == "horaire") {
            $order_by_journalier = "
                HOUR(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris')) ASC,
                 CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris') ASC";
               
            $order_by_journalier_extract_filtres = "
                HOUR(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')) ASC";
        }


        $where_clause_complement = " ";
        if (isset($params["filtre_utm_campaign"]) && $params["filtre_utm_campaign"] != "aucun") {
            $where_clause_complement = " AND utm_campaigns LIKE '%" . $params["filtre_utm_campaign"] . "%' ";
        }

        // on extrait tous les id_pub de ce compte
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
            WEEKDAY(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris')) ASC,
            " . $order_by_journalier_extract_filtres . "
        ;
        ";
        $results_utm_campaign = (new Collection())->requete($query_utm_campaign)->fetchAll();
        $utm_campaigns = ["aucun" => 1]; // on utilisae un tableau associatif pour accélérer la recherche isset() dans le if suivant
        foreach ($results_utm_campaign as $result_utm_campaign) {
            if ($result_utm_campaign->utm_campaign != null && trim($result_utm_campaign->utm_campaign) != "" && !isset($utm_campaigns[$result_utm_campaign->utm_campaign])) {
                $utm_campaigns[$result_utm_campaign->utm_campaign] = 1;
            }
        }

        // on extrait toutes les pubs
        $query = "
        SELECT
            YEAR(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris')) as annee, 
            WEEK(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris'), 3) as numero_semaine, 
            WEEKDAY(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris')) + 1 as jour_semaine,
            HOUR(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris')) as heure_du_jour,
            CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris') as date_creation_local,
            min(vs.id_visiteur_session) as id_visiteur_session, 
            vs.visiteur_session_uuid, 
            vs.id_visiteur, 
            count(vs.sequence_index) as count_sequences , 
            GROUP_CONCAT(vs.user_ip SEPARATOR '¤') as user_ips,GROUP_CONCAT(vs.url SEPARATOR '¤') as urls,
            SUM(vs.is_bot) as is_bot,
            SUM(vs.is_team) as is_team, 
            SUM(vs.seconds_active) as total_seconds_active, 
            GROUP_CONCAT(vs.utm_campaign SEPARATOR '¤') as utm_campaigns,
            GROUP_CONCAT(vs.utm_content SEPARATOR '¤') as utm_contents,
            GROUP_CONCAT(vs.utm_medium SEPARATOR '¤') as utm_mediums,
            GROUP_CONCAT(vs.utm_source SEPARATOR '¤') as utm_sources,
            c.nom as nom_collection,
            cu.id_utilisateur
        FROM  visiteur_sessions vs 
        LEFT JOIN visiteurs v on vs.id_visiteur = v.id_visiteur 
        LEFT JOIN collections c on v.id_collection = c.id_collection
        LEFT JOIN collections_utilisateurs cu on c.id_collection = cu.id_collection
        GROUP BY cu.id_utilisateur, vs.id_visiteur, vs.visiteur_session_uuid,
            DATE(CONVERT_TZ(vs.date_creation, 'UTC', 'Europe/Paris'))
        HAVING cu.id_utilisateur = " . $id_utilisateur  . " 
            AND CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris') BETWEEN '" . $params["filtre_date_start"] . " 00:00' AND '" . $params["filtre_date_end"] . " 23:59:59'
            AND is_bot = 0 AND is_team = 0
            " . $where_clause_complement . "
        ORDER BY 
            YEAR(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris')) DESC, 
            WEEK(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris'), 3) DESC, 
            WEEKDAY(CONVERT_TZ(min(vs.date_creation), 'UTC', 'Europe/Paris')) ASC,
            " . $order_by_journalier . "
        ;
        ";

        $tunnel_traceurs_par_semaine = (new Collection())->requete($query)->fetchAll();

        $sessions_json = [];
        $moyenne_json = [];
        $count_visites_par_duree_par_jour = [];
        foreach ($tunnel_traceurs_par_semaine as $t) {
            $jour = explode(' ', $t->date_creation_local)[0];
            if($t->total_seconds_active > 300)
            {
                $t->total_seconds_active = 300;
            }
            $total_seconds = $t->total_seconds_active;
        
            if (!isset($count_visites_par_duree_par_jour[$jour])) {
                $count_visites_par_duree_par_jour[$jour] = [
                    'eclair' => 0,
                    'courte' => 0,
                    'longue' => 0,
                    'total_sessions' => 0,  // Pour le total des sessions
                    'total_duree' => 0  
                ];
            }
        
            if ($total_seconds < 10) {
                $count_visites_par_duree_par_jour[$jour]['eclair'] += 1;
            } elseif ($total_seconds <= 60) {
                $count_visites_par_duree_par_jour[$jour]['courte'] += 1;
            } else {
                $count_visites_par_duree_par_jour[$jour]['longue'] += 1;
            }

            // Calculer le total des sessions et de la durée pour chaque jour
            $count_visites_par_duree_par_jour[$jour]['total_sessions'] += 1;
            $count_visites_par_duree_par_jour[$jour]['total_duree'] += $total_seconds;
        }
        
        // Préparer les données pour les graphiques
        foreach ($count_visites_par_duree_par_jour as $jour => $data) {
            // Données pour le nombre de sessions par jour
            $sessions_json[] = [
                'x' => $jour,
                'y' => $data['total_sessions']
            ];

            // Données pour le temps moyen par session
            $temps_moyen = $data['total_sessions'] > 0 ? $data['total_duree'] / $data['total_sessions'] : 0;
            $moyenne_json[] = [
                'x' => $jour,
                'y' => $temps_moyen
            ];
        }

        // on trie les tableau de session_json et moyenne_json car il sont desordonnée
        usort($sessions_json, function($a, $b) {
            return strcmp($a['x'], $b['x']);
        });
        usort($moyenne_json, function($a, $b) {
            return strcmp($a['x'], $b['x']);
        });

        // Convertir en JSON pour injecter dans le JavaScript
        $sessions_json = json_encode($sessions_json);
        $moyenne_json = json_encode($moyenne_json);

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
            'tunnel_traceurs_par_semaine' => $tunnel_traceurs_par_semaine,
            "utm_campaigns" => $utm_campaigns,
            "filtre_utm_campaign" => isset($params["filtre_utm_campaign"]) ? $params["filtre_utm_campaign"] : "aucun",
            "filtre_date_start" => $params["filtre_date_start"],
            "filtre_date_end" => $params["filtre_date_end"],
            "type_vue" => $params["type_vue"],
            "statut_paiement" => $utilisateur->statut_paiement,

            /*  pour les graph */
            "count_visites_par_duree_par_jour_json" =>  json_encode($count_visites_par_duree_par_jour),
            "sessions_json" => $sessions_json,
            "moyenne_json" => $moyenne_json
        ]);
    }

    function isValidDateString($date) {
        // Tenter de créer un objet DateTime à partir du format
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        
        // Vérifier si la conversion a été réussie et si la date correspond au format
        return $d && $d->format('Y-m-d') === $date;
    }

    private function affiche_tutoriel()
    {
        $id_utilisateur = $this->verif_authentification();

        $this->afficheur->config_affichage->template = 'template_connected';
        $this->afficheur->config_affichage->view = 'pages/tutoriel_code_tracage';

        /* pour le panneau menu_gauche */
        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->chercheUtilisateurParId($id_utilisateur);

        //statut paiement pour redirection vers la page de page/tarif formule 
        $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;
        
        //trouver formule en cours
        $NumeroFormule = 1;

        $ModelTunnel = new PageConfiguration();
        $listeTunnel = $ModelTunnel->select_all_listetunnel($id_utilisateur, 1);
        $listeSondage = $ModelTunnel->select_all_listetunnel($id_utilisateur, 3);
        $listeFeedback = $ModelTunnel->select_all_listetunnel($id_utilisateur, 4);
        $listeFormulaire = $ModelTunnel->select_all_listetunnel($id_utilisateur, 5);


        /* pour la page */
        //  la methode crée la collection par default qui est lié à l'utilisateur si celui-ci n'en a pas encore une
        $snippet_analytics = HelperIntegration::get_default_snippet_analytics_create_it_if_necessary($id_utilisateur);
        
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
            
            /* pour la page */
            'snippet_analytics' => $snippet_analytics
        ]);

    }
}
