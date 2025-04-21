class FlowFinderAnalytics {
    // Définir le tool_id comme une constante statique de la classe
    static TOOL_ID = 'flowfinder_analytics';

    constructor() {
        window.__FLOWFINDER_ANALYTICS_GLOBALS = {};
        window.flowfinderShowForm = function(type_page, id_page_configuration)
        {
            let id = "flowfinder-form_" + type_page + "_" + id_page_configuration;
            window.__FLOWFINDER_ANALYTICS_GLOBALS[id]["flowfinder_modal_instance"].show();
        }
        window.flowfinderHideForm = function(type_page, id_page_configuration)
        {
            let id = "flowfinder-form_" + type_page + "_" + id_page_configuration;
            window.__FLOWFINDER_ANALYTICS_GLOBALS[id]["flowfinder_modal_instance"].hide();
        }

        this.isReady = false;
        this.visiteurUUID = null;
        this.visiteurSessionUUID = null;
        this.traceurUUID = null;
        this.apiEndpoint = null;
        this.rrwebScriptUrl = null;
        this.idle = false; // idle est remis a true lorsqu'on fait un appel markAlive, puis il est remis a true si un evenement est detecté dans la fenetre
        this.considerIdleAfter = 12; // en iteratons, on fait le markAlive même si il n'y a pas d'activité pedant ces x premiere itération
        this.markAliveInProgress = false;
        this.rrwebEvents = []; // Stocker les événements enregistrés par rrweb
        this.declencheurs = [];
        this.flowfinderGenerateurFormulaires = null;

        // charge le UUID de traçage qui a été injecté dans la tableau dataLayer par le snippet
        this.dataLayer = window.dataLayer || [];
        let config = this.dataLayer.find(item => item.tool_id === FlowFinderAnalytics.TOOL_ID);
        if (config && config.traceur_uuid && config.endpoint) {
            this.traceurUUID = config.traceur_uuid;
            this.apiEndpoint = config.endpoint;
            //console.log('Traceur UUID:', this.traceurUUID);
            //console.log('Traceur Endpoint:', this.apiEndpoint);
        } else {
            console.error('Traceur UUID ou endpoint manquant dans dataLayer ou identifiant incorrect');
            return;
        }

        // retrouve le visiteur uuid via la cookie
        this.visiteurUUID = this.getCookie("__gla__v_id");
        if (this.visiteurUUID == null) { this.visiteurUUID = "" }; // pas de valeur null, on prefere une chaine vide pour s'assurer de la veleur reçu coté serveur

        // retrouve le visiteur session uuid via le cookie
        this.visiteurSessionUUID = this.getCookie("__gla__vs_id");
        if (this.visiteurSessionUUID == null) { this.visiteurSessionUUID = "" }; // pas de valeur null, on prefere une chaine vide pour s'assurer de la veleur reçu coté serveur

        this.visiteurSessionSequenceIdx = null;
        // prépare les timers de traçage de présence basique
        this.initializeTraceActivity();

        const activityEvents = [
            'load', 'mousemove', 'mousedown', 'touchstart', 'touchmove',
            'click', 'keydown', 'scroll', 'wheel',
        ];
        activityEvents.forEach(event => {
            window.addEventListener(event, () => { this.idle = false; }, true);
        });

    }

    initializeTraceActivity() {
        const xhttp = new XMLHttpRequest();
        xhttp.addEventListener("load", (event) => {
            var data = JSON.parse(event.target.responseText);
            if (this.visiteurUUID != data["visiteur_uuid"]) {
                this.setCookie("__gla__v_id", data["visiteur_uuid"], 3650); // valide 10 ans
                this.visiteurUUID = data["visiteur_uuid"];
            }
            this.visiteurSessionUUID = data["visiteur_session_uuid"];
            this.setCookie('__gla__vs_id', data["visiteur_session_uuid"]); // cookie de session qui va expirer à la fermeture du navigateur

            this.visiteurSessionSequenceIdx = data["visiteur_session_seq_idx"];
            //console.log("reçois seq idx", this.visiteurSessionSequenceIdx);

            // on initialise le recorder rrweb si on a reçu l'url du script
            this.rrwebScriptUrl = data["rrweb_script_url"];
            if (this.rrwebScriptUrl != null && this.rrwebScriptUrl != "") {
                this.loadRrweb().then(() => {
                    this.startRrweb();
                }).catch(error => {
                    console.error('Erreur lors du chargement de rrweb :', error);
                });
            }

            this.declencheurs = data["declencheurs"];
            if (this.declencheurs != null && this.declencheurs.length > 0) {
                this.flowfinderGenerateurFormulaires = new FlowFinderGenerateurFormulaires(this.apiEndpoint, this.traceurUUID, this.visiteurUUID, this.visiteurSessionUUID, this.visiteurSessionSequenceIdx, this.declencheurs);
                this.flowfinderGenerateurFormulaires.activate();
            }

            this.isReady = true;
            //alert(event.target.responseText);
            //console.log(event.target.responseText)
            setInterval(() => { this.markAlive(); }, 10000); // chaque 10 secondes on marque la trace comme vivante si il y a eu une activité 
            this.markAlive();

        });
        var form_data = new FormData();
        form_data.append("action", "init");
        form_data.append("traceur_uuid", this.traceurUUID);
        form_data.append("visiteur_uuid", this.visiteurUUID);
        form_data.append("visiteur_session_uuid", this.visiteurSessionUUID);
        form_data.append("url", document.location.href);

        const utmData = this.extractUTMs();
        form_data.append("utm_source", utmData.utm_source);
        form_data.append("utm_medium", utmData.utm_medium);
        form_data.append("utm_campaign", utmData.utm_campaign);
        form_data.append("utm_content", utmData.utm_content);
        form_data.append("utm_term", utmData.utm_term);
        form_data.append("fbclid", utmData.fbclid);

        form_data.append("referer", document.referrer);
        
        const screenInfo = this.getScreenInfo();
        form_data.append("screen_width", screenInfo.screen_width);
        form_data.append("screen_height", screenInfo.screen_height);
        form_data.append("window_width", screenInfo.window_width);
        form_data.append("window_height", screenInfo.window_height);
        form_data.append("pixel_ratio", screenInfo.pixel_ratio);
        form_data.append("orientation", screenInfo.orientation);

        xhttp.open("POST", this.apiEndpoint);
        xhttp.send(form_data);
    }

    trackEvent(eventName, eventValue)
    {
        if(this.isReady)
        {
            const xhttp = new XMLHttpRequest();
            xhttp.addEventListener("load", (event) => {
                this.markAliveInProgress = false;
                //alert(event.target.responseText);
                //console.log()
            });
            var form_data = new FormData();
            form_data.append("traceur_uuid", this.traceurUUID);
            form_data.append("visiteur_session_uuid", this.visiteurSessionUUID);
            //console.log("mark visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
            form_data.append("visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
            form_data.append("action", "track_event");
            form_data.append("event_name", eventName);
            form_data.append("event_value", eventValue == null ? "" : JSON.stringify(eventValue));

            xhttp.open("POST", this.apiEndpoint);
            xhttp.send(form_data);
        }
    }

    markAlive() {
        //console.log("enter mark alive");
        //console.log('considerIdleAfter', this.considerIdleAfter);
        //console.log('idle', this.idle);

        if (this.markAliveInProgress) {
            return; // un appel est déja en cours est n'est pas retourné
        }

        if (this.considerIdleAfter > 0) {
            this.considerIdleAfter--;
        }

        if (!this.idle || this.considerIdleAfter > 0) {
            //console.log("reset idle");
            this.idle = true;

            this.markAliveInProgress = true;
            const xhttp = new XMLHttpRequest();
            xhttp.addEventListener("load", (event) => {
                this.markAliveInProgress = false;
                //alert(event.target.responseText);
                //console.log()
            });
            var form_data = new FormData();
            form_data.append("traceur_uuid", this.traceurUUID);
            form_data.append("visiteur_session_uuid", this.visiteurSessionUUID);
            //console.log("mark visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
            form_data.append("visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
            form_data.append("action", "mark");

            xhttp.open("POST", this.apiEndpoint);
            xhttp.send(form_data);
        }

    }

    async loadRrweb() {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = this.rrwebScriptUrl;
            script.async = true;
            script.onload = () => resolve();
            script.onerror = () => reject(new Error('Échec du chargement de rrweb'));
            document.head.appendChild(script);
        });
    }

    startRrweb() {
        try {
            rrwebRecord({
                emit: (event) => {
                    this.rrwebEvents.push(event); // Stocker les événements
                    this.sendRrwebEvent(event); // Envoyer les événements au serveur
                }
            });
            //console.log('rrweb enregistreur démarré avec succès.');
        } catch (error) {
            console.error('Erreur lors de l\'initialisation de rrweb :', error);
        }
    }

    sendRrwebEvent(event) {
        const xhttp = new XMLHttpRequest();
        xhttp.addEventListener("load", (event) => {
            // on ne fait rien    
        });

        var form_data = new FormData();

        form_data.append("action", "rec");
        form_data.append("traceur_uuid", this.traceurUUID);
        form_data.append("visiteur_session_uuid", this.visiteurSessionUUID);
        //console.log("rrweb visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
        form_data.append("visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
        form_data.append("event", JSON.stringify(event));
        form_data.append("event_timestamp", event.timestamp);

        xhttp.open("POST", this.apiEndpoint);
        xhttp.send(form_data);
    }

    extractUTMs() {
        const urlParams = new URLSearchParams(window.location.search);
        const utmSource = urlParams.get('utm_source');
        const utmMedium = urlParams.get('utm_medium');
        const utmCampaign = urlParams.get('utm_campaign');
        const utmContent = urlParams.get('utm_content');
        const utmTerm = urlParams.get('utm_term');
        const fbclid = urlParams.get('fbclid');

        return {
            utm_source: utmSource || "",
            utm_medium: utmMedium || "",
            utm_campaign: utmCampaign || "",
            utm_content: utmContent || "",
            utm_term: utmTerm || "",
            fbclid: fbclid || ""
        };
    }

    getScreenInfo() {
        // Taille de l'écran (largeur et hauteur de l'écran physique)
        const screenWidth = screen.width;
        const screenHeight = screen.height;
    
        // Taille de la fenêtre du navigateur (largeur et hauteur visibles)
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;
    
        // Facteur de zoom
        // Le zoom est calculé en fonction de la taille de l'écran et de la taille de la fenêtre
        const pixelRatio = window.devicePixelRatio || 1;
    
        // Récupérer l'orientation de l'écran si nécessaire
        const orientation = window.matchMedia("(orientation: portrait)").matches ? "portrait" : "landscape";
    
        return {
            screen_width: screenWidth,
            screen_height: screenHeight,
            window_width: windowWidth,
            window_height: windowHeight,
            pixel_ratio: pixelRatio,
            orientation: orientation
        };
    }

    setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (encodeURIComponent(value) || "") + expires + "; path=/";
    }

    getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
        return null;
    }

    eraseCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/'
    }
}


