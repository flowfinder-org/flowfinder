<div class="d-flex justify-content-between align-items-center">
    <span>Logo visible</span>
    <div class="sortable-logo-active form-check form-switch">
        <input class="flowfinder-SortableElement-InputSwitch-Visibilite-logo form-check-input" id="flowfinder-SortableElement-InputSwitch-Visibilite-logo" type="checkbox" role="switch" checked="">
    </div>
    <?php
    $imagelogo = '_USERS_FILES/' . $id_utilisateur . '/logo_200.jpg';
    if (!file_exists($imagelogo)) { ?>
        <span style="color:tomato;">Vous n'avez pas téléchargé votre logo. Vous pouvez le faire dans votre <a href="/<?= APP_LANG ?>/profil" target="_blank">profil</a>, rubrique 'Personnalisation de mon logo' </span>
    <?php
    }
    ?>
</div>
<hr />

<span>Titre - Nom de société</span>
<div class="input-group mb-3">
    <input type="text" class="flowfinder-SortableElement-InputText-nomsociete form-control" placeholder="Nom de société" value="" aria-label="nom-societe" id="flowfinder-SortableElement-InputText-nomsociete">
</div>

<span>Courte description</span>
<div class="input-group mb-3">
    <textarea class="flowfinder-SortableElement-InputTextarea-descriptionsociete form-control" aria-label="With textarea" id="flowfinder-SortableElement-InputTextarea-descriptionsociete"></textarea>
</div>

<hr />

<span>URL de votre tunnel</span>
<div class="input-group mb-3">
    <input type="text" class="form-control" id="copyInput-tunnel" value="<?= $_SERVER['HTTP_HOST']; ?>/p/<?php if ($url_identifier != "") {
                                                                                                        $ak->ecrit($url_identifier);
                                                                                                    } else {
                                                                                                        $ak->ecrit("Aucune-adresse-trouvé");
                                                                                                    } ?>/t<?= $page_numero ?>" readonly>
    <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" id="copyButton-tunnel">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z" />
            </svg>
        </button>
    </div>

    <?php if ($url_identifier == "") { ?>
        <span style="color:tomato;">Vous n'avez pas personnalisé votre adresse. Vous pouvez le faire dans votre <a href="/<?= APP_LANG ?>/profil" target="_blank">profil</a>, rubrique 'Personnalisation de mon adresse FlowFinder' </span>
    <?php } ?>

</div>

<span>QRCODE de votre tunnel</span>
<div class="input-group mb-3">
    <div id="qrcode" class="mt-2"></div>
</div>