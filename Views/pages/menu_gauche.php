<script src="<?= $ak->asset("ressources/js/menu_gauche.js") ?>"></script>
<section class="historique">
    <div class="offcanvas offcanvas-start" tabindex="-1" id="historique" aria-labelledby="historiqueLabel">

        <div class="d-flex justify-content-between barre-menu-gauche-top">
            <div>
                <a class="navbar-brand " href="#" onclick="menuAccueil('<?= $code_personnage ?>','');">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-house-door-fill me-0" viewBox="0 0 16 16">
                        <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5" />
                    </svg>
                </a>
                <!--
                <a class="navbar-brand" href="/<?= APP_LANG ?>/profil">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                    </svg>
                </a>
                -->
            </div>
            <div>
                <a class="navbar-brand" href="#" data-bs-dismiss="offcanvas" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                        <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="offcanvas-body">
            <?php
            if ($id_utilisateur > 110 && $id_utilisateur < 5475) {
                include 'menu_gauche_ancien.php';
            }
            ?>


            <section>
                <h4 class="p-3 text-center rounded" style="background-color:#eee; color:#000;">
                    Analyse visuelle du comportement
                </h4>
                <h2 class="menugauche-header border_bottom">
                    <a href='/<?= APP_LANG ?>/statistiques?ank={"type_vue":"top_en_haut","filtre_pub":"aucun"}'>
                        <button class="menugauche-button collapsed" type="button">

                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                <defs>
                                    <style>
                                        .cls-1 {
                                            fill: none;
                                            stroke: #000;
                                            stroke-miterlimit: 10;
                                            stroke-width: .5px;
                                        }
                                    </style>
                                </defs>
                                <!-- Generator: Adobe Illustrator 28.7.3, SVG Export Plug-In . SVG Version: 1.2.0 Build 164)  -->
                                <g>
                                    <g id="Calque_1">
                                        <g>
                                            <polygon points="8.3 14.6 8.3 14.3 7.5 14.3 7.8 13.8 6.3 14.5 7.8 15.2 7.5 14.7 8.3 14.6" />
                                            <rect x="6.4" y="15" width=".3" height=".1" transform="translate(-8 22.5) rotate(-93.7)" />
                                            <rect x="6.4" y="14" width=".3" height=".1" transform="translate(-7 21.5) rotate(-93.7)" />
                                            <polygon points="6.3 14.8 6.1 15 6.1 15 6.3 14.7 6.3 14.8" />
                                            <rect x="5.9" y="14.5" width=".3" height=".1" transform="translate(-.4 .2) rotate(-1.5)" />
                                            <polygon points="6.1 14 6.3 14.2 6.3 14.3 6 14.1 6.1 14" />
                                            <path d="M2.1,9.4c0-.3,0-.7,0-1,0-3.3,2.7-6,6-5.9s5.9,2.7,5.9,6-2.7,6-6,5.9,0,0,0,0l-.3.3c.1,0,.2,0,.3,0,3.5,0,6.3-2.8,6.3-6.3s-2.8-6.3-6.3-6.3S1.7,4.9,1.7,8.3s0,.7,0,1h.3Z" />
                                        </g>
                                        <polygon points="4 3.8 3.4 4.4 2.7 3.8 3.4 3.1 4 3.8" />
                                        <path d="M3.5,3.1l-.8.8c0,0-.2,0-.3,0h0c0-.2,0-.3,0-.4l.8-.8c0,0,.2,0,.3,0h0c0,.2,0,.3,0,.4Z" />
                                        <polyline class="cls-1" points="9.5 5.8 7.4 7.7 9.4 12.3" />
                                        <polygon points="8.7 2.1 7.5 2.2 7.4 1.3 8.7 1.2 8.7 2.1" />
                                        <path d="M8.8,1.4h-1.5c-.2,0-.3-.1-.3-.3v-.2c0-.2.1-.3.3-.3h1.5c.2,0,.3.1.3.3v.2c0,.2-.1.3-.3.3Z" />
                                        <polygon points="12.8 4.5 12.2 3.8 12.9 3.2 13.5 4 12.8 4.5" />
                                        <path d="M13.5,4.1l-.7-.9c0,0,0-.2,0-.3h.1c0-.1.2-.1.3,0l.7.9c0,0,0,.2,0,.3h-.1c0,.1-.2.1-.3,0Z" />
                                    </g>
                                </g>
                            </svg>
                            <div class="text-left">
                                <p class="m-0 p-0 fw-bold">Temps post-publicité</p>
                                <small class="m-0 p-0">Quelles publicités fonctionnent ?</small>
                            </div>
                        </button>
                    </a>
                </h2>

                <h2 class="menugauche-header">
                    <a href='/<?= APP_LANG ?>/SessionsReplays/list?ank={"filtre_pub":"aucun"}'>
                        <button class="menugauche-button collapsed" type="button">

                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                <defs>
                                    <style>
                                        .cls-1 {
                                            fill: none;
                                            stroke: #000;
                                            stroke-miterlimit: 10;
                                            stroke-width: .2px;
                                        }
                                    </style>
                                </defs>
                                <g>
                                    <g id="Calque_1">
                                        <path d="M7,5.4c-.2-.2-.5-.1-.7.1,0,0,0,.2,0,.3v5.1c0,.3.2.5.5.5.1,0,.2,0,.3,0l3.5-2.5c.2-.2.3-.5.1-.7,0,0,0,0-.1-.1l-3.5-2.5Z" />
                                        <polygon points="12.8 6.8 13.5 6 11.7 4.2 13.5 3.6 8.4 1.7 10.3 6.8 10.9 5 12.8 6.8" />
                                        <rect x="7.5" y="3.3" width="1" height=".3" transform="translate(0 6.9) rotate(-47.3)" />
                                        <rect x="9.7" y="1" width="1" height=".3" transform="translate(2.4 7.9) rotate(-47.3)" />
                                        <rect x="7" y="2.1" width="1" height=".3" transform="translate(0 .1) rotate(-.9)" />
                                        <rect x="7.5" y="1" width="1" height=".3" transform="translate(3.2 -5.3) rotate(45)" />
                                        <rect x="8.6" y=".5" width="1" height=".3" transform="translate(8.4 9.8) rotate(-90.4)" />
                                        <path class="cls-1" d="M13.1,6.5s1.5,1.8.8,4.9c-1.8,7.1-5.5.5-7.2,3.4S.7,11.9.7,10.3" />
                                    </g>
                                </g>
                            </svg>
                            <div class="text-left">
                                <p class="m-0 p-0 fw-bold">Replay des interactions</p>
                                <small class="m-0 p-0">Analyser le comportement.</small>
                            </div>
                        </button>
                    </a>
                </h2>
            </section>
            <section>
                <h4 class="p-3 text-center rounded" style="background-color:#eee; color:#000;">
                    Mesurez les opinions et ressentis
                </h4>
                <div class="accordion accordion-flush" id="accordion_perception">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <?php
                            $nombre_sondage = count($listesondage);
                            ?>
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-sondages" aria-expanded="false" aria-controls="flush-sondages">

                                <svg id="Calque_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .2px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="st0" x="1.7" y="2.6" width="4.6" height="4.6" />
                                    <rect class="st0" x="1.7" y="9.3" width="4.6" height="4.6" />
                                    <polyline class="st0" points="2.3 4.7 3.6 6.5 8 2" />
                                    <g>
                                        <path d="M9.8,4.9c0,.9-.6,1.4-1.3,1.4s-1.2-.6-1.2-1.4.5-1.4,1.3-1.4,1.2.6,1.2,1.4ZM7.7,4.9c0,.6.3,1.1.9,1.1s.9-.5.9-1.1-.3-1.1-.9-1.1-.9.5-.9,1.1Z" />
                                        <path d="M10.6,3.5v1.6c0,.6.3.9.6.9s.7-.3.7-.9v-1.6h.4v1.6c0,.8-.4,1.2-1,1.2s-1-.3-1-1.2v-1.6h.4Z" />
                                        <path d="M13.1,3.5v2.7h-.3v-2.7h.3Z" />
                                    </g>
                                    <g>
                                        <path d="M7.1,13.2v-2.7h.4l.9,1.4c.2.3.4.6.5.9h0c0-.4,0-.7,0-1.1v-1.1h.3v2.7h-.4l-.9-1.4c-.2-.3-.4-.6-.5-.9h0c0,.3,0,.7,0,1.1v1.2h-.3Z" />
                                        <path d="M12.1,11.9c0,.9-.6,1.4-1.3,1.4s-1.2-.6-1.2-1.4.5-1.4,1.3-1.4,1.2.6,1.2,1.4ZM10,11.9c0,.6.3,1.1.9,1.1s.9-.5.9-1.1-.3-1.1-.9-1.1-.9.5-.9,1.1Z" />
                                        <path d="M12.5,13.2v-2.7h.4l.9,1.4c.2.3.4.6.5.9h0c0-.4,0-.7,0-1.1v-1.1h.3v2.7h-.4l-.9-1.4c-.2-.3-.4-.6-.5-.9h0c0,.3,0,.7,0,1.1v1.2h-.3Z" />
                                    </g>
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">Sondages
                                        <span class="badge rounded-pill bg-creative" style="position: relative; top: -5px;"><?= $nombre_sondage ?></span>
                                    </p>
                                    <small class="m-0 p-0">Mesurez rapidement les opinions et ressentis</small>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-sondages" class="accordion-collapse collapse" data-bs-parent="#accordionFlushSondages">
                            <div class="accordion-body">
                                <a href="/<?= APP_LANG ?>/sondage/t<?= $nombre_sondage + 1 ?>">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="width:2em; height: 2em;">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                                        </svg>
                                        Créer un sondage
                                    </button>
                                </a>
                                <?php
                                if ($listesondage) {
                                    foreach ($listesondage as $item) {
                                        $config = json_decode($item->json_configuration_page, true);
                                        if (isset($config['titretunnel']['InputText'])) {
                                            $nvconfig = $config['titretunnel']['InputText'];
                                            if ($nvconfig == "") {
                                                $nvconfig = 'Pas de titre renseigné';
                                            }
                                            $numsondage[$item->id_page_configuration] = true;
                                ?>
                                            <a href="/<?= APP_LANG ?>/sondage/t<?= $item->id_page_configuration; ?>">
                                                <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                                    (<?= $item->id_page_configuration; ?>) <?= $nvconfig ?>
                                                </button>
                                            </a>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <?php
                            $nombre_feedback = count($listefeedback);
                            ?>
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-feedbackrapide" aria-expanded="false" aria-controls="flush-feedbackrapide">
                                <svg id="Calque_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .2px;
                                            }
                                        </style>
                                    </defs>
                                    <polygon points="3.5 7.1 2.6 6.5 1.8 7.1 2.1 6.1 1.2 5.5 2.3 5.5 2.6 4.5 2.9 5.5 4 5.5 3.1 6.1 3.5 7.1" />
                                    <polygon points="7 7.1 6.1 6.5 5.2 7.1 5.6 6.1 4.7 5.5 5.7 5.5 6.1 4.5 6.4 5.5 7.5 5.5 6.6 6.1 7 7.1" />
                                    <polygon class="st0" points="10.4 7.1 9.6 6.5 8.7 7.1 9 6.1 8.2 5.5 9.2 5.5 9.5 4.5 9.9 5.5 10.9 5.5 10.1 6.1 10.4 7.1" />
                                    <g>
                                        <path d="M14.3,9.6c-.1.1-.2.2-.4.3-.1.1-.3.3-.5.4-.3.2-.6.5-.9.7,0-.1-.2-.3-.3-.4-.2-.1-.4-.2-.7-.2.1-.2.4-.3.6-.4.2-.1.4-.3.6-.4.2-.2.4-.3.7-.3.2,0,.6,0,.7.3Z" />
                                        <path d="M13.9,9.1c0,0,0,.1-.1.2,0,0-.1,0-.2,0-.3,0-.6.1-.8.3-.2.2-.4.4-.7.5-.2.1-.5.2-.7.4,0,0,0,0,0,0,0,0-.2,0-.2,0,0,0,0,0,0,0,.2-.2.4-.3.6-.5.2-.1.5-.3.7-.5.2-.2.4-.4.7-.5.2,0,.6-.1.8,0Z" />
                                        <path d="M13.1,9c-.2,0-.4.2-.6.4-.1,0-.2.2-.3.3-.1,0-.3.2-.4.3-.3.2-.5.3-.7.6-.2,0-.4,0-.6,0,.2-.2.4-.3.6-.5.2-.2.4-.3.6-.5.2-.2.4-.4.7-.5.2,0,.5-.1.8,0Z" />
                                        <path d="M9.8,11.7s0,0,0,0c0,0-.1,0-.2,0h-.2s0,0,0,0c0,0-.2,0-.3,0h0c0,0-.2,0-.3,0-.4,0-.8.2-1.1.3,0,0-.1,0,0,0,.4-.2.8-.3,1.2-.4.3,0,.7,0,1,0,0,0,0,0,0,0,0,0,0,0,.1,0,.3,0,.6,0,1,.1.3,0,.7,0,1,0,.2,0,.6-.2.5-.5,0-.3-.4-.4-.7-.4-.4-.1-.8-.1-1.1,0-.4,0-.8,0-1.1,0-.4,0-.7-.1-1-.3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0-.5-.4-1.1-.8-1.7-.8-.3,0-.7,0-1,.1-.2,0-.3,0-.5,0-.2,0-.3,0-.5,0-.4,0-.8,0-1.1.2-.3.1-.7.3-1,.5-.3.2-.6.5-.8.7,0,0,0,0,0,0v2.1c0,0,.8,0,1.7-.2s.7-.3,1.1-.3c.4,0,.8,0,1.1.2.4,0,.8.2,1.2.3.4.1.8.2,1.2.3.4,0,.8.1,1.2,0,0,0,0,0,0,0,.4-.2.7-.5,1-.9.2-.2.3-.3.5-.5.1-.1.3-.2.4-.3-.4,0-.8,0-1.2-.1h-.3Z" />
                                        <polygon points="10.5 10.3 10.5 10.3 10.5 10.3 10.5 10.3" />
                                    </g>
                                    <polygon class="st0" points="14.3 7.1 13.4 6.5 12.6 7.1 12.9 6.1 12 5.5 13.1 5.5 13.4 4.5 13.7 5.5 14.8 5.5 13.9 6.1 14.3 7.1" />
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">Feedback rapide
                                        <span class="badge rounded-pill bg-creative" style="position: relative; top: -5px;"><?= $nombre_feedback ?></span>
                                    </p>
                                    <small class="m-0 p-0">Collectez des avis instantanément.</small>
                                </div>

                            </button>
                        </h2>
                        <div id="flush-feedbackrapide" class="accordion-collapse collapse" data-bs-parent="#accordionFlushFeedbackRapide">
                            <div class="accordion-body">
                                <a href="/<?= APP_LANG ?>/feedback/t<?= $nombre_feedback + 1 ?>">

                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="width:2em; height: 2em;">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                                        </svg> Créer un feedback
                                    </button>
                                </a>
                                <?php
                                if ($listefeedback) {
                                    foreach ($listefeedback as $item) {
                                        $config = json_decode($item->json_configuration_page, true);
                                        if (isset($config['titretunnel']['InputText'])) {
                                            $nvconfig = $config['titretunnel']['InputText'];
                                            if ($nvconfig == "") {
                                                $nvconfig = 'Pas de titre renseigné';
                                            }
                                            $numfeedback[$item->id_page_configuration] = true;
                                ?>
                                            <a href="/<?= APP_LANG ?>/feedback/t<?= $item->id_page_configuration; ?>">
                                                <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                                    (<?= $item->id_page_configuration; ?>) <?= $nvconfig ?>
                                                </button>
                                            </a>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <h4 class="p-3 text-center rounded" style="background-color:#eee; color:#000;">
                    Obtenez des réponses complètes et approfondies
                </h4>
                <div class="accordion accordion-flush" id="accordion_analyse">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <?php
                            $nombre_formulaire = count($listeformulaire);
                            ?>
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-retourformulaires" aria-expanded="false" aria-controls="flush-feedback rapide">
                                <svg id="Calque_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .1px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="st0" x="1.4" y=".9" width="13.6" height="19" rx=".8" ry=".8" />
                                    <polygon points="3.7 13.2 3.4 12.9 3 13.2 3.1 12.8 2.8 12.5 3.2 12.5 3.3 12.1 3.5 12.5 3.9 12.5 3.6 12.8 3.7 13.2" />
                                    <polygon points="5.1 13.2 4.7 12.9 4.4 13.2 4.5 12.8 4.2 12.5 4.6 12.5 4.7 12.1 4.9 12.5 5.3 12.5 5 12.8 5.1 13.2" />
                                    <polygon class="st0" points="6.5 13.2 6.1 12.9 5.8 13.2 5.9 12.8 5.6 12.5 6 12.5 6.1 12.1 6.3 12.5 6.7 12.5 6.3 12.8 6.5 13.2" />
                                    <polygon class="st0" points="7.9 13.2 7.6 12.9 7.2 13.2 7.4 12.8 7 12.5 7.4 12.5 7.6 12.1 7.7 12.5 8.1 12.5 7.8 12.8 7.9 13.2" />
                                    <line class="st0" x1="13.3" y1="4.5" x2="2.8" y2="4.5" />
                                    <line class="st0" x1="12.6" y1="5.8" x2="2.8" y2="5.8" />
                                    <line class="st0" x1="8.6" y1="7.1" x2="2.8" y2="7.1" />
                                    <line class="st0" x1="12.5" y1="8.5" x2="2.8" y2="8.5" />
                                    <line class="st0" x1="13.3" y1="9.8" x2="2.8" y2="9.8" />
                                    <line class="st0" x1="12.6" y1="11.1" x2="2.8" y2="11.1" />
                                    <circle class="st0" cx="3.2" cy="14.7" r=".4" />
                                    <circle class="st0" cx="3.2" cy="16" r=".4" />
                                    <circle class="st0" cx="3.2" cy="17.3" r=".4" />
                                    <circle class="st0" cx="3.2" cy="18.6" r=".4" />
                                    <line class="st0" x1="13.9" y1="14.7" x2="4.2" y2="14.7" />
                                    <line class="st0" x1="13.2" y1="16" x2="4.2" y2="16" />
                                    <line class="st0" x1="9.5" y1="17.3" x2="4.2" y2="17.3" />
                                    <line class="st0" x1="13.1" y1="18.6" x2="4.2" y2="18.6" />
                                    <rect class="st0" x="2.8" y="2.7" width="1" height="1" />
                                    <polyline class="st0" points="3 3 3.4 3.5 4.3 2.5" />
                                    <rect class="st0" x="8.2" y="2.7" width="1" height="1" />
                                    <line class="st0" x1="7.8" y1="3.7" x2="4.2" y2="3.7" />
                                    <line class="st0" x1="13.3" y1="3.7" x2="9.5" y2="3.7" />
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">Retour formulaires
                                        <span class="badge rounded-pill bg-creative" style="position: relative; top: -5px;"><?= $nombre_formulaire ?></span>
                                    </p>
                                    <small class="m-0 p-0">Collectez des insights détaillés</small>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-retourformulaires" class="accordion-collapse collapse" data-bs-parent="#accordionFlushRetourFormulaires">
                            <div class="accordion-body">
                                <a href="/<?= APP_LANG ?>/formulaire/t<?= $nombre_formulaire + 1 ?>">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16" style="width:2em; height: 2em;">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                                        </svg> Créer un formulaire
                                    </button>
                                </a>
                                <?php
                                if ($listeformulaire) {
                                    foreach ($listeformulaire as $item) {
                                        $config = json_decode($item->json_configuration_page, true);
                                        if (isset($config['titretunnel']['InputText'])) {
                                            $nvconfig = $config['titretunnel']['InputText'];
                                            if ($nvconfig == "") {
                                                $nvconfig = 'Pas de titre renseigné';
                                            }
                                            $numformulaire[$item->id_page_configuration] = true;
                                ?>
                                            <a href="/<?= APP_LANG ?>/formulaire/t<?= $item->id_page_configuration; ?>">
                                                <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                                    (<?= $item->id_page_configuration; ?>) <?= $nvconfig ?>
                                                </button>
                                            </a>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <section>
                <h4 class="p-3 text-center rounded" style="background-color:#eee; color:#000;">
                    Documentation
                </h4>
                <div class="accordion accordion-flush" id="accordion_documentation">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-docinstallation" aria-expanded="false" aria-controls="flush-feedback rapide">
                                <svg id="Documentation" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .1px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="st0" x="1.4" y=".9" width="13.6" height="19" rx=".8" ry=".8" />
                                    <line class="st0" x1="12.3" y1="2.2" x2="2.8" y2="2.2" />
                                    <line class="st0" x1="8" y1="8" x2="2.8" y2="8" />
                                    <line class="st0" x1="13.3" y1="9.3" x2="2.8" y2="9.3" />
                                    <line class="st0" x1="13.3" y1="10.7" x2="2.8" y2="10.7" />
                                    <line class="st0" x1="13.3" y1="12" x2="2.8" y2="12" />
                                    <line class="st0" x1="13.3" y1="13.3" x2="2.8" y2="13.3" />
                                    <line class="st0" x1="13.3" y1="14.7" x2="2.8" y2="14.7" />
                                    <path d="M2.8,2.7v4.5h10.5V2.7H2.8ZM8.1,6.7c-1,0-1.8-.8-1.8-1.8s.8-1.8,1.8-1.8,1.8.8,1.8,1.8-.8,1.8-1.8,1.8Z" />
                                    <polygon points="7.8 4.1 8.8 4.9 7.8 5.9 7.8 4.9 7.8 4.1" />
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">
                                        Premiers pas
                                    </p>
                                    <small class="m-0 p-0">Connexion, installation</small>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-docinstallation" class="accordion-collapse collapse" data-bs-parent="#accordionFlushdocinstallation">
                            <div class="accordion-body">
                                <!--
                                <a href="/<?= APP_LANG ?>/page/doc/connexion">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Connexion à votre espace utilisateur
                                    </button>
                                </a>
                                        -->
                                <a href="/<?= APP_LANG ?>/page/doc/installation">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Installation du script sur votre site en 2 minutes
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--
                <div class="accordion accordion-flush" id="accordion_docanalyse">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-docanalyse" aria-expanded="false" aria-controls="flush-feedback rapide">
                                <svg id="Documentation" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .1px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="st0" x="1.4" y=".9" width="13.6" height="19" rx=".8" ry=".8" />
                                    <line class="st0" x1="12.3" y1="2.2" x2="2.8" y2="2.2" />
                                    <line class="st0" x1="8" y1="8" x2="2.8" y2="8" />
                                    <line class="st0" x1="13.3" y1="9.3" x2="2.8" y2="9.3" />
                                    <line class="st0" x1="13.3" y1="10.7" x2="2.8" y2="10.7" />
                                    <line class="st0" x1="13.3" y1="12" x2="2.8" y2="12" />
                                    <line class="st0" x1="13.3" y1="13.3" x2="2.8" y2="13.3" />
                                    <line class="st0" x1="13.3" y1="14.7" x2="2.8" y2="14.7" />
                                    <path d="M2.8,2.7v4.5h10.5V2.7H2.8ZM8.1,6.7c-1,0-1.8-.8-1.8-1.8s.8-1.8,1.8-1.8,1.8.8,1.8,1.8-.8,1.8-1.8,1.8Z" />
                                    <polygon points="7.8 4.1 8.8 4.9 7.8 5.9 7.8 4.9 7.8 4.1" />
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">
                                        Analyse
                                    </p>
                                    <small class="m-0 p-0">Comportement des visiteurs</small>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-docanalyse" class="accordion-collapse collapse" data-bs-parent="#accordionFlushdocanalyse">
                            <div class="accordion-body">
                                <a href="/<?= APP_LANG ?>/page/doc/parcours">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Visualisez chaque parcours
                                    </button>
                                </a>
                                <a href="/<?= APP_LANG ?>/page/doc/detection">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Détection des blocages et abandons
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion accordion-flush" id="accordion_docretours">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-docretour" aria-expanded="false" aria-controls="flush-feedback rapide">
                                <svg id="Documentation" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .1px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="st0" x="1.4" y=".9" width="13.6" height="19" rx=".8" ry=".8" />
                                    <line class="st0" x1="12.3" y1="2.2" x2="2.8" y2="2.2" />
                                    <line class="st0" x1="8" y1="8" x2="2.8" y2="8" />
                                    <line class="st0" x1="13.3" y1="9.3" x2="2.8" y2="9.3" />
                                    <line class="st0" x1="13.3" y1="10.7" x2="2.8" y2="10.7" />
                                    <line class="st0" x1="13.3" y1="12" x2="2.8" y2="12" />
                                    <line class="st0" x1="13.3" y1="13.3" x2="2.8" y2="13.3" />
                                    <line class="st0" x1="13.3" y1="14.7" x2="2.8" y2="14.7" />
                                    <path d="M2.8,2.7v4.5h10.5V2.7H2.8ZM8.1,6.7c-1,0-1.8-.8-1.8-1.8s.8-1.8,1.8-1.8,1.8.8,1.8,1.8-.8,1.8-1.8,1.8Z" />
                                    <polygon points="7.8 4.1 8.8 4.9 7.8 5.9 7.8 4.9 7.8 4.1" />
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">
                                        Création et anaylse
                                    </p>
                                    <small class="m-0 p-0">Feedbacks, sondages et formulaires</small>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-docretour" class="accordion-collapse collapse" data-bs-parent="#accordionFlushdocretour">
                            <div class="accordion-body">
                                <a href="/<?= APP_LANG ?>/page/doc/feedbacks">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Feedbacks en temps réel
                                    </button>
                                </a>
                                <a href="/<?= APP_LANG ?>/page/doc/sondages">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Création et analyse des sondages
                                    </button>
                                </a>
                                <a href="/<?= APP_LANG ?>/page/doc/formulaires">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Optimisation des formulaires
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion accordion-flush" id="accordion_ameliorer_conversions">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-docconversions" aria-expanded="false" aria-controls="flush-feedback rapide">
                                <svg id="Documentation" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" class="me-2">
                                    <defs>
                                        <style>
                                            .st0 {
                                                fill: none;
                                                stroke: #000;
                                                stroke-miterlimit: 10;
                                                stroke-width: .1px;
                                            }
                                        </style>
                                    </defs>
                                    <rect class="st0" x="1.4" y=".9" width="13.6" height="19" rx=".8" ry=".8" />
                                    <line class="st0" x1="12.3" y1="2.2" x2="2.8" y2="2.2" />
                                    <line class="st0" x1="8" y1="8" x2="2.8" y2="8" />
                                    <line class="st0" x1="13.3" y1="9.3" x2="2.8" y2="9.3" />
                                    <line class="st0" x1="13.3" y1="10.7" x2="2.8" y2="10.7" />
                                    <line class="st0" x1="13.3" y1="12" x2="2.8" y2="12" />
                                    <line class="st0" x1="13.3" y1="13.3" x2="2.8" y2="13.3" />
                                    <line class="st0" x1="13.3" y1="14.7" x2="2.8" y2="14.7" />
                                    <path d="M2.8,2.7v4.5h10.5V2.7H2.8ZM8.1,6.7c-1,0-1.8-.8-1.8-1.8s.8-1.8,1.8-1.8,1.8.8,1.8,1.8-.8,1.8-1.8,1.8Z" />
                                    <polygon points="7.8 4.1 8.8 4.9 7.8 5.9 7.8 4.9 7.8 4.1" />
                                </svg>
                                <div class="text-left">
                                    <p class="m-0 p-0 fw-bold">
                                        Améliorer vos conversions
                                    </p>
                                    <small class="m-0 p-0"></small>
                                </div>
                            </button>
                        </h2>
                        <div id="flush-docconversions" class="accordion-collapse collapse" data-bs-parent="#accordionFlushdocconversions">
                            <div class="accordion-body">
                                <a href="/<?= APP_LANG ?>/page/doc/pub">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Tests A/B des publicités : trouvez le visuel gagnant
                                    </button>
                                </a>
                                <a href="/<?= APP_LANG ?>/page/doc/optimisation">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Optimisation du tunnel de conversion
                                    </button>
                                </a>
                                <a href="/<?= APP_LANG ?>/page/doc/roi">
                                    <button class="nav-div btn m-1 ms-0 me-0 btn-primary btn-sm w-100">
                                        Maximiser le ROI des campagnes marketing
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                                        -->
            </section>

        </div>

        <hr />
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../../ressources/img/views/avatar/moi.svg" alt="mon avatar" style="width: 45px; height: 100%;" class="rounded-circle me-2 object-fit-cover ms-2 mb-2">
                <strong><?php $ak->ecrit(($prenom == '') ? 'utilisateur' : $prenom);
                        ?> <?php $ak->ecrit(($nom == '') ? 'inconnu' : $nom); ?></strong>
            </a>
            <ul class="dropdown-menu text-small shadow ms-4">
                <div class="d-none">
                    <li class="dropdown-submenu m-2">
                        <a class="nav-div btn btn-inv btn-primary btn-sm me-2" href="#">Mes landing pages</a>
                        <ul class="dropdown-menu">
                            <?php
                            $numtunnel = [1 => false, 2 => false, 3 => false, 4 => false, 5 => false];
                            if ($listetunnel) {
                                foreach ($listetunnel as $item) {
                                    $config = json_decode($item->json_configuration_page, true);
                                    if (isset($config['nomsociete']['InputText'])) {
                                        $nvconfig = $config['nomsociete']['InputText'];
                                        if ($nvconfig == "") {
                                            $nvconfig = 'Titre tu';
                                        }
                                        $numtunnel[$item->id_page_configuration] = true;
                            ?>
                                        <li class="m-2"><a href="/<?= APP_LANG ?>/tunnel/t<?= $item->id_page_configuration; ?>" class="nav-div btn btn-inv btn-primary btn-sm me-2">Editer le tunnel (<?= $item->id_page_configuration; ?>)</a></li>
                                    <?php
                                    }
                                }
                            }

                            foreach ($numtunnel as $key => $value) {
                                if (!$value) { ?>
                                    <li class="m-2"><a href="/<?= APP_LANG ?>/tunnel/t<?= $key ?>" class="nav-div btn btn-inv btn-primary btn-sm me-2">Editer le tunnel (<?= $key; ?>)</a></li>
                            <?php
                                    $numtunnel[$key] = true;
                                }
                            }
                            ?>
                        </ul>
                    </li>
                </div>

                <!--
                <li class="m-2"><a href="/<?= APP_LANG ?>/portail" class="nav-div btn btn-inv btn-primary btn-sm me-2">Editer Mon portail</a></li>

                <li class="m-2"><a href="/<?= APP_LANG ?>/profil" class="nav-div btn btn-inv btn-primary btn-sm me-2">Gérer Mon Compte</a></li>
                -->
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="m-2"><a class="nav-div btn btn-inv btn-primary btn-sm me-2" href="/<?= APP_LANG ?>/accueil/logout" aria-selected="false">Se déconnecter</a></li>
            </ul>

            <a href="preferences.html" class="ms-auto d-flex align-items-center text-decoration-none" style="margin-left: auto;">
                <i class="fas fa-cog fa-lg"></i>
            </a>
        </div>

    </div>

</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Créer une instance de la classe Menu
        window.__MenuGauche = new MenuGauche();
    });
</script>