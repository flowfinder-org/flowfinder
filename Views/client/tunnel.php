<?php include APP_ROOT . DS . 'Views' . DS . 'pages' . DS . 'menu_gauche.php'; ?>

<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-pagepublic.css") ?>">
<link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-adminpage.css") ?>">

<div id="templateItem" class="d-none">
  <div class="row">
    <div class="col-custom-4 d-flex align-items-center justify-content-center">
      <div class="flowfinder-SortableElement-header">
        <svg class="drag-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
          <circle cx="8" cy="4" r="1.5" />
          <circle cx="8" cy="8" r="1.5" />
          <circle cx="8" cy="12" r="1.5" />
        </svg>
      </div>
    </div>

    <div class="col-custom-96">
      <div class="rounded-circle bg-creative text-white d-flex justify-content-center align-items-center flowfinder-ID" style="position:absolute; left: 10px; width: 20px; height: 20px; font-size: .6rem; font-weight: bold;">

      </div>
      <div class="row">
        <div class="col-12 flowfinder-SortableElement-modif">
          <div class="flex-container">
            <div class="flowfinder-SortableElement-Width flowfinder-topbar-SortableElement">
              <input type="text" class="flowfinder-SortableElement-InputText-Title flowfinder-SortableElement-Edit dynamic-input" value="Indiquez ici le titre ou la question que vous souhaitez poser." readonly />
              <svg class="edit-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z" />
              </svg>
            </div>

            <div class="form-check form-switch">
              <input class="flowfinder-SortableElement-InputSwitch-Active form-check-input" type="checkbox" role="switch" checked="">
            </div>
          </div>
        </div>
        <div class="item-type-form">
          <div class="col-12 mt-1 flowfinder-SortableElement-form-choix">
            <select class="flowfinder-SortableElement-InputSelect-Choix form-select" aria-label="choix select">
              <option value="texte" selected>Champ Texte</option>
              <option value="note">Note</option>
              <option value="radio">Bouton Radio (choix unique)</option>
              <option value="case">Case à Cocher (choix multiple)</option>
              <option value="interrupteur">Bouton On/Off</option>
              <option value="date">Sélecteur de Date</option>
              <option value="couleur" disabled>Sélecteur de Couleur</option>
              <option value="listederoulante">Liste déroulante</option>
              <option value="fichier" disabled>Téléversement de Fichier</option>
              <option value="textarea">Texte Libre</option>
              <option value="captcha" disabled>Captcha</option>
              <option value="seppage">Séparateur de page</option>
              <option value="htmlbloc">Bloc HTML</option>
              <option value="findepage" class="d-none">Fin de page</option>
            </select>
          </div>
          <div class="flowfinder-SortableElement-InputOptions-Choix mt-3">

          </div>
        </div>

        <div class="col-12">
          <div class="flowfinder-SortableElement-info">
            <div class="col">
              <div class="flex-container mt-2">
                <div class="left-content">
                </div>
                <div>
                  <svg class="delete-icon" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="TemplateOption-texte" class="d-none">
  <!-- bien construire [id-template] + nom de mes choix + '-Option-InputRadio' + donnee -->
  <input type="radio" class="btn-check" name="[id-template]-type-Option-InputRadio" id="[id-template]-type-Option-InputRadio-txt" autocomplete="off" checked>
  <label class="btn" for="[id-template]-type-Option-InputRadio-txt">Texte</label>

  <input type="radio" class="btn-check" name="[id-template]-type-Option-InputRadio" id="[id-template]-type-Option-InputRadio-email" autocomplete="off">
  <label class="btn" for="[id-template]-type-Option-InputRadio-email">Email</label>

  <input type="radio" class="btn-check" name="[id-template]-type-Option-InputRadio" id="[id-template]-type-Option-InputRadio-pswd" autocomplete="off" disabled>
  <label class="btn" for="[id-template]-type-Option-InputRadio-pswd">Password</label>

  <input type="radio" class="btn-check" name="[id-template]-type-Option-InputRadio" id="[id-template]-type-Option-InputRadio-url" autocomplete="off">
  <label class="btn" for="[id-template]-type-Option-InputRadio-url">URL</label>

  <input type="radio" class="btn-check" name="[id-template]-type-Option-InputRadio" id="[id-template]-type-Option-InputRadio-tel" autocomplete="off">
  <label class="btn" for="[id-template]-type-Option-InputRadio-tel">Téléphone</label>

