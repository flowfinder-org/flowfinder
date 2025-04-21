<?php include APP_ROOT . DS . 'Views' . DS . 'pages' . DS . 'menu_gauche.php'; ?>
<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-adminpage.css") ?>">
<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-statistiques.css") ?>">

<script src="<?= $ak->asset("ressources/js/chartjs/chart.js") ?>"></script> 
<script src="<?= $ak->asset("ressources/js/chartjs/chartjs-adapter-date-fns.bundle.min.js") ?>"></script> 
<script src="<?= $ak->asset("ressources/js/chartjs/chart-utils.min.js") ?>"></script> 

<?php

// pour le graphique de type sunburst voir:
// https://observablehq.com/@kerryrodden/sequences-sunburst

?>

<div class="d-flex flex-grow-1 flex-column">
    <!-- menu filtrage et légendes des couleurs -->
    <div class="ps-2 pe-2 d-flex flex-column fixed-top" style="margin-top: 4rem; padding-bottom: 0.5rem; padding-top: 0.5rem; background-color: white; z-index: 1020">
        <!-- filtrage -->
        <div class="d-flex flex-row">
            <div style="display: inline-block">
                Type de vue : 
                <div class="d-inline-block"><select class="form-select" id="type_vue_select">
                <?php
                $idx_pub = 0; // juste pour afficher un numero devant la pub pour que l'utilisateur se souvienne facilement de la pub qu'il est en train d'analyser
                foreach(["horaire", "top_en_haut"] as $type_vue_possible){ 
                    $selected_text = " ";
                    if($type_vue == $type_vue_possible)
                    {
                        $selected_text = " selected='true' ";
                    }
                ?>
                    <option value="<?php $ak->ecrit($type_vue_possible); ?>" <?php $ak->ecrit($selected_text); ?>><?php $ak->ecrit($type_vue_possible); ?></option>
                <?php } ?>
                </select></div>
            </div>
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
        
        <div style="display: none;">
            <input type="hidden" id="count_visites_par_duree_par_jour_json" value="<?php $ak->ecrit($count_visites_par_duree_par_jour_json); ?>"></input>
            <input type="hidden" id="sessions_data" value='<?php  $ak->ecrit($sessions_json); ?>'></input>
            <input type="hidden" id="moyenne_data" value='<?php  $ak->ecrit($moyenne_json); ?>'></input>
        </div>
        <div>
            <div class="text-center" style="width:600px; justify-content: center;">
                <p style="color: rgba(var(--bs-creative-rgb));">Sessions par durée et par jour</p>
            </div>
            <div style="width:600px; height:300px;">
                <canvas id="count_visites_par_duree_par_jour_graph" ></canvas>
            </div>
        </div>

        <div style="margin-left: 60px; border-left: 1px solid #EFEFEF;">
            <!-- Premier graphique: Nombre total de sessions par jour -->
            <div class="chart">
                <div class="text-center" style="width:500px;">
                    <p style="color: rgba(var(--bs-creative-rgb));">Nombre total de sessions: <span id="total_sessions_count" style="font-size:2rem;">0</span></p>
                </div>
                <div class="text-center d-flex justify-content-center" style="width: 500px; justify-content: center;">
                    <div class="text-center" style="width:400px; height: 100px;">
                        <canvas id="sessions_per_day_chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Deuxième graphique: Temps moyen passé par session -->
            <div class="chart mt-2" style="border-top: 1px solid #EFEFEF">
                <div class="text-center" style="width:500px;">
                    <p style="color: rgba(var(--bs-creative-rgb));">Temps moyen par session <span id="avg_time_per_session" style="font-size:2rem;">0</span> secondes</p>
                </div>
                <div class="text-center d-flex justify-content-center" style="width: 500px;">
                    <div class="text-center" style="width:400px; height:100px;">
                        <canvas id="avg_time_per_session_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- légendes des couleurs -->
    <div class="ps-2 pe-2 d-flex pt-4 mt-2 flex-column" style="border-top: 1px solid #EFEFEF;">
        <div class="text-center">
            <p style="color: rgba(var(--bs-creative-rgb));">Détails des sessions</p>
        </div>
        <div class="d-flex flex-wrap justify-content-center align-items-center mt-1 mb-3" >
            <div class="block_prospect me-2">Visite éclair</div>
            <div class="block_prospect block_prospect_visite_courte me-2">Visite courte</div>
            <div class="block_prospect block_prospect_visite_longue me-2">Visite longue</div>
            <div class="block_prospect block_prospect_inscrit me-2">Inscription</div>
            <div class="block_prospect block_prospect_profil me-2">Inscrit</div>
            <div class="block_prospect block_prospect_discussion me-2">Profil</div>
            <div class="block_prospect block_prospect_choix_formule me-2">Panier</div>
            <div class="block_prospect block_prospect_page_paiement me-2">Paiement</div>
            <div class="block_prospect block_prospect_a_paye me-2">A payé</div>
        </div>
    </div>

    <!-- partie centrale--> 
    <div class="d-flex flex-grow-1 ps-2 pe-2">

        <!-- afficage des semaines--> 
        <div class="d-block">
            <?php 

                if(count($tunnel_traceurs_par_semaine) == 0)
                {
                    echo "Aucune statistique disponible. Si vous avez récemment mis en place un tunnel, une landing page ou un portail, assurez-vous d'avoir reçu au moins un visiteur.";
                }
                else
                {
                    $counter_verrouillage = 0;  

                    $numero_semaine_en_cours = -1;
                    $numero_jour_de_semaine_en_cours = -1;
                    $heure_en_cours = -1;
                    $stats = [];

                    foreach ($tunnel_traceurs_par_semaine as $tunnel_traceur) {
                        if($tunnel_traceur->numero_semaine != $numero_semaine_en_cours)
                        {
                            // avant de fermer la semaine il faut peut-être fermer le dernier jour de la semaine precedente
                            if($numero_jour_de_semaine_en_cours > -1)
                            {
                                if($type_vue == "horaire") // avant de fermer le jour on vas fermer la case horaire
                                {
                                    ajoute_cases_horaires_vide_jusqua_24($heure_en_cours);

                                    echo "</div></div>";
                                    $heure_en_cours = -1;
                                }

                                affiche_lignes_stat($stats);
                                echo "</div>"; // on ferme la semaine precedente
                                $numero_jour_de_semaine_en_cours = -1; // on va forcer l'ouverture d'un nouveau jour
                                $counter_verrouillage = 0;
                            }

                            if($numero_semaine_en_cours > -1)
                            {
                                echo "</div>"; // on ferme la semaine precedente
                            }
                            // on ouvre la nouvelle semaine
                            $numero_semaine_en_cours = $tunnel_traceur->numero_semaine;
                            echo "<div class='semaine'><div class='semaine_titre'>Semaine " . $numero_semaine_en_cours."</div>";
                        }

                        if($tunnel_traceur->jour_semaine != $numero_jour_de_semaine_en_cours)
                        {
                            if($numero_jour_de_semaine_en_cours > -1)
                            {
                                if($type_vue == "horaire") // avant de fermer le jour on va fermer la case horaire
                                {
                                    ajoute_cases_horaires_vide_jusqua_24($heure_en_cours);

                                    echo "</div></div>";
                                    $heure_en_cours = -1;
                                }

                                affiche_lignes_stat($stats);
                                echo "</div>"; // on ferme le jour précedent
                                $counter_verrouillage = 0;
                            }
                            // on ouvre le nouveau jour
                            $numero_jour_de_semaine_en_cours = $tunnel_traceur->jour_semaine;
                            $stats = [];

                            $date_texte = $ak->retourneDateUTCVersTimezoneAffichageFormatJourLong($tunnel_traceur->date_creation_local);

                            echo "<div class='jour_semaine jour_semaine_". $numero_jour_de_semaine_en_cours ."'><div class='semaine_titre'>" . $date_texte . "</div>";
                        }

                        if($type_vue == "horaire") // doit-on fermer l'heure et en ouvrir une nouvelle
                        {
                            if($tunnel_traceur->heure_du_jour != $heure_en_cours)
                            {
                                // s'il y a un trou entre l'eure précédente et l'heure actuelle, on va afficher les horaire manquantes en cases vides
                                while($tunnel_traceur->heure_du_jour - $heure_en_cours > 1)
                                {
                                    if($heure_en_cours > -1)
                                    {
                                        echo "</div></div>";  
                                    }
                                    $heure_en_cours = $heure_en_cours + 1;
                                    echo "<div class='heure_du_jour'><div class='heure_titre'>" . $heure_en_cours . ":00</div><div class='ligne_heure_prospects'>";
                                }

                                // maintenant on s'occupe de fermé l'heure précédente celle qu'on va ouvrir
                                if($heure_en_cours > -1)
                                {
                                    echo "</div></div>";
                                }
                                $heure_en_cours = $tunnel_traceur->heure_du_jour;
                                echo "<div class='heure_du_jour'><div class='heure_titre'>" . $heure_en_cours . ":00</div><div class='ligne_heure_prospects'>";
                            }
                        }

                        // maintenant on empile les blocks
                        $class_couleur_avancement = "";
                        
                        $counter_verrouillage++;
                        
                        $block_text = "-";
                        if($tunnel_traceur->utm_campaigns != null && $tunnel_traceur->utm_campaigns != "")
                        {
                            $block_text = $tunnel_traceur->utm_campaigns;
                        }

                        
                        if(!isset($stats[$block_text]))
                        {
                            $stats[$block_text] = ["count" => 0];
                        }
                        $stats[$block_text]["count"] += 1;
                        
                        /*
                        if($tunnel_traceur->a_payer_1_mois == 1)
                        {
                            $class_couleur_avancement = "block_prospect_a_paye";
                        }        
                        else if($tunnel_traceur->affiche_page_paiement == 1)
                        {
                            $class_couleur_avancement = "block_prospect_page_paiement";
                        }
                        else if($tunnel_traceur->affiche_page_choix_formule == 1)
                        {
                            $class_couleur_avancement = "block_prospect_choix_formule";
                        }
                        else if($tunnel_traceur->premiere_discussion == 1)
                        {
                            $class_couleur_avancement = "block_prospect_discussion";
                        }
                        else if($tunnel_traceur->remplissage_profil == 1)
                        {
                            $class_couleur_avancement = "block_prospect_profil";
                        }
                        else if($tunnel_traceur->inscription_email == 1)
                        {
                            $class_couleur_avancement = "block_prospect_inscrit";
                        }
                        else*/ 
                        if($tunnel_traceur->total_seconds_active >= 60) // + de 30 secondes sur la page
                        {
                            $class_couleur_avancement = "block_prospect_visite_longue";
                        }
                        else if($tunnel_traceur->total_seconds_active >= 10) // + de 10 secondes sur la page
                        {
                            $class_couleur_avancement = "block_prospect_visite_courte";    
                        }
                        
                    

                        $info_prospect = "Collection: " . $tunnel_traceur->nom_collection;
                        $info_prospect .= "\nid_visiteur: " . $tunnel_traceur->id_visiteur;
                        $info_prospect .= "\ndate: " . $tunnel_traceur->date_creation_local;
                        $info_prospect .= "\nseconds: " . $tunnel_traceur->total_seconds_active;

                        $overlay_pins = "";
                        /*if($tunnel_traceur->click_relance_1 == 1)
                        {
                            $overlay_pins .= "<div class=\"pins_click_relance_1\">1</div>";
                        }
                        else if($tunnel_traceur->envoie_relance_1 == 1)
                        {
                            $overlay_pins .= "<div class=\"pins_envoie_relance_1\">0</div>";
                        }
                        */
                        if ($statut_paiement > 0 || $counter_verrouillage <= 9)
                        {
                            // pour les sessions courtes ou si l'utilisateur a payé on permet de voir les replays
                            echo "<a style=\"position: relative;\" href='/<?= APP_LANG ?>/SessionsReplays/player?ank=%7B\"id_visiteur_session\"%3A". $tunnel_traceur->id_visiteur_session. "%7D' target='_blank'><div class='block_prospect ". $class_couleur_avancement ."' title='" . $info_prospect . "'>";
                        }
                        else
                        {
                            // session de plus de 30 secondes et utilisateur qui n'a pas payé
                            $overlay_pins = "<div class='verrou_position_1'>&#128274</div>";
                            echo "<a style=\"position: relative;\" href='/<?= APP_LANG ?>/choixFormule' target='_blank'><div class='block_prospect ". $class_couleur_avancement ."' title='" . $info_prospect . "'>";
                        }
                        
                        echo $block_text ;
                        echo "</div>" . $overlay_pins . "</a>";
                        

                        echo " ";        
                
                    } 

                    
                    // on a fini de boucler, on ferme les div
                    if($numero_jour_de_semaine_en_cours > -1)
                    {
                        affiche_lignes_stat($stats);
                        echo "</div>"; // on ferme le dernier jour
                    }

                    // on a fini de boucler, on ferme les div
                    if($numero_semaine_en_cours > -1)
                    {
                        echo "</div>"; // on ferme la derniere semaine
                    }

                }
                function affiche_lignes_stat($stats)
                {
                    /*
                    echo "<div class='stats'>";
                    foreach($stats as $k => $v)
                    {
                        echo "<div class='ligne_stat'>";
                        echo $k . " : " . $v["count"];
                        echo '</div>';
                    }
                    echo "</div>";
                    */
                }

                function ajoute_cases_horaires_vide_jusqua_24($heure_depart)
                {
                    // s'il y a un trou entre l'eure précédente et l'heure actuelle, on va afficher les horaire manquantes en cases vides
                    while($heure_depart < 23)
                    {
                        // fermeture de la case horaire précédente
                        echo "</div></div>";  
                        $heure_depart = $heure_depart + 1;
                        echo "<div class='heure_du_jour'><div class='heure_titre'>" . $heure_depart . ":00</div><div class='ligne_heure_prospects'>";
                        // la fermeture de cette case se fait lors du changement de jour
                    }
                }
            ?>
        </div> <?php /* fin de la partie scrollable qui contient les données*/ ?>

    </div>