class FlowFinderGenerateurFormulaires {
    constructor(apiEndpoint, traceurUUID, visiteurUUID, visiteurSessionUUID, visiteurSessionSequenceIdx, declencheurs) {
        this.apiEndpoint = apiEndpoint;
        this.traceurUUID = traceurUUID;
        this.visiteurUUID = visiteurUUID,
            this.visiteurSessionUUID = visiteurSessionUUID;
        this.visiteurSessionSequenceIdx = visiteurSessionSequenceIdx;
        this.declencheurs = declencheurs;
        this.confirmationModalInstance = null;
        this.formulairesInfo = [];
        this.themes = {};
        this.declencheurs.forEach(declencheur => {
            this.formulairesInfo.push({
                "id_collection": declencheur["id_collection"],
                "type_page": declencheur["type_page"],
                "seconds_delay": declencheur["seconds_delay"],
                "inject_into_elem_id": declencheur["inject_into_elem_id"],
                "id_page_configuration": declencheur["id_page_configuration"],
                "html": ""
            })

        });
    }

    activate() {
        const xhttp = new XMLHttpRequest();
        xhttp.addEventListener("load", (event) => {
            var data = JSON.parse(event.target.responseText);
            this.injectionformulaire(data);
        });
        var form_data = new FormData();
        form_data.append("traceur_uuid", this.traceurUUID);
        form_data.append("action", "get_forms");
        form_data.append("forms_info", JSON.stringify(this.formulairesInfo));

        xhttp.open("POST", this.apiEndpoint);
        xhttp.send(form_data);
    }