</div>

<div id="TemplateOption-note" class="d-none">
  <div class="form-group">
    <label>Maximum de la Note</label>
    <input type="number" class="form-control" name="[id-template]-maxnote-Option-InputNumber" id="[id-template]-maxnote-Option-InputNumber" placeholder="Maximum de la note">
  </div>
</div>

<div id="TemplateOption-radio" class="d-none">
  <div class="form-group">
    <input type="text" class="form-control" name="[id-template]-liste-Option-InputText" id="[id-template]-liste-Option-InputText" placeholder="Options (séparées par ; ajoutez `:checked` pour cocher par défaut)">
    <p><small>Options (séparées par ; ajoutez `:checked` pour cocher par défaut)</small></p>
  </div>
</div>

<div id="TemplateOption-case" class="d-none">
  <div class="form-group">
    <input type="text" class="form-control" name="[id-template]-liste-Option-InputText" id="[id-template]-liste-Option-InputText" placeholder="Options (séparées par ; ajoutez `:checked` pour cocher par défaut)">
    <p><small>Options (séparées par ; ajoutez `:checked` pour cocher par défaut)</small></p>
  </div>
</div>

<div id="TemplateOption-interrupteur" class="d-none">
  <div class="form-group">
    <input type="text" class="form-control" name="[id-template]-liste-Option-InputText" id="[id-template]-liste-Option-InputText" placeholder="Options (séparées par ; ajoutez `:checked` pour cocher par défaut)">
    <p><small>Options (séparées par ; ajoutez `:checked` pour cocher par défaut)</small></p>
  </div>
</div>

<div id="TemplateOption-date" class="d-none">
</div>

<div id="TemplateOption-couleur" class="d-none">
  <div class="form-group">
    <label>Couleur par default</label>
    <input type="color" class="form-control form-control-color" value="#000000" name="[id-template]-CouleurDefault-Option" id="[id-template]-CouleurDefault-Option">
  </div>
</div>

<div id="TemplateOption-listederoulante" class="d-none">
  <div class="form-group">
    <div class="form-check d-inline-block me-3">
      <input type="checkbox" class="form-check-input" name="[id-template]-multiple-Option-InputCheckbox" id="[id-template]-multiple-Option-InputCheckbox" multiple>
      <label class="form-check-label" for="multipleFiles">Selection multiple</label>
    </div>

    <input type="text" class="form-control" name="[id-template]-liste-Option-InputText" id="[id-template]-liste-Option-InputText" placeholder="Options (séparées par ; ajoutez `:selected` pour cocher par défaut)">
    <p><small>Options (séparées par ; ajoutez `:selected` pour cocher par défaut)</small></p>
  </div>
</div>

<div id="TemplateOption-fichier" class="d-none">
  <div class="form-group">
    <label>Accept</label>
    <input type="file" class="form-control" id="fileInput" accept=".jpg, .pdf">
    <div class="form-check d-inline-block me-3">
      <input class="form-check-input" type="checkbox" id="multipleFiles" multiple>
      <label class="form-check-label" for="multipleFiles">Téléverser plusieurs fichiers</label>
    </div>
    <label>Max Size</label>
    <input type="number" class="form-control" id="maxSize" placeholder="Taille maximale en Mo">
  </div>
</div>

<div id="TemplateOption-textarea" class="d-none">
  <div class="form-group">
    <label>Nombre de lignes</label>
    <input type="number" class="form-control" name="[id-template]-nbligne-Option-InputNumber" id="[id-template]-nbligne-Option-InputNumber" placeholder="Nombre de lignes visibles">
    <label>Longueur maximale</label>
    <input type="number" class="form-control" name="[id-template]-nbmaxligne-Option-InputNumber" id="[id-template]-nbmaxligne-Option-InputNumber" placeholder="Longueur maximale du texte">
  </div>
</div>

<div id="TemplateOption-htmlbloc" class="d-none">
  <div class="form-group">
    <textarea name="[id-template]-htmlbloc-Option-InputTextarea" id="[id-template]-htmlbloc-Option-InputTextarea" class="form-control" rows="1" placeholder="Réservé aux experts : modifier le code HTML personnalisé peut entraîner des problèmes d'affichage ou de fonctionnement si vous n'êtes pas sûr de ce que vous faites. Assurez-vous de bien comprendre les balises HTML et leur impact avant d'apporter des modifications."></textarea>
  </div>
