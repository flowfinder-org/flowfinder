<?php include APP_ROOT . DS . 'Views' . DS . 'pages' . DS . 'menu_gauche.php'; ?>
<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-adminpage.css") ?>">

<style>

</style>
<div class="d-flex flex-grow-1 flex-column">
    <!-- menu filtrage et légendes des couleurs -->
    <div class="ps-2 pe-2 d-flex flex-column fixed-top" style="margin-top: 4rem; padding-bottom: 0.5rem; padding-top: 0.5rem; background-color: white; z-index: 1020">
        <!-- filtrage -->
        <div class="d-flex flex-row">
            <div style="display: inline-block; margin-left:12px;">
                Filtre campagne : 
                <div class="d-inline-block"><select class="form-select" id="filtre_utm_campaign_select">
            <?php
                $idx_utm_campaign = 0; // juste pour afficher un numero devant la pub pour que l'utilisateur se souvienne facilement de la pub qu'il est en train d'analyser
                foreach($utm_campaigns as $utm_campaign => $value){ 
                    $idx_utm_campaign++;
                    $selected_text = " ";
                    if($utm_campaign == $filtre_utm_campaign)
                    {
                        $selected_text = " selected='true' ";
                    }
                ?>

                    <option value="<?php $ak->ecrit($utm_campaign); ?>" <?php $ak->ecrit($selected_text); ?>><?php $ak->ecrit($idx_utm_campaign); ?> - <?php $ak->ecrit($utm_campaign); ?></option>
            <?php } ?>
                </select></div>
            </div>

            <div style="display: inline-block; margin-left:12px;">
                Du : 
                <div class="d-inline-block">
                    <input type="date" class="form-control" id="filtre_date_start" value="<?php $ak->ecrit($filtre_date_start); ?>" >
                </div>
            </div>

            <div style="display: inline-block; margin-left:12px;">
                au : 
                <div class="d-inline-block">
                    <input type="date" class="form-control" id="filtre_date_end" value="<?php $ak->ecrit($filtre_date_end); ?>" >
                </div>
            </div>


            <div style="display: flex; align-items: center; margin-left:12px;">
                <button class="btn btn-inv btn-primary btn-sm" onclick="filtre_changed()"> Go </button>
            </div>
        </div>

    </div>

    <!-- graphiques -->
    <div class="ps-2 p-2 d-flex flex-row" style="margin-top: 4.8rem;">
        <div>
            <table style="font-size: 12px;" class="table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Date</th>
                        <th>Durée (secondes)</th>
                        <th>Id visiteur</th>
                        <th>Session</th>
                        <th>Sequence</th>
                        <th>Url</th>
                        <th>IP</th>
                        <th>Pays</th>
                        <th>Région</th>
                        <th>Ville</th>
                        <th>Sytème</th>
                        <th>Appareil</th>
                        <th>Navigateur</th>
                        <th>UTM Source</th>
                        <th>UTM Campaign</th>
                        <th>UTM Content</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $counter_verrouillage = 0;
                        foreach ($visiteurs_session as $visiteur_session) {
                            $counter_verrouillage++;
                     ?>
                        <tr>
                            <td>
                    <?php 
                                if ($statut_paiement > 0 || $counter_verrouillage <= 9)
                                {
                                    // pour les sessions courtes ou si l'utilisateur a payé on permet de voir les replays
                    ?>    
                                <a href='/<?= APP_LANG ?>/SessionsReplays/player?ank=%7B"id_visiteur_session"%3A<?php $ak->ecrit($visiteur_session->id_visiteur_session); ?>%7D' target='_blank' class="play-bouton">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                        <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393" />
                                    </svg>
                                </a>
                    <?php   
                                }
                                else
                                {
                    ?>
                                <div style="position: relative;">
                                    <a href='/<?= APP_LANG ?>/choixFormule' target='_blank' class='verrou_position_2'>&#128274</a>
                                    <a href='/<?= APP_LANG ?>/choixFormule' target='_blank' class="play-bouton">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393" />
                                        </svg>
                                    </a>   
                                </div>
                    <?php
                                }            
                    ?>
                            </td>
                            <td>
                                <?php $ak->ecritDateUTCVersTimezoneAffichage($visiteur_session->date_creation); ?>
                            </td>
                            <td><?php $ak->ecrit($visiteur_session->seconds_active); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->id_visiteur); ?></td>
                            <td>
                                <?php
                                $session_short_code = "";
                                if ($visiteur_session->visiteur_session_uuid != null && $visiteur_session->visiteur_session_uuid != "") {
                                    $session_short_code = explode('-', $visiteur_session->visiteur_session_uuid)[2];
                                }
                                $ak->ecrit($session_short_code);
                                ?>
                            </td>
                            <td><?php $ak->ecrit($visiteur_session->sequence_index); ?></td>
                            <td>
                                <?php

                                $url_short = $visiteur_session->url;
                                if ($url_short != null) {
                                    $pos_url_params_start = strpos($url_short, '?');
                                    if ($pos_url_params_start !== false) {
                                        $url_short = substr($url_short, 0, $pos_url_params_start);
                                    }
                                } else {
                                    $url_short = "";
                                }

                                $ak->ecrit($url_short);
                                ?>
                            </td>
                            <td><?php $ak->ecrit(substr($visiteur_session->user_ip, 0, 9) . "..."); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->geo_country != null ? $visiteur_session->geo_country : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->geo_region != null ? $visiteur_session->geo_region : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->geo_city != null ? $visiteur_session->geo_city : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->os_family != null ? $visiteur_session->os_family : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->device_type != null ? $visiteur_session->device_type : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->browser_family != null ? $visiteur_session->browser_family : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->utm_source != null ? $visiteur_session->utm_source : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->utm_campaign != null ? $visiteur_session->utm_campaign : ""); ?></td>
                            <td><?php $ak->ecrit($visiteur_session->utm_content != null ? $visiteur_session->utm_content : ""); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="background-creative mb-4 pb-5 d-flex justify-content-center p-2 text-white">
        Page : 1 / 1
    </div>
</div>


<script>
    function filtre_changed()
    {
        var filtreUtmCampaign = document.getElementById("filtre_utm_campaign_select").value;
        var filtreDateStart = document.getElementById("filtre_date_start").value;
        var filtreDateEnd = document.getElementById("filtre_date_end").value;
        var baseUrl = document.location.origin + document.location.pathname;
        var url = baseUrl + '?ank={"filtre_utm_campaign":"'+ filtreUtmCampaign +'","filtre_date_start":"'+ filtreDateStart +'","filtre_date_end":"'+ filtreDateEnd +'"}';
        document.location.href = url;

    }
</script>