</div>

<script>
    function filtre_changed()
    {
        var typeVue = document.getElementById("type_vue_select").value;
        var filtreUtmCampaign = document.getElementById("filtre_utm_campaign_select").value;
        var filtreDateStart = document.getElementById("filtre_date_start").value;
        var filtreDateEnd = document.getElementById("filtre_date_end").value;
        var baseUrl = document.location.origin + "/statistiques";
        var url = baseUrl + '?ank={"type_vue":"' + typeVue + '","filtre_utm_campaign":"'+ filtreUtmCampaign +'","filtre_date_start":"'+ filtreDateStart +'","filtre_date_end":"'+ filtreDateEnd +'"}';
        document.location.href = url;

    }
</script>


<script>const Utils = ChartUtils.init();</script>
<script>
    function createGraphVisiteurCountParDureeParJour(count_visites_par_duree_par_jour_graph_data)
    {
        // Récupérer le contexte du canvas
        var ctx = document.getElementById('count_visites_par_duree_par_jour_graph').getContext('2d');
        const labels = Object.keys(count_visites_par_duree_par_jour_graph_data);
    
        const datasets = [
            {
                label: 'Visite éclair (<10s)',
                backgroundColor: '#000',
                data: labels.map(day => count_visites_par_duree_par_jour_graph_data[day]['y']['eclair'])
            },
            {
                label: 'Visite courte (10-60s)',
                backgroundColor: '#a26648',
                data: labels.map(day => count_visites_par_duree_par_jour_graph_data[day]['y']['courte'])
            },
            {
                label: 'Visite longue (>60s)',
                backgroundColor: '#dc9977',
                data: labels.map(day => count_visites_par_duree_par_jour_graph_data[day]['y']['longue'])
            }
        ];

        const data = {
            labels: labels.map(day => count_visites_par_duree_par_jour_graph_data[day]['x']),
            datasets: datasets
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: false,
                        text: ''
                    }
                },
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: false,
                            text: 'Jour'
                        },
                        type: 'time',
                        time: {
                            // Luxon format string
                            tooltipFormat: 'yyyy-MM-dd HH:mm'
                        },

                    },
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Nombre de session'
                        },
                        beginAtZero: true
                    }
                }
            }
        };

        // Créer le graphique
        var myChart = new Chart(ctx, config);
        return myChart;

    }

    var visiteurs_count_par_jour = JSON.parse(document.getElementById("count_visites_par_duree_par_jour_json").value);
    
    var visiteurs_count_par_jour_graph_data= [];
    for (const [key, value] of Object.entries(visiteurs_count_par_jour)) {
        visiteurs_count_par_jour_graph_data.push({
            x: key,
            y: value
        });
    }
    var graph = createGraphVisiteurCountParDureeParJour(visiteurs_count_par_jour_graph_data);
    


    function createGraphSessionsPerDay(sessionsData) {
        var ctx = document.getElementById('sessions_per_day_chart').getContext('2d');

        // Calcul du nombre total de sessions
        var totalSessions = sessionsData.reduce(function(acc, curr) {
            return acc + curr;
        }, 0);

        // Affichage du nombre total de sessions
        document.getElementById('total_sessions_count').innerText = totalSessions;

        const data = {
            labels: sessionsData.map(item => 0), // Labels: dates
            datasets: [{
                label: 'Sessions par jour',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                data: sessionsData // Données: nombre de sessions
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    title: {
                        display: false // Pas de titre pour ce graphique
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: false,
                            text: 'Date'
                        },
                        display: false
                    },
                    y: {
                        title: {
                            display: false,
                            text: 'Nombre de Sessions'
                        },
                        display: false
                    }
                }
            }
        };

        return new Chart(ctx, config);
    }

    function createGraphAvgTimePerSession(avgTimeData) {
        var ctx = document.getElementById('avg_time_per_session_chart').getContext('2d');

        // Calcul du temps moyen par session
        var avgTime = avgTimeData.reduce(function(acc, curr) {
            return acc + curr;
        }, 0) / avgTimeData.length;

        // Affichage du temps moyen
        document.getElementById('avg_time_per_session').innerText = avgTime.toFixed(0);

        const data = {
            labels: avgTimeData.map(item => 0), // Labels: dates
            datasets: [{
                label: 'Temps moyen par session',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                data: avgTimeData // Données: temps moyen par session
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    title: {
                        display: false // Pas de titre pour ce graphique
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        display: false
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Temps moyen (sec)'
                        },
                        display: false
                    }
                }
            }
        };

        return new Chart(ctx, config);
    }

    // Exemples de données (vous devrez adapter cela en fonction de vos données réelles)
    // Récupérer les données JSON passées par PHP
    var sessions_data = JSON.parse(document.getElementById("sessions_data").value);
    var moyenne_data = JSON.parse(document.getElementById("moyenne_data").value);

    // Création des graphiques
    createGraphSessionsPerDay(sessions_data.map(item => item.y));
    createGraphAvgTimePerSession(moyenne_data.map(item => item.y));
</script>