</div>

<div id="TemplateOption-captcha" class="d-none">
  <div class="form-group">
    <div class="form-check d-inline-block me-3">
      <input class="form-check-input" type="radio" name="captchaType" value="image" id="captchaImage" checked>
      <label class="form-check-label" for="captchaImage">Image</label>
    </div>
    <div class="form-check d-inline-block me-3">
      <input class="form-check-input" type="radio" name="captchaType" value="text" id="captchaText">
      <label class="form-check-label" for="captchaText">Texte</label>
    </div>
  </div>
</div>

<div id="TemplateOption-seppage" class="d-none">
  <div class="flex-container justify-content-start">
    <input type="text" class="form-control w-50" name="[id-template]-Precedent-Option-InputText" id="[id-template]-Precedent-Option-InputText" placeholder="Bouton retour">

    <div class="form-check form-switch ms-3">
      <input class="Option-InputText-InputSwitch form-check-input" name="[id-template]-Precedent-Option-InputSwitch" id="[id-template]-Precedent-Option-InputSwitch" type="checkbox" role="switch" checked="">
    </div>

  </div>
  <div class="flex-container justify-content-start mt-2">

    <input type="text" class="form-control w-50" name="[id-template]-Suivant-Option-InputText" id="[id-template]-Suivant-Option-InputText" placeholder="Bouton suivant">
    <div class="form-check form-switch ms-3">
      <input class="Option-InputText-InputSwitch form-check-input" name="[id-template]-Suivant-Option-InputSwitch" id="[id-template]-Suivant-Option-InputSwitch" type="checkbox" role="switch" checked="">
    </div>
  </div>
</div>

<div id="TemplateOption-findepage" class="d-none">
  <div class="flex-container justify-content-start">
    <input type="text" class="form-control w-50" name="[id-template]-Precedent-Option-InputText" id="[id-template]-Precedent-Option-InputText" placeholder="Bouton retour">

    <div class="form-check form-switch ms-3">
      <input class="Option-InputText-InputSwitch form-check-input" name="[id-template]-Precedent-Option-InputSwitch" id="[id-template]-Precedent-Option-InputSwitch" type="checkbox" role="switch" checked="">
    </div>

  </div>
  <div class="flex-container justify-content-start mt-2">
    <input type="text" class="form-control w-50" name="[id-template]-Send-Option-InputText" id="[id-template]-Send-Option-InputText" placeholder="Bouton envoyer">

    <div class="form-check form-switch ms-3">
      <input class="Option-InputText-InputSwitch form-check-input" name="[id-template]-Send-Option-InputSwitch" id="[id-template]-Send-Option-InputSwitch" type="checkbox" role="switch" checked="">
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label>Message de validation d’enregistrement :</label>
    <textarea name="[id-template]-htmlbloc-Option-InputTextarea" id="[id-template]-htmlbloc-Option-InputTextarea" class="form-control" rows="4" placeholder="texte afficher une fois l'utilisateur ayant validé le formulaire"></textarea>
  </div>
</div>

