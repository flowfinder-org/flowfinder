<div class="col">
    <div class="card m-4 rounded-3">
        <div class="card-header py-3">
            <h4 class="my-0 fw-normal text-center"></i>Ajout de déclencheur</h4>
        </div>
        <div class="card-body">
            <form id="nouveau_declencheur_form">
                
                <div class="row mb-3 ">
                    <div class="col-md-6">
                        <label for="type_page">Type d'élement à injecter :</label>
                        <select class="form-control" name="type_page">
                            <option value="1">Portail</option>
                            <option value="2">Tunnel</option>
                            <option value="3">Sondage</option>
                            <option value="4">Feedback</option>
                            <option value="5">Formulaire</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_page_configuration">Numero de l'élement :</label>
                        <select class="form-control" name="id_page_configuration">
                    <?php for($i = 1; $i < 20 ; $i++){ ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                    <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="seconds_delay">Delai avant affichage en secondes :</label>
                    <select class="form-control width-auto" name="seconds_delay">
                    <option value="-1">désactivé</option>
                    <option value="0">immédiat</option>
                    <?php for($i = 1; $i < 21 ; $i++){ ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                    <?php } ?>
                        </select>
                </div>

                
                <div class="mb-3">
                    <label for="url_regep">Affichage aux URL qui correspondent à la regexp :</label>
                    <input type="text" class="form-control" name="url_regexp" placeholder="ex: .*/produit/.* pour les page contenant /produit/">
                    <i style="font-size:0.7rem;">Laisser vide pour déclencher sur toutes les pages</i>
                </div>
                
                <div class="mb-3">
                    <label for="inject_into_elem_id">Identififiant containeur HTML (facultatif Id de la div) :</label>
                    <input type="text" class="form-control" name="inject_into_elem_id" placeholder="id de la DIV de destination">
                    <i style="font-size:0.7rem;">Laisser vide pour laisser FlowFinder trouver le meilleur emplacement automatiquement</i>
                </div>

                <div class="mb-3 mt-3 text-end" >
                    <button type="submit" class="btn btn-primary" onclick="enregistrePopupAjouteDeclencheur(); return false;">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>