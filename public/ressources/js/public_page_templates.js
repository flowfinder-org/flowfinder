class FlowFinderPublicPageTemplate
{
    constructor()
    {

        this.backdropModal = '<div class="flowfinder-modal-backdrop flowfinder-d-none"></div>';

        this.confirmationModal = '<div class="flowfinder-modal modal-dialog-centered flowfinder-d-none flowfinder-confirmation-modal" tabindex="-1" aria-labelledby="confirmationModalLabel" > \
            <div class="flowfinder-modal-dialog"> \
                <div class="flowfinder-modal-content flowfinder-content-page"> \
                    <div class="flowfinder-modal-header"> \
                        <div class="flowfinder-modal-title" id="confirmationModalLabel">Confirmation</div> \
                        <button type="button" class="flowfinder-btn flowfinder-btn-close flowfinder-btn-fermer-modal" data-bs-dismiss="modal" aria-label="Close"></button> \
                    </div> \
                    <div class="flowfinder-modal-body"> \
                        Les éléments ont été transmis avec succès. \
                    </div> \
                    <div class="flowfinder-modal-footer"> \
                        <button type="button" class="flowfinder-btn flowfinder-btn-secondary flowfinder-btn-fermer-modal flowfinder-content-sendBtn" data-bs-dismiss="modal">Fermer</button> \
                    </div> \
                </div> \
            </div> \
        </div>'

        this.pleinecran = '\
        <div class="flowfinder-modal flowfinder-form-container flowfinder-d-none" id="flowfinder-pleinecran-conteneur" tabindex="-1" aria-labelledby="modalFullscreenLabel"> \
            <div class="flowfinder-modal-fullscreen"> \
                <div class="flowfinder-modal-content flowfinder-content-page"> \
                    <div class="flowfinder-modal-header"> \
                        <button type="button" class="flowfinder-btn flowfinder-btn-close flowfinder-btn-fermer-modal" data-bs-dismiss="modal" aria-label="Close"></button> \
                    </div> \
                    <div class="flowfinder-modal-body"> \
                        <div id="flowfinder-pleinecran-contenu"> \
                        </div> \
                    </div> \
                </div> \
            </div> \
        </div>';

        this.integre = '\
        <div class="flowfinder-form-container flowfinder-d-none flowfinder-content-page" id="flowfinder-integre-conteneur"> \
            <div id="flowfinder-integre-contenu" class="d-none"> \
            </div> \
        </div>';

        this.popup = '\
        <div class="flowfinder-modal flowfinder-form-container modal-dialog-centered flowfinder-d-none" tabindex="-1" id="flowfinder-popup-conteneur" aria-labelledby="modalPopupLabel" aria-hidden="true"> \
            <div class="flowfinder-modal-dialog flowfinder-modal-dialog-centered "> \
                <div class="flowfinder-modal-content flowfinder-content-page"> \
                    <div class="flowfinder-modal-header"> \
                        <button type="button" class="flowfinder-btn flowfinder-btn-close flowfinder-btn-fermer-modal" data-bs-dismiss="modal" aria-label="Close"></button> \
                    </div> \
                    <div class="flowfinder-modal-body"> \
                        <div id="flowfinder-popup-contenu"> \
                        </div> \
                    </div> \
                </div> \
            </div> \
        </div>';

        this.bouton = '\
        <div class="flowfinder-form-container flowfinder-d-none" id="flowfinder-bouton-conteneur"> \
            <div class="flowfinder-feedback-button" aria-controls="flowfinderbouton"> \
                Feedback \
            </div> \
            <div class="flowfinder-offcanvas flowfinder-offcanvas-end flowfinder-feedback-offcanvas" tabindex="-1" aria-labelledby="flowfinderboutonLabel"> \
                <div class="flowfinder-offcanvas-body flowfinder-content-page"> \
                    <div id="flowfinder-bouton-contenu"> \
                    </div> \
                </div> \
            </div> \
        </div>';

    }

    getTemplateByName(template_name)
    {
        if(template_name == "confirmationModal")
        {
            return this.confirmationModal;
        }
        else if(template_name == "backdropModal")
        {
            return this.backdropModal;
        }
        else if(template_name == "integre")
        {
            return this.integre;
        }
        else if (template_name == "bouton") {
            return this.bouton;
        }
        else if (template_name == "popup") {
            return this.popup;
        }
        else if (template_name == "pleinecran") {
            return this.pleinecran;
        }
        else {
            console.log("mode d'affichage FlowFinder non reconnu : '" + template_name);
            return this.integre;
        }
    }

}