function initialize_integration(formContaineurNode, enregistrerValeursFunc) {
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

    function endStep(formEntries) {
        let phraseElement = formContaineurNode?.querySelector('.flowfinder-step-fin');
        let phrase = phraseElement ? phraseElement.innerHTML : "";
        if (phraseElement && phrase) {
            let phrasefinal = replacePlaceholders(phrase, formEntries);
            phraseElement.innerHTML = phrasefinal;
        }
        showStep(currentStep+1);
    }

    function replacePlaceholders(phrase, data) {
        return phrase.replace(/\{\$(\d+)\}/g, (match, key) => {
            for (const objKey in data) {
                if (objKey.endsWith(`-${key}`) || objKey === key) {
                    return data[objKey].value || ""; 
                }
            }
            return match; // Si non trouvé, on garde le placeholder
        });
    }

    formContaineurNode.querySelectorAll('.flowfinder-content-nextBtn').forEach(btn => {
        btn.addEventListener('click', function (event) {
            // Empêche le comportement par défaut du bouton (soumission du formulaire)
            event.preventDefault();
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

    showStep(currentStep);

    Array.from(formContaineurNode.getElementsByClassName('flowfinder-formulaire-event')).forEach((el) => {
        el.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const formEntries = {};

            formData.forEach((value, key) => {
                // Récupérer l'élément input
                const inputElement = this.querySelector(`[name="${key}"]`);
                let labelElement ="";
                let label = "";

                if (inputElement) {
                    // Chercher le label correspondant en utilisant l'attribut "for"
                    let id_element = inputElement.id;
                    //gère les flex radio et flexCase
                    if (id_element.includes("flexRadio") || id_element.includes("flexCase") || id_element.includes("flexSwitch") || id_element.includes("star") ) {
                        const parts = id_element.split('-');
                        const firstTwoParts = parts.slice(0, 2);
                        const elementidparent = 'id' + firstTwoParts.join('-');
                        labelElement = this.querySelector(`label[for="${elementidparent}"]`);
                      
                    }else{
                        labelElement = this.querySelector(`label[for="${inputElement.id}"]`);
                    }
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

            enregistrerValeursFunc(formEntries, ()=>{
                endStep(formEntries);
                this.reset();
            });
        })
    });
};


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
