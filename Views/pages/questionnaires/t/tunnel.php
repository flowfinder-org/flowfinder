<div class="col">
    <div class="card m-4 rounded-3">
        <div class="card-header py-3">
            <h4 class="my-0 fw-normal text-center">Quel tunnel d'acquisition voulez-vous créer ?</h4>
        </div>
        <div class="card-body">
            <form id="questionnaireForm">
                <div class="step" id="step1">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="prospects" id="sendvalue-v1" checked>
                            <label class="form-check-label" for="sendvalue-v1">
                                de prospects
                            </label>
                            <label class="small mb-3"><i>(Attirer des leads qualifiés et obtenir leurs coordonnées)</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="avis" id="sendvalue-v2">
                            <label class="form-check-label" for="sendvalue-v2">
                                d'avis
                            </label>
                            <label class="small mb-3"><i>(Collecter des témoignages clients pour renforcer la preuve sociale)</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="fidelisation" id="sendvalue-v3">
                            <label class="form-check-label" for="sendvalue-v3">
                                de fidélisation
                            </label>
                            <label class="small mb-3"><i>(Convertir les clients existants en clients récurrents ou ambassadeurs de la marque)</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="lancement" id="sendvalue-v4">
                            <label class="form-check-label" for="sendvalue-v4">
                                de lancement
                            </label>
                            <label class="small mb-3"><i>(Lancer un nouveau produit ou service.)</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="webinaire" id="sendvalue-v5">
                            <label class="form-check-label" for="sendvalue-v5">
                                de webinaire
                            </label>
                            <label class="small mb-3"><i>(Engager les prospects via un webinaire pour les convertir en clients)</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="consultation" id="sendvalue-v6">
                            <label class="form-check-label" for="sendvalue-v6">
                                de consultation
                            </label>
                            <label class="small mb-3"><i>(Convertir des prospects en clients via des sessions de consultation gratuites ou payantes.)</i></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typetunnel" value="educatif" id="sendvalue-v7">
                            <label class="form-check-label" for="sendvalue-v7">
                                de contenu éducatif
                            </label>
                            <label class="small mb-3"><i>(Attirer et éduquer les prospects pour les convertir en clients informés.)</i></label>
                        </div>
                    </div>

                    <button class="btn btn-primary float-end nextBtn">Suivant</button>
                </div>

                <div class="step d-none" id="step2">
                    <div class="mb-3">
                    <div class="mb-3" id="sendvalue-option-v1">
                        <label for="sendvalue-v8" class="form-label">Quel type de prospects ciblez-vous spécifiquement ?</label>
                        <label class="small mb-3"><i>(par exemple : particuliers, TPE, PME, freelances, etc.)</i></label>
                        <textarea id="sendvalue-v8" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3" id="sendvalue-option-v2">
                        <label for="sendvalue-v9" class="form-label">Comment souhaitez-vous utiliser les témoignages pour renforcer votre crédibilité ?</label>
                        <label class="small mb-3"><i>Montrer ces témoignages sur mon site web ? Les partager dans mes campagnes de marketing ? Les utiliser pour améliorer la perception de ma marque ? </i></label>
                        <textarea id="sendvalue-v9" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3" id="sendvalue-option-v3">
                        <label for="sendvalue-v10" class="form-label">Quels avantages ou offres souhaitez-vous proposer pour fidéliser vos clients existants ?</label>
                        <label class="small mb-3"><i>Que voulez-vous offrir à vos clients pour les encourager à rester ou à devenir ambassadeurs (réductions, contenu exclusif, programmes de parrainage).</i></label>
                        <textarea id="sendvalue-v10" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3" id="sendvalue-option-v4">
                        <label for="sendvalue-v11" class="form-label">Quel est l'élément le plus attractif de votre nouveau produit ou service que vous souhaitez mettre en avant ?</label>
                        <label class="small mb-3"><i>(innovation, prix compétitif, caractéristiques uniques).</i></label>
                        <textarea id="sendvalue-v11" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3" id="sendvalue-option-v5">
                        <label for="sendvalue-v12" class="form-label">Quel type de contenu ou d'expertise allez-vous partager durant le webinaire ?</label>
                        <label class="small mb-3"><i>(conseils pratiques, démonstrations, études de cas).</i></label>
                        <textarea id="sendvalue-v12" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3" id="sendvalue-option-v6">
                        <label for="sendvalue-v13" class="form-label">Quelles sont les principales problématiques que vous allez résoudre lors de ces consultations ?</label>
                        <textarea id="sendvalue-v13" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3" id="sendvalue-option-v7">
                        <label for="sendvalue-v14" class="form-label">Quel sujet ou domaine souhaitez-vous éduquer vos prospects sur ?</label>
                        <label class="small mb-3"><i>La thématique (par exemple : marketing digital, développement personnel, gestion d'entreprise).</i></label>
                        <textarea id="sendvalue-v14" class="form-control" rows="3"></textarea>
                    </div>
                        
                    </div>
                    <button class="btn btn-primary float-start prevBtn">Retour</button>
                    <div class="text-center">
                        <button class="btn btn-primary float-end sendBtn" type="submit" id="envoyer_reponses">Créer mon tunnel</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

