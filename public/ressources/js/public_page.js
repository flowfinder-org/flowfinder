function initialize_flowfinder_step(formContaineurNode, enregistrerValeursFunc) {
    if (typeof (formContaineurNode) == "undefined" || formContaineurNode == null) {
        formContaineurNode = document;
    }

    if (typeof (enregistrerValeursFunc) == "undefined" || enregistrerValeursFunc == null) {
        enregistrerValeursFunc = EnregistrerValeurs;
    }
    const steps = formContaineurNode.querySelectorAll('.flowfinder-step');
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

    formContaineurNode.querySelectorAll('.flowfinder-content-nextBtn').forEach(btn => {
        btn.addEventListener('click', function (event) {
            // Empêche le comportement par défaut du bouton (soumission du formulaire)
            event.preventDefault();
            console.log("nextstep");
            nextStep();
        });
    });

    formContaineurNode.querySelectorAll('.flowfinder-content-prevBtn').forEach(btn => {
        btn.addEventListener('click', function (event) {
            // Empêche le comportement par défaut du bouton (soumission du formulaire)
            event.preventDefault();
            prevStep();
        });
    });

    Array.from(formContaineurNode.getElementsByClassName('flowfinder-formulaire-event')).forEach((el) => {
        el.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const formEntries = {};

            formData.forEach((value, key) => {
                // Récupérer l'élément input
                const inputElement = this.querySelector(`[name="${key}"]`);
                let label = "";

                if (inputElement) {
                    // Chercher le label correspondant en utilisant l'attribut "for"
                    const labelElement = this.querySelector(`label[for="${inputElement.id}"]`);
                    if (labelElement) {
                        label = labelElement.innerText;
                    }
                }

                // Si la clé existe déjà, traiter comme un tableau pour gérer plusieurs valeurs
                if (formEntries[key]) {
                    if (Array.isArray(formEntries[key].value)) {
                        formEntries[key].value.push(value);
                    } else {
                        formEntries[key].value = [formEntries[key].value, value];
                    }
                } else {
                    formEntries[key] = { label: label, value: value };
                }
            });

            if (enregistrerValeursFunc(formEntries)) {
                this.reset();
                console.log("suppression demandé");
            }
        })
    });
};

function EnregistrerValeurs(jsonvalues) {
    const xhttp = new XMLHttpRequest();
    var form_data = new FormData();
    form_data.append('jsonvalues', JSON.stringify(jsonvalues));
    console.log(jsonvalues);
    const url = window.location.pathname;
    const divthemepage = document.querySelector('[id^="themepage-tunnel-"]');
    //chercher l'id tunnel en javascript
    if (divthemepage) {
        const match = divthemepage.id.match(/^themepage-tunnel-(\d+)$/);
        if (match) {
            var idTunnel = match[1];
            console.log("ID trouvé :", idTunnel);
        }
    }

    form_data.append('id_utilisateur_proprietaire_tunnel', document.getElementById("id_utilisateur_proprietaire_tunnel").value);
    form_data.append('numero_tunnel', idTunnel);

    xhttp.open("POST", "/tunnel/enregistrement_form", true);
    xhttp.send(form_data);
    xhttp.onload = () => {
        if (xhttp.status === 200) {
            //console.log("Données envoyées avec succès :", xhttp.responseText);
            const myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            myModal.show();
        } else {
            console.log("Erreur lors de l'envoi des données :", xhttp.statusText);
        }
    };
}

