<?php include APP_ROOT . DS . 'Views' . DS . 'pages' . DS . 'menu_gauche.php'; ?>
<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-adminpage.css") ?>">

<div class="d-flex flex-grow-1 flex-column">


    <div class="ps-2 p-2 d-flex flex-row" style="margin-top: 4.8rem;">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="bloc_presentation mt-2 text-center">
                <h3>Nous n'avons pas encore reçu de données de votre site web.</h3>
            </div>
            <div class="text-muted">
                <p>Veuillez ajouter le fragment de code ci-dessous à vos pages web afin de commencer à collecter des données sur vos visiteurs et leurs sessions.</p>
                <div>
                    <div class="mb-3">
                        <textarea class="form-control" id="PageProfil_SnippetAnalytics" readonly="true" rows="9" style="font-size: 10px; background-color:#EEEEEE;"><?php $ak->ecrit($snippet_analytics); ?></textarea>
                    </div>
                </div>

                <a href="/<?= APP_LANG ?>/page/doc/installation" class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill mb-2 ">
                    <span class="me-2">Accéder à la documentation d'installation </span>
                </a>

                <p>Lorsque le système aura commencé à recevoir des données, ce message sera remplacé par le panneau de statistiques.</p>
                
                <div class="text-center">
                    <a class="btn btn-primary" href='/<?= APP_LANG ?>/statistiques?ank={"type_vue":"top_en_haut","filtre_pub":"aucun"}'>Rafraichir la page</a>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>

    <div class="pb-5 pt-5"></div>
</div>