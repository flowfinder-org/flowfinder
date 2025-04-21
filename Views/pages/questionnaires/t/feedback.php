<div class="col">
    <div class="card m-4 rounded-3">
        <div class="card-header py-3">
            <h4 class="my-0 fw-normal text-center">Quel feedback voulez-vous créer ?</h4>
        </div>
        <div class="card-body">
            <form id="questionnaireForm">
                <div class="step" id="step1">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="instantanee" id="sendvalue-v1" checked>
                            <label class="form-check-label w-100" for="sendvalue-v1">Impression instantanée
                            </label>
                            <label class="small mb-3"><i>Quelle est votre impression générale du site.</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="evaluation" id="sendvalue-v2">
                            <label class="form-check-label w-100" for="sendvalue-v2">Évaluation spécifique
                            </label>
                            <label class="small mb-3"><i>Évaluez [nom de la fonctionnalité spécifique] sur une échelle de 1 à 5.</i></label>
                        </div>  
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="utile" id="sendvalue-v3">
                            <label class="form-check-label w-100" for="sendvalue-v3">
                            Temps passé sur le site
                            </label>
                            <label class="small mb-3"><i>Avez-vous trouvé ce que vous cherchiez ? Oui ou non</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="personnalise" id="sendvalue-v4">
                            <label class="form-check-label w-100" for="sendvalue-v4">
                            Créez votre feedback personnalisé
                            </label>
                            <label class="small mb-3"><i>Concevez facilement un feedback adapté à vos besoins</i></label>
                        </div>
                    </div>

                    <button class="btn btn-primary float-end nextBtn">Suivant</button>
                </div>

                <div class="step d-none" id="step2">
                    <div class="mb-3">
                    <div class="form-check">
                            <input class="form-check-input" type="radio" name="design" value="popup" id="sendvalue-v5">
                            <label class="form-check-label w-100" for="sendvalue-v5">
                            Pop-up
                            </label>
                            <label class="small"><i> Posez des questions à des moments clés.</i></label>
                            <label class="small mb-3">Interagissez avec vos utilisateurs en posant des questions au bon moment dans leur parcours.</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="design" value="bouton" id="sendvalue-v6" checked>
                            <label class="form-check-label w-100" for="sendvalue-v6">
                            Bouton
                            </label>
                            <label class="small"><i>Encouragez les retours grâce à un bouton accrocheur.</i></label>
                            <label class="small mb-3">Attirez l’attention des utilisateurs avec un bouton qui incite à répondre à votre sondage.</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="design" value="integre" id="sendvalue-v7">
                            <label class="form-check-label w-100" for="sendvalue-v7">
                            Intégré
                            </label>
                            <label class="small"><i>Collectez des réponses contextuelles directement sur votre page.</i></label>
                            <label class="small mb-3">Intégrez le sondage dans votre contenu pour recueillir des réponses en contexte.</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="design" value="pleinecran" id="sendvalue-v8">
                            <label class="form-check-label w-100" for="sendvalue-v8">
                            Plein écran
                            </label>
                            <label class="small"><i>Plongez vos utilisateurs dans un sondage immersif.</i></label>
                            <label class="small mb-3">Offrez une expérience de sondage en plein écran, sans quitter votre site.</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="design" value="lien" id="sendvalue-v9">
                            <label class="form-check-label w-100" for="sendvalue-v9">
                            Lien
                            </label>
                            <label class="small"><i>Partagez facilement votre sondage avec un lien.</i></label>
                            <label class="small mb-3">Obtenez un lien unique à partager via le canal de votre choix.</label>
                        </div>
                    </div>
                    <button class="btn btn-primary float-start prevBtn">Retour</button>
                    <div class="text-center">
                        <button class="btn btn-primary float-end sendBtn" type="submit" id="envoyer_reponses">Créez mon feedback</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>