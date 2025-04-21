<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Models\Utilisateur;

class LoginController extends Controller
{
    public function index()
    {
        if ($this->verif_authentification(false)) {
            // L'utilisateur est déjà connecté
            header('Location: /' . APP_LANG . '/MenuAccueil/');
            exit;
        }
        return $this->index_login();
        //return $this->login();
    }

    public function index_login(){
        if (!$this->verif_authentification(false)) {
            $connecte = 0;   
        }else{
            $connecte = 1;
        }

        //on defini déja les routes
        $this->afficheur->config_affichage->view = 'login/form';
        $this->afficheur->config_affichage->template = 'template_default';
        return  $this->afficheur->return_rendu([
            'page' => 'presentation',
            'connecte' => $connecte
        ]);    
    }

    public function login()
    {
        if ($this->verif_authentification(false)) {
            // L'utilisateur est déjà connecté
            header('Location: /' . APP_LANG . '/MenuAccueil/');
            exit;
        }

        //on defini déja les routes
        $this->afficheur->config_affichage->view = 'login/form';
        $this->afficheur->config_affichage->template = 'template_default';

        $prefill_email = "";
        $message_erreur = "";

        if (isset($_POST["username"])) {
            $usersModel = new Utilisateur;
            $userRecord = $usersModel->chercheUtilisateurParUsername(strip_tags($_POST['username']));
            // Si l'utilisateur n'existe pas
            if (!$userRecord) {
                // On envoie un message de session
                $message_erreur = "Username ou mot de passe est incorrect";
            } else if (password_verify($_POST['password'], $userRecord->password)) {
                $_SESSION['utilisateur'] = [
                    'id' => $userRecord->id_utilisateur,
                    'email' => $userRecord->email,
                    'abonnement_actif' => $userRecord->date_paiement != null,
                    'compte_micro_demo' => false
                ];
                header('Location: /' . APP_LANG . '/MenuAccueil/');
            } else {
                //Mauvais password
                $message_erreur = "Adresse email ou mot de passe est incorrect";
            }
        }

        return  $this->afficheur->return_rendu(["message_erreur" => $message_erreur]);
    }

    public function logout()
    {
        if (isset($_SESSION["utilisateur"])) {
            unset($_SESSION["utilisateur"]);
        }

        header('Location: /' . APP_LANG . '/accueil/login');
        exit();
    }
}
