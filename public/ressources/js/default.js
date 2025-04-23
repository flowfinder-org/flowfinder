var modalInstanceGlobalFlowFinder = null;

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
