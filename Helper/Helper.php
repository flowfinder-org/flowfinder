<?php

namespace FlowFinder\Helper;

use Exception;

class Helper
{
    public $view = '';
    public $source_trad = ''; // source des traductions, si vide alors utilise this->view
    public $template = '';
    public $idbody = '';
    public $pageactuelle_body = '';
    public $statut_paiement = '';
    public $lang = '';
    protected $chemin = '';
    
    //function permettant de véifier et d'ajouter des fichiers css js jpg ect en vérifiant qu'il existe avant
    public function asset(string $path = '')
    {
        $filepath = APP_ROOT . DS . 'public' . '/' . $path;

        if (!file_exists($filepath)) {
            // prévoir la gestion de l'erreur ici
            throw new Exception("La ressource '" . $path . "' n'existe pas");
        }
        $filepath = BASE_URL . '/' . $path . "?version=" . ASSETS_VERSION_FOR_NOCACHE;
        return $filepath;
    }

    //function vérifiant avant d'ecrire la donnée par la méthode echo ou return
    public function ecrit($donnees, string $method = 'ecrit', ?string $cheminspecifique = null)
    {
        if ($donnees === null) {
            return "";
        }
        if ($method == 'return') {
            return ($donnees);
        } else {
            echo htmlspecialchars($donnees);
        }
    }

    public function ecrithtml(string $donneeshtml)
    {
        echo $donneeshtml;
    }

    public function donne_chemin_include_client(int $site_id_utilisateur, string $chemin_relatif_fichier)
    {
        return APP_ROOT . DS . 'Views' . DS . 'clients' . DS . $site_id_utilisateur . DS . 'site' . DS . 'includes' . DS . $chemin_relatif_fichier;
    }

    public function html_select_from_enum($name, $enum_type, $selected_value)
    {
        $html = "<select name='" . $name . "'>";
        foreach ($enum_type::cases() as $case) {
            $html_selected = $case->value == $selected_value ? " selected='true' " : "";

            $html .= "<option value=\"" . $case->value . "\" . $html_selected . >" . $case->name . "</option>\n";
        }
        $html .= "</select>";
        echo $html;
    }


