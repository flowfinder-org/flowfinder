<style>
    /*style du feedback*/
    /* Style pour le bouton fixe à droite */
    #flowfinder-feedbackButton {
        position: fixed;
        top: 50%;
        transform: translateY(-50%) rotate(-90deg);
        /* Applique une rotation de 90° */
        background-color: red;
        color: white;
        border-radius: 5px 5px 0 0;
        padding: 10px 30px;
        cursor: pointer;
        z-index: 1050;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        writing-mode: horizontal-tb;
        white-space: nowrap;
        transition: right 0.3s ease-in-out;
        /* Animation fluide pour déplacer le bouton */
    }

    #flowfinder-feedbackButton:hover {
        background-color: #000;
        /* Couleur au survol */
    }

    /* Modal latérale droite */
    #flowfinderbouton {
        height: max-content;
        top: 50%;
        border-radius: 5px;
        transform: translate(100%, -50%);
        transition: transform 0.3s ease-in-out;
        /* Animation fluide pour ouvrir/fermer */
        z-index: 1049;
        /* S'assurer que la modal reste au-dessus de tout autre contenu */
    }

    #flowfinderbouton .flowfinder-content-page {
        border-radius: 5px 0px 0px 5px;
    }

    #flowfinder-content-Form {
        height: auto;
    }

    /* Lorsque la modal est ouverte, elle glisse vers le centre */
    .offcanvas.show#flowfinderbouton {
        transform: translate(0%, -50%);
    }

    /* Enlever le fond derrière la modal */
    .offcanvas-backdrop {
        background-color: transparent;
    }
</style>
<div style="height:75px;"></div>

<div class="container mb-5">
    <button class="btn btn-inverse-primary w-100 mt-5 p-1" type="button" id="moveContentButton">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
        </svg> Voir la prévisualisation
    </button>
</div>

<div class="apercu_telephone">
    <div class="flowfinder-pagepublic" id="flowfinder-content-page">
        <div class="flowfinder-content-page pt-4">
            <!-- <h3 class="text-center" id="flowfinder-content-titretunnelclient"></h3> -->
            <div id="sourceDivPreview">
                <form id="flowfinder-content-Form" class="flowfinder-reset-css">
                    <div class="container" id="flowfinder-sortable-elements">
                    </div>
                </form>
            </div>
        </div>


        <div class="d-none" id="flowfinder-bouton-preview">
            <div id="flowfinder-feedbackButton" data-bs-toggle="offcanvas" data-bs-target="#flowfinderbouton" aria-controls="flowfinderbouton">
                Feedback
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="flowfinderbouton" aria-labelledby="flowfinderboutonLabel">
                <div class="offcanvas-body flowfinder-content-page">
                    <div id="flowfinder-bouton-contenu">
                    </div>
                </div>
            </div>
        </div>

        <!-- preview de la popup -->
        <div class="modal fade d-none" tabindex="-1" id="flowfinder-popup-preview" aria-labelledby="modalPopupLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content flowfinder-content-page">
                    <div class="modal-header">
                        <!--
                <h5 id="modalPopupLabel">Modal Popup</h5>
                -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <div id="flowfinder-popup-contenu">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- preview du integre -->
        <div class="d-none" id="flowfinder-integre-preview">
            <!-- ajout en dessous de la class d-none voir plus tard comment representer -->
            <div id="flowfinder-integre-contenu" class="d-none">
            </div>
        </div>

        <!-- preview du plein ecran -->
        <div class="modal fade d-none" id="flowfinder-pleinecran-preview" tabindex="-1" aria-labelledby="modalFullscreenLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content flowfinder-content-page">
                    <div class="modal-header">
                        <!--
                <h5 id="modalFullscreenLabel">Modal Plein écran</h5> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <div id="flowfinder-pleinecran-contenu">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- preview du lien-->
        <div class="d-none" id="flowfinder-lien-preview">
            <!-- ajout en dessous de la class d-none voir plus tard comment representer -->
            <div id="flowfinder-lien-contenu" class="d-none">
            </div>
        </div>
    </div>