    injectionformulaire(data) {
        //console.log('ici forms : ' , data['ensemble_construction_formulaires']['forms'] );
        //console.log(data);

        let js_requires = data['ensemble_construction_formulaires']['js_requires'];
        js_requires.forEach((js_require) => {
            let scriptTag = document.createElement("SCRIPT");
            scriptTag.textContent = js_require;
            scriptTag.type = "text/javascript";
            document.head.appendChild(scriptTag);
        });
        js_requires = null; // pour libérer la mémoire

        let css_requires = data['ensemble_construction_formulaires']['css_requires'];
        css_requires.forEach((css_require) => {
            let scriptTag = document.createElement("STYLE");
            scriptTag.textContent = css_require;
            document.head.appendChild(scriptTag);
        });
        css_requires = null; // pour libérer la mémoire

        this.themes = data['ensemble_construction_formulaires']['themes'];

        let forms_info = data['ensemble_construction_formulaires']['forms'];
        forms_info.forEach((form_info) => {
            setTimeout(() => {

                let containeurElem = null;
                if (form_info["inject_into_elem_id"] != null && form_info["inject_into_elem_id"] != "") {
                    containeurElem = document.getElementById(form_info["inject_into_elem_id"]);
                    if (containeurElem == null) {
                        console.log("Cannot detect the target element with id " + form_info["inject_into_elem_id"] + " for FlowFinder form injection. Will inject in document.body.");
                    }
                }

                let flowfinderPagePublicTemplate = new FlowFinderPublicPageTemplate();
                let design_affichage = form_info["design_affichage"];
                //console.log(design_affichage);
                let template = flowfinderPagePublicTemplate.getTemplateByName(design_affichage);
                
                if(containeurElem == null)
                {
                    containeurElem = document.createElement("DIV");
                    document.body.appendChild(containeurElem);
                }

                containeurElem.innerHTML = template;
                let subContaineur = containeurElem.querySelector("#flowfinder-" + design_affichage + "-conteneur");
                // on change l'id pour ne pas avoir de conflit avec le prochain form flowfinder inclu
                subContaineur.id = "flowfinder-form-" + form_info["type_page"] + "_" + form_info["id_page_configuration"];
                subContaineur.classList.add("flowfinder-conteneur");
                subContaineur.classList.remove("d-none");
                let subContaineurContenu = containeurElem.querySelector("#flowfinder-" + design_affichage + "-contenu");
                subContaineurContenu.id = "flowfinder-conteneur_" + form_info["type_page"] + "_" + form_info["id_page_configuration"] + "-contenu";
                subContaineurContenu.innerHTML = form_info["html"];
                subContaineurContenu.classList.remove("d-none");
                subContaineurContenu.style.display = "";

                form_info["html"] = null; // pour libérer la mémoire JS

                if (design_affichage == "popup") {
                    // on affiche la popup
                    let modalInstance = new FlowFinderModal(containeurElem);
                    window.__FLOWFINDER_ANALYTICS_GLOBALS["flowfinder-form_" + form_info["type_page"] + "_" + form_info["id_page_configuration"]] = {'flowfinder_modal_instance' : modalInstance};
                    // on affiche la popup immediatement si le delay n'est pas mis sur jamais (-1)
                    if(form_info["seconds_delay"] > -1)
                    {
                        modalInstance.show();
                    }
                }
                else if(design_affichage == "pleinecran")
                {
                    let modalInstance = new FlowFinderModal(containeurElem);
                    window.__FLOWFINDER_ANALYTICS_GLOBALS["flowfinder-form_" + form_info["type_page"] + "_" + form_info["id_page_configuration"]] = {'flowfinder_modal_instance' : modalInstance};
                    // on affiche la popup immediatement si le delay n'est pas mis sur jamais (-1)
                    if (form_info["seconds_delay"] > -1) {
                        console.log("show modal");
                        modalInstance.show();
                    }

                }
                else if(design_affichage == "bouton")
                {
                    // on commmence par afficher les boutons car on va faire des calcul en JS sur les tailles
                    subContaineur.classList.remove("flowfinder-d-none");

                    //code pour flowfinder-bouton_preview
                    const feedbackButton = subContaineur.querySelector('.flowfinder-feedback-button');
                    feedbackButton.id = "flowfinder-conteneur_" + form_info["type_page"] + "_" + form_info["id_page_configuration"] + "_feedbackButton"; // on change l'id pour ne pas avoir de conflit avec le prochain form flowfinder inclu
                    const rightModal = subContaineur.querySelector('.flowfinder-feedback-offcanvas');
                    rightModal.id = "flowfinder-conteneur_" + form_info["type_page"] + "_" + form_info["id_page_configuration"] + "_rightModal";
                    feedbackButton.setAttribute('data-bs-target', '#'+rightModal.id);
                    
                    //initianiliser le positionement du bouton feedback
                    let decalage = button_calculdecalage(feedbackButton);
                    feedbackButton.style.right = "-"+(decalage)+"px";
                    
                    feedbackButton.addEventListener("click", () => {
                        if(rightModal.classList.contains("show"))
                        {
                            rightModal.classList.remove("show");
                            let decalage = button_calculdecalage(feedbackButton);
                            feedbackButton.style.right = "-"+(decalage)+"px";
                        }
                        else
                        {
                            rightModal.classList.add("show");
                            let largeurRightModal = rightModal.offsetWidth;
                            let decalage = button_calculdecalage(feedbackButton);
                            feedbackButton.style.right = (largeurRightModal - decalage) + "px";
                        }
                    });  
                    
                    // pour la fermeture automatique après la confirmation d'enregistrement on créé un objet partiel qui continet uniquement la fonctin hide, c'est un peu moyen mais ça fonctionne
                    window.__FLOWFINDER_ANALYTICS_GLOBALS["flowfinder-form_" + form_info["type_page"] + "_" + form_info["id_page_configuration"]] = {'flowfinder_modal_instance' : 
                        {'hide': ()=>{  
                            if(rightModal.classList.contains("show"))
                                {
                                    rightModal.classList.remove("show");
                                    let decalage = button_calculdecalage(feedbackButton);
                                    feedbackButton.style.right = "-"+(decalage)+"px";
                                }
                        }}
                    };
                    
                }
                else {
                    // affichage sans passer par modal de bootstrap
                    subContaineur.classList.remove("flowfinder-d-none");
                }

                function button_calculdecalage(element) {
                    let hauteurelement = element.offsetHeight;
                    let largeurelement = element.offsetWidth;
                    let decalage = (largeurelement - hauteurelement) / 2;
                    return decalage + 1;
                }

                // on attache le évenements sur les bouton suivant et précédent des formulaires
                initialize_integration(containeurElem, (form_values, callbacksucces) => { this.enregistreValeursFormulaire(form_values, form_info, callbacksucces); });
                this.applique_theme(containeurElem, this.themes[form_info["type_page"]]);

            }, form_info["seconds_delay"] > 0 ? form_info["seconds_delay"] * 1000 : 0);
        });

    }

