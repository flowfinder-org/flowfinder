
<section id="profil" class="col-12 col-xl-12 mx-auto section background-creative mt-5 pb-5">
    <div class="container col-12 col-md-10">
        <div class="row align-items-xl-top gy-5">
                   
            <div class="col-md-6 ">
                <div class="bloc_presentation mt-2">
                    <h3 class="mt-4">Paramètres de Collecte et Événements</h3>
                    <h6 class="heading-small text-muted mb-4">Ajoutez le fragment de code ci-dessous à vos pages web pour commencer à collecter des données sur vos visiteurs et leurs sessions. Ce code est également nécessaire pour déclencher l'affichage des sondages et des enquêtes.</h6>
                    <div>
                        <div class="mb-3">
                            <label for="PageSettings_SnippetAnalytics" class="form-label">Fragment de code :</label>
                            <textarea class="form-control" id="PageSettings_SnippetAnalytics" readonly="true" rows="9" style="font-size: 10px; background-color:#EEEEEE;"><?php $ak->ecrit($snippet_analytics); ?></textarea>                            
                        </div>
                    </div>
                    <hr/>
                    <div>
                        <label class="form-label">Déclencheur d'enregistrements:</label>
                        <div class="mb-3">
                            <label for="PageSettings_DeclencheurEnregistrementInput" class="text-muted p-2">Enregistrer les URL qui correspondent à la regexp :</label>
                            <input type="text" class="form-control  ms-2" id="PageSettings_DeclencheurEnregistrementInput" placeholder="ex: .*/produit/.* pour les page contenant /produit/" value="<?php $ak->ecrit($declencheurs_enregistrement_regexp); ?>">
                        </div>
                        <div class="mb-3">
                            <div id="PageSettings_DeclencheurEnregistrementSuccessDiv" class="alert alert-success d-none">Enregistrement validé.</div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-primary" onclick="page_settings_enregistre_declencheur_enregistrement()">Enregistrer</button>
                        </div>
                    </div>
                    <hr/>
                    <label id="declencheurs_formulaires" class="form-label">Déclencheurs de formulaires:</label>
                    <div>
                        <table class="w-100 mb-2">
            <?php foreach($forms_declencheurs as $form_declencheur){ 
                $type_page_nom = "formulaire";

                switch ($form_declencheur->type_page) {
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

            ?>
                            <tr style="border-bottom: 1px solid #CCC;" id="bloc_declencheur_<?php $ak->ecrit($form_declencheur->id_declencheur); ?>">
                                <td class="text-muted p-2" style="line-height:1.5rem; ">
                                    <br/>
                                    <?php if($form_declencheur->seconds_delay == -1){ ?>
                                        Integration du <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($type_page_nom); ?></span> numéro <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->id_page_configuration) ?></span>
                                        <br />
                                        sans affichage automatique
                                        <span data-bs-toggle="tooltip" data-bs-placement="right" title="Vous pouvez déclencher l'affichage avec l'appel javascript à 'flowfinderShowForm(<?php $ak->ecrit($form_declencheur->type_page) ?>, <?php $ak->ecrit($form_declencheur->id_page_configuration) ?>);' ou avec le target bootstrap 'flowfinder-form-<?php $ak->ecrit($form_declencheur->type_page) ?>_<?php $ak->ecrit($form_declencheur->id_page_configuration) ?>'">
                                            <svg id="svg2" xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 220 220" version="1.0">
                                                <path stroke="blue" id="path2382" d="m165.33 113.44a103.61 103.61 0 1 1 -207.22 0 103.61 103.61 0 1 1 207.22 0z" transform="matrix(.93739 0 0 .93739 42.143 -6.3392)" stroke-width="0" fill="#fff"/>
                                                <g id="layer1">
                                                    <path id="path2413"  stroke="blue" d="m100 0c-55.2 0-100 44.8-100 100-5.0495e-15 55.2 44.8 100 100 100s100-44.8 100-100-44.8-100-100-100zm0 12.812c48.13 0 87.19 39.058 87.19 87.188s-39.06 87.19-87.19 87.19-87.188-39.06-87.188-87.19 39.058-87.188 87.188-87.188zm1.47 21.25c-5.45 0.03-10.653 0.737-15.282 2.063-4.699 1.346-9.126 3.484-12.876 6.219-3.238 2.362-6.333 5.391-8.687 8.531-4.159 5.549-6.461 11.651-7.063 18.687-0.04 0.468-0.07 0.868-0.062 0.876 0.016 0.016 21.702 2.687 21.812 2.687 0.053 0 0.113-0.234 0.282-0.937 1.941-8.085 5.486-13.521 10.968-16.813 4.32-2.594 9.808-3.612 15.778-2.969 2.74 0.295 5.21 0.96 7.38 2 2.71 1.301 5.18 3.361 6.94 5.813 1.54 2.156 2.46 4.584 2.75 7.312 0.08 0.759 0.05 2.48-0.03 3.219-0.23 1.826-0.7 3.378-1.5 4.969-0.81 1.597-1.48 2.514-2.76 3.812-2.03 2.077-5.18 4.829-10.78 9.407-3.6 2.944-6.04 5.156-8.12 7.343-4.943 5.179-7.191 9.069-8.564 14.719-0.905 3.72-1.256 7.55-1.156 13.19 0.025 1.4 0.062 2.73 0.062 2.97v0.43h21.598l0.03-2.4c0.03-3.27 0.21-5.37 0.56-7.41 0.57-3.27 1.43-5 3.94-7.81 1.6-1.8 3.7-3.76 6.93-6.47 4.77-3.991 8.11-6.99 11.26-10.125 4.91-4.907 7.46-8.26 9.28-12.187 1.43-3.092 2.22-6.166 2.46-9.532 0.06-0.816 0.07-3.03 0-3.968-0.45-7.043-3.1-13.253-8.15-19.032-0.8-0.909-2.78-2.887-3.72-3.718-4.96-4.394-10.69-7.353-17.56-9.094-4.19-1.062-8.23-1.6-13.35-1.75-0.78-0.023-1.59-0.036-2.37-0.032zm-10.908 103.6v22h21.998v-22h-21.998z"/>
                                                </g>
                                            </svg>
                                        </span>
                                    <?php }else if($form_declencheur->seconds_delay < 2){ ?>
                                        Affichage du <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($type_page_nom); ?></span> numéro <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->id_page_configuration) ?></span>
                                        <br />
                                        avec un délai de <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->seconds_delay) ?></span> seconde
                                    <?php }else{ ?>
                                        Affichage du <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($type_page_nom); ?></span> numéro <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->id_page_configuration) ?></span>
                                        <br />
                                        avec un délai de <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->seconds_delay) ?></span> secondes
                                    <?php } ?>
                                    <br/>
                                    sur les url répondant à <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->url_regexp) ?></span>
                        <?php if($form_declencheur->inject_into_elem_id != null && trim($form_declencheur->inject_into_elem_id) != ""){ ?>
                                    <br/>
                                    dans l'élement HTML identifié par <span class="badge bg-secondary" style="font-size: 0.7rem;"><?php $ak->ecrit($form_declencheur->inject_into_elem_id) ?></span>
                        <?php } ?>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary" onclick="affichePopupModifieDeclencheur(<?php $ak->ecrit($form_declencheur->id_declencheur); ?>); return false;">Modifier</button>
                                    <br/>
                                    <button class="btn btn-sm btn-danger mt-1" onclick="supprimeDeclencheurDemandeConfirmation(<?php $ak->ecrit($form_declencheur->id_declencheur); ?>); return false;">Supprimer</button>
                                </td>
                            </div>
            <?php } ?>
                        </table>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-sm btn-primary" onclick="affichePopupAjouteDeclencheur();">Ajouter un déclencheur</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>