</div>
<script>
    // Gestion du bouton pour démarrer le processus de clonage et de synchronisation
    const moveContentButton = document.getElementById('moveContentButton');

    moveContentButton.addEventListener('click', () => {
        const selectElement = document.getElementById('flowfinder-SortableElement-InputSelect-Design');
        const selectedValue = selectElement.value;

        const sourceDivPreview = document.getElementById('sourceDivPreview');
        const destinationDiv = document.getElementById('flowfinder-' + selectedValue + '-contenu');
        const element = document.getElementById('flowfinder-' + selectedValue + '-preview');
        if (element && sourceDivPreview && destinationDiv) {
            // Vérifier si c'est une modal
            if (selectedValue === 'pleinecran' || selectedValue === 'popup') {
                element.classList.remove('d-none');
                const myModal = new bootstrap.Modal(element);
                myModal.show();
            } else {
                element.classList.remove('d-none');
            }

            // si c'est un bouton
            if (selectedValue == 'bouton') {
                feedbackactif();
            }

            const clone = sourceDivPreview.cloneNode(true);
            updateIds(clone);
            // nettoyer la destination avant d'ajouter le nouveau clone
            destinationDiv.innerHTML = '';
            destinationDiv.appendChild(clone);

            // Prévenir les actions par défaut des boutons
            initializeButtons(destinationDiv);

            // Observer les changements dans la source pour maintenir la synchronisation
            observeChanges(sourceDivPreview, destinationDiv);

        }

    });

    // Fonction pour modifier les IDs et les attributs
    function updateIds(container) {
        const uniqueSuffix = Date.now(); // Suffixe basé sur le timestamp pour assurer l'unicité
        const elementsWithIds = container.querySelectorAll('[id]');

        elementsWithIds.forEach(element => {
            const originalId = element.id;
            const newId = `${originalId}-${uniqueSuffix}`; // Ajouter un suffixe unique
            element.id = newId;

            // Mettre à jour les attributs qui pointent vers cet ID (par exemple, "for" ou "name")
            const labels = container.querySelectorAll(`[for="${originalId}"]`);
            labels.forEach(label => label.setAttribute('for', newId));
        });

        const elementsWithNames = container.querySelectorAll('[name]');
        elementsWithNames.forEach(element => {
            element.name = `${element.name}-${uniqueSuffix}`; // Ajouter un suffixe unique
        });
    }

    // Fonction pour empêcher le rechargement des boutons
    function initializeButtons(container) {
        const nextButtons = container.querySelectorAll('.flowfinder-content-nextBtn');
        const prevButtons = container.querySelectorAll('.flowfinder-content-prevBtn');
        const submitButton = container.querySelector('button[type="submit"]');

        // Gérer le bouton "Suivant"
        nextButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                moveToNextStep(container, button);
            });
        });

        // Gérer le bouton "Retour"
        prevButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                moveToPreviousStep(container, button);
            });
        });

        // Gérer le bouton "Envoyer"
        if (submitButton) {
            submitButton.addEventListener('click', (event) => {
                event.preventDefault();
            });
        }
    }

    // Fonction pour aller au prochain "step"
    function moveToNextStep(container, button) {
        const currentStep = button.closest('.flowfinder-step');
        const nextStep = currentStep.nextElementSibling;

        if (nextStep && nextStep.classList.contains('flowfinder-step')) {
            currentStep.classList.add('flowfinder-d-none');
            nextStep.classList.remove('flowfinder-d-none');
        }
    }

    // Fonction pour revenir au "step" précédent
    function moveToPreviousStep(container, button) {
        const currentStep = button.closest('.flowfinder-step');
        const prevStep = currentStep.previousElementSibling;

        if (prevStep && prevStep.classList.contains('flowfinder-step')) {
            currentStep.classList.add('flowfinder-d-none');
            prevStep.classList.remove('flowfinder-d-none');
        }
    }

    // Fonction pour synchroniser les changements entre source et destination
    function syncClone(sourceDiv, destinationDiv) {
        destinationDiv.innerHTML = '';
        Array.from(sourceDiv.children).forEach(child => {
            const clone = child.cloneNode(true);
            updateIds(clone); // Assurer des IDs uniques
            destinationDiv.appendChild(clone);
        });
        initializeButtons(destinationDiv); // Réinitialiser les événements des boutons
    }

    // Observer les changements dans la source
    function observeChanges(sourceDiv, destinationDiv) {
        const observer = new MutationObserver(() => {
            syncClone(sourceDiv, destinationDiv);
        });

        // Démarrer l'observation
        observer.observe(sourceDiv, {
            childList: true, // Surveiller les ajouts et suppressions d'enfants
            subtree: true, // Inclure les sous-nœuds
        });
    }

    // Fonction pour gérer le changement du select Design
    function DesignSelectChange() {
        const selectElement = document.getElementById('flowfinder-SortableElement-InputSelect-Design');
        selectElement.addEventListener('change', () => {
            const allValues = Array.from(selectElement.options).map(option => option.value);

            allValues.forEach(value => {
                const button = document.getElementById('flowfinder-' + value + '-preview');
                if (button) {
                    if (!button.classList.contains('d-none')) {
                        button.classList.add('d-none');
                    }
                }
            });
        });
    }

    // Initialisation de la fonction pour voir si le design change
    DesignSelectChange();

    function feedbackactif() {
        //code pour flowfinder-bouton_preview
        const flowfinder_feedbackButton = document.getElementById('flowfinder-feedbackButton');
        const rightModal = document.getElementById('flowfinderbouton');

        if (flowfinder_feedbackButton) {
            //initianiliser le positionement du bouton feedback
            let blocdiv = document.getElementById(rightModal.id);
            let buttonfeedback = blocdiv.parentElement.querySelector(".flowfinder_feedbackButton");
            let decalage = button_calculdecalage(flowfinder_feedbackButton);
            flowfinder_feedbackButton.style.right = "-" + (decalage) + "px";

            //décale le bouton quand la modal est ouverte
            rightModal.addEventListener('shown.bs.offcanvas', () => {
                let blocdiv = document.getElementById(rightModal.id);
                let buttonfeedback = blocdiv.parentElement.querySelector(".flowfinder_feedbackButton");
                let decalage = button_calculdecalage(flowfinder_feedbackButton);
                let largeurblocdiv = blocdiv.offsetWidth;
                flowfinder_feedbackButton.style.right = largeurblocdiv - decalage + "px";
            });

            //décale le bouton quand la modal est ouverte
            rightModal.addEventListener('hidden.bs.offcanvas', () => {
                let blocdiv = document.getElementById(rightModal.id);
                let buttonfeedback = blocdiv.parentElement.querySelector(".flowfinder_feedbackButton");
                let decalage = button_calculdecalage(flowfinder_feedbackButton);
                flowfinder_feedbackButton.style.right = "-" + (decalage) + "px";
            });
            flowfinder_feedbackButton.setAttribute('data-bs-target', '#' + rightModal.id);
        }
    }

    function button_calculdecalage(element) {
        let hauteurelement = element.offsetHeight;
        let largeurelement = element.offsetWidth;
        let decalage = (largeurelement - hauteurelement) / 2;
        return decalage + 1;
    }
</script>