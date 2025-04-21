<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Controllers\StatistiquesController;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\PageConfiguration;

class MenuAccueilController extends Controller
{


    public function index()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }else{
            // redirect to the /StatistiquesController which acts as the dashboard for now
            $this->afficheur->config_affichage->template = 'template_connected';
            
            $id_utilisateur = $this->verif_authentification();
            $utilisateurModel = new Utilisateur();
            $utilisateur = $utilisateurModel->chercheUtilisateurParId($id_utilisateur);
            //statut paiement pour redirection vers la page de page/tarif formule 
            $this->afficheur->config_affichage->statut_paiement = $utilisateur->statut_paiement;

            $s = new StatistiquesController();
            return $s->index([
                "type_vue" => "top_en_haut",
                "filtre_pub" => "aucun"
            ]);
        }
    }

}
