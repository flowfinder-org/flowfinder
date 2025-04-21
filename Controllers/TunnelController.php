<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\PageConfiguration;

class TunnelController extends Controller
{

    public function index()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
    }

    public function loadtunnelt($parametres)
    {
        return $this->loadtunnel($parametres);
    }

    public function tunnelform()
    {
        $type_tunnel = filter_input(INPUT_POST, 'type_tunnel', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->afficheur->config_affichage->template = 'template_pure';
        $this->afficheur->config_affichage->view = 'pages/questionnaires/t/' . $type_tunnel;
        return  $this->afficheur->return_rendu();
    }

    public function loadtunnel($parametres)
    {
        $page_numero = $parametres["numero_tunnel"];
        $typeTunnel = strtolower($parametres["type_tunnel"]);
        
        $id_utilisateur = $this->verif_authentification();
        //on defini déja les routes
        $this->afficheur->config_affichage->template = 'template_connected';
        $this->afficheur->config_affichage->view = 'client/tunnel';

        $is_logged = $this->verif_authentification(false);

        $prenom = "";
        $nom = "";
        $email = "";

        $date_fin_promotion = null;
        $statut_paiement = 0;

        if ($is_logged) {
            $id_utilisateur = $this->verif_authentification();
            $utilisateurModel = new Utilisateur();
            $utilisateur = $utilisateurModel->chercheUtilisateurParId($id_utilisateur);

            //statut paiement pour redirection vers la page de page/tarif formule 
            $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;

            if ($utilisateur->prenom != null && $utilisateur->prenom != "") {
                $prenom = $utilisateur->prenom;
            }

            if ($utilisateur->nom != null && $utilisateur->nom != "") {
                $nom = $utilisateur->nom;
            }

            if ($utilisateur->email != null) {
                $email = $utilisateur->email;
            }

            $statut_paiement = $utilisateur->statut_paiement;
        }

        // A chaque rechargement de la page on met à jour certaines données
        $usersModel = new Utilisateur;
        $utilisateur = $usersModel->chercheUtilisateurParId($id_utilisateur);
        $_SESSION['utilisateur']['abonnement_actif'] = $utilisateur->date_paiement != null;

        //Partie qui devra être transféré dans un controller menu gauche afin de ne pas à avoir à tout répéter
        $ModelTunnel = new PageConfiguration;
        $listeTunnel = $ModelTunnel->select_all_listetunnel($id_utilisateur, 1);
        $listeSondage = $ModelTunnel->select_all_listetunnel($id_utilisateur, 3);
        $listeFeedback = $ModelTunnel->select_all_listetunnel($id_utilisateur, 4);
        $listeFormulaire = $ModelTunnel->select_all_listetunnel($id_utilisateur, 5);


        //trouver formule en cours
        $NumeroFormule = 1;

        
        return  $this->afficheur->return_rendu([
            'id_utilisateur' => $id_utilisateur,
            'listetunnel' => $listeTunnel,
            'listesondage' => $listeSondage,
            'listefeedback' => $listeFeedback,
            'listeformulaire' => $listeFormulaire,
            'typetunnel' => $typeTunnel,
            'connecte' => $is_logged,
            'page_numero' => $page_numero,
            "is_logged" => $is_logged,
            "prenom" => $prenom,
            "nom" => $nom,
            "email" => $email,
            "description_activite" => "",
            "statut_paiement" => $statut_paiement,
            "photo_disponible" => $utilisateur->photo_disponible == 1,
            "photo_url" => USER_FILES_URL . "/" . $id_utilisateur . "/logo_200.jpg",
            "Numero_Formule" => $NumeroFormule
        ]);
    }


    public function CreationElementTunnel()
    {
        $values = filter_input(INPUT_POST, 'values', FILTER_SANITIZE_SPECIAL_CHARS);
        $type_tunnel = filter_input(INPUT_POST, 'type_tunnel', FILTER_SANITIZE_SPECIAL_CHARS);

        //decode le json à son retour
        $json_string = html_entity_decode($values);
        $data = json_decode($json_string, true);
        $data_values = array_values($data);
        
        if ($data === null) {
            echo "Erreur dans le décodage du JSON.";
        } else {
            for ($i = 0; $i < 4; $i++) {
                if ($data_values[$i] != null){
                $value_template = $data_values[$i];
                }
            }
        }
        $fichierjson = APP_ROOT . DS . 'public' . DS . 'ressources' . DS .'json_exemple' . DS . $type_tunnel. DS .$value_template.'.json';
        $donneejson = file_get_contents($fichierjson);
        return $donneejson;
    }

    public function ConfigurationTunnelJson()
    {
        $values = filter_input(INPUT_POST, 'values', FILTER_SANITIZE_SPECIAL_CHARS);
        $type_tunnel = filter_input(INPUT_POST, 'type_tunnel', FILTER_SANITIZE_SPECIAL_CHARS);
        
        //decode le retour json
        $json_string = html_entity_decode($values);
        $data = json_decode($json_string, true);
        $data_values = array_values($data);

        $retour = '{"Visibilite-logo":{"Switch":1},"nomsociete":{"InputText":"Ici le nom de société"},"descriptionsociete":{"InputTextarea":"Ici une description de l activité"},"OrdreElements":{}}';

        if ($data === null) {
            echo "Erreur dans le décodage du JSON.";
        } else {
            for ($i = 0; $i < 4; $i++) {
                if ($data_values[$i] != null){
                $value_template = $data_values[$i];
                }
            }
            for ($i = 4; $i < 9; $i++) {
                if ($data_values[$i] != null){
                $value_design = $data_values[$i];
                }
            }
        }

        if ($type_tunnel == 'sondage') {
            switch ($value_template) {
                case 'ux':
                    $title = 'Expérience utilisateur (UX)';
                    $description = 'Évaluer l’ergonomie et l’expérience générale des utilisateurs sur votre site.';
                    break;
                case 'contenu':
                    $title = 'Contenu du site';
                    $description = 'Mesurer la satisfaction par rapport au contenu proposé.';
                    break;
                case 'satisfaction':
                    $title = 'Satisfaction globale';
                    $description = 'Obtenir une vision globale sur l’impression générale.';
                    break;
                case 'personnalise':
                    $title = 'Sondage personnalisé';
                    $description = 'Votre description';
                    break;
                default:
                $title = 'Titre';
                $description = 'Description';
            }
            $retour = '{"titretunnel":{"InputText": "'.$title.'"},"descriptiontunnel":{"InputTextarea": "'.$description.'"},"Design":{"Select": "'.$value_design.'"},"OrdreElements":{}}';
        }

        if ($type_tunnel == 'feedback') {
            switch ($value_template) {
                case 'instantanee':
                    $title = 'Impression instantanée';
                    $description = 'Quelle est votre impression générale du site.';
                    break;
                case 'evaluation':
                    $title = 'Évaluation spécifique';
                    $description = 'Évaluez [nom de la fonctionnalité spécifique] sur une échelle de 1 à 5.';
                    break;
                case 'utile':
                    $title = 'Temps passé sur le site';
                    $description = 'Avez-vous trouvé ce que vous cherchiez ? Oui ou non';
                    break;
                case 'personnalise':
                    $title = 'Feedback personnalisé';
                    $description = 'Votre description';
                    break;
                default:
                    $title = 'Titre';
                    $description = 'Description';
            }
            $retour = '{"titretunnel":{"InputText": "'.$title.'"},"descriptiontunnel":{"InputTextarea": "'.$description.'"},"Design":{"Select": "'.$value_design.'"},"OrdreElements":{}}';
        }

        if ($type_tunnel == 'formulaire') {
            switch ($value_template) {
                case 'ux':
                    $title = 'Analyse de l’Expérience Utilisateur (UX)';
                    $description = 'Comprendre en détail comment les utilisateurs interagissent avec votre site.';
                    break;
                case 'comprehension':
                    $title = 'Compréhension des besoins utilisateur';
                    $description = 'Identifier ce que recherchent les visiteurs et s’ils trouvent satisfaction.';
                    break;
                case 'satisfaction':
                    $title = 'Retour sur la satisfaction globale';
                    $description = 'Obtenir un retour général et des suggestions stratégiques pour améliorer le site.';
                    break;
                case 'personnalise':
                    $title = 'Votre titre';
                    $description = 'Votre description';
                    break;
                default:
                    $title = 'Titre';
                    $description = 'Description';
            }
            $retour = '{"titretunnel":{"InputText": "'.$title.'"},"descriptiontunnel":{"InputTextarea": "'.$description.'"},"Design":{"Select": "'.$value_design.'"},"OrdreElements":{}}';
        }  
      
        return $retour;
    }
}
