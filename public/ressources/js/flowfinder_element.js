class SortableList {
    constructor(containerId) {
        this.lang = getCurrentLangFromUrl();
        this.container = document.getElementById(containerId);
        this.draggedItem = null;
        this.typepage = null;
        this.numpage = null;
        this.orderelements = null;
        this.optionsvaleurs = [];
        this.datajsoninscription = []
        this.errorConfiguration = false;
        this.errorElements = false;
        this.findepage = false;
        this.initializefunction = false;
        // Initialisation des événements selon la page
        let elementportail = document.querySelector('[id^="themepage-portail"]');
        let elementtunnel = document.querySelector('[id^="themepage-tunnel"]');
        let elementsondage = document.querySelector('[id^="themepage-sondage"]');
        let elementfeedback = document.querySelector('[id^="themepage-feedback"]');
        let elementformulaire = document.querySelector('[id^="themepage-formulaire"]');

        //nombre de création 
        this.callAjouterElement = 1;

        if (elementportail) {
            let id = elementportail.id;
            this.numpage = id.split('themepage-portail-')[1];
            this.typepage = 'portail';
        }

        if (elementtunnel) {
            let id = elementtunnel.id;
            let elementtypeselector = elementtunnel;
            this.numpage = id.split('themepage-tunnel-')[1];
            this.typepage = 'tunnel';
        }

        if (elementsondage) {
            let id = elementsondage.id;
            let elementtypeselector = elementsondage;
            this.numpage = id.split('themepage-sondage-')[1];
            this.typepage = 'sondage';
        }

        if (elementfeedback) {
            let id = elementfeedback.id;
            let elementtypeselector = elementfeedback;
            this.numpage = id.split('themepage-feedback-')[1];
            this.typepage = 'feedback';
        }

        if (elementformulaire) {
            let id = elementformulaire.id;
            let elementtypeselector = elementformulaire;
            this.numpage = id.split('themepage-formulaire-')[1];
            this.typepage = 'formulaire';
        }

        // Initialisation des boutons ajouter évènements
        const ajouterElement = document.getElementById('ajouter_element');
        if (ajouterElement) {
            ajouterElement.addEventListener('click', (e) => {
                e.preventDefault();
                this.ajouterElement();
            });
        }
    }

    //à l'initialisation
    initialize() {
        this.container.addEventListener('dragstart', this.onDragStart.bind(this));
        this.container.addEventListener('dragover', this.onDragOver.bind(this));
        this.container.addEventListener('dragend', this.onDragEnd.bind(this));

        // Initialiser les inputs avec le crayon
        this.initializeAdjustInputWidth();

        // Initialiser les événements des switchs
        this.initializeSwitchEvents();

        // Initialiser les événements de suppression et d'édition
        this.initializeIconEvents();

        if (this.typepage == 'tunnel' || this.typepage == 'sondage' || this.typepage == 'feedback' || this.typepage == 'formulaire') {
            // Initilaiser les options aux niveaux du choix des inputs
            //declinaison créer
            this.initializeFormChoix();

            //Applique les donnees des options
            this.initializeFormOptions();
            //Applique les changements des options
            this.initialiseFormOptionsChange();

            // Ajouter un event listener pour le clic sur le lien
            document.getElementById('inscriptions-tab').addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le comportement par défaut du lien
                this.LoadInscriptions();
            });

        }
        //GG si il n'y à pas de fin de page on va en ajouter une
        if (this.findepage == false) {
            this.ajouterElement(false, 'findepage');
        }
        this.PrevisualisationPortable();
    }

    initializenewid() {
        //reintialiser tout pas genant
        this.initializeAdjustInputWidth();
        this.initializeSwitchEvents();
        this.initializeIconEvents();
        this.PrevisualisationPortable();
        this.initialiseFormOptionsChange();
    }

    initializeSwitchEvents() {
        document.querySelectorAll('.flowfinder-SortableElement-InputSwitch-Active').forEach(switches => {
            switches.addEventListener('click', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });
    }

    initializeIconEvents() {
        document.querySelectorAll('.delete-icon').forEach(icon => {
            icon.addEventListener('click', (e) => {
                const item = e.target.closest('.SortableElement');
                if (item) {
                    this.DeleteLien(item);
                    item.remove();
                    this.PrevisualisationPortable();
                }
            });
        });

        document.querySelectorAll('.edit-icon').forEach(icon => {
            icon.addEventListener('click', (e) => {
                const item = e.target.closest('.SortableElement');
                this.editItem(item, e.target);
            });
        });
    }

    //special tunnel FormChoix Global
    initializeFormChoix() {
        document.querySelectorAll('.flowfinder-SortableElement-InputSelect-Choix').forEach(choix => {
            let parentElement = choix.closest('.item-type-form');
            let grandparentElement = choix.closest('.SortableElement');
            if (parentElement != null && grandparentElement != null) {
                let option = parentElement.querySelector('.flowfinder-SortableElement-InputOptions-Choix');
                let recuptemplate = document.getElementById('TemplateOption-' + choix.value).innerHTML;
                option.innerHTML = this.replaceTemplateId(recuptemplate, grandparentElement.id)
                choix.addEventListener('change', (e) => {
                    this.FormChoix(grandparentElement.id);
                    this.CacheTitle(grandparentElement.id);
                    this.EnregistrerElement(grandparentElement.id);
                    this.initialiseFormOptionsChange();
                    this.PrevisualisationPortable();
                });

                //initialize CacheTitle
                this.CacheTitle(grandparentElement.id);
            }
        });
    }

    CacheTitle(itemid) {
        let divelement = document.getElementById(itemid);
        if (divelement) {
            //cacher l'édition du titre car inutile pour le seppage et le html page sinon c'est visible et autres
            let selectchoix = divelement.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
            let title_seppage = divelement.querySelector('.flowfinder-topbar-SortableElement');
            
            if (selectchoix.value == 'seppage' || selectchoix.value == 'htmlbloc' || selectchoix.value == 'findepage') {
                title_seppage.style.visibility = 'hidden';
            } else {
                title_seppage.style.visibility = 'visible';
            }
            if (selectchoix.value == 'findepage') {
                let bas_page = divelement.querySelector('.flowfinder-SortableElement-info');
                let select_choix = divelement.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
                let barre_top = divelement.querySelector('.flowfinder-SortableElement-modif');
                
                select_choix.disabled = true;
                //divelement.removeAttribute("draggable");
                barre_top.style.display = 'none';
                bas_page.style.visibility = 'hidden';
            }
        }
    }

    FormChoix(itemid) {
        let divelement = document.getElementById(itemid);
        if (divelement) {
            let selectchoix = divelement.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
            let option = divelement.querySelector('.flowfinder-SortableElement-InputOptions-Choix');
            let recuptemplate = document.getElementById('TemplateOption-' + selectchoix.value).innerHTML;
            option.innerHTML = this.replaceTemplateId(recuptemplate, itemid);
        }
    }

    initializeNewFormChoix(itemid) {
        let divelement = document.getElementById(itemid);
        if (divelement) {
            let selectchoix = divelement.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
            let option = divelement.querySelector('.flowfinder-SortableElement-InputOptions-Choix');
            let recuptemplate = document.getElementById('TemplateOption-' + selectchoix.value).innerHTML;
            option.innerHTML = this.replaceTemplateId(recuptemplate, itemid);
            selectchoix.addEventListener('change', (e) => {
                this.FormChoix(itemid);
                this.EnregistrerElement(itemid);
                this.initialiseFormOptionsChange();
            });
        }
    }

    initializeTemplateType(itemid, type) {
        let divelement = document.getElementById(itemid);
        if (divelement) {
            let selectchoix = divelement.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
            let option = divelement.querySelector('.flowfinder-SortableElement-InputOptions-Choix');
            selectchoix.value = type;
            let recuptemplate = document.getElementById('TemplateOption-' + type).innerHTML;
            option.innerHTML = this.replaceTemplateId(recuptemplate, itemid);
            this.FormChoix(itemid);
            this.EnregistrerElement(itemid);
            if (type == 'findepage') {
                this.findepage = true;
            }
        }
    }

    //special tunnel option choix Initialiser les valeurs des optons
    initializeFormOptions() {
        //On va remplir avec les bonnes valeurs
        let optionradioform;
        let optiontextform;
        let optiontextareaform;
        let optionnumerbform;
        let optioncheckboxform;
        let optionswitchform;

        for (let data in this.optionsvaleurs) {
            let ligne = this.optionsvaleurs[data];
            //on va chargé les données à l'initialisation pour les Option-Radio
            if (ligne.typededonnee == 'Option-InputRadio') {
                if (ligne.id != null && ligne.nomform != null && ligne.donnee != null) {
                    optionradioform = document.getElementById(ligne.id + '-' + ligne.nomform + '-' + ligne.typededonnee + '-' + ligne.donnee);
                    if (optionradioform) {
                        optionradioform.checked = true;
                    }
                }
            }

            //on va chargé les données à l'initialisation pour les Option-Checkbox
            if (ligne.typededonnee == 'Option-InputCheckbox') {
                if (ligne.id != null && ligne.nomform != null && ligne.donnee != null) {
                    optioncheckboxform = document.getElementById(ligne.id + '-' + ligne.nomform + '-' + ligne.typededonnee);

                    if (optioncheckboxform) {
                        optioncheckboxform.checked = true;
                    }

                }
            }

            //on va chargé les données à l'initialisation pour les Option-Switch
            if (ligne.typededonnee == 'Option-InputSwitch') {
                if (ligne.id != null && ligne.nomform != null && ligne.donnee) {
                    optionswitchform = document.getElementById(ligne.id + '-' + ligne.nomform + '-' + ligne.typededonnee);
                    if (optionswitchform && ligne.donnee == 1) {
                        optionswitchform.checked = true;
                    } else if (optionswitchform) {
                        optionswitchform.checked = false;
                    }

                }
            }

            //on va chargé les données à l'initialisation pour les Texte
            if (ligne.typededonnee == 'Option-InputText') {
                if (ligne.id != null && ligne.nomform != null && ligne.donnee != null) {
                    optiontextform = document.getElementById(ligne.id + '-' + ligne.nomform + '-' + ligne.typededonnee);
                    if (optiontextform) {
                        optiontextform.value = ligne.donnee;
                    }
                }
            }

            //on va chargé les données à l'initialisation pour les Textearea
            if (ligne.typededonnee == 'Option-InputTextarea') {
                if (ligne.id != null && ligne.nomform != null && ligne.donnee != null) {
                    optiontextareaform = document.getElementById(ligne.id + '-' + ligne.nomform + '-' + ligne.typededonnee);
                    if (optiontextareaform) {
                        optiontextareaform.value = ligne.donnee;
                    }
                }
            }

            //on va chargé les données à l'initialisation pour les Nombre
            if (ligne.typededonnee == 'Option-InputNumber') {
                if (ligne.id != null && ligne.nomform != null && ligne.donnee != null) {
                    optionnumerbform = document.getElementById(ligne.id + '-' + ligne.nomform + '-' + ligne.typededonnee);
                    if (optionnumerbform) {
                        optionnumerbform.value = ligne.donnee;
                    }
                }
            }
        }
    }

    IAInitialiseInputSubmitOption() {
        var radios = document.querySelectorAll('input[type="radio"][id^="sendvalue-v"]');
        radios.forEach((radio) => {
            radio.addEventListener('change', (e) => {
                this.IAInitialiseFormOption();
            });
        });

        document.querySelector('#questionnaireForm').addEventListener('submit', (event) => {
            event.preventDefault();
            this.IAEnvoyerQuestionnaireTunnel(event);
            var envoyer_reponses = document.querySelector('#envoyer_reponses');
            envoyer_reponses.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Je réfléchis ...
        `;
            envoyer_reponses.disabled = true;
        });
    }

    InitialiseInputSubmitOption() {
        var radios = document.querySelectorAll('input[type="radio"][id^="sendvalue-v"]');
        radios.forEach((radio) => {
            radio.addEventListener('change', (e) => {
                this.InitialiseFormOption();
            });
        });

        document.querySelector('#questionnaireForm').addEventListener('submit', (event) => {
            event.preventDefault();
            this.EnvoyerQuestionnaireTunnel(event);
            var envoyer_reponses = document.querySelector('#envoyer_reponses');
            envoyer_reponses.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Je réfléchis ...
        `;
            envoyer_reponses.disabled = true;
        });
    }

    initialiseFormOptionsChange() {
        //initialize Tous les boutons options
        document.querySelectorAll('input[type="radio"][name$="Option-InputRadio"]').forEach(select => {
            select.addEventListener('click', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });

        //initialize Toutes les checkbox
        document.querySelectorAll('input[type="checkbox"][name$="Option-InputCheckbox"]').forEach(select => {
            select.addEventListener('click', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });

        //initialize Toutes les switchs optoin
        document.querySelectorAll('input[type="checkbox"][name$="Option-InputSwitch"]').forEach(select => {
            select.addEventListener('click', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });

        document.querySelectorAll('input[type="text"][name$="Option-InputText"]').forEach(select => {
            select.addEventListener('input', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });

        document.querySelectorAll('textarea[name$="Option-InputTextarea"]').forEach(select => {
            select.addEventListener('input', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });

        document.querySelectorAll('input[type="number"][name$="Option-InputNumber"]').forEach(select => {
            select.addEventListener('input', (e) => {
                const item = e.target.closest('.SortableElement');
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            });
        });

    }

    initializeAdjustInputWidth() {
        document.querySelectorAll('.dynamic-input').forEach(input => {
            let parentDiv = input.closest('.flowfinder-SortableElement-Width');
            if (parentDiv) {
                // Obtenir la largeur de la div parent en pixels
                let largeurMax = parentDiv.clientWidth;
                // Calculer la largeur en fonction du texte (1 caractère ~ 1ch)
                let tailleTexte = (input.value.length + 1) * 8;
                // Ajuster la largeur de l'input, mais ne pas dépasser la largeur de la div parent
                input.style.width = Math.min(tailleTexte, largeurMax - 20) + "px"; // Utiliser px pour la cohérence avec la div - 20px de l'icone crayon
            }
        });
    }

    AdjustInputWidth(itemid) {
        let itemparent = document.querySelector('#' + itemid);
        itemparent.querySelectorAll('.dynamic-input').forEach(input => {
            let parentDiv = input.closest('.flowfinder-SortableElement-Width');
            if (parentDiv) {
                // Obtenir la largeur de la div parent en pixels
                let largeurMax = parentDiv.clientWidth;
                // Calculer la largeur en fonction du texte (1 caractère ~ 1ch)
                let tailleTexte = (input.value.length + 1) * 8;
                // Ajuster la largeur de l'input, mais ne pas dépasser la largeur de la div parent
                input.style.width = Math.min(tailleTexte, largeurMax - 20) + "px"; // Utiliser px pour la cohérence avec la div - 20px de l'icone crayon
            }
        });
    }

    onDragStart(e) {
        if (e.target.closest('.SortableElement')) {
            this.draggedItem = e.target.closest('.SortableElement');
            this.draggedItem.classList.add('dragging');

            if (e.type === 'dragstart') {
                e.dataTransfer.effectAllowed = "move";
            }

            if (e.type === 'touchstart') {
                e.preventDefault(); // Empêcher le comportement par défaut lors du touchstart
                this.startY = e.touches[0].clientY; // Enregistrer la position de départ
            }
        }
    }

    onDragOver(e) {
        e.preventDefault();

        const dragging = document.querySelector('.dragging');
        let clientY;

        if (e.type === 'dragover') {
            clientY = e.clientY;
        }

        if (e.type === 'touchmove') {
            e.preventDefault(); // Empêcher le défilement de la page pendant le touchmove
            clientY = e.touches[0].clientY;
        }

        const afterElement = this.getDragAfterElement(this.container, clientY);
        if (afterElement == null) {
            this.container.appendChild(dragging);
        } else {
            this.container.insertBefore(dragging, afterElement);
        }
    }

    onDragEnd(e) {
        if (this.draggedItem) {
            this.draggedItem.classList.remove('dragging');
            this.draggedItem = null;
        }
        this.EnregistrerConfiguration();
        this.PrevisualisationPortable();
    }

    getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.SortableElement:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Ajouter les événements pour desktop et mobile
    addEventListeners() {
        const sortableElements = document.querySelectorAll('.SortableElement');
        sortableElements.forEach(item => {
            item.addEventListener('dragstart', this.onDragStart.bind(this));
            item.addEventListener('dragover', this.onDragOver.bind(this));
            item.addEventListener('dragend', this.onDragEnd.bind(this));

            // Événements tactiles pour mobile
            item.addEventListener('touchstart', this.onDragStart.bind(this));
            item.addEventListener('touchmove', this.onDragOver.bind(this));
            item.addEventListener('touchend', this.onDragEnd.bind(this));
        });
    }

    // Ajouter les événements pour desktop et mobile
    addEventListeners() {
        const sortableElements = document.querySelectorAll('.SortableElement');
        sortableElements.forEach(item => {
            item.addEventListener('dragstart', this.onDragStart.bind(this));
            item.addEventListener('dragover', this.onDragOver.bind(this));
            item.addEventListener('dragend', this.onDragEnd.bind(this));

            // Événements tactiles pour mobile
            item.addEventListener('touchstart', this.onDragStart.bind(this));
            item.addEventListener('touchmove', this.onDragOver.bind(this));
            item.addEventListener('touchend', this.onDragEnd.bind(this));
        });
    }

    replaceTemplateId(template, newId) {
        // Utilise une expression régulière pour rechercher [id-template]
        const regex = /\[id-template\]/g;
        // Remplace toutes les occurrences trouvées par newId
        return template.replace(regex, newId);
    }

    LoadInscriptions() {
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('numpage', this.numpage);
        xhttp.open("POST", `/${this.lang}/element/liste_inscriptions_form`, true);
        xhttp.send(form_data);
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                try {
                    const response = xhttp.responseText;
                    var datajson = response;
                    this.datajsoninscription = JSON.parse(datajson);
                    datajson = JSON.parse(datajson);
                    this.LoadInscriptionsFromJson(datajson);
                } catch (error) {
                    console.log("error load evenement" + xhttp.responseText + error);
                }
            }
        };
    }

    LoadInscriptionsDatajson() {
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('numpage', this.numpage);
        xhttp.open("POST", `/${this.lang}/element/liste_inscriptions_form`, true);
        xhttp.send(form_data);
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                try {
                    const response = xhttp.responseText;
                    var datajson = response;
                    datajson = JSON.parse(datajson);
                    return datajson;
                } catch (error) {
                    console.log("error load evenement" + xhttp.responseText + error);
                }
            }
        };
    }

    // Méthode pour échapper les caractères HTML
    escapeHTML(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    //LoadJson
    async Loadjson(url) {
        const response = await fetch(url);
        return await response.json();
    }

    
    LoadInscriptionsFromJson(datajson) {
        const container = document.getElementById('contenu_inscription');
        container.innerHTML = '';
        let length = Array.from(datajson.entries()).length;
        let datajsonofficiel = datajson;
        datajson.forEach((item, index) => {

            const formData = JSON.parse(item.json_formdonnees); // Convertir JSON en objet
            const dateSoumission = item.date_soumission;
            // Créer un accordéon Bootstrap
            let accordionItem = `
                <div class="accordion-item">
                  <h2 class="accordion-header" id="heading-${index}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${index}" aria-expanded="true" aria-controls="collapse-${index}">
                      ${length}- Données soumises le ${this.escapeHTML(dateSoumission)}
                    </button>
                  </h2>
                  <div id="collapse-${index}" class="accordion-collapse collapse" aria-labelledby="heading-${index}" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <table class="table">
                        <tbody>`;

            // Parcourir chaque clé et valeur de l'objet
            for (const [key, data] of Object.entries(formData)) {
                const label = this.escapeHTML(data.label ? data.label : key);
                const displayValue = this.escapeHTML(Array.isArray(data.value) ? data.value.join(", ") : data.value); // Gérer les tableaux dans les valeurs

                accordionItem += `
                          <tr>
                            <td><strong>${label}</strong></td>
                          </tr>
                          <tr>
                            <td>${displayValue}</td>
                          </tr>`;
            }

            // Ajouter la date de soumission à la fin du tableau
            accordionItem += `
                          <tr>
                            <td><strong>Date de soumission </strong>${this.escapeHTML(dateSoumission)}</td>
                          </tr>`;

            // Fermer le tableau et l'accordéon
            accordionItem += `
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>`;

            // Insérer l'élément accordéon dans le conteneur
            container.innerHTML += accordionItem;
            length--;
        });

    }

    LoadElements() {
        //load des elements
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);
        xhttp.open("POST", `/${this.lang}/element/load_elements`, true);
        xhttp.send(form_data);
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                try {
                    const response = xhttp.responseText;
                    var datajson = response;
                    this.errorElements = false;
                    datajson = JSON.parse(datajson);
                    this.LoadFromJson(datajson, 'element', () => {
                        this.initialize();
                    });
                } catch (error) {
                    this.errorElements = true;
                    console.log("error load evenement" + xhttp.responseText + error);
                }
            }
        };
    }

    LoadConfiguration() {
        //load des elements
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);
        xhttp.open("POST", `/${this.lang}/element/load_configuration_page`, true);
        xhttp.send(form_data);
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                try {
                    const response = xhttp.responseText;
                    var datajsonconfig = response;
                    //si pas de configuration et que le serveur envoie false
                    if (datajsonconfig == "false") {
                        this.errorConfiguration = true;
                        if (this.typepage == 'tunnel' || this.typepage == 'sondage' || this.typepage == 'feedback' || this.typepage == 'formulaire') {
                            this.ModalChoixTunnel();
                        }
                        if (this.typepage == 'portail') {
                            this.Loadjson('/ressources/json_exemple/portail_configuration.json').then(datajsonconfig => {
                                this.IAPersonnaliseJSONConfigurationPortail(datajsonconfig);
                            });
                        }
                    } else {
                        datajsonconfig = [JSON.parse(datajsonconfig)];
                        this.LoadFromJson(datajsonconfig, 'config', () => {
                            //pas besoin d'initialisation this.initialize();
                        });
                        this.errorConfiguration = false;
                        this.LoadElements();
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        };
    }

    ModalChoixTunnel() {
        afficherModal("Chargement...");
        var form_data = new FormData();
        form_data.append("type_tunnel", this.typepage);
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + getCurrentLangFromUrl() + "/Tunnel/tunnelform", options)
            .then(response => response.text())
            .then(data => {
                document.getElementById('modal-content-contenu').innerHTML = data;
                initialize_flowfinder_step();
                //Desactivation de l'IA
                //this.IAInitialiseFormOption();
                //this.IAInitialiseInputSubmitOption();
                this.InitialiseFormOption();
                this.InitialiseInputSubmitOption();
            })
            .catch(error => console.error('Erreur lors du chargement de la page:', error));
    }

    LoadFromJson(datajson, elementrecup, callback) {

        let elementMAJ;
        let donneejson;
        let orderConfiguration;
        let idselection;

        if (elementrecup == 'config') {
            orderConfiguration = datajson[0].json_configuration_page;
            let order = JSON.parse(orderConfiguration);
            this.orderelements = order.OrdreElements;
        }

        if (elementrecup == 'element') {
            if (Object.keys(this.orderelements).length === 0) {
                // Si il n'y à pas d'éléments à mettre
                if (this.typepage == 'tunnel' || this.typepage == 'sondage' || this.typepage == 'feedback' || this.typepage == 'formulaire') {
                    this.ModalChoixTunnel();
                }
            } else {
                // Je créer chaque item 
                Object.keys(this.orderelements).forEach(key => {
                    this.ajouterElement(this.orderelements[key]);
                });
            }
        }

        //puis je vais les mettres à jour maintenant 
        datajson.forEach((item) => {
            if (elementrecup == 'element') {
                elementMAJ = document.getElementById('idtemplate-' + item.id_element);
                let rondflowfinderid = elementMAJ.querySelector('.flowfinder-ID');
                if (rondflowfinderid) {
                    rondflowfinderid.innerHTML = '$' + item.id_element;
                }
                donneejson = item.donnee_json_element;
                idselection = 'idtemplate-' + item.id_element;
            }

            if (elementrecup == 'config') {
                elementMAJ = document.getElementById('config');
                donneejson = item.json_configuration_page;
            }
            // Parcourir le tableau et parser les données JSON
            // Parser la chaîne JSON en un objet JavaScript
            const parsedData = JSON.parse(donneejson);

            Object.keys(parsedData).forEach((key) => {
                let nom = key;
                let type = Object.keys(parsedData[key])[0];
                let valeur = parsedData[nom][type];
                let options = {};

                //Vérifier si il y à une fin de page;
                if (key == 'Choix' && valeur == 'findepage') {
                    this.findepage = true;
                }

                //Si c'est texte
                if (type == 'InputText' && elementMAJ) {
                    let inputtextmaj = elementMAJ.querySelector('.flowfinder-SortableElement-InputText-' + nom);
                    if (inputtextmaj) {
                        inputtextmaj.value = valeur;
                    }
                }

                //Si c'est textearea
                if (type == 'InputTextarea' && elementMAJ) {
                    let inputtextmaj = elementMAJ.querySelector('.flowfinder-SortableElement-InputTextarea-' + nom);
                    if (inputtextmaj) {
                        inputtextmaj.value = valeur;
                    }
                }

                //Si c'est un Switch
                if (type == 'Switch' && elementMAJ) {
                    let inputswitch = elementMAJ.querySelector('.flowfinder-SortableElement-InputSwitch-' + nom);
                    if (inputswitch) {
                        if (valeur == 1) { inputswitch.checked = true; } else { inputswitch.checked = false; }
                    }
                }

                //Si c'est un Select
                if (type == 'Select' && elementMAJ) {
                    let inputsselect = elementMAJ.querySelector('.flowfinder-SortableElement-InputSelect-' + nom);

                    if (inputsselect) {
                        inputsselect.value = valeur;
                        //Pour les tunnels initialiser quand il y à un Choix avec option
                        if (nom == 'Choix') {
                            // Boucle sur les propriétés de parsedData ou l'option Radio
                            for (let nom in parsedData['Choix']) {
                                let newsRadio = parsedData['Choix'][nom]['Option-InputRadio'];
                                let newsText = parsedData['Choix'][nom]['Option-InputText'];
                                let newsTextarea = parsedData['Choix'][nom]['Option-InputTextarea'];
                                let newsNumber = parsedData['Choix'][nom]['Option-InputNumber'];
                                let newsCheckbox = parsedData['Choix'][nom]['Option-InputCheckbox'];
                                let newsSwitch = parsedData['Choix'][nom]['Option-InputSwitch'];

                                if (newsRadio) {
                                    this.optionsvaleurs.push({
                                        id: idselection,
                                        nomform: nom,
                                        typededonnee: 'Option-InputRadio',
                                        donnee: newsRadio
                                    });
                                }

                                if (newsCheckbox) {
                                    this.optionsvaleurs.push({
                                        id: idselection,
                                        nomform: nom,
                                        typededonnee: 'Option-InputCheckbox',
                                        donnee: newsCheckbox
                                    });
                                }

                                if (newsText) {
                                    this.optionsvaleurs.push({
                                        id: idselection,
                                        nomform: nom,
                                        typededonnee: 'Option-InputText',
                                        donnee: newsText
                                    });
                                }

                                if (newsTextarea) {
                                    this.optionsvaleurs.push({
                                        id: idselection,
                                        nomform: nom,
                                        typededonnee: 'Option-InputTextarea',
                                        donnee: newsTextarea
                                    });
                                }

                                if (newsNumber) {
                                    this.optionsvaleurs.push({
                                        id: idselection,
                                        nomform: nom,
                                        typededonnee: 'Option-InputNumber',
                                        donnee: newsNumber
                                    });
                                }

                                if (newsSwitch) {
                                    this.optionsvaleurs.push({
                                        id: idselection,
                                        nomform: nom,
                                        typededonnee: 'Option-InputSwitch',
                                        donnee: newsSwitch
                                    });
                                }

                            }
                        }
                    }
                }

                if (elementrecup == 'config') {

                    //créer les configurations et intializer celle ci pour la preview
                    //Si c'est texte
                    if (type == 'InputText') {
                        let inputtextmaj = document.getElementById('flowfinder-content-' + nom);
                        if (inputtextmaj) {
                            inputtextmaj.textContent = valeur;
                        }

                        let configinput = document.getElementById('flowfinder-SortableElement-InputText-' + nom);
                        if (configinput) {
                            configinput.addEventListener('input', () => {
                                if (inputtextmaj) {
                                    inputtextmaj.textContent = configinput.value;
                                }
                            });
                            configinput.addEventListener('blur', () => {
                                this.EnregistrerConfiguration();
                            });
                        }
                    }

                    //Si c'est texte
                    if (type == 'InputTextarea') {
                        let inputtextmaj = document.getElementById('flowfinder-content-' + nom);
                        if (inputtextmaj) {
                            inputtextmaj.textContent = valeur;
                        }

                        let configinput = document.getElementById('flowfinder-SortableElement-InputTextarea-' + nom);
                        if (configinput) {
                            configinput.addEventListener('input', () => {
                                if (inputtextmaj) {
                                    inputtextmaj.textContent = configinput.value;
                                }
                            });
                            configinput.addEventListener('blur', () => {
                                this.EnregistrerConfiguration();
                            });
                        }
                    }

                    //Si c'est un Switch
                    if (type == 'Switch') {
                        let configswitch = document.getElementById('flowfinder-SortableElement-InputSwitch-' + nom);
                        let inputswitch = document.getElementById('flowfinder-content-' + nom);
                        if (inputswitch) {
                            //Spécial pour savoir ce que fais le switch
                            if (nom.split('-')[0] == 'Visibilite') {
                                if (valeur == 1) {
                                    inputswitch.classList.remove('flowfinder-d-none');
                                } else {
                                    inputswitch.classList.add('flowfinder-d-none');
                                }
                            }
                            if (configswitch) {
                                configswitch.addEventListener('input', () => {
                                    if (configswitch.checked) {
                                        inputswitch.classList.remove('flowfinder-d-none');
                                    } else {
                                        inputswitch.classList.add('flowfinder-d-none');
                                    }
                                    this.EnregistrerConfiguration();
                                });
                            }
                        }
                    }
                    //Si c'est un Select
                    if (type == 'Select') {
                        let selectElement = document.getElementById('flowfinder-SortableElement-InputSelect-' + nom);
                        let displayElement = valeur;
                        if (selectElement && displayElement) {
                            selectElement.addEventListener('blur', () => {
                                this.EnregistrerConfiguration();
                            });
                        }

                    }

                }

            });
        });
        if (callback) callback();
    }

    DeleteLien(item) {
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('id_template', item.id);
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);

        xhttp.open("POST", `/${this.lang}/element/delete_evenement`, true);
        xhttp.send(form_data);
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
                this.EnregistrerConfiguration();
            } else {
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }

    EnregistrerAllElements() {
        const sortableElements = document.querySelectorAll('div.SortableElement');
        sortableElements.forEach(element => {
            if (element.id) {
                if (element.id.includes('idtemplate-')) {
                    this.EnregistrerElement(element.id);
                }
            }
        });
        this.EnregistrerConfiguration();
    }

    EnregistrerElement(idunique) {
        //enregistrement
        let ElementsEnregistrer = this.ChercheEnregistrerElement(idunique);
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('idelement', idunique);
        form_data.append('elements', ElementsEnregistrer);
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);
        xhttp.open("POST", `/${this.lang}/element/save_evenement`, true);
        if (this.errorElements == false) {
            xhttp.send(form_data);
        }
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
            } else {
                alert('veuillez recharger la page');
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }


    EnregistrerConfiguration() {
        //enregistrement
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        let elementsconfiguration = this.ChercheEnregistrerConfiguration();
        form_data.append('jsonconfiguration', elementsconfiguration);
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);

        xhttp.open("POST", `/${this.lang}/element/save_configuration_page`, true);
        if (this.errorConfiguration == false && this.errorElements == false) {
            xhttp.send(form_data);
        }
        xhttp.onload = () => {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
            } else {
                alert('Veuillez recharger la page une erreur s\'est produite');
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }

    CreerConfiguration() {
        //enregistrement
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        let elementsconfiguration = this.ChercheEnregistrerConfiguration();
        form_data.append('jsonconfiguration', elementsconfiguration);
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);

        xhttp.open("POST", `/${this.lang}/element/create_configuration_page`, true);
        xhttp.send(form_data);

        xhttp.onload = () => {
            if (xhttp.status === 200) {
                this.LoadConfiguration();
                //console.log("Données envoyées avec succès :", xhttp.responseText);
            } else {
                this.errorConfiguration = true;
                console.log('CreerConfiguration passe le this.error à true ' + this.errorConfiguration);
            }
        };
    }


    ChercheEnregistrerElement(idunique) {
        const Sortablecontainer = document.getElementById('sortable');
        const ElementsEnregistre = {};
        let mesElements;
        if (idunique) {
            //enregistrer un element
            const monElement = Sortablecontainer.querySelector('#' + idunique);
            mesElements = monElement ? [monElement] : [];
        } else {
            mesElements = Sortablecontainer.querySelectorAll('[class="SortableElement"]');
        }

        mesElements.forEach(Elements => {
            const idElement = Elements.id;
            if (!ElementsEnregistre[idElement]) {
                ElementsEnregistre[idElement] = {}; // Créer l'objet si non existant
            }

            const inputs = Elements.querySelectorAll('[class^="flowfinder-SortableElement-Input"]');
            inputs.forEach(input => {
                const prefixText = 'flowfinder-SortableElement-InputText-';
                const prefixTextarea = 'flowfinder-SortableElement-InputTextarea-';
                const prefixSwitch = 'flowfinder-SortableElement-InputSwitch-';
                const prefixSelect = 'flowfinder-SortableElement-InputSelect-';
                const prefixOptions = 'flowfinder-SortableElement-InputOptions-';
                let valueswitche = 0;

                const classList = Array.from(input.classList);
                const presencePrefixText = classList.find(className => className.startsWith(prefixText));
                const presencePrefixTextarea = classList.find(className => className.startsWith(prefixTextarea));
                const presencePrefixSwitch = classList.find(className => className.startsWith(prefixSwitch));
                const presencePrefixSelect = classList.find(className => className.startsWith(prefixSelect));
                const presencePrefixOptions = classList.find(className => className.startsWith(prefixOptions));

                //Si c'est un input texte
                if (presencePrefixText) {
                    // Récupère le texte après le préfixe
                    const suffix = presencePrefixText.slice(prefixText.length);
                    if (!ElementsEnregistre[idElement][suffix]) {
                        ElementsEnregistre[idElement][suffix] = {}; // Créer l'objet si non existant
                    }
                    ElementsEnregistre[idElement][suffix]['InputText'] = input.value;
                }

                //Si c'est un textarea
                if (presencePrefixTextarea) {
                    // Récupère le texte après le préfixe
                    const suffix = presencePrefixTextarea.slice(prefixTextarea.length);
                    if (!ElementsEnregistre[idElement][suffix]) {
                        ElementsEnregistre[idElement][suffix] = {}; // Créer l'objet si non existant
                    }
                    ElementsEnregistre[idElement][suffix]['InputTextarea'] = textarea.value;
                }

                //Si c'est un input Switch
                if (presencePrefixSwitch) {
                    // Récupère le texte après le préfixe
                    const suffix = presencePrefixSwitch.slice(prefixSwitch.length);
                    if (!ElementsEnregistre[idElement][suffix]) {
                        ElementsEnregistre[idElement][suffix] = {}; // Créer l'objet si non existant
                    }
                    if (input.checked) {
                        valueswitche = 1;
                    }
                    ElementsEnregistre[idElement][suffix]['Switch'] = valueswitche;
                }

                //Si c'est un input Select
                if (presencePrefixSelect) {
                    // Récupère le texte après le préfixe
                    const suffix = presencePrefixSelect.slice(prefixSelect.length);
                    if (!ElementsEnregistre[idElement][suffix]) {
                        ElementsEnregistre[idElement][suffix] = {}; // Créer l'objet si non existant
                    }
                    ElementsEnregistre[idElement][suffix]['Select'] = input.value;
                }

                //Si il y à des option suite à un select recupérer les donnes 

                if (presencePrefixOptions) {
                    const suffix = presencePrefixOptions.slice(prefixOptions.length);

                    // Enregistrement options Radio
                    let selectedOptionRadio = input.querySelectorAll('input[name^="' + Elements.id + '"][name$="-Option-InputRadio"]:checked');
                    selectedOptionRadio.forEach(Option => {
                        let decoupnameOption = Option.name.split('-');
                        let valeurchecked = Option.id.split('-').pop();
                        // le 2 c'est le nom du form option
                        let nomduformOption = decoupnameOption[2];
                        if (!ElementsEnregistre[idElement][suffix][nomduformOption]) {
                            ElementsEnregistre[idElement][suffix][nomduformOption] = {}; // Créer l'objet si non existant
                        }
                        ElementsEnregistre[idElement][suffix][nomduformOption]['Option-InputRadio'] = valeurchecked;
                    });

                    // Enregistrement options Checkbox
                    let selectedOptionCheckbox = input.querySelectorAll('input[name^="' + Elements.id + '"][name$="-Option-InputCheckbox"]:checked');
                    selectedOptionCheckbox.forEach(Option => {
                        let decoupnameOption = Option.name.split('-');
                        let valeurchecked = 1;
                        // le 2 c'est le nom du form option
                        let nomduformOption = decoupnameOption[2];
                        // Initialiser l'objet pour stocker les valeurs des checkboxes
                        if (!ElementsEnregistre[idElement][suffix][nomduformOption]) {
                            ElementsEnregistre[idElement][suffix][nomduformOption] = {}; // Créer l'objet si non existant
                        }
                        ElementsEnregistre[idElement][suffix][nomduformOption]['Option-InputCheckbox'] = valeurchecked;
                    });

                    // Enregistrement options Switch
                    let selectedOptionSwitch = input.querySelectorAll('input[name^="' + Elements.id + '"][name$="-Option-InputSwitch"]');
                    selectedOptionSwitch.forEach(Option => {
                        let decoupnameOption = Option.name.split('-');
                        let valeurchecked = Option.checked ? 1 : 2; // 2 si coché, 1 si non coché
                        // le 2 c'est le nom du form option*
                        let nomduformOption = decoupnameOption[2];
                        // Initialiser l'objet pour stocker les valeurs des checkboxes
                        if (!ElementsEnregistre[idElement][suffix][nomduformOption]) {
                            ElementsEnregistre[idElement][suffix][nomduformOption] = {}; // Créer l'objet si non existant
                        }
                        ElementsEnregistre[idElement][suffix][nomduformOption]['Option-InputSwitch'] = valeurchecked;
                    });


                    // Enregistrement options Text
                    let selectedOptionText = input.querySelectorAll('input[name^="' + Elements.id + '"][name$="-Option-InputText"]');
                    selectedOptionText.forEach(Option => {
                        let decoupnameOption = Option.name.split('-');
                        let valeur = Option.value;
                        // le 2 c'est le nom du form option
                        let nomduformOption = decoupnameOption[2];
                        if (!ElementsEnregistre[idElement][suffix][nomduformOption]) {
                            ElementsEnregistre[idElement][suffix][nomduformOption] = {}; // Créer l'objet si non existant
                        }
                        ElementsEnregistre[idElement][suffix][nomduformOption]['Option-InputText'] = valeur;
                    });

                    // Enregistrement options Textarea
                    let selectedOptionTextarea = input.querySelectorAll('textarea[name^="' + Elements.id + '"][name$="-Option-InputTextarea"]');
                    selectedOptionTextarea.forEach(Option => {
                        let decoupnameOption = Option.name.split('-');
                        let valeur = Option.value;
                        // le 2 c'est le nom du form option
                        let nomduformOption = decoupnameOption[2];
                        if (!ElementsEnregistre[idElement][suffix][nomduformOption]) {
                            ElementsEnregistre[idElement][suffix][nomduformOption] = {}; // Créer l'objet si non existant
                        }
                        ElementsEnregistre[idElement][suffix][nomduformOption]['Option-InputTextarea'] = valeur;
                    });

                    // Enregistrement options Number
                    let selectedOptionNumber = input.querySelectorAll('input[name^="' + Elements.id + '"][name$="-Option-InputNumber"]');
                    selectedOptionNumber.forEach(Option => {
                        let decoupnameOption = Option.name.split('-');
                        let valeur = Option.value;
                        // le 2 c'est le nom du form option
                        let nomduformOption = decoupnameOption[2];
                        if (!ElementsEnregistre[idElement][suffix][nomduformOption]) {
                            ElementsEnregistre[idElement][suffix][nomduformOption] = {}; // Créer l'objet si non existant
                        }
                        ElementsEnregistre[idElement][suffix][nomduformOption]['Option-InputNumber'] = valeur;
                    });
                }

            });

        });
        return JSON.stringify(ElementsEnregistre);

    }

    ChercheOrdreElement() {
        const elements = document.querySelectorAll('#sortable .SortableElement');
        const templateOrder = Array.from(elements).reduce((obj, element, index) => {
            obj[index] = element.id.replace('idtemplate-', '');  // Associer l'index à l'ID
            return obj;
        }, {});

        return templateOrder;
    }

    ChercheEnregistrerConfiguration() {
        const Configurationcontainer = document.getElementById('config');
        const ElementsEnregistre = {};

        const inputs = Configurationcontainer.querySelectorAll('[class^="flowfinder-SortableElement-Input"]');
        inputs.forEach(input => {
            const prefixText = 'flowfinder-SortableElement-InputText-';
            const prefixTextarea = 'flowfinder-SortableElement-InputTextarea-';
            const prefixSwitch = 'flowfinder-SortableElement-InputSwitch-';
            const prefixSelect = 'flowfinder-SortableElement-InputSelect-';
            //const prefixOptions = 'flowfinder-SortableElement-InputOptions-';

            let valueswitche = 0;

            const classList = Array.from(input.classList);
            const presencePrefixText = classList.find(className => className.startsWith(prefixText));
            const presencePrefixTextarea = classList.find(className => className.startsWith(prefixTextarea));
            const presencePrefixSwitch = classList.find(className => className.startsWith(prefixSwitch));
            const presencePrefixSelect = classList.find(className => className.startsWith(prefixSelect));
            //const presencePrefixOptions = classList.find(className => className.startsWith(prefixOptions));

            //Si c'est un input texte
            if (presencePrefixText) {
                // Récupère le texte après le préfixe
                const suffix = presencePrefixText.slice(prefixText.length);
                if (!ElementsEnregistre[suffix]) {
                    ElementsEnregistre[suffix] = {}; // Créer l'objet si non existant
                }
                ElementsEnregistre[suffix]['InputText'] = input.value;

            }

            //Si c'est un input texte
            if (presencePrefixTextarea) {
                // Récupère le texte après le préfixe
                const suffix = presencePrefixTextarea.slice(prefixTextarea.length);
                if (!ElementsEnregistre[suffix]) {
                    ElementsEnregistre[suffix] = {}; // Créer l'objet si non existant
                }
                ElementsEnregistre[suffix]['InputTextarea'] = input.value;
            }

            //Si c'est un input Switch
            if (presencePrefixSwitch) {
                // Récupère le texte après le préfixe
                const suffix = presencePrefixSwitch.slice(prefixSwitch.length);
                if (!ElementsEnregistre[suffix]) {
                    ElementsEnregistre[suffix] = {}; // Créer l'objet si non existant
                }
                if (input.checked) {
                    valueswitche = 1;
                }
                ElementsEnregistre[suffix]['Switch'] = valueswitche;
            }
            //Si c'est un input Select
            if (presencePrefixSelect) {
                // Récupère le texte après le préfixe
                const suffix = presencePrefixSelect.slice(prefixSelect.length);
                if (!ElementsEnregistre[suffix]) {
                    ElementsEnregistre[suffix] = {}; // Créer l'objet si non existant
                }
                ElementsEnregistre[suffix]['Select'] = input.value;
            }

        });

        ElementsEnregistre['OrdreElements'] = this.ChercheOrdreElement();
        return JSON.stringify(ElementsEnregistre);

    }

    editItem(item, icon) {
        item.setAttribute('draggable', false);

        const itemModif = icon.closest('.flowfinder-SortableElement-modif');
        if (!item) return;

        const titleInput = itemModif.querySelector('.flowfinder-SortableElement-Edit');
        if (titleInput) {
            titleInput.removeAttribute('readonly');
            titleInput.focus();
        }

        icon.classList.add('hidden');

        const resetInputs = () => {
            if (titleInput) titleInput.setAttribute('readonly', true);
            icon.classList.remove('hidden');
            item.setAttribute('draggable', true);
        };

        if (titleInput) {
            titleInput.addEventListener('blur', () => {
                resetInputs();
                this.AdjustInputWidth(item.id);
                this.EnregistrerElement(item.id);
                this.PrevisualisationPortable();
            }, { once: true });
        }

    }

    CherchePlusGrandId() {
        if (this.orderelements) {
            if (Object.keys(this.orderelements).length > 0) {
                const values = Object.values(this.orderelements).map(Number);
                const maxNumber = Math.max(...values);
                return isNaN(maxNumber) ? 0 : maxNumber;
            } else {
                return 0;
            }
        } else { return 0; }
    }

    ajouterElement(idtemplate = false, type = false) {
        if (this.errorConfiguration == false && this.errorElements == false) {
            const newItem = document.createElement('div');
            if (idtemplate === false) {
                let idelement = this.CherchePlusGrandId() + this.callAjouterElement;
                newItem.id = 'idtemplate-' + idelement;
            } else {
                newItem.id = 'idtemplate-' + idtemplate;
            }

            newItem.classList.add('SortableElement');
            newItem.setAttribute('draggable', 'true');
            newItem.innerHTML = this.getNewtemplateItemHTML();
            this.container.appendChild(newItem);

            // Vérifie si l'élément a bien été ajouté
            const addedItem = document.getElementById(newItem.id);
            if (addedItem) {
                if (this.typepage === 'tunnel' || this.typepage === 'sondage' || this.typepage === 'feedback' || this.typepage === 'formulaire' && !idtemplate) {
                    if (type === false) {
                        this.initializeNewFormChoix(newItem.id);

                    } else {
                        this.initializeTemplateType(newItem.id, type);
                        this.findepage = true;
                    }
                }
                if (idtemplate === false) {
                    this.callAjouterElement++;
                    this.AdjustInputWidth(newItem.id);
                    this.EnregistrerElement(newItem.id);
                    this.initializenewid();
                    this.EnregistrerConfiguration();
                }
            } else {
                console.error("L'élément n'a pas été trouvé dans le DOM.");
            }
        } else {
            this.LoadConfiguration();
        }
    }

    getNewtemplateItemHTML() {
        const templateItem = document.getElementById('templateItem').innerHTML;
        return templateItem;
    }

    PrevisualisationPortable() {
        const sortableElement = document.getElementById('flowfinder-sortable-elements');
        const sortableItems = document.querySelectorAll('.SortableElement');
        sortableElement.innerHTML = "";

        //Prévisualisation des liens
        if (this.typepage == 'portail') {
            sortableItems.forEach(item => {
                const inputsTitle = item.querySelector('.flowfinder-SortableElement-InputText-Title');
                const inputsActive = item.querySelector('.flowfinder-SortableElement-InputSwitch-Active');
                const inputsUrl = item.querySelector('.flowfinder-SortableElement-InputText-Url');
                if (inputsActive.checked) {
                    sortableElement.innerHTML += '<a class="flowfinder-btn flowfinder-btn-primary w-100 flowfinder-mt-1 flowfinder-content-lienportail" target="_blank" href="' + inputsUrl.value + '" role="button">' + inputsTitle.value + '</a>';
                }
            });
        }

        //Prévisualisation du form
        if (this.typepage == 'tunnel' || this.typepage == 'sondage' || this.typepage == 'feedback' || this.typepage == 'formulaire') {

            // Compter le nombre de séparateurs de page existants
            let sortableItems = document.querySelectorAll('.SortableElement');

            // Compter le nombre de pages actif avant la fin de page
            /*
            const countPageTotale = Array.from(sortableItems).filter(item => {
                const inputsType = item.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
                const inputsActive = item.querySelector('.flowfinder-SortableElement-InputSwitch-Active');
                // Vérifier si l'élément est de type "seppage" et si l'input est coché
                return inputsType && inputsType.value == 'seppage' && inputsActive.checked;
            }).length;
            */
           
            const countPageTotale = (() => {
                let stopCounting = false; // Drapeau pour arrêter quand on trouve 'findepage'
                let count = 0;
            
                for (const item of Array.from(sortableItems)) {
                    const inputsType = item.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
                    const inputsActive = item.querySelector('.flowfinder-SortableElement-InputSwitch-Active');
            
                    // Si on trouve 'findepage', on arrête immédiatement le comptage
                    if (inputsType && inputsType.value === 'findepage') {
                        break;
                    }
            
                    // Vérifier si l'élément est de type "seppage" et activé
                    if (inputsType && inputsType.value === 'seppage' && inputsActive?.checked) {
                        count++;
                    }
                }
            
                return count;
            })();

            //console.log('nombre de page : ' + countPageTotale);

            // Compter le nombre de questions
            /*
            const countQuestionTotale = Array.from(sortableItems).filter(item => {
                const inputsType = item.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
                const inputsActive = item.querySelector('.flowfinder-SortableElement-InputSwitch-Active');
                // Vérifier si l'élément n'est pas de type "page" et si l'input est coché
                return inputsType && inputsType.value != 'seppage' && inputsActive.checked;
            }).length;
            //countQuestionTotale = countQuestionTotale-1;
            */

            const countQuestionTotale = (() => {
                let stopCounting = false; // Flag pour arrêter le comptage dès qu'on tombe sur 'findepage'
                let lastValidCount = 0; // Pour stocker le dernier compte avant la fin
            
                return Array.from(sortableItems).reduce((count, item, index, array) => {
                    if (stopCounting) return count; // Si on doit arrêter, on garde la même valeur
            
                    const inputsType = item.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
                    const inputsActive = item.querySelector('.flowfinder-SortableElement-InputSwitch-Active');
            
                    // Si on rencontre 'findepage', on arrête le comptage
                    if (inputsType && inputsType.value === 'findepage') {
                        stopCounting = true;
                        return lastValidCount; // Retourne le dernier comptage valide avant cette page
                    }
            
                    // Vérifier si l'élément est valide (ni 'seppage' ni 'findepage', et activé)
                    if (inputsType && inputsType.value !== 'seppage' && inputsActive?.checked) {
                        lastValidCount = count + 1; // Sauvegarde le dernier compte valide
                        return count + 1;
                    }
            
                    return count;
                }, 0);
            })();

            //console.log('nombre de question totale : ' + countQuestionTotale);

            let nombrequestion = 0;
            let numeropage = 0;
            let contenuform = "";

            sortableItems.forEach(item => {

                const inputsType = item.querySelector('.flowfinder-SortableElement-InputSelect-Choix');
                const inputsId = item.id;
                const inputsTitle = item.querySelector('.flowfinder-SortableElement-InputText-Title');
                const inputsActive = item.querySelector('.flowfinder-SortableElement-InputSwitch-Active');
                const inputsOption = item.querySelector('.flowfinder-SortableElement-InputOptions-Choix');
                let valeurchecked;

                //console.log('valeur checked' + inputsType.value);
                //console.log(item);
                
                if (inputsActive.checked) {
                    //type texte
                    if (inputsType.value == 'texte') {
                        let selectedOption = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Option-InputRadio"]:checked');
                        if (selectedOption) {
                            //Encours
                            // Vérifier et afficher l'ID ou la valeur de l'option sélectionnée
                            valeurchecked = selectedOption.id.split('-').pop();
                            if (valeurchecked == "txt") {
                                contenuform += '<div class="flowfinder-form-txt">';
                                contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                                contenuform += '<input type="text" class="flowfinder-form-control" id="' + inputsId + '" placeholder="">';
                                contenuform += '</div>';
                            }
                            if (valeurchecked == "email") {
                                contenuform += '<div class="flowfinder-form-email">';
                                contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                                contenuform += '<input type="email" class="flowfinder-form-control" id="' + inputsId + '" placeholder="">';
                                contenuform += '</div>';
                            }
                            if (valeurchecked == "pswd") {
                                contenuform += '<div class="flowfinder-form-password">';
                                contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                                contenuform += '<input type="password" class="flowfinder-form-control" id="' + inputsId + '" placeholder="">';
                                contenuform += '</div>';
                            }
                            if (valeurchecked == "url") {
                                contenuform += '<div class="flowfinder-form-url">';
                                contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                                contenuform += '<div class="input-group">';
                                contenuform += '<input type="text" class="flowfinder-form-control ms-1" id="' + inputsId + '" placeholder="">';
                                contenuform += '</div>';
                                contenuform += '</div>';
                            }
                            if (valeurchecked == "tel") {
                                contenuform += '<div class="flowfinder-form-tel">';
                                contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                                contenuform += '<input type="tel" class="flowfinder-form-control" id="' + inputsId + '" placeholder="">';
                                contenuform += '</div>';
                            }
                        }
                    }

                    //textearea
                    if (inputsType.value == 'textarea') {

                        let selectedOption = inputsOption.querySelectorAll('input[name^="' + inputsId + '"][name$="-Option-InputNumber"]');
                        if (selectedOption) {
                            contenuform += '<div class="flowfinder-form-textarea">';
                            contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                            contenuform += '<textarea class="flowfinder-form-control" id="' + inputsId + '" rows="' + selectedOption[0].value + '" maxlength="' + selectedOption[1].value + '"></textarea>';
                            contenuform += '</div>';
                        }
                    }
                    //htmlbloc
                    if (inputsType.value == 'htmlbloc') {
                        //affichage 
                        let selectedOption = inputsOption.querySelectorAll('textarea[name^="' + inputsId + '"][name$="-Option-InputTextarea"]');
                        if (selectedOption) {
                            contenuform += '<div class="flowfinder-htmlbloc">';
                            contenuform += '<div id="' + inputsId + '">' + selectedOption[0].value + '</div>';
                            contenuform += '</div>';
                        }
                    }

                    //case à coché
                    if (inputsType.value == 'case') {
                        let selectedOption = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Option-InputText"]');
                        if (selectedOption) {
                            contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                            let optionArray = selectedOption.value.split(';');
                            let i = 0;
                            optionArray.forEach(value => {
                                if (value.trim() !== "") {
                                    // Vérifie si l'option contient ":checked"
                                    let isChecked = value.trim().endsWith(':checked');
                                    let label = isChecked ? value.slice(0, value.lastIndexOf(':checked')).trim() : value.trim();

                                    contenuform += '<div class="flowfinder-form-case">';
                                    contenuform += '<input class="flowfinder-form-check-input" type="checkbox"  id="' + inputsId + '-flexCase-' + i + '"' + (isChecked ? ' checked' : '') + '>';
                                    contenuform += '<label class="flowfinder-form-check-label flowfinder-content-label" for="' + inputsId + '-flexCase-' + i + '">' + label + '</label>';
                                    contenuform += '</div>';
                                    i++;
                                }
                            });
                        }
                    }

                    //Note
                    if (inputsType.value == 'note') {
                        let selectedOption = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-maxnote-Option-InputNumber"]');
                        if (selectedOption) {
                            // Construction du formulaire
                            contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                            contenuform += '<div class="flowfinder-rating" id="rating-' + inputsId + '">';
                            for (let i = 1; i <= selectedOption.value; i++) {
                                contenuform += '<div class="flowfinder-rating-etoile">';
                                contenuform += '<input type="radio" name="rating-' + inputsId + '" id="' + inputsId + '-star' + i + '" value="' + i + '">';
                                contenuform += '<label for="' + inputsId + '-star' + i + '" onmouseover="flowfinder_highlightStars(' + i + ', \'' + inputsId + '\')" onmouseout="flowfinder_resetStars(\'' + inputsId + '\')" onclick="flowfinder_selectStars(' + i + ', \'' + inputsId + '\')">★</label>';
                                contenuform += '</div>';
                            }
                            contenuform += '</div>';
                        }
                    }

                    //button on/off
                    if (inputsType.value == 'interrupteur') {
                        let selectedOption = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Option-InputText"]');
                        if (selectedOption) {
                            contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                            let optionArray = selectedOption.value.split(';');
                            let i = 0;

                            optionArray.forEach(value => {
                                if (value.trim() !== "") {
                                    // Vérifie si l'option contient ":checked"
                                    let isChecked = value.trim().endsWith(':checked');
                                    let label = isChecked ? value.slice(0, value.lastIndexOf(':checked')).trim() : value.trim();

                                    contenuform += '<div class="flowfinder-form-interrupteur flowfinder-form-check form-switch">';
                                    contenuform += '<input class="flowfinder-form-check-input" type="checkbox" id="' + inputsId + '-flexInterrupteur-' + i + '"' + (isChecked ? ' checked' : '') + '>';
                                    contenuform += '<label class="flowfinder-form-check-label flowfinder-content-label" for="' + inputsId + '-flexInterrupteur-' + i + '">' + label + '</label>';
                                    contenuform += '</div>';
                                    i++;
                                }
                            });
                        }
                    }

                    //bouton radio
                    if (inputsType.value == 'radio') {
                        // Pour mettre des boutons radio
                        let selectedOption = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Option-InputText"]');
                        if (selectedOption) {
                            contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                            let optionArray = selectedOption.value.split(';');
                            let i = 0;

                            optionArray.forEach(value => {
                                if (value.trim() !== "") {
                                    // Vérifie si l'option contient ":checked"
                                    let isChecked = value.trim().endsWith(':checked');
                                    let label = isChecked ? value.slice(0, value.lastIndexOf(':checked')).trim() : value.trim();

                                    contenuform += '<div class="flowfinder-form-radio">';
                                    contenuform += '<input class="flowfinder-form-check-input" type="radio" name="' + inputsId + '" id="' + inputsId + '-flexRadio-' + i + '"' + (isChecked ? ' checked' : '') + '>';
                                    contenuform += '<label class="flowfinder-form-check-label flowfinder-content-label" for="' + inputsId + '-flexRadio-' + i + '">' + label + '</label>';
                                    contenuform += '</div>';
                                    i++;
                                }
                            });
                        }
                    }

                    // Liste déroulante 
                    if (inputsType.value == 'listederoulante') {
                        // Pour mettre une liste déroulante
                        let selectedOption = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Option-InputText"]');
                        let selectedOptionMultiple = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-multiple-Option-InputCheckbox"]');

                        if (selectedOption) {
                            contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-titre-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                            contenuform += '<select class="flowfinder-form-select" id="' + inputsId + '" name="' + inputsId + '"' + (selectedOptionMultiple && selectedOptionMultiple.checked ? ' multiple' : '') + '>';

                            let optionArray = selectedOption.value.split(';'); // Divise les options par ";"
                            optionArray.forEach((value, index) => {
                                if (value.trim() !== "") {
                                    // Vérifie si l'option contient ":selected"
                                    let isSelected = value.trim().endsWith(':selected');
                                    let optionValue = isSelected ? value.slice(0, value.lastIndexOf(':selected')).trim() : value.trim();

                                    contenuform += '<option value="' + optionValue + '"' + (isSelected ? ' selected' : '') + '>';
                                    contenuform += optionValue; // Texte affiché pour l'option
                                    contenuform += '</option>';
                                }
                            });

                            contenuform += '</select>';
                        }
                    }

                    //date
                    if (inputsType.value == 'date') {
                        // Date
                        contenuform += '<div class="flowfinder-form-date">';
                        contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                        contenuform += '<input type="date" class="flowfinder-form-control" id="' + inputsId + '" ></input>';
                        contenuform += '</div>';
                    }

                    //Couleur
                    if (inputsType.value == 'couleur') {
                        // Couleur
                        let selectedOptionCouleur = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-CouleurDefault-Option"]');
                        contenuform += '<div class="flowfinder-form-couleur">';
                        contenuform += '<label for="' + inputsId + '" class="flowfinder-form-label flowfinder-content-label">' + inputsTitle.value + '</label>';
                        contenuform += '<input type="color" class="flowfinder-form-control flowfinder-form-control-color" id="defaultColor" value="' + selectedOptionCouleur.value + '">';
                        contenuform += '</div>';
                    }

                    //page
                    //faire les steps pour les pages
                    if (inputsType.value == 'seppage') {
                        let template_actif_suivant = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Suivant-Option-InputSwitch"]');
                        let template_actif_precedent = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Precedent-Option-InputSwitch"]');

                        let text_suivant = 'Suivant';
                        let template_text_suivant = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Suivant-Option-InputText"]');
                        if (template_text_suivant.value) {
                            text_suivant = template_text_suivant.value;
                        }

                        let text_precedent = 'Retour';
                        let template_text_precedent = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Precedent-Option-InputText"]');
                        if (template_text_precedent.value) {
                            text_precedent = template_text_precedent.value;
                        }

                        if (countPageTotale > 0 && numeropage == 0) {
                            //c'est le debut de la première page
                            contenuform += '<div class="flowfinder-form-navigation">';
                            if (template_actif_suivant.checked) {
                                contenuform += '<button class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-nextBtn">';
                                contenuform += text_suivant;
                                contenuform += '</button>';
                            }
                            contenuform += '</div>';
                            sortableElement.innerHTML += '<div class="flowfinder-step" id="flowfinder-step' + numeropage + '">' + contenuform + '</div>';
                            contenuform = "";
                            numeropage++;
                        } else if (numeropage < countPageTotale) {
                            //page suivante
                            contenuform += '<div class="flowfinder-form-navigation">';
                            if (template_actif_precedent.checked) {
                                contenuform += '<button class="flowfinder-btn flowfinder-btn-primary float-start flowfinder-content-prevBtn">';
                                contenuform += text_precedent;
                                contenuform += '</button>';
                            }
                            if (template_actif_suivant.checked) {
                                contenuform += '<button class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-nextBtn">';
                                contenuform += text_suivant;
                                contenuform += '</button>';
                            }
                            contenuform += '</div>';
                            sortableElement.innerHTML += '<div class="flowfinder-step flowfinder-d-none" id="flowfinder-step' + numeropage + '">' + contenuform + '</div>';
                            contenuform = "";
                            numeropage++;
                        }

                    } else if (inputsType.value != 'findepage'){
                        //c'est une question
                        nombrequestion++;
                    }

                    //dernière page
                    //if (nombrequestion == countQuestionTotale && countPageTotale > 0) {
                    if (inputsType.value == 'findepage') {
                        
                        let text_precedent = 'Retour';
                        let text_suivant = 'Suivant';
                        let text_envoyer = 'Envoyer';

                        let template_actif_precedent = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Precedent-Option-InputSwitch"]');
                        let template_text_precedent = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Precedent-Option-InputText"]');
                        if (template_text_precedent.value) {
                            text_precedent = template_text_precedent.value;
                        }

                        let template_actif_envoyer = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Send-Option-InputSwitch"]');

                        let template_text_envoyer = inputsOption.querySelector('input[name^="' + inputsId + '"][name$="-Send-Option-InputText"]');
                        if (template_text_envoyer.value) {
                            text_envoyer = template_text_envoyer.value;
                        }
                        
                        if (nombrequestion == countQuestionTotale && countPageTotale > 0) {
                            contenuform += '<div class="flowfinder-form-navigation">';
                            if (template_actif_precedent.checked) {
                                contenuform += '<button class="flowfinder-btn flowfinder-btn-primary float-start flowfinder-content-prevBtn">';
                                contenuform += text_precedent;
                                contenuform += '</button>';
                            };
                            if (template_actif_envoyer.checked) {
                                contenuform += '<button class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-sendBtn" type="submit" id="envoyer_reponses">';
                                contenuform += text_envoyer;
                                contenuform += '</button>';
                            };
                            contenuform += '</div>';
                            sortableElement.innerHTML += '<div class="flowfinder-step flowfinder-d-none" id="flowfinder-step' + numeropage + '">' + contenuform + '</div>';
                            contenuform = "";
                        }

                        if (nombrequestion == countQuestionTotale && countPageTotale == 0) {
                            contenuform += '<div class="flowfinder-form-navigation">';
                            if (template_actif_envoyer.checked) {
                                contenuform += '<button class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-sendBtn" type="submit" id="envoyer_reponses">'
                                contenuform += text_envoyer;
                                contenuform += '</button>';
                            };
                            contenuform += '</div>';
                            sortableElement.innerHTML += contenuform;
                            contenuform = "";
                        }
                        //initialiser les steps 
                        initialize_flowfinder_step();
                    }

                }
            });
        };

        // Initialiser le gestionnaire de thème
        this.themeManager = new ThemeManager(this.typepage);
        this.themeManager.loadTheme('perso');
        this.themeManager.PreviewInputTheme();
    }

    //IA automatique création
    IAInitialiseFormOption() {
        const formulaire = document.getElementById('questionnaireForm');
        var elements = formulaire.querySelectorAll('[id^="sendvalue-option"]');

        elements.forEach(function (element) {
            element.style.display = 'none';

            var inputs = element.querySelectorAll('input, textarea');
            inputs.forEach(function (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
        });
        var checkedRadio = formulaire.querySelector('input[type="radio"]:checked');
        if (checkedRadio) {
            var radioId = checkedRadio.id;
            var optionId = radioId.replace('sendvalue-v', 'sendvalue-option-v');
            var optionToShow = formulaire.querySelector('#' + optionId);
            if (optionToShow) {
                optionToShow.style.display = 'block';
            }
        }
    }

    //InitialiseFormOption
    InitialiseFormOption() {
        const formulaire = document.getElementById('questionnaireForm');
        var elements = formulaire.querySelectorAll('[id^="sendvalue-option"]');

        elements.forEach(function (element) {
            element.style.display = 'none';

            var inputs = element.querySelectorAll('input, textarea');
            inputs.forEach(function (input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
        });
        var checkedRadio = formulaire.querySelector('input[type="radio"]:checked');
        if (checkedRadio) {
            var radioId = checkedRadio.id;
            var optionId = radioId.replace('sendvalue-v', 'sendvalue-option-v');
            var optionToShow = formulaire.querySelector('#' + optionId);
            if (optionToShow) {
                optionToShow.style.display = 'block';
            }
        }
    }

    IAEnvoyerQuestionnaireTunnel(event) {
        event.preventDefault();
        var elements = document.querySelectorAll('[id^="sendvalue-"]');
        var values = {};
        var type_tunnel = '';
        var infos_supplementaires = '';

        elements.forEach(function (element) {
            if (element.type === 'radio' || element.type === 'checkbox') {
                if (element.checked) {
                    var id = element.id;
                    var value = element.value;
                    values[id] = value;
                    type_tunnel += value;
                } else {
                    var id = element.id;
                    values[id] = "";
                }
            } else {
                var id = element.id;
                var value = element.value;
                values[id] = value;
                infos_supplementaires += value;
            }
        });

        this.Loadjson('/ressources/json_exemple/tunnel_configuration.json').then(datajsonconfig => {
            this.IAPersonnaliseJSONConfigurationTunnel(datajsonconfig, infos_supplementaires);
        });
    }

    EnvoyerQuestionnaireTunnel(event) {
        event.preventDefault();
        var elements = document.querySelectorAll('[id^="sendvalue-"]');
        var values = {};
        var type_tunnel = '';
        var infos_supplementaires = '';

        elements.forEach(function (element) {
            if (element.type === 'radio' || element.type === 'checkbox') {
                if (element.checked) {
                    var id = element.id;
                    var value = element.value;
                    values[id] = value;
                    type_tunnel += value;
                } else {
                    var id = element.id;
                    values[id] = "";
                }
            } else {
                var id = element.id;
                var value = element.value;
                values[id] = value;
                infos_supplementaires += value;
            }
        });

        this.PersonnaliseJSONConfigurationTunnel(values);

    }

    IAEnregistrerConfigurationTunnel(elementsconfiguration, infos_supplementaires) {
        //enregistrement
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('jsonconfiguration', elementsconfiguration);
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);
        xhttp.open("POST", `/${this.lang}/element/save_configuration_page`, true);
        xhttp.send(form_data);

        xhttp.onload = (infos_supplementaires) => {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
                this.LoadConfiguration();
                this.IAJsonElementTunnel(infos_supplementaires);
            } else {
                alert('Veuillez recharger la page une erreur s\'est produite');
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }

    EnregistrerConfigurationTunnel(jsonString, values) {
        //enregistrement
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('jsonconfiguration', jsonString);
        form_data.append('typepage', this.typepage);
        form_data.append('numpage', this.numpage);
        
        xhttp.open("POST", `/${this.lang}/element/save_configuration_page`, true);
        xhttp.send(form_data);

        xhttp.onload = () => {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
                this.LoadConfiguration();
                this.JsonElementTunnel(values);
            } else {
                alert('Veuillez recharger la page une erreur s\'est produite');
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }

    IAEnregistrerConfigurationPortail(elementsconfiguration) {
        //enregistrement
        const xhttp = new XMLHttpRequest();
        var form_data = new FormData();
        form_data.append('jsonconfiguration', elementsconfiguration);
        form_data.append('typepage', 'portail');

        let elementportail = document.querySelector('[id^="themepage-portail"]');
        let id = elementportail.id;
        form_data.append('numpage', id.split('themepage-portail-')[1]);
        xhttp.open("POST", `/${this.lang}/element/save_configuration_page`, true);
        xhttp.send(form_data);

        xhttp.onload = () => {
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
                this.LoadConfiguration();
                this.IAJsonElementPortail();
            } else {
                alert('Veuillez recharger la page une erreur s\'est produite');
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        };
    }

    IAPersonnaliseJSONConfigurationPortail(datajson) {
        var form_data = new FormData();
        form_data.append("values", JSON.stringify(datajson));
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + this.lang + "/Discussion/SendConfigurationPortailJson", options)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        let jsonString = data.replace(/```json/g, '').replace(/```/g, '');
                        this.IAEnregistrerConfigurationPortail(jsonString);
                    });

                } else {
                }
            })
            .catch(function (error) {
            });
    }

    IAPersonnaliseJSONConfigurationTunnel(datajson, infos_supplementaires) {
        var form_data = new FormData();
        form_data.append("values", JSON.stringify(datajson));
        form_data.append("type_tunnel", this.typepage);
        form_data.append("infos_supplementaires", infos_supplementaires);
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + this.lang + "/Discussion/SendConfigurationTunnelJson", options)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        let jsonString = data.replace(/```json/g, '').replace(/```/g, '');
                        this.IAEnregistrerConfigurationTunnel(jsonString);
                    });

                } else {
                }
            })
            .catch(function (error) {
            });
    }

    PersonnaliseJSONConfigurationTunnel(values) {
        var form_data = new FormData();
        form_data.append("values", JSON.stringify(values));
        form_data.append("type_tunnel", this.typepage);
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + this.lang + "/Tunnel/ConfigurationTunnelJson", options)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        let jsonString = data;
                        this.EnregistrerConfigurationTunnel(jsonString, values);
                    });

                } else {
                }
            })
            .catch(function (error) {
            });
    }

    IAJsonElementTunnel(infos_supplementaires) {
        this.Loadjson('/ressources/json_exemple/tunnel_element.json').then(datajsonElement => {
            this.IAPersonnaliseJSONElementsTunnel(datajsonElement, infos_supplementaires);
        });
    }

    JsonElementTunnel(values) {
        this.PersonnaliseJSONElementsTunnel(values);
    }

    IAJsonElementPortail() {
        this.Loadjson('/ressources/json_exemple/portail_element.json').then(datajsonElement => {
            this.IAPersonnaliseJSONElementsPortail(datajsonElement);
        });
    }

    IAPersonnaliseJSONElementsTunnel(datajson, infos_supplementaires) {

        var form_data = new FormData();
        form_data.append("values", JSON.stringify(datajson));
        form_data.append("type_tunnel", this.typepage);
        form_data.append("infos_supplementaires", infos_supplementaires);
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + getCurrentLangFromUrl() + "/Discussion/IACreationElementTunnel", options)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        let jsonString = data.replace(/```json/g, '').replace(/```/g, '');

                        try {
                            let dataJsonString = JSON.parse(jsonString);
                            let numberOfElements = dataJsonString.length;
                            let result = this.IAGenerateOrderElements(numberOfElements - 1);
                            this.orderelements = result;
                            this.LoadFromJson(dataJsonString, 'element', () => {
                                this.initialize();
                            });
                            this.EnregistrerAllElements();
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } catch (error) {
                            this.IAPersonnaliseJSONElementsTunnel(datajson, infos_supplementaires);
                        }
                    });

                } else {

                }
            })
            .catch(function (error) {
            });
    }

    PersonnaliseJSONElementsTunnel(values) {
        var form_data = new FormData();
        form_data.append("values", JSON.stringify(values));
        form_data.append("type_tunnel", this.typepage);
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + getCurrentLangFromUrl() + "/Tunnel/CreationElementTunnel", options)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        let jsonString = data;
                        try {
                            let dataJsonString = JSON.parse(jsonString);
                            let numberOfElements = dataJsonString.length;
                            let result = this.GenerateOrderElements(numberOfElements);
                            this.orderelements = result;
                            this.LoadFromJson(dataJsonString, 'element', () => {
                                this.initialize();
                            });
                            this.EnregistrerAllElements();
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } catch (error) {
                            console.log(error);
                        }
                    });

                } else {

                }
            })
            .catch(function (error) {
                console.log(error);
            });
    }

    IAPersonnaliseJSONElementsPortail(datajson) {

        var form_data = new FormData();
        form_data.append("values", JSON.stringify(datajson));
        var options = {
            method: "POST",
            body: form_data
        };

        fetch("/" + getCurrentLangFromUrl() + "/Discussion/IACreationElementPortail", options)
            .then(response => {
                if (response.ok) {
                    response.text().then(data => {
                        let jsonString = data.replace(/```json/g, '').replace(/```/g, '');

                        try {
                            let dataJsonString = JSON.parse(jsonString);
                            let numberOfElements = dataJsonString.length;
                            let result = this.IAGenerateOrderElements(numberOfElements - 1);
                            this.orderelements = result;
                            this.LoadFromJson(dataJsonString, 'element', () => {
                                this.initialize();
                            });
                            this.EnregistrerAllElements();
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } catch (error) {
                            this.IAPersonnaliseJSONElementsPortail(datajson);
                        }
                    });

                } else {
                }
            })
            .catch(function (error) {
            });
    }

    IAGenerateOrderElements(numberOfElements) {
        let ordreElements = {};
        for (let i = 0; i < numberOfElements; i++) {
            ordreElements[i] = (i + 1).toString();
        }
        const configuration = {
            "json_configuration_page": JSON.stringify({ "OrdreElements": ordreElements })
        };

        return ordreElements;
    }

    GenerateOrderElements(numberOfElements) {
        let ordreElements = {};
        for (let i = 0; i < numberOfElements; i++) {
            ordreElements[i] = (i + 1).toString();
        }
        const configuration = {
            "json_configuration_page": JSON.stringify({ "OrdreElements": ordreElements })
        };
        return ordreElements;
    }

    InitializeEtoiles(Inputid) {
        // Récupère toutes les étoiles
        // changer le timeaout plus tard hack rapide
        setTimeout(() => {
            let ratingContainer = document.getElementById("rating-" + Inputid);
            let stars = ratingContainer.querySelectorAll(".flowfinder-rating-etoile label");
            if (!stars) {
                console.error("Éléments étoiles introuvables !");
                return;
            }

            stars.forEach((star, index) => {
                // Ajouter l'événement de survol
                star.addEventListener("mouseover", () => {
                    flowfinder_highlightStars(index, Inputid);
                });

                // Ajouter l'événement de sortie
                star.addEventListener("mouseout", flowfinder_resetStars);
            });

        }, 2); // 10 secondes = 10000 millisecondes

    }

    ExportCsv() {
        //console.log("Export CSV...");
        if (this.datajsoninscription.length === 0) {
            console.error("Export impossible.");
            return;
        }
        const csvData = this.jsonToCSV(this.datajsoninscription);
        this.downloadCSV(csvData, 'data_export.csv');
    }

    jsonToCSV(datajson) {
        const csvRows = [];
        let headersSet = new Set();
    
        datajson.forEach(item => {
            const formData = JSON.parse(item.json_formdonnees);
            Object.values(formData).forEach(({ label }) => {
                if (label) headersSet.add(label);
            });
        });
    
        const headers = Array.from(headersSet);
        csvRows.push(['Date de soumission', ...headers].join(','));
    
        datajson.forEach(item => {
            const formData = JSON.parse(item.json_formdonnees);
            const row = [];
            row.push(item.date_soumission);
    
            headers.forEach(header => {
                const entry = Object.values(formData).find(d => d.label === header);
                if (entry) {
                    const value = Array.isArray(entry.value) ? entry.value.join(", ") : entry.value;
                    row.push(`"${value.replace(/"/g, '""')}"`); 
                } else {
                    row.push(''); 
                }
            });
            csvRows.push(row.join(','));
        });
        return csvRows.join('\r\n');
    }

    downloadCSV(csvData, fileName) {
        const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', fileName);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

}

