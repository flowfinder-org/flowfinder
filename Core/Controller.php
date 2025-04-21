<?php

namespace FlowFinder\Core;

class Controller
{
    protected $afficheur = Null;
    function __construct()
    {
        $this->afficheur = new Afficheur();
    }

    public function action($namefunc, $json_param = null)
    {
        $renduhtml = "";
        if ($json_param == null) {
            $renduhtml = call_user_func_array([$this, $namefunc], []);
        } else {
            $renduhtml = call_user_func_array([$this, $namefunc], [$json_param]);
        }

        $this->afficheur->rendu($renduhtml);
    }

    /**
     * Vérifie que l'utilisateur est logué sinon renvoie une page vide avec un code de retour que les appel ajax peuvent comprendre pour afficher une erreur
     */
    protected function verif_authentification_silencieux()
    {
        $id_utilisateur = $this->verif_authentification(false);
        if (!$id_utilisateur) {
            header("HTTP/1.1 401 Unauthorized");
            print("Utilisateur non identifié.");
            exit();
        }
        return $id_utilisateur;
    }

    /**
     * Vérifie que l'utilisateur est logué sinon redirige vers la page login et retourne true/false (le parametre permet de ne pas rediriger automatiquement)
     */
    protected function verif_authentification(bool $redirect_auto = true)
    {
        if (isset($_SESSION["utilisateur"])) {
            /*if(isset($_SESSION["utilisateur"]["compte_inactif"]) && $_SESSION["utilisateur"]["compte_inactif"] == true)
            {
                // si on arrive ici, le compte utilisateur est inactif (n"cessite une activation manuelle après inscription ou à été désactivé par un administrateur)
                header('Location: /' . APP_LANG . '/utilisateur/compte_inactif/');        
                exit();
            }*/

            // si on a une session serveur, cela valide que l'utilisateur a été authentifié précedement via le couple pseudo|token du cookie par appel DB
            return $_SESSION["utilisateur"]["id"];
        }

        if (!$redirect_auto)
            return false;
        // si on arrive ici, l'utilisateur n'est pas identifé alors on le redirige vers la page inscription
        header('Location: /' . APP_LANG . '/accueil/login/');
        exit();
    }
}
