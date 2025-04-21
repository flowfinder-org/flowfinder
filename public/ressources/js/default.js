var modalInstanceGlobalFlowFinder = null;

function getCurrentLangFromUrl() {
    const supportedLangs = ['fr', 'en', 'es', 'de', 'it'];
    const segments = window.location.pathname.split('/').filter(Boolean);
    const lang = segments[0];
    return supportedLangs.includes(lang) ? lang : 'fr';
}


const supportedLangs = {
    'fr': { flag: '🇫🇷', label: 'Français' },
    'en': { flag: '🇬🇧', label: 'English' },
    'es': { flag: '🇪🇸', label: 'Español' },
    'de': { flag: '🇩🇪', label: 'Deutsch' },
    'it': { flag: '🇮🇹', label: 'Italiano' }
};

function switchLanguageMenu() {
    const currentLang = getCurrentLangFromUrl();

    // Met à jour le bouton affichant la langue actuelle
    document.getElementById('current-lang').innerHTML = `
        <span class="d-inline-flex align-items-center">
            <span style="font-size: 2rem; line-height: 1;">${supportedLangs[currentLang].flag}</span>
            <span class="ms-2 text-uppercase" style="font-size: 0.9rem;">${currentLang}</span>
        </span>
    `;

    // Met à jour la dropdown avec toutes les autres langues sauf celle actuelle
    const langMenu = document.getElementById('lang-menu');
    langMenu.innerHTML = '';

    Object.keys(supportedLangs).forEach(lang => {
        if (lang !== currentLang) {
            const item = document.createElement('li');
            item.innerHTML = `
                <a class="dropdown-item lang-switch" data-lang="${lang}" href="#">
                    ${supportedLangs[lang].flag} ${supportedLangs[lang].label}
                </a>
            `;
            langMenu.appendChild(item);
        }
    });

    // Ajoute le listener à tous les items
    document.querySelectorAll('.lang-switch').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            const selectedLang = this.dataset.lang;
            const currentUrl = window.location.pathname;

            // Remplace la langue actuelle dans l'URL, ou la rajoute si absente
            const newUrl = currentUrl.replace(/^\/(fr|en|es|de|it)/, '/' + selectedLang);
            const finalUrl = newUrl === currentUrl ? '/' + selectedLang + currentUrl : newUrl;

            window.location.pathname = finalUrl;
        });
    });
}

function initialize_flowfinder_step() {
    const steps = document.querySelectorAll('.flowfinder-step');
    let currentStep = 0;

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            if (index === stepIndex) {
                step.classList.remove('flowfinder-d-none');
            } else {
                step.classList.add('flowfinder-d-none');
            }
        });
    }

    function nextStep() {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    }

    document.querySelectorAll('.flowfinder-content-nextBtn').forEach(btn => {
        btn.addEventListener('click', function (event) {
            // Empêche le comportement par défaut du bouton (soumission du formulaire)
            event.preventDefault();
            nextStep();
        });
    });

    document.querySelectorAll('.flowfinder-content-prevBtn').forEach(btn => {
        btn.addEventListener('click', function (event) {
            // Empêche le comportement par défaut du bouton (soumission du formulaire)
            event.preventDefault();
            prevStep();
        });
    });

};

function afficherModal(contenu) {
    //const modalBody = document.querySelector('#informationModal .modal-content-contenu');
    //modalBody.innerHTML = contenu;
    const modalElement = document.getElementById('informationModal');
    if (modalInstanceGlobalFlowFinder == null) {
        modalInstanceGlobalFlowFinder = new bootstrap.Modal(modalElement);
    }
    modalInstanceGlobalFlowFinder.show();
}

function cacherModal() {
    const modalBody = document.querySelector('#informationModal .modal-content-contenu');
    modalBody.innerHTML = "";
    const modalElement = document.getElementById('informationModal');
    if (modalInstanceGlobalFlowFinder == null) {
        modalInstanceGlobalFlowFinder = new bootstrap.Modal(modalElement);
    }
    modalInstanceGlobalFlowFinder.hide();
}

function scriptLoader(scripts, callback) {

    var count = scripts.length;

    function urlCallback(url) {
        return function () {
            console.log(url + ' was loaded (' + --count + ' more scripts remaining).');
            if (count < 1) {
                callback();
            }
        };
    }

    function loadScript(url) {
        var s = document.createElement('script');
        s.setAttribute('src', url);
    }

    for (var script of scripts) {
        loadScript(script);
    }
};


function redirigeVersMenuAccueil() {
    document.location.href = document.location.origin + "/MenuAccueil/"
}

function affichePopupAjouteDeclencheur() {
    afficherModal();
    fetch('/profil/AfficheAjouteDeclencheurPopup')
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

    xhttp.open("POST", "/profil/EnregistreAjouteDeclencheurPopup");
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
        xhttp.open("POST", "/profil/SupprimeDeclencheur");
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
    xhttp.open("POST", '/profil/AfficheModifieDeclencheurPopup');
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

    xhttp.open("POST", "/profil/EnregistreModifieDeclencheurPopup");
    xhttp.send(form_data);

    return false;
}

