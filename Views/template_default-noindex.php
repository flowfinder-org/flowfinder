<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="title" content="FlowFinder - Optimisez l'expérience client et boostez vos conversions avec notre solution sans code">
  <meta name="description" content="Découvrez comment FlowFinder aide votre entreprise à analyser le parcours client, améliorer l'engagement et maximiser les conversions grâce à une solution simple et intuitive.">
  <meta name="keywords" content="optimisation conversion, analyse comportementale, tests A/B, engagement client, expérience utilisateur, feedback, rétention client, solution sans code">
  <meta name="robots" content="noindex, follow">

  <title>FlowFinder</title>

  <link rel="shortcut icon" href="/favicon.ico">
  <link rel="stylesheet" href="<?= $ak->asset("ressources/css/ak_bootstrap.css") ?>">
  <link rel="stylesheet" href="<?= $ak->asset("ressources/css/flowfinder-web.css") ?>">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">


  <script src="<?= $ak->asset("ressources/js/ak_bootstrap.js") ?>"></script>
  <script src="<?= $ak->asset("ressources/js/default.js") ?>"></script>
  <script src="<?= $ak->asset("ressources/js/simple_cookie.js") ?>"></script>

</head>

<body id="body-<?= $ak->idbody ?>" class="vh-100">
  <!-- Modal -->
  <div class="modal fade" id="informationModal" tabindex="-1" aria-labelledby="informationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-content-contenu" id="modal-content-contenu">
        </div>
        <div class="modal-footer" id="modal-footer">
          <button type="button" id="fermeinformationModal" class="btn btn-secondary fermeinformationModal" data-bs-dismiss="modal">&times;</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin Modal -->

  <div class="d-flex" id="barre-top">
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-white">
        <div class="container-fluid d-flex justify-content-between">

          <div class="menu_conversation">
            <a href="/<?= APP_LANG ?>/">
              <button class="btn btn-primary" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                </svg>
              </button>
            </a>
          </div>

          <a class="navbar-brand" href="/<?= APP_LANG ?>/"> <img src="/ressources/img/logo_flowfinder.svg" alt="logo flowfinder" style="height: 2em"></a>
          <div></div>
        </div>
      </nav>
    </header>
  </div>

  <?= $contenu ?>

  <div class="d-flex">
    <footer class="footer fixed-bottom text-center">
      <div class="d-flex justify-content-center align-items-center">

        <p class="mt-2 text-white mb-0 d-block d-md-none">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/" class="text-white" target="_blank">&copy; <?php $ak->ecrit(date("Y")) ?> - <?php $ak->ecrit(date("Y") + 1) ?>&nbsp; FlowFinder <?php $ak->traduit('TDBOTTOM-PAR'); ?> FlowFinder </a>
        </p>

        <p class="mt-2 text-white mb-0 d-none d-md-block">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/page/cgv" class="text-white" target="_blank">&copy; <?php $ak->ecrit(date("Y")) ?> - <?php $ak->ecrit(date("Y") + 1) ?>&nbsp; FlowFinder  — <?php $ak->traduit('TDBOTTOM-TOUSDROITSRESERVES'); ?> </a>
        </p>

        <p class="mt-2 text-white mb-0">&nbsp;|&nbsp;</p>

        <p class="mt-2 text-white mb-0 d-block d-md-none">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/page/cgv" class="text-white" target="_blank"><?php $ak->traduit('TDBOTTOM-CGV'); ?></a>
        </p>

        <p class="mt-2 text-white mb-0 d-none d-md-block">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/page/cgv" class="text-white" target="_blank"><?php $ak->traduit('TDBOTTOM-CGVLP'); ?></a>
        </p>

        <p class="mt-2 text-white mb-0">&nbsp;|&nbsp;</p>

        <p class="mt-2 text-white mb-0 d-block d-md-none">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/page/mentionslegales" class="text-white" target="_blank"><?php $ak->traduit('TDBOTTOM-MENTIONSLEGALES'); ?></a>
        </p>

        <p class="mt-2 text-white mb-0 d-none d-md-block">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/page/mentionslegales" class="text-white" target="_blank"><?php $ak->traduit('TDBOTTOM-MENTIONSLEGALES'); ?></a>
        </p>

      </div>
    </footer>
  </div>

  <div class="cookie-popup-container mb-3" id="cookiePopup">
    <div class="cookie-popup-row d-flex justify-content-center align-items-center">
      <div id="cookie-info" class="cookie-popup-columns cookie-popup-ten text-center">
        <div class="banner_logo"></div>
        <div id="cookie-policy">
          <h2 id="cookie-policy-title">Votre vie privée nous importe</h2>
          <div id="cookie-policy-text">
            Nous utilisons des cookies pour améliorer votre expérience sur notre site. Ces cookies nous permettent de mémoriser vos préférences et d’optimiser la navigation. Vous pouvez accepter ou refuser cette utilisation en cliquant sur l'un des boutons.
          </div>
        </div>
      </div>

      <div id="cookie-button-group" class="cookie-popup-columns cookie-popup-two text-center">
        <div id="cookie-buttons" class="d-flex flex-column flex-sm-row justify-content-center">
          <button id="cookie-accept-btn" class="nav-div btn btn-primary text-white mb-2 mb-sm-0 mr-sm-2">Accepter les cookies</button>
          <button id="cookie-reject-all-btn" class="nav-div btn btn-danger text-white">Refuser les cookies</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    if (!localStorage.getItem('cookiesConsent')) {
      document.getElementById('cookiePopup').style.display = 'block';
      if (document.getElementById('cookie-accept-btn')) {
        document.getElementById('cookie-accept-btn').addEventListener('click', () => {
          localStorage.setItem('cookiesConsent', 'accepted');
          document.getElementById('cookiePopup').style.display = 'none';
        })
      };
      if (document.getElementById('cookie-reject-all-btn')) {
        document.getElementById('cookie-reject-all-btn').addEventListener('click', () => {
          localStorage.setItem('cookiesConsent', 'rejected');
          document.getElementById('cookiePopup').style.display = 'none';
        });
      }
    }
  </script>

</body>

</html>