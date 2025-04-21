<div class="apercu_telephone">
    <div class="flowfinder-pagepublic" id="flowfinder-content-page">
        <div class="flowfinder-container flowfinder-d-flex flowfinder-justify-content-center flowfinder-mt-2 flowfinder-mb-2">
            <div id="flowfinder-content-Visibilite-logo" class="flowfinder-rounded-circle flowfinder-bg-circle flowfinder-d-flex flowfinder-justify-content-center">
                <?php if ($photo_disponible) { ?>
                    <img id="flowfinder-content-logo-fichier" src="<?php $ak->ecrit($photo_url); ?>" />
                <?php } else { ?>
                    <img id="flowfinder-content-logo-fichier" src="/ressources/img/logo_default.png" alt="logo">
                <?php
                }
                ?>
            </div>
        </div>
        <h3 class="flowfinder-text-center" id="flowfinder-content-nomsociete"></h3>
        <p class="flowfinder-text-center" id="flowfinder-content-descriptionsociete"></p>
        <form id="flowfinder-content-Form" class="flowfinder-reset-css">
            <div class="container" id="flowfinder-sortable-elements">
            </div>
        </form>
    </div>
</div>