document.addEventListener('DOMContentLoaded', () => {
    const NewsortableList = new SortableList('sortable');
    async function run() {
        NewsortableList.LoadConfiguration();
    }
    run().catch(error => console.error(error)); 
    document.getElementById('bouton_download_csv').addEventListener('click', () => {
        NewsortableList.ExportCsv();  // Appeler la méthode DownloadCsv lors du clic
    });
});

// Fonction pour allumer les étoiles jusqu'à l'index survolé
function flowfinder_selectStars(index, inputsId) {
    const ratingContainer = document.getElementById("rating-" + inputsId);
    const inputs = ratingContainer.querySelectorAll("input[type='radio']");
    const stars = ratingContainer.querySelectorAll(".flowfinder-rating-etoile label");

    // Sélectionner l'input correspondant
    if (inputs[index - 1]) {
        inputs[index - 1].checked = true;
    }

    // Mettre à jour les étoiles (allumer jusqu'à l'index cliqué)
    stars.forEach((star, i) => {
        if (i < index) {
            star.style.color = "gold";
        } else {
            star.style.color = "#ddd";
        }
    });
}

// Fonction pour survoler les étoiles (sans affecter la sélection)
function flowfinder_highlightStars(index, inputsId) {
    const ratingContainer = document.getElementById("rating-" + inputsId);
    const stars = ratingContainer.querySelectorAll(".flowfinder-rating-etoile label");

    // Allumer les étoiles jusqu'à l'index survolé
    stars.forEach((star, i) => {
        if (i < index) {
            star.style.color = "gold";
        } else {
            star.style.color = "#ddd";
        }
    });
}

// Fonction pour réinitialiser les étoiles après survol
function flowfinder_resetStars(inputsId) {
    const ratingContainer = document.getElementById("rating-" + inputsId);
    const stars = ratingContainer.querySelectorAll(".flowfinder-rating-etoile label");
    const inputs = ratingContainer.querySelectorAll("input[type='radio']");

    // Trouver l'input sélectionné
    let selectedIndex = -1;
    inputs.forEach((input, index) => {
        if (input.checked) {
            selectedIndex = index;
        }
    });

    // Réinitialiser les étoiles en fonction de la sélection
    stars.forEach((star, i) => {
        if (i <= selectedIndex) {
            star.style.color = "gold";
        } else {
            star.style.color = "#ddd";
        }
    });
}