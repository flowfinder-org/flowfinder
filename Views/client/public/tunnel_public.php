<?php if (!$integration_web) { ?>
    <link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-pagepublic.css") ?>">
<?php } ?>

<?php // le champ flowfinder_site_utilisateur_id est utilisé par statistics.js 
?>
<?php if (!$integration_web) { ?>
    <input type="hidden" id="" value="<?php $ak->ecrit($id_utilisateur); ?>" />

    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="flowfinder-btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Les éléments ont été transmis avec succès.
                </div>
                <div class="modal-footer">
                    <button type="button" class="flowfinder-btn flowfinder-btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
// Décoder la chaîne JSON dans json_configuration_page
$config = json_decode($json_configuration_page->json_configuration_page);
// Afficher le nom de la société

$nom_societe = isset($config->nomsociete) ? $config->nomsociete->InputText : "";
// Afficher la description de la société
$description_societe = isset($config->descriptionsociete) ? $config->descriptionsociete->InputTextarea : "";
// Créer un tableau associatif des éléments pour un accès plus rapide
$elements_map = [];
foreach ($json_elements as $element) {
    // Décoder la chaîne JSON dans donnee_json_element
    $elements_map[$element->id_element] = json_decode($element->donnee_json_element);
}


?>

<section id="themepage-tunnel-<?= $numero_tunnel ?>">
    <input type="hidden" id="id_utilisateur_proprietaire_tunnel" value="<?= $id_utilisateur_proprietaire_tunnel ?>" />
    <div class="flowfinder-pagepublic" id="flowfinder-content-page">

        <?php
        if (isset($config->{"Visibilite-logo"}) && $config->{"Visibilite-logo"}->Switch == 1) {

        ?>
            <div class="flowfinder-container flowfinder-d-flex flowfinder-justify-content-center flowfinder-mt-2 flowfinder-mb-2">
                <div id="content-Visibilite-logo" class="flowfinder-rounded-circle flowfinder-bg-circle flowfinder-d-flex flowfinder-justify-content-center">
                    <?php if ($photo_disponible) { ?>
                        <img id="content-logo-fichier" src="<?php $ak->ecrit($photo_url); ?>" />
                    <?php } else { ?>
                        <img id="content-logo-fichier" src="/ressources/img/logo_default.png" alt="logo">
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>
        <?php if (!$integration_web) { ?>
            <div class="flowfinder-container">
                <h3 class="flowfinder-content-nomsociete flowfinder-text-center" id="flowfinder-content-nomsociete"><?= $nom_societe ?></h3>
                <p class="flowfinder-text-center" id="flowfinder-content-descriptionsociete"><?= $description_societe ?></p>
            </div>
        <?php } ?>
        <div class="flowfinder-container" id="flowfinder-sortable-elements">
            <form class="flowfinder-formulaire-event flowfinder-reset-css">
                <?php
                $seppage = 0;
                $findepage = false;
                foreach ($config->OrdreElements as $index => $id_element) {
                    if (isset($elements_map[$id_element])) {
                        $element = $elements_map[$id_element];
                        if ($element->Active->Switch == 1) {
                            $elements[$id_element] = $element;

                            if ($element->Choix->Select == 'findepage'){
                                $findepage = true;
                            }
                            if ($element->Choix->Select == 'seppage' && $findepage == false) {
                                $seppage++;
                            }
                        }
                    }
                }
                echo $ak->genererFormulaire($elements, $seppage);
                ?>
            </form>
        </div>
    </div>
</section>

<?php if (!$integration_web) { ?>

    <script src="<?= $ak->asset("ressources/js/flowfinder_element_theme.js") ?>"></script>
    <script src="<?= $ak->asset("ressources/js/public_page.js") ?>"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialiser le gestionnaire de thème
            this.themeManager = new ThemeManager("tunnel");
            this.themeManager.loadTheme('perso', <?php $ak->ecrit($id_utilisateur_proprietaire_tunnel); ?>);
            initialize_flowfinder_step();

        });
    </script>
<?php } ?>