    applique_theme(containeurElem, json_data) {
        function apply_style(element, styles) {
            //console.log("apply_style", element, styles);
            for (const property in styles) {
                if (styles.hasOwnProperty(property)) {
                    if (property === 'borderRadius') {
                        element.style.borderRadius = styles[property] + 'rem';;
                    } else if (property === 'borderColor') {
                        element.style.borderColor = styles[property];
                        element.style.borderStyle = 'solid';
                    } else {
                        element.style[property] = styles[property];
                    }
                }
            }
        }
        let theme_data = JSON.parse(json_data["theme_css"]);
        //console.log(theme_data);
        for (const key in theme_data) {
            if (theme_data.hasOwnProperty(key)) {
                const styles = theme_data[key];
                //console.log("styles", styles);
                // Recherche par ID
                const idSelector = `#content-${key}`;
                const idElement = containeurElem.querySelector(idSelector);
                if (idElement) {
                    apply_style(idElement, styles);
                }

                // Recherche par classe
                const classSelector = `.flowfinder-content-${key}`;
                const classElements = containeurElem.querySelectorAll(classSelector);
                classElements.forEach(element => {
                    apply_style(element, styles);
                });

                // Si c'est la Css Personnalisé
                if (theme_data[key].inputCssPersonnalise && typeof (theme_data[key].already_injected == "undefined" || theme_data[key].already_injected == false)) {
                    //console.log(theme_data[key].inputCssPersonnalise);
                    const style = document.createElement('style');
                    style.innerHTML = `${theme_data[key].inputCssPersonnalise}`;
                    document.head.appendChild(style);
                    theme_data[key].already_injected = true; // pour ne pas injecter plusieur fois lorsqu'il y a plusieur forms flowfinder dans la meme page
                }

            }
        }
    }

