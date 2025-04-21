<?php

namespace FlowFinder\Controllers;

use FlowFinder\Core\Controller;
use FlowFinder\Models\Utilisateur;
use FlowFinder\Models\Element;
use FlowFinder\Models\PageConfiguration;
use FlowFinder\Models\PageFormDonnees;

class ElementController extends Controller
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
        $this->afficheur->config_affichage->template = 'template_default';
        $this->afficheur->config_affichage->view = 'client/portail';

        // A chaque rechargement de la page on met à jour certaines données
        $usersModel = new Utilisateur;
        $utilisateur = $usersModel->chercheUtilisateurParId($id_utilisateur);
        $_SESSION['utilisateur']['abonnement_actif'] = $utilisateur->date_paiement != null;

        if ($this->verif_authentification(false)) {
            $id_utilisateur = $this->verif_authentification();
            $usersModel = new Utilisateur;
            $utilisateur = $usersModel->chercheUtilisateurParId($id_utilisateur);
            $connecte = true;
        }

        return  $this->afficheur->return_rendu(['connecte' => $connecte]);
    }

    public function retourn_typePage($page)
    {
        //on va verifier à quel page il appartient
        switch ($page) {
            case 'portail':
                return 1;
                break;

            case 'tunnel':
                return 2;
                break;

            case 'sondage':
                return 3;
                break;

            case 'feedback':
                return 4;
                break;

            case 'formulaire':
                return 5;
                break;

            default:
                break;
        }
    }

    public function delete_evenement()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
        $id_utilisateur = $this->verif_authentification();
        $evenement = new Element();
        $templateId = $_POST["id_template"];
        $parts = explode('-', $templateId);
        if (isset($parts[1])) {
            $id_element = $parts[1];
            if (!is_numeric($id_element)) {
                return " n'est pas un chiffre.";
            }
        } else {
            return " n'est pas un chiffre.";
        }
        $type_page = $this->retourn_typePage($_POST["typepage"]);
        $id_page_configuration = $_POST["numpage"];

        $result = $evenement->delete_element($id_element, $id_utilisateur, $type_page, $id_page_configuration);
    }

    public function create_configuration_page()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
        $id_utilisateur = $this->verif_authentification();
        if (isset($_POST["jsonconfiguration"]) && isset($_POST["typepage"]) && isset($_POST["numpage"])) {
            $id_page_configuration = $_POST["numpage"];
            $type_page = $this->retourn_typePage($_POST["typepage"]);
            $json_configuration_page = $_POST["jsonconfiguration"];
            //forcer l'actif à 1
            $actif_page_configuration = 1;
            $evenement = new PageConfiguration();
            //verifier si il existe deja
            $result = $evenement->select_pageconfiguration($id_page_configuration, $id_utilisateur, $type_page);

            // Vérifier si des résultats ont été trouvés
            if (!$result) {
                //elle n'existe pas il faut bien le créer
                $result = $evenement->create_pageconfiguration($id_page_configuration, $actif_page_configuration,  $id_utilisateur, $type_page, $json_configuration_page);
            }
        }
    }

    public function save_configuration_page()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
        $id_utilisateur = $this->verif_authentification();
        if (isset($_POST["jsonconfiguration"]) && isset($_POST["typepage"]) && isset($_POST["numpage"])) {
            $id_page_configuration = $_POST["numpage"];
            $type_page = $this->retourn_typePage($_POST["typepage"]);
            $json_configuration_page = $_POST["jsonconfiguration"];
            var_dump($json_configuration_page);
            //forcer l'actif à 1
            $actif_page_configuration = 1;
            $evenement = new PageConfiguration();
            //verifier si il existe deja
            $result = $evenement->select_pageconfiguration($id_page_configuration, $id_utilisateur, $type_page);

            // Vérifier si des résultats ont été trouvés
            if ($result) {
                // elle existe deja
                var_dump($result);
                $result = $evenement->update_pageconfiguration($id_page_configuration, $actif_page_configuration,  $id_utilisateur, $type_page, $json_configuration_page);
            } else {
                //elle n'existe pas il faut le créer
                $result = $evenement->create_pageconfiguration($id_page_configuration, $actif_page_configuration,  $id_utilisateur, $type_page, $json_configuration_page);
            }
        }
    }

    public function load_configuration_page()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
        $id_utilisateur = $this->verif_authentification();
        //var_dump($_POST);
        $type_page = $this->retourn_typePage($_POST["typepage"]);
        $id_page_configuration = $_POST["numpage"];

        $evenement = new PageConfiguration();
        $result = $evenement->select_pageconfiguration($id_page_configuration, $id_utilisateur, $type_page);
        return json_encode($result);
    }

    public function load_elements()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
        $id_utilisateur = $this->verif_authentification();
        $type_page = $this->retourn_typePage($_POST["typepage"]);
        $id_page_configuration = $_POST["numpage"];

        $evenement = new Element();
        $result = $evenement->select_all_elements($id_utilisateur, $type_page, $id_page_configuration);
        return json_encode($result);
    }

    public function save_evenement()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }
        $id_utilisateur = $this->verif_authentification();

        if (isset($_POST["elements"]) && isset($_POST["typepage"]) && isset($_POST["numpage"])) {

            $info_elements = json_decode($_POST["elements"], true);
            $evenement = new Element();
            foreach ($info_elements as $templateId => $templateData) {
                $parts = explode('-', $templateId);
                if (isset($parts[1])) {
                    $id_element = $parts[1];
                    if (!is_numeric($id_element)) {
                        return " n'est pas un chiffre.";
                    }
                } else {
                    return " n'est pas un chiffre.";
                }
                $titre_element = $templateData['Title']['InputText'];
                $actif_element = ($templateData['Active']['Switch'] === 1) ? 1 : 0;
                $donnee_json_element = json_encode($templateData);
                $id_page_configuration = $_POST["numpage"];
                $type_page = $this->retourn_typePage($_POST["typepage"]);

                //verifier si il existe deja
                $result = $evenement->select_element($id_element, $id_utilisateur, $type_page, $id_page_configuration);

                // Vérifier si des résultats ont été trouvés
                if ($result) {
                    // Il existe deja
                    $result = $evenement->update_element($id_element, $id_utilisateur, $type_page, $actif_element, $titre_element, $donnee_json_element, $id_page_configuration);
                } else {
                    //il n'existe pas il faut le créer
                    $result = $evenement->create_element($id_element, $id_utilisateur, $type_page, $actif_element, $titre_element, $donnee_json_element, $id_page_configuration);
                }
            }
        }
    }

    public function liste_inscriptions_form()
    {
        if (!$this->verif_authentification(false)) {
            header('Location: /' . APP_LANG . '/');
            exit;
        }

        $id_utilisateur = $this->verif_authentification();
        if (isset($_POST["numpage"])) {
            $id_page_configuration = $_POST["numpage"];
            $evenement = new PageFormDonnees();
            $result = $evenement->select_all_elements($id_utilisateur, $id_page_configuration);
            return json_encode($result);
        }
    }
}