<script>

    function page_settings_enregistre_declencheur_enregistrement()
    {
        var declencheurEnregistrementInput = document.getElementById("PageSettings_DeclencheurEnregistrementInput");
        var declencheurEnregistrementSuccessDiv = document.getElementById("PageSettings_DeclencheurEnregistrementSuccessDiv");
        
        declencheurEnregistrementSuccessDiv.classList.add("d-none");
        
        var regexp = declencheurEnregistrementInput.value;

        const xhttp = new XMLHttpRequest();
        xhttp.addEventListener("load", (event) => {
            var data = JSON.parse(event.target.responseText);
            if (data["success"] == "ok") {
                declencheurEnregistrementSuccessDiv.classList.remove("d-none");
                setTimeout(function(){
                    declencheurEnregistrementSuccessDiv.classList.add("d-none");
                }, 2500);
            }
            else
            {
                alert("Une erreur est survenue");
            }
        });
        var form_data = new FormData();
        form_data.append("regexp", regexp);
        xhttp.open("POST", "/" + getCurrentLangFromUrl() + "/Settings/enregistre_declencheur_enregistrement");
        xhttp.send(form_data);
    }

    function page_settings_url_identifier_validate_button()
    {
        var urlIdentifierInput = document.getElementById("PageSettings_UrlIdentifierInput");
        var urlIdentifierErrorMessageDiv = document.getElementById("PageSettings_UrlIdentifierErrorMessageDiv");
        var urlIdentifierWarningMessageDiv = document.getElementById("PageSettings_UrlIdentifierWarningMessageDiv");
        var urlIdentifierSuccessMessageDiv = document.getElementById("PageSettings_UrlIdentifierSuccessMessageDiv");
        var urlIdentifierPreviewSpan = document.getElementById("PageSettings_UrlIdentifierPreviewSpan");
        var urlIdentifierValidationBouton = document.getElementById("PageSettings_UrlIdentifierValidationBouton");

        urlIdentifierWarningMessageDiv.classList.add("d-none");
        urlIdentifierSuccessMessageDiv.classList.add("d-none");
        urlIdentifierErrorMessageDiv.classList.add("d-none");
        urlIdentifierValidationBouton.setAttribute("disabled", "true");

        var candidat = urlIdentifierInput.value;

        const xhttp = new XMLHttpRequest();
        xhttp.addEventListener("load", (event) => {
            var data = JSON.parse(event.target.responseText);
            if (data["success"] == "ok") {
                urlIdentifierWarningMessageDiv.classList.add("d-none");
                urlIdentifierErrorMessageDiv.classList.add("d-none");
                urlIdentifierSuccessMessageDiv.classList.remove("d-none");
                urlIdentifierSuccessMessageDiv.textContent = "Votre code a été reservé, il sera totalement opérationel dans moins de 72 heures";
                urlIdentifierValidationBouton.setAttribute("disabled", "true");
            }
            else if (data["success"] == "pas_libre") {
                urlIdentifierWarningMessageDiv.classList.add("d-none");
                urlIdentifierSuccessMessageDiv.classList.add("d-none");
                urlIdentifierErrorMessageDiv.classList.remove("d-none");
                urlIdentifierErrorMessageDiv.textContent = "Ce code n'est pas disponible.";
                urlIdentifierValidationBouton.setAttribute("disabled", "true");
            }
            else
            {
                urlIdentifierWarningMessageDiv.classList.add("d-none");
                urlIdentifierSuccessMessageDiv.classList.add("d-none");
                urlIdentifierErrorMessageDiv.classList.remove("d-none");
                urlIdentifierErrorMessageDiv.textContent = "Une erreur est survenue";
                urlIdentifierValidationBouton.setAttribute("disabled", "true");
            }
        });
        var form_data = new FormData();
        form_data.append("candidat", candidat);
        xhttp.open("POST", "/" + getCurrentLangFromUrl() + "/Settings/enregistre_url_identifier");
        xhttp.send(form_data);
    }

    function affichePopupAjouteDeclencheur() {
        afficherModal();
        fetch('/Settings/AfficheAjouteDeclencheurPopup')
            .then(response => response.text())
            .then(data => {
                document.getElementById('modal-content-contenu').innerHTML = data;
            })

            .catch(error => console.error('Erreur lors du chargement de la page:', error));
        document.getElementById('modal-footer').style.display = 'none';
    }

    function enregistrePopupAjouteDeclencheur() {
        var form = document.getElementById('nouveau_declencheur_form');

        const xhttp = new XMLHttpRequest();
        var form_data = new FormData(form);

        xhttp.addEventListener("load", (event) => {
            try {
                var data = JSON.parse(event.target.responseText);
                if (data["success"] == "yes") {
                    cacherModal();
                    alert("Enregistrement effectué, veuillez recharger la page.");
                }
                else {
                    alert("Une erreur est survenue");
                }
            } catch (error) {
                console.log(error);
                alert("Une erreur est survenue");
            }
        });

        xhttp.open("POST", "/" + getCurrentLangFromUrl() + "/Settings/EnregistreAjouteDeclencheurPopup");
        xhttp.send(form_data);

        return false;
    }

    function supprimeDeclencheurDemandeConfirmation(id_declencheur) {
        if (confirm("Voulez vous vraiment supprimer ce déclencheur ? Il ne sera pas possible d'annuler cette suppression.")) {
            const xhttp = new XMLHttpRequest();
            var form_data = new FormData();

            xhttp.addEventListener("load", (event) => {
                try {
                    var data = JSON.parse(event.target.responseText);
                    if (data["success"] == "yes") {
                        cacherModal();
                        //alert("Suppression effectué, veuillez recharger la page.");
                        let bloc_declencheur = document.getElementById("bloc_declencheur_" + id_declencheur);
                        bloc_declencheur.parentElement.removeChild(bloc_declencheur);
                    }
                    else {
                        alert("Une erreur est survenue");
                    }
                } catch (error) {
                    console.log(error);
                    alert("Une erreur est survenue");
                }
            });

            form_data.append("id_declencheur", id_declencheur);
            xhttp.open("POST", "/" + getCurrentLangFromUrl() + "/Settings/SupprimeDeclencheur");
            xhttp.send(form_data);

        }

        return false;
    }

    function affichePopupModifieDeclencheur(id_declencheur) {
        afficherModal();
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();

        xhttp.addEventListener("load", (event) => {
            try {
                document.getElementById('modal-content-contenu').innerHTML = event.target.responseText;
                document.getElementById('modal-footer').style.display = 'none';
            } catch (error) {
                console.log(error);
                alert("Une erreur est survenue");
            }
        });

        form_data.append("id_declencheur", id_declencheur);
        xhttp.open("POST", "/" + getCurrentLangFromUrl() + '/Settings/AfficheModifieDeclencheurPopup');
        xhttp.send(form_data);

    }

    function enregistrePopupModifieDeclencheur() {
        var form = document.getElementById('modifie_declencheur_form');

        const xhttp = new XMLHttpRequest();
        var form_data = new FormData(form);

        xhttp.addEventListener("load", (event) => {
            try {
                var data = JSON.parse(event.target.responseText);
                if (data["success"] == "yes") {
                    cacherModal();
                    alert("Enregistrement effectué, veuillez recharger la page.");
                }
                else {
                    alert("Une erreur est survenue");
                }
            } catch (error) {
                console.log(error);
                alert("Une erreur est survenue");
            }
        });

        xhttp.open("POST", "/" + getCurrentLangFromUrl() + "/Settings/EnregistreModifieDeclencheurPopup");
        xhttp.send(form_data);

        return false;
    }


</script>