    showConfirmationModal(type_page)
    {
        if(this.confirmationModalInstance == null)
        {
            this.confirmationModalInstance = new FlowFinderConfirmationModal();
            this.applique_theme(this.confirmationModalInstance.getContainerElem(), this.themes[type_page]);
        }
        this.confirmationModalInstance.show();
    }


    enregistreValeursFormulaire(form_values, form_info, callbacksucces)
    {
        //console.log(form_values, form_info);

        const xhttp = new XMLHttpRequest();
        xhttp.addEventListener("load", (event) => {
            //alert(event.target.responseText);
            //console.log()
            if (xhttp.status === 200) {
                //console.log("Données envoyées avec succès :", xhttp.responseText);
                //this.showConfirmationModal(form_info["type_page"]);
                /*
                if(form_info["design_affichage"] == "popup" 
                    || form_info["design_affichage"] == "pleinecran"
                    || form_info["design_affichage"] == "bouton")
                {
                    window.flowfinderHideForm(form_info["type_page"], form_info["id_page_configuration"]);
                }
                */

                if(typeof(window.callbackFlowFinderFormSent) != "undefined")
                {
                    window.callbackFlowFinderFormSent(form_info["id_collection"], form_info["type_page"], form_info["id_page_configuration"], form_values);
                }
                if(typeof(callbacksucces) != "undefined"){
                    callbacksucces();
                }
            } else {
                console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
            }
        });

        var form_data = new FormData();
        form_data.append("action", "save_form");
        form_data.append("traceur_uuid", this.traceurUUID);
        form_data.append("visiteur_uuid", this.visiteurUUID);
        form_data.append("visiteur_session_uuid", this.visiteurSessionUUID);
        form_data.append("visiteur_session_seq_idx", this.visiteurSessionSequenceIdx);
        form_data.append("id_collection", form_info["id_collection"]);
        form_data.append("type_page", form_info["type_page"]);
        form_data.append("id_page_configuration", form_info["id_page_configuration"]);
        form_data.append("json_values", JSON.stringify(form_values));

        xhttp.open("POST", this.apiEndpoint);
        xhttp.send(form_data);

    }
}