    public function retourneDateCourteUTCVersTimezoneAffichage($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            echo "";
            return;
        }
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        return $dt->format('d-m-Y');
    }

    public function ecritDateCourteUTCVersTimezoneAffichage($dateUTC)
    {
        echo $this->retourneDateCourteUTCVersTimezoneAffichage($dateUTC);
    }

    public function ecritDateUTCVersTimezoneAffichage($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            echo "";
            return;
        }
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        echo $dt->format('d‑m‑Y H:i:s');
    }


    public function retourneDateJourUTCVersTimezoneAffichage($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            return "";
        }
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        return $dt->format('Y-m-d');
    }


    public function retourneDateUTCVersTimezoneAffichageFormatJourLong($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            echo "";
            return;
        }
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        $num_jour_semaine = $dt->format('w');
        $num_jour_mois = $dt->format('d');
        $num_mois = $dt->format('m');
        $num_annee = $dt->format('Y');
        return $this->numero_jour_semaine_vers_nom_jour_fr($num_jour_semaine) . " " . $num_jour_mois . " " . $this->numero_mois_vers_nom_mois_fr($num_mois) . " " . $num_annee;
    }

    public function ecritDateUTCVersTimezoneAffichageFormatJourLong($dateUTC)
    {
        echo $this->retourneDateUTCVersTimezoneAffichageFormatJourLong($dateUTC);
    }


    public function ecritHeureUTCVersTimezoneAffichage($dateUTC)
    {
        echo $this->retourneHeureUTCVersTimezoneAffichage($dateUTC);
    }

    public function retourneHeureUTCVersTimezoneAffichage($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            echo "";
            return;
        }
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        return $dt->format('H:i:s');
    }


    public function ecritNomJourFrEtNombreDeJourDifference($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            echo "";
            return;
        }
        
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        $interval = $dt->diff($now);
        $nombre_jour_ecoule = $interval->format('%R%a');

        $num_jour_semaine = $dt->format('w');
        echo $this->numero_jour_semaine_vers_nom_jour_fr($num_jour_semaine) . " (" . $nombre_jour_ecoule . " jours)";
    }

    public function ecritNombreDeJourDifference($dateUTC)
    {
        if (!isset($dateUTC) || $dateUTC == null || trim($dateUTC) == "") {
            echo "";
            return;
        }
        $dt = new \DateTime($dateUTC, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        $now = new \DateTime();
        $now->setTimezone(new \DateTimeZone(TIMEZONE_AFFICHAGE));
        $interval = $dt->diff($now);
        $nombre_jour_ecoule = $interval->format('%R%a');

        echo $nombre_jour_ecoule . " jours";
    }

    private function numero_mois_vers_nom_mois_fr($num_jour_mois)
    {
        if ($num_jour_mois == "01") {
            return "janvier";
        } else if ($num_jour_mois == "02") {
            return "février";
        } else if ($num_jour_mois == "03") {
            return "mars";
        } else if ($num_jour_mois == "04") {
            return "avril";
        } else if ($num_jour_mois == "05") {
            return "mais";
        } else if ($num_jour_mois == "06") {
            return "juin";
        } else if ($num_jour_mois == "07") {
            return "juillet";
        } else if ($num_jour_mois == "08") {
            return "août";
        } else if ($num_jour_mois == "09") {
            return "septembre";
        } else if ($num_jour_mois == "10") {
            return "octobre";
        } else if ($num_jour_mois == "11") {
            return "novembre";
        } else if ($num_jour_mois == "12") {
            return "décembre";
        }
    }

    private function numero_jour_semaine_vers_nom_jour_fr($num_jour_semaine)
    {
        if ($num_jour_semaine == "00") {
            return "dimanche";
        } else if ($num_jour_semaine == "01") {
            return "lundi";
        } else if ($num_jour_semaine == "02") {
            return "mardi";
        } else if ($num_jour_semaine == "03") {
            return "mercredi";
        } else if ($num_jour_semaine == "04") {
            return "jeudi";
        } else if ($num_jour_semaine == "05") {
            return "vendredi";
        } else if ($num_jour_semaine == "06") {
            return "samedi";
        }
    }


    public function cherche_trad(string $donnees)
    {
        return $this->traduit($donnees, 'return');
    }

    //function traduit par rapport à la langue
    public function traduit(string $donnees, string $method = 'ecrit')
    {
        //ma traduction
        $trad = "";
        //chemin de la vue default
        $chemindefault = APP_ROOT . '/lang' . '/' . APP_LANG . '/';
        
        $source_trad = $this->source_trad;

        if ($source_trad == "")
            $source_trad = $this->view;
        $chem_default = $source_trad;
        $chem_default = explode("/", $chem_default);
        for ($i = 0; $i < count($chem_default) - 1; $i++) {
            $chemindefault .= $chem_default[$i] . '/';
        }
        $chemindefault .= 'default.csv';
        if (file_exists($chemindefault) && $trad == "") {
            $trad = $this->tradcsv($chemindefault, $donnees);
        }
        if ($source_trad==""){$source_trad="template";};
        //chemin de la source des traductions
        $chemin = APP_ROOT . '/lang' . '/' . APP_LANG . '/' . $source_trad . '.csv';
        //echo $chemin;
        if (file_exists($chemin) && $trad == "") {
            $trad = $this->tradcsv($chemin, $donnees);
        }
        //echo $trad.' avec la méthode '.$method;
        if ($trad != "") {
            return ($this->ecrithtml($trad, $method));
        } else {
            error_log("Attention traduction manquante pour '" . $donnees . "' en '" . APP_LANG . "' avec la source de trad '" . $source_trad . "' sur la vue '" . $this->view . "'", 0);
            $trad_donnee = iconv('UTF-8', 'ASCII//TRANSLIT', $donnees);
            $trad = strtoupper(strrev($trad_donnee));

            return ($this->ecrit($trad, $method));
        }
    }

    public function tradcsv(string $chemin, string $donnees)
    {

        $fileview = fopen($chemin, 'r');
        while (($data = fgetcsv($fileview, null, ";")) !== false) {
            $champs = count($data); //nombre de champ dans la ligne en question
            if ($data[0] == $donnees) {
                return ($data[1]);
            }
        }
        fclose($fileview);
    }

    function prepare_url_autoconnexion($user_id, $user_autoconnect_token, $redirect_url)
    {
        $redirect_url_encoded = base64_encode($redirect_url);
        $autologin_params = $user_id . "$" . $user_autoconnect_token . ";" . $redirect_url_encoded;
        $autologin_params_encoded = base64_encode($autologin_params);

        $url_json_params_encoded = urlencode(json_encode(["E" => $autologin_params_encoded]));

        return BASE_URL . "/accueil/autologin?ank=" . $url_json_params_encoded;
    }

    public function get_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function get_user_ip()
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

    public function retourneNomPaysAPartirDeCodePays($code_pays)
    {
        if ($code_pays == "DE") {
            return "Allemagne";
        } else if ($code_pays == "BE") {
            return "Belgique";
        } else if ($code_pays == "ES") {
            return "Espagne";
        } else if ($code_pays == "FR") {
            return "France";
        } else if ($code_pays == "IT") {
            return "Italie";
        } else if ($code_pays == "LU") {
            return "Luxembourg";
        } else if ($code_pays == "CH") {
            return "Suisse";
        }

        return $code_pays;
    }

    
    public function genererFormulaire($jsonObjects, $seppage)
    {
        $contenuForm = "";
        $pageNumber = 0;
        
       
        $contenuForm .= '<div class="flowfinder-step" id="flowfinder-step0">';
        
        foreach ($jsonObjects as $key => $object) {

            $typeInput = $object->Choix->Select;
            $label = $object->Title->InputText;
            /*
            $contenuForm .= '<pre>';
            ob_start();
            var_dump($object);
            $contenuForm .= ob_get_clean();
            $contenuForm .= '</pre>';
            echo '<br/>';
            */
            switch ($typeInput) {
                case 'texte':
                    if (isset($object->Choix->type->{"Option-InputRadio"})) {
                        $option = $object->Choix->type->{"Option-InputRadio"};
                        switch ($option) {
                            case 'txt':
                                $contenuForm .= '<div class="flowfinder-form-txt">';
                                $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                                $contenuForm .= '<input type="text" class="flowfinder-form-control" name="texte-txt-' . $key . '" id="idtemplate-' . $key . '" placeholder="">';
                                $contenuForm .= '</div>';
                                break;
                            case 'email':
                                $contenuForm .= '<div class="flowfinder-form-email">';
                                $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                                $contenuForm .= '<input type="text" class="flowfinder-form-control" name="texte-email-' . $key . '" id="idtemplate-' . $key . '" placeholder="">';
                                $contenuForm .= '</div>';
                                break;
                            case 'pswd':
                                $contenuForm .= '<div class="form-password">';
                                $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                                $contenuForm .= '<input type="password" class="flowfinder-form-control" name="texte-pswd-' . $key . '" id="idtemplate-' . $key . '" placeholder="">';
                                $contenuForm .= '</div>';
                                break;
                            case 'url':
                                $contenuForm .= '<div class="flowfinder-form-url">';
                                $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                                $contenuForm .= '<div class="input-group">';
                                $contenuForm .= '<input type="text" class="flowfinder-form-control ms1" name="texte-url-' . $key . '" id="idtemplate-' . $key . '" placeholder="">';
                                $contenuForm .= '</div>';
                                $contenuForm .= '</div>';
                                break;
                            case 'tel':
                                $contenuForm .= '<div class="flowfinder-form-tel">';
                                $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                                $contenuForm .= '<input type="tel" class="flowfinder-form-control" name="texte-tel-' . $key . '" id="idtemplate-' . $key . '" placeholder="">';
                                $contenuForm .= '</div>';
                                break;
                        }
                    }
                    break;

                case 'textarea':
                    $nbligne = $object->Choix->nbligne->{'Option-InputNumber'};
                    $nbmaxligne = $object->Choix->nbmaxligne->{'Option-InputNumber'};
                    $contenuForm .= '<div class="flowfinder-form-textarea">';
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $contenuForm .= '<textarea class="flowfinder-form-control" name="textarea-' . $key . '" id="idtemplate-' . $key . '" rows="' . htmlspecialchars($nbligne) . '" maxlength="' . htmlspecialchars($nbmaxligne) . '"></textarea>';
                    $contenuForm .= '</div>';
                    break;

                case 'case':
                    $options = explode(';', $object->Choix->liste->{'Option-InputText'});
                    
                    $contenuForm .= '<div class="flowfinder-form-group">';
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label  flowfinder-titre-label  flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $i = 0;
                    
                    // Crée le fait que aucun soit checké afin d'envoyer quand meme la donnée
                    $contenuForm .= '<div class="flowfinder-form-case">';
                    $contenuForm .= '<input class="flowfinder-form-check-input" type="checkbox" name="case-' . $key . '"  id="template-' . $key . '-flexCase" value="" checked style="display:none;">';
                    $contenuForm .= '</div>';

                    foreach ($options as $index => $option) {
                        $checked = '';
                        if ($isSelected = str_ends_with(trim($option), ':checked')){
                            $optionValue = $isSelected ? trim(substr($option, 0, strrpos($option, ':checked'))) : trim($option);
                          $option = $optionValue;
                            $checked = 'checked';
                        }  
                        $contenuForm .= '<div class="flowfinder-form-case">';
                        $contenuForm .= '<input class="flowfinder-form-check-input" type="checkbox" name="case-' . $key . '" id="template-' . $key . '-flexCase-' . $i . '" value="' . htmlspecialchars(trim($option)) . '" '. $checked .'>';
                        $contenuForm .= '<label class="flowfinder-form-check-label flowfinder-form-label flowfinder-content-label" for="template-' . $key . '-flexCase-' . $i . '">';
                        $contenuForm .= htmlspecialchars(trim($option));
                        $contenuForm .= '</label>';
                        $contenuForm .= '</div>';
                        $i++;
                    }

                    $contenuForm .= '</div>';
                    break;

                case 'radio':
                    $options = explode(';', $object->Choix->liste->{'Option-InputText'});

                    $contenuForm .= '<div class="flowfinder-form-group">';
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $i = 0;
                    
                    // Crée le fait que aucun soit checké afin d'envoyer quand meme la donnée
                    $contenuForm .= '<div class="flowfinder-form-radio">';
                    $contenuForm .= '<input class="flowfinder-form-check-input" name="radio-' . $key . '" type="radio" id="template-' . $key . '-flexRadio" value="" checked style="display:none;>';
                    $contenuForm .= '</div>';

                    foreach ($options as $index => $option) {

                        // Vérifie si l'option contient ":checked"
                        $isChecked = str_ends_with(trim($option), ':checked');
                        // Extrait le label
                        $label = $isChecked ? trim(substr($option, 0, strrpos($option, ':checked'))) : trim($option);

                        $contenuForm .= '<div class="flowfinder-form-radio">';
                        $contenuForm .= '<input class="flowfinder-form-check-input" name="radio-' . $key . '" type="radio" id="template-' . $key . '-flexRadio-' . $i . '" value="' . htmlspecialchars(trim($label)) . '" ' . ($isChecked ? 'checked' : ' ') . '>';
                        $contenuForm .= '<label class="flowfinder-form-check-label flowfinder-form-label flowfinder-content-label" for="template-' . $key . '-flexRadio-' . $i . '">';
                        $contenuForm .= htmlspecialchars(trim($label));
                        $contenuForm .= '</label>';
                        $contenuForm .= '</div>';
                        $i++;
                    }
                    $contenuForm .= '</div>';
                    break;

                case 'interrupteur':
                    $options = explode(';', $object->Choix->liste->{'Option-InputText'});

                    $contenuForm .= '<div class="flowfinder-form-group">';
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $i = 0;

                    // Crée le fait que aucun soit checké afin d'envoyer quand meme la donnée
                    $contenuForm .= '<div class="flowfinder-form-interrupteur flowfinder-form-check form-switch">';
                    $contenuForm .= '<input class="flowfinder-form-check-input" name="switch-' . $key . '" type="checkbox" id="template-' . $key . '-flexSwitch" value="" checked style="display:none">';
                    $contenuForm .= '</div>';

                    foreach ($options as $index => $option) {

                        // Vérifie si l'option contient ":checked"
                        $isChecked = str_ends_with(trim($option), ':checked');
                        // Extrait le label
                        $label = $isChecked ? trim(substr(
                            $option,
                            0,
                            strrpos($option, ':checked')
                        )) : trim($option);

                        $contenuForm .= '<div class="flowfinder-form-interrupteur flowfinder-form-check form-switch">';
                        $contenuForm .= '<input class="flowfinder-form-check-input" name="switch-' . $key . '" type="checkbox" id="template-' . $key . '-flexSwitch-' . $i . '" value="' . htmlspecialchars(trim($label)) . '" ' . ($isChecked ? 'checked' : ' ') . '>';
                        $contenuForm .= '<label class="flowfinder-form-check-label flowfinder-form-label flowfinder-content-label" for="template-' . $key . '-flexSwitch-' . $i . '">';
                        $contenuForm .= htmlspecialchars(trim($label));
                        $contenuForm .= '</label>';
                        $contenuForm .= '</div>';
                        $i++;
                    }
                    $contenuForm .= '</div>';
                    break;

                case 'note':
                    // Récupère la valeur maximale de la note
                    $maxNote = $object->Choix->maxnote->{'Option-InputNumber'};

                                    
                    // Génère l'HTML pour l'affichage des étoiles
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $contenuForm .= '<div class="flowfinder-rating" id="rating-' . htmlspecialchars($key) . '">';

                    
                    // Crée le fait que aucun soit checké afin d'envoyer quand meme la donnée
                    $contenuForm .= '<div class="flowfinder-rating-etoile">';
                    $contenuForm .= '<input for="template-' . $key .'-star0" type="radio" name="rating-' . htmlspecialchars($key) . '" id="template-' . htmlspecialchars($key) . '-star0" value="0" checked style="display:none;">';
                    $contenuForm .= '</div>';

                    // Crée les boutons radio pour les étoiles
                    for ($i = 1; $i <= $maxNote; $i++) {
                        $contenuForm .= '<div class="flowfinder-rating-etoile">';
                        $contenuForm .= '<input for="template-' . $key .'-star' . $i . '" type="radio" name="rating-' . htmlspecialchars($key) . '" id="template-' . htmlspecialchars($key) . '-star' . $i . '" value="' . $i . '">';
                        $contenuForm .= '<label for="' . htmlspecialchars($key) . '-star' . $i . '" onmouseover="flowfinder_highlightStars(' . $i . ', \'' . htmlspecialchars($key) . '\')" onmouseout="flowfinder_resetStars(\'' . htmlspecialchars($key) . '\')" onclick="flowfinder_selectStars(' . $i . ', \'' . htmlspecialchars($key) . '\')">★</label>';
                        $contenuForm .= '</div>';
                    }

                    $contenuForm .= '</div>';
                    break;

                case 'htmlbloc':
                    $contenuForm .= '<div class="flowfinder-htmlbloc">';
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $contenuForm .= '<label for="template-' . $key . ' " class="flowfinder-form-label flowfinder-content-label">' . $object->Choix->htmlbloc->{'Option-InputTextarea'} . '</label>';
                    $contenuForm .= '';
                    $contenuForm .= '</div>';
                    break;

                    $contenuForm .= '</div>';
                    break;

                case 'date':
                    $contenuForm .= '<div class="flowfinder-form-date">';
                    $contenuForm .= '<label for="idtemplate-' . $key . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $contenuForm .= '<input type="date" class="flowfinder-form-control"  name="date-' . htmlspecialchars($key) . '" id="idtemplate-' . $key . '">';
                    $contenuForm .= '</div>';
                    break;


                    //liste déroulante à faire
                case 'listederoulante':
                    // Récupérer les options en les divisant par ";"
                    $options = explode(';', $object->Choix->liste->{'Option-InputText'});
                    $contenuForm .= '<div class="flowfinder-form-group">';
                    $contenuForm .= '<label for="' . htmlspecialchars($key) . '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' . htmlspecialchars($label) . '</label>';
                    $contenuForm .= '<select class="flowfinder-form-select" id="' . htmlspecialchars($key) . '" name="' . htmlspecialchars($key) . '"';

                    // Vérifie si l'attribut multiple est défini
                    if (isset($object->Choix->{'multiple'}) && $object->Choix->{'multiple'} == true) {
                        $contenuForm .= ' multiple';
                    }
                    $contenuForm .= '>';

                    // Boucle pour générer les options
                    foreach ($options as $option) {
                        if (trim($option) !== "") {
                            // Vérifie si l'option contient ":selected"
                            $isSelected = str_ends_with(trim($option), ':selected');
                            // Extrait la valeur réelle de l'option
                            $optionValue = $isSelected ? trim(substr($option, 0, strrpos($option, ':selected'))) : trim($option);

                            // Ajout de l'option à la liste déroulante
                            $contenuForm .= '<option value="' . htmlspecialchars($optionValue) . '"';
                            $contenuForm .= $isSelected ? ' selected' : '';
                            $contenuForm .= '>';
                            $contenuForm .= htmlspecialchars($optionValue); // Texte affiché pour l'option
                            $contenuForm .= '</option>';
                        }
                    }
                    $contenuForm .= '</select>';
                    $contenuForm .= '</div>';
                    break;
            }

            // Gestion des séparateurs de page
            if (isset($object->Choix->Select) && $object->Choix->Select == 'seppage') {
                if ($pageNumber <= $seppage) {
                    if (isset($object->Choix->Suivant) && $object->Choix->Suivant->{"Option-InputText"}){
                        $texte_btn_suivant = $object->Choix->Suivant->{"Option-InputText"};
                    }else{
                        $texte_btn_suivant = 'Suivant';
                    }

                    if (isset($object->Choix->Precedent) && $object->Choix->Precedent->{"Option-InputText"}){
                        $texte_btn_precedent = $object->Choix->Precedent->{"Option-InputText"};
                    }else{
                        $texte_btn_precedent = 'Retour';
                    }

                    $contenuForm .= '<div class="flowfinder-form-navigation">';
                    if ($pageNumber > 0 
                        && (
                            !isset($object->Choix->Precedent) 
                            || isset($object->Choix->Precedent) && $object->Choix->Precedent->{"Option-InputSwitch"} != 2
                        ) 
                    ) {
                        $contenuForm .= '<button class="flowfinder-btn flowfinder-btn-primary float-start flowfinder-content-prevBtn">';
                        $contenuForm .= $texte_btn_precedent;
                        $contenuForm .= '</button>';
                    }
                    if ($pageNumber < $seppage 
                        && (
                            !isset($object->Choix->Suivant) 
                            || isset($object->Choix->Suivant) && ($object->Choix->Suivant->{"Option-InputSwitch"} != 2)
                        )   
                    ) {
                        $contenuForm .= '<button class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-nextBtn">';
                        $contenuForm .= $texte_btn_suivant ;
                        $contenuForm .= '</button>';
                    }
                    $contenuForm .= '</div>';
                }
                $contenuForm .= '</div>';
                $pageNumber++;
                $contenuForm .= '<div class="flowfinder-step flowfinder-d-none" id="flowfinder-step' . $pageNumber . '">';
            }

            if ($object->Choix->Select == 'findepage') {
                
                if ($object->Choix->Send->{"Option-InputText"}){
                    $texte_btn_send = $object->Choix->Send->{"Option-InputText"};
                }else{
                    $texte_btn_send = 'Envoyer';
                }
                
                if ($object->Choix->Precedent->{"Option-InputText"}){
                    $texte_btn_precedent = $object->Choix->Precedent->{"Option-InputText"};
                }else{
                    $texte_btn_precedent = 'Retour';
                }

                if ($object->Choix->htmlbloc->{"Option-InputTextarea"}){
                    $texte_de_fin = $object->Choix->htmlbloc->{"Option-InputTextarea"};
                }else {
                    $texte_de_fin = 'Votre demande a bien été prise en compte. <br/>Merci de votre confiance !';
                }
    
                $contenuForm .= '<div class="flowfinder-form-navigation">';

                if ($seppage > 0 && $object->Choix->Precedent->{"Option-InputSwitch"} != 2) {
                $contenuForm .= '<button class="flowfinder-btn flowfinder-btn-primary float-start flowfinder-content-prevBtn">';
                $contenuForm .= $texte_btn_precedent;
                $contenuForm .= '</button>';
                }

                if ($object->Choix->Send->{"Option-InputSwitch"} != 2) {
                $contenuForm .= '<button type="submit" class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-sendBtn" id="envoyer_reponses">';
                $contenuForm .= $texte_btn_send;
                $contenuForm .= '</button>';
                }

                $contenuForm .= '</div>';
                //fermer l'avant dernier flowfinder-step
                $contenuForm .= '</div>';

                //créer le dernier message validation flowfinder-step
                
                $contenuForm .= '<div class="flowfinder-step flowfinder-d-none flowfinder-step-fin" id="flowfinder-step' . $pageNumber+1 . '"><p>';
                $contenuForm .= $texte_de_fin;
                
                $contenuForm .= '</p></div>';

                return $contenuForm;
            }
        }        
    }
}