<div class="container-fluid flowfinder_styleform">
  <section id="themepage-<?= $typetunnel ?>-<?= $page_numero ?>" class="col-12 col-xl-12 mx-auto section background-creative mt-5 pb-5 w-100">
    <div class="container col-10 col-xl-12">
      <div class="row align-items-xl-top gy-5">
        <div class="col-xl-7 col-12 mb-5 mb-sm-0">

          <div class="row gy-4">
            <div class="col-md-12">
              <div class="bloc_presentation">
                <div class="mb-4">
                  <ul class="nav nav-tabs" id="MenuTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="meschamps-tab" data-bs-toggle="tab" href="#meschamps" role="tab" aria-controls="meschamps" aria-selected="true">Mes éléments</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="config-tab" data-bs-toggle="tab" href="#config" role="tab" aria-controls="config" aria-selected="false">Configuration</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="personnalisation-tab" data-bs-toggle="tab" href="#personnalisation" role="tab" aria-controls="personnalisation" aria-selected="false">Personnalisation</a>
                    </li>
                    <li class="nav-item" role="inscriptions">
                      <a class="nav-link" id="inscriptions-tab" data-bs-toggle="tab" href="#inscriptions" role="tab" aria-controls="inscriptions" aria-selected="false">Inscriptions</a>
                    </li>
                  </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="MenuTabContent">
                  <div class="tab-pane fade show active" id="meschamps" role="tabpanel" aria-labelledby="meschamps-tab">
                    <div class="d-flex justify-content-center">
                      <a class="btn btn-primary mb-2 mt-0 w-100" id="ajouter_element" href="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                          <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                        </svg>
                        Ajouter un élément
                      </a>
                    </div>

                    <div class="sortable-container" id="sortable">

                    </div>
                  </div>
                  <div class="tab-pane fade" id="config" role="tabpanel" aria-labelledby="config-tab">
                    <?php include('type_tunnel/config/config_' . $typetunnel . '.php') ?>
                  </div>

                  <div class="tab-pane fade" id="personnalisation" role="tabpanel" aria-labelledby="personnalisation-tab">
                    <div class="text-end mb-4">
                      <button type="button" class="btn btn-primary" id="savePersonaliseeTheme"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy2-fill" viewBox="0 0 16 16">
                          <path d="M12 2h-2v3h2z"></path>
                          <path d="M1.5 0A1.5 1.5 0 0 0 0 1.5v13A1.5 1.5 0 0 0 1.5 16h13a1.5 1.5 0 0 0 1.5-1.5V2.914a1.5 1.5 0 0 0-.44-1.06L14.147.439A1.5 1.5 0 0 0 13.086 0zM4 6a1 1 0 0 1-1-1V1h10v4a1 1 0 0 1-1 1zM3 9h10a1 1 0 0 1 1 1v5H2v-5a1 1 0 0 1 1-1"></path>
                        </svg> Enregistrer mon thème pour tous mes tunnels</button>
                    </div>

                    <?php include('type_tunnel/persotheme/perso_' . $typetunnel . '.php') ?>

                    <div class="text-center mt-4">
                      <button type="button" class="btn btn-small btn-warning" id="loadDefaultTheme">Réinitialiser tous les paramètres aux valeurs par défaut</button>
                    </div>

                  </div>

                  <div class="tab-pane fade" id="inscriptions" role="tabpanel" aria-labelledby="inscriptions-tab">
                    <div class="d-flex justify-content-end">
                      <a class="btn btn-primary mb-2 mt-0" id="bouton_download_csv" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                          <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                          <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                        </svg>
                        Télécharger en CSV
                      </a>
                    </div>

                    <div class="" id="contenu_inscription">

                    </div>
                  </div>

                  <div class="tab-pane fade" id="statistiques" role="tabpanel" aria-labelledby="inscriptions-tab">
                    <div class="d-flex justify-content-center">
                      <i>L'affichage des statistiques a été déplacé. Pour afficher les statistiques de vos tunnels veuillez cliquer sur le bouton ci-dessous.</i>
                    </div>
                    <div class="d-flex justify-content-center">
                      <a class="btn btn-primary mb-2 mt-0 w-100" id="ajouter_element" href='/<?= APP_LANG ?>/statistiques?ank={"type_vue":"top_en_haut","filtre_pub":"aucun"}'>
                        Ouvrir dans une nouvelle fenêtre
                      </a>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-5 content text-white">
          <!-- voir plus tard le sticky-top -->
          <div class="col-md-12">
            <div class="container">
              <?php
              if ($typetunnel == 'portail' || $typetunnel == 'tunnel') {
                $apercu = $typetunnel;
              } else {
                $apercu = 'design';
              }
              include('type_tunnel/apercu/apercu_' . $apercu . '.php')
              ?>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<script src="<?= $ak->asset("ressources/js/flowfinder_element_theme.js") ?>"></script>
<script src="<?= $ak->asset("ressources/js/flowfinder_element.js") ?>"></script>
<script src="<?= $ak->asset("ressources/js/qrcode.min.js") ?>"></script>

<script>
  if (document.getElementById("qrcode")) {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
      text: <?php echo '"' . $_SERVER['HTTP_HOST'] . '"'; ?>,
      width: 64, // Largeur du QR code
      height: 64, // Hauteur du QR code
      colorDark: "#000000", // Couleur du QR code
      colorLight: "#ffffff", // Couleur de fond du QR code
      correctLevel: QRCode.CorrectLevel.H // Niveau de correction d'erreur
    });
  }
</script>