class FlowFinderConfirmationModal
{
    constructor()
    {
        this.containerElem = document.createElement("DIV");
        let modalSkeltonHtml = (new FlowFinderPublicPageTemplate()).getTemplateByName("confirmationModal");
        this.containerElem.innerHTML = modalSkeltonHtml;
        document.body.appendChild(this.containerElem);
        
        this.modal = new FlowFinderModal(this.containerElem);
    }

    getContainerElem()
    {
        return this.containerElem;
    }

    show()
    {
        this.modal.show();
    }

    hide()
    {
        this.modal.hide();
    }
}

class FlowFinderModal
{
    constructor(containerElem)
    {
        this.containerElem = containerElem;
        // ajoute un fond noir
        let modalBackdropHtml = (new FlowFinderPublicPageTemplate()).getTemplateByName("backdropModal");
        let modalBackdropElem = document.createElement("DIV");
        modalBackdropElem.innerHTML = modalBackdropHtml;
        this.containerElem.appendChild(modalBackdropElem);

        this.containerElem.querySelectorAll(".flowfinder-btn-fermer-modal").forEach((elem) => {
            elem.addEventListener("click", () => {
                console.log("clicked flowfinder-btn-fermer-modal");
                this.hide();
            });
        });
    }

    show()
    {
        this.containerElem.querySelector(".flowfinder-modal").classList.remove("flowfinder-d-none");
        this.containerElem.querySelector(".flowfinder-modal-backdrop").classList.remove("flowfinder-d-none");
    }

    hide()
    {
        this.containerElem.querySelector(".flowfinder-modal").classList.add("flowfinder-d-none");
        this.containerElem.querySelector(".flowfinder-modal-backdrop").classList.add("flowfinder-d-none");
    }
}


document.addEventListener('DOMContentLoaded', function(){
    window.__FlowFinderAnalytics = new FlowFinderAnalytics();
    window.flowfinder_track_event = function(eventName, eventValue)
    {
        if(typeof(eventName) == "undefined")
        {
            return;
        }
        if(typeof(eventValue) == "undefined")
        {
            eventValue = null;
        }
        window.__FlowFinderAnalytics.trackEvent(eventName, eventValue);
    }
});