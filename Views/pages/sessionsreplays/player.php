<?php include APP_ROOT . DS . 'Views' . DS . 'pages' . DS . 'menu_gauche.php'; ?>
<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-adminpage.css") ?>">

<style>
    #rrweb-player-container .rr-player {
        box-shadow: none;
        background-color: transparent;
    }

    #rrweb-player-container .rr-player .rr-controller {
        background-color: transparent;
    }

    #rrweb-player-container .rr-player .rr-progress {

        border-top: solid 4px #EFEFEF;
        border-bottom: solid 4px #EFEFEF;
        background-color: #CCC;
    }

    #rrweb-player-container .rr-player .rr-progress__step {
        background-color: rgba(var(--bs-creative-hover-rgb));
    }
</style>
<div class="container-fluid d-flex flex-column flowfinder_styleform" style="top: -3em; position:relative; background: #EFEFEF;">
    <div class="background-creative topbarlecteur">
        <div>
            <ul>
                <li>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-date" viewBox="0 0 16 16">
                        <path d="M6.445 11.688V6.354h-.633A13 13 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23" />
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                    </svg>
                    <?php $ak->ecritDateUTCVersTimezoneAffichageFormatJourLong($visiteur_session->date_creation); ?> <?php $ak->ecritHeureUTCVersTimezoneAffichage($visiteur_session->date_creation); ?>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg>
                    <?php
                    $session_short_code = "";
                    if ($visiteur_session->visiteur_session_uuid != null && $visiteur_session->visiteur_session_uuid != "") {
                        $session_short_code = explode('-', $visiteur_session->visiteur_session_uuid)[2];
                    }
                    $ak->ecrit($session_short_code);
                    ?>
                </li>

                <li><svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 16 16" width="16" height="16" fill="currentColor">
                        <path d="M13.5.6c-.2,0-.8.3-1.3.5-.5.2-1.2.4-1.5.6-.3.1-1,.4-1.5.6-.5.2-1.2.4-1.6.6-1.2.4-2.3.8-2.9,1.1-1.5.6-2.3.8-3.1,1.1-.5.2-.5.2-.6.2,0,0,0,0,0,.2v.2c0,0,0,.2,0,.2,0,.2,0,8.5,0,8.7,0,.4.3.8.8.9.2,0-.3,0,6.3,0h5.9s0,0,0,0c.4-.1.6-.3.7-.7,0,0,0-.2,0-3.8,0-3.4,0-3.8,0-3.9,0,0,0-.2,0-.5,0-.2,0-.4,0-.4h0s-5.8,0-5.8,0c-3.2,0-5.8,0-5.8,0,0,0,0,0,0-.1,0,0,0-.2,0-.2,0-.1,0-.1.1-.2.1,0,1.9-.6,7.6-2.5,2.1-.7,3.3-1.1,3.3-1.1,0,0,0,0,0-.2,0-.2,0-.2,0-.2,0,0,0,0-.1-.6-.1-.3-.2-.5-.2-.5,0,0-.2,0-.4.1Z" />
                    </svg>
                    <?php $ak->ecrit($visiteur_session->sequence_index); ?></li>

                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                        <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584l.013-.012.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354-.012.012A6.97 6.97 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                    </svg>
                    <?php $ak->ecrit($visiteur_session->seconds_active) ?> secondes
                </li>


                <li><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hdd-network-fill" viewBox="0 0 16 16">
                        <path d="M2 2a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h5.5v3A1.5 1.5 0 0 0 6 11.5H.5a.5.5 0 0 0 0 1H6A1.5 1.5 0 0 0 7.5 14h1a1.5 1.5 0 0 0 1.5-1.5h5.5a.5.5 0 0 0 0-1H10A1.5 1.5 0 0 0 8.5 10V7H14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm.5 3a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m2 0a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1" />
                    </svg>
                    <?php $ak->ecrit($visiteur_session->user_ip); ?> : 
                    <?php $ak->ecrit($visiteur_session->geo_country != null ? $visiteur_session->geo_country : ""); ?> 
                    <?php $ak->ecrit($visiteur_session->geo_region != null ? $visiteur_session->geo_region : ""); ?> 
                    <?php $ak->ecrit($visiteur_session->geo_city != null ? $visiteur_session->geo_city : ""); ?>
                </li>
                <li>
                    
                </li>
                    
            </ul>
        </div>

    </div>
    <div class="topsousbarlecteur">
        <ul>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="currentColor" class="bi bi-browser-edge" viewBox="0 0 16 16">
                    <path d="M9.482 9.341c-.069.062-.17.153-.17.309 0 .162.107.325.3.456.877.613 2.521.54 2.592.538h.002c.667 0 1.32-.18 1.894-.519A3.84 3.84 0 0 0 16 6.819c.018-1.316-.44-2.218-.666-2.664l-.04-.08C13.963 1.487 11.106 0 8 0A8 8 0 0 0 .473 5.29C1.488 4.048 3.183 3.262 5 3.262c2.83 0 5.01 1.885 5.01 4.797h-.004v.002c0 .338-.168.832-.487 1.244l.006-.006z" />
                    <path d="M.01 7.753a8.14 8.14 0 0 0 .753 3.641 8 8 0 0 0 6.495 4.564 5 5 0 0 1-.785-.377h-.01l-.12-.075a5.5 5.5 0 0 1-1.56-1.463A5.543 5.543 0 0 1 6.81 5.8l.01-.004.025-.012c.208-.098.62-.292 1.167-.285q.194.001.384.033a4 4 0 0 0-.993-.698l-.01-.005C6.348 4.282 5.199 4.263 5 4.263c-2.44 0-4.824 1.634-4.99 3.49m10.263 7.912q.133-.04.265-.084-.153.047-.307.086z" />
                    <path d="M10.228 15.667a5 5 0 0 0 .303-.086l.082-.025a8.02 8.02 0 0 0 4.162-3.3.25.25 0 0 0-.331-.35q-.322.168-.663.294a6.4 6.4 0 0 1-2.243.4c-2.957 0-5.532-2.031-5.532-4.644q.003-.203.046-.399a4.54 4.54 0 0 0-.46 5.898l.003.005c.315.441.707.821 1.158 1.121h.003l.144.09c.877.55 1.721 1.078 3.328.996" />
                </svg>
                <?php $ak->ecrit($visiteur_session->os_family != null ? $visiteur_session->os_family : ""); ?> -
                <?php $ak->ecrit($visiteur_session->device_type != null ? $visiteur_session->device_type : ""); ?> - 
                <?php $ak->ecrit($visiteur_session->browser_family != null ? $visiteur_session->browser_family : ""); ?>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="currentColor" class="bi bi-browser-edge" viewBox="0 0 16 16">
                    <path d="M9.482 9.341c-.069.062-.17.153-.17.309 0 .162.107.325.3.456.877.613 2.521.54 2.592.538h.002c.667 0 1.32-.18 1.894-.519A3.84 3.84 0 0 0 16 6.819c.018-1.316-.44-2.218-.666-2.664l-.04-.08C13.963 1.487 11.106 0 8 0A8 8 0 0 0 .473 5.29C1.488 4.048 3.183 3.262 5 3.262c2.83 0 5.01 1.885 5.01 4.797h-.004v.002c0 .338-.168.832-.487 1.244l.006-.006z" />
                    <path d="M.01 7.753a8.14 8.14 0 0 0 .753 3.641 8 8 0 0 0 6.495 4.564 5 5 0 0 1-.785-.377h-.01l-.12-.075a5.5 5.5 0 0 1-1.56-1.463A5.543 5.543 0 0 1 6.81 5.8l.01-.004.025-.012c.208-.098.62-.292 1.167-.285q.194.001.384.033a4 4 0 0 0-.993-.698l-.01-.005C6.348 4.282 5.199 4.263 5 4.263c-2.44 0-4.824 1.634-4.99 3.49m10.263 7.912q.133-.04.265-.084-.153.047-.307.086z" />
                    <path d="M10.228 15.667a5 5 0 0 0 .303-.086l.082-.025a8.02 8.02 0 0 0 4.162-3.3.25.25 0 0 0-.331-.35q-.322.168-.663.294a6.4 6.4 0 0 1-2.243.4c-2.957 0-5.532-2.031-5.532-4.644q.003-.203.046-.399a4.54 4.54 0 0 0-.46 5.898l.003.005c.315.441.707.821 1.158 1.121h.003l.144.09c.877.55 1.721 1.078 3.328.996" />
                </svg>
                <?php $ak->ecrit($visiteur_session->utm_source != null ? $visiteur_session->utm_source : ""); ?> - 
                <?php $ak->ecrit($visiteur_session->utm_content != null ? $visiteur_session->utm_content : ""); ?>
            </li>
        </ul>
        <ul>
            <li>
                <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                    <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z" />
                    <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z" />
                </svg>
                <?php $ak->ecrit($visiteur_session->url); ?> 
            </li>
        </ul>
    </div>
    <div class="d-flex flex-grow-1 p-1 flex-row overflow-hidden">
        <div class="d-flex flex-grow-1" id="rrweb-player-container-flex-parent">
            <textarea style="display:none" id="rrweb_session_events_json_encoded_textarea">
                <?php $ak->ecrit($visiteurs_session_events_json_encoded); ?>
            </textarea>
            <div id="rrweb-player-container"></div>
        </div>
        <div class="d-flex flex-column ps-2 pe-2 overflow-hidden">
           
            <div class="d-flex flex-column overflow-x-scroll">
                <?php foreach ($session_sequences as $session_sequence) { ?>
                    <a class="btn btn-sm btn-primary mb-2" style="position: relative;" href='/<?= APP_LANG ?>/SessionsReplays/player?ank=%7B"id_visiteur_session"%3A<?php $ak->ecrit($session_sequence->id_visiteur_session); ?>%7D' target='_self'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                        <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393" />
                                    </svg> <?php $ak->ecrit($session_sequence->sequence_index); ?>
                    
                    <!--
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill ms-4" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                    </svg> -->
                        <?php /*
                        $session_short_code = "";
                        if ($session_sequence->visiteur_session_uuid != null && $session_sequence->visiteur_session_uuid != "") {
                            $session_short_code = explode('-', $session_sequence->visiteur_session_uuid)[2];
                        }
                        $ak->ecrit($session_short_code); */
                        ?>
                       
                        <?php /*
                        $url_short = $session_sequence->url;
                        $pos_url_domain_start = strpos($url_short, '://');
                        if ($pos_url_domain_start !== false) {
                            $url_short = substr($url_short, $pos_url_domain_start + 3);
                        }
                        $pos_url_params_start = strpos($url_short, '?');
                        if ($pos_url_params_start !== false) {
                            $url_short = substr($url_short, 0, $pos_url_params_start);
                        }
                        $pos_url_domain_ends = strpos($url_short, '/');
                        if ($pos_url_domain_ends !== false) {
                            $url_short = substr($url_short, $pos_url_domain_ends + 1);
                        }
                        $ak->ecrit("/" . $url_short);
                        */
                        ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="mb-3 d-flex justify-content-center p-2">

    </div>
</div>

<link rel="stylesheet" href="<?= $ak->asset("ressources/js/rrweb/rrweb-player.min.css"); ?>" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rrwebSessionEventsJSONEncoded = document.getElementById("rrweb_session_events_json_encoded_textarea").value;
        console.log("sessions_length:", rrwebSessionEventsJSONEncoded.length);

        const scriptTag = document.createElement('script');
        scriptTag.src = '<?= $ak->asset("ressources/js/rrweb/rrweb-player.min.js") ?>';
        //console.log(scriptTag.src);
        scriptTag.async = true;

        scriptTag.onload = () => {
            console.log("script player chargé");

            /*
            Le replayer inclu de base dans rrweb n'a pas de barre de contrôle
            donc on utilise le module rrwebPlayer plus bas pour ne pas avoir a recoder un UI
            new rrweb.Replayer({
                target: document.getElementById('rrweb-player-container'),
                data: {
                    events: JSON.parse(rrwebSessionEventsJSONEncoded),
                    autoPlay: false,
                },
            });
            */

            /* le player a besoin de connaitre la taille de la fenetre, on va intérroger le contneur flex
             car il a un flex-grow dessus donc c'est un bonne reference */
            let playerFlexParent = document.getElementById("rrweb-player-container-flex-parent");
            let props = {
                events: JSON.parse(rrwebSessionEventsJSONEncoded),
                width: playerFlexParent.offsetWidth,
                height: playerFlexParent.offsetHeight - 80 /* 80px est la taille codé en dur des controls du player */
            };
            new rrwebPlayer({
                target: document.getElementById('rrweb-player-container'), // customizable root element
                props: props,
            });

            /*const replayer = new rrweb.Replayer(JSON.parse(rrwebSessionEventsJSONEncoded));
            replayer.play();*/
        };

        scriptTag.onerror = () => {
            console.error("Erreur lors du chargement du script rrwebPlayer.");
        };

        // Ajouter le script au document
        document.head.appendChild(scriptTag);

    });
</script>