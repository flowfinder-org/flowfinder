<div class="col">
    <div class="card m-4 rounded-3">
        <div class="card-header py-3">
            <h4 class="my-0 fw-normal text-center"></i>Modifie le déclencheur</h4>
        </div>
        <div class="card-body">
            <form id="modifie_declencheur_form">
                <input type="hidden" value="<?php  $ak->ecrit($declencheur->id_declencheur); ?>" name="id_declencheur">
                <div class="row mb-3 ">
                    <div class="col-md-6">
                        <label for="type_page">Type d'élement à injecter :</label>
                        <select class="form-control" name="type_page">
                        <?php 
                            $selected_txt_portail = $declencheur->type_page == 1 ? "selected" : "";
                            $selected_txt_tunnel = $declencheur->type_page == 2 ? "selected" : "";
                            $selected_txt_sondage = $declencheur->type_page == 3 ? "selected" : "";
                            $selected_txt_feedback = $declencheur->type_page == 4 ? "selected" : "";
                            $selected_txt_formulaire = $declencheur->type_page == 5 ? "selected" : "";
                        ?>
                            <option value="1" <?= $selected_txt_portail ?> >Portail</option>
                            <option value="2" <?= $selected_txt_tunnel ?> >Tunnel</option>
                            <option value="3" <?= $selected_txt_sondage ?> >Sondage</option>
                            <option value="4" <?= $selected_txt_feedback ?> >Feedback</option>
                            <option value="5" <?= $selected_txt_formulaire ?> >Formulaire</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_page_configuration">Numero de l'élement :</label>
                        <select class="form-control" name="id_page_configuration">
                    <?php for($i = 1; $i < 20 ; $i++){ 
                        $selected_txt = "";
                        if($declencheur->id_page_configuration == $i){ $selected_txt = "selected"; }    
                    ?>
                            <option value="<?= $i ?>" <?= $selected_txt ?> ><?= $i ?></option>
                    <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="seconds_delay">Delai avant affichage en secondes :</label>
                    <select class="form-control width-auto" name="seconds_delay">
                    <option value="-1" <?php $declencheur->seconds_delay == -1  ? " selected " : "" ?> >désactivé</option>
                    <option value="0" <?php $declencheur->seconds_delay == 0  ? " selected " : "" ?> >immédiat</option>
                    <?php for($i = 1; $i < 20 ; $i++){ 
                        $selected_txt = "";
                        if($declencheur->seconds_delay == $i){ $selected_txt = "selected"; }
                    ?>
                            <option value="<?= $i ?>" <?= $selected_txt ?> ><?= $i ?></option>
                    <?php } ?>
                        </select>
                </div>

                
                <div class="mb-3">
                    <label for="url_regep">Affichage aux URL qui correspondent à la regexp :</label>
                    <input type="text" class="form-control" name="url_regexp" placeholder="ex: .*/produit/.* pour les page contenant /produit/" value="<?php  $ak->ecrit($declencheur->url_regexp); ?>">
                    <i style="font-size:0.7rem;">Laisser vide pour déclencher sur toutes les pages</i>
                </div>
                
                <div class="mb-3">
                    <label for="inject_into_elem_id">Identififiant containeur HTML (facultatif) :</label>
                    <input type="text" class="form-control" name="inject_into_elem_id" placeholder="id de la DIV de destination" value="<?php  $ak->ecrit($declencheur->inject_into_elem_id); ?>">
                    <i style="font-size:0.7rem;">Laisser vide pour laisser FlowFinder trouver le meilleur emplacement automatiquement</i>
                </div>

                <div class="mb-3 mt-3 text-end" >
                    <button type="submit" class="btn btn-primary" onclick="enregistrePopupModifieDeclencheur(); return false;">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>