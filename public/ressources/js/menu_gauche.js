class MenuGauche {
    constructor() {
        this.accordionStateKey = 'accordionState';
        this.accordionItems = document.querySelectorAll('.accordion-item');
        this.initializeModalState();
        this.addModalEventListeners();
        this.initializeAccordionState();

        // Vérifiez les paramètres d'URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("modif_password")) {
            this.afficher_formulaire_modification_password();
        } else if (urlParams.has("supprimer_compte")) {
            this.afficher_formulaire_supprimer_compte();
        }

    }

    initializeAccordionState() {
        const savedAccordionState = localStorage.getItem(this.accordionStateKey);

        if (savedAccordionState) {
            const openItems = savedAccordionState.split(',');
            openItems.forEach(itemId => {
                const accordionElement = document.getElementById(itemId);
                if (accordionElement) {
                    new bootstrap.Collapse(accordionElement, { toggle: true });
                }
            });
        }
        this.accordionItems.forEach(item => {
            const button = item.querySelector('.accordion-button');
            if (button) {
                button.addEventListener('click', this.updateAccordionState.bind(this));
            }
        });
    }

    updateAccordionState(event) {
        const button = event.target.closest('.accordion-button');
        if (!button) return;

        const collapseId = button.getAttribute('data-bs-target').replace('#', '');
        const savedAccordionState = localStorage.getItem(this.accordionStateKey);

        let openItems = savedAccordionState ? savedAccordionState.split(',') : [];

        if (openItems.includes(collapseId)) {
            openItems = openItems.filter(id => id !== collapseId);
        } else {
            openItems.push(collapseId);
        }

        localStorage.setItem(this.accordionStateKey, openItems.join(','));
    }

    addModalEventListeners() {
        const historiqueModal = document.getElementById('historique');
        if (historiqueModal) {
            historiqueModal.addEventListener('shown.bs.offcanvas', () => {
                localStorage.setItem('historiqueModalOpen', 'true');
            });

            historiqueModal.addEventListener('hidden.bs.offcanvas', () => {
                localStorage.setItem('historiqueModalOpen', 'false');
            });
        }
    }

    initializeModalState() {
        const isModalOpen = localStorage.getItem('historiqueModalOpen');

        if (isModalOpen === 'true') {
            const historiqueModal = document.getElementById('historique');
            if (historiqueModal) {
                historiqueModal.classList.add('no-transition');

                setTimeout(() => {
                    const offcanvas = new bootstrap.Offcanvas(historiqueModal);
                    offcanvas.show();
                    historiqueModal.classList.remove('no-transition');
                }, 0);
            }
        }
    }
    
}
