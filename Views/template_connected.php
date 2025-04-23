<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

<body class="vh-100 d-flex flex-grow-1" id="body-<?= $ak->idbody ?>">
  <div class="mode-demonstration-block mode-demonstration d-none" id="mode-demonstration">
  </div>

  <div class="mode-demonstration w-100 mode-demonstration-quitter d-none">
    <a class="nav-div btn btn-inv btn-sm me-2 btn-danger" href="/<?= APP_LANG ?>/" aria-selected="false">
      Quitter le mode démonstration
    </a>
  </div>
  <div class="d-none d-md-flex">
    <header class="d-flex">

      <nav class="navbar-expand-md navbar-dark fixed-top bg-light" id="navbar-top">

        <?php if ($ak->pageactuelle_body == "discution_p100") { ?>
          <div class="version_gratuite d-none">
            Un expert vous rappelle, c’est gratuit !
            <a href="/<?= APP_LANG ?>/calendrier/rdv" class="d-inline-flex align-items-center btn btn-sm btn-inverse-primary btn-lg px-4 rounded-pill mb-1 mt-1 ">
              <span class="me-2">Planifier un appel </span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"></path>
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"></path>
              </svg>
            </a>
          </div>
        <?php } else { ?>
          <div class="version_gratuite d-none">
            L'outil indispensable pour propulser votre activité : <a class="nav-div btn btn-inv btn-primary btn-sm me-2" href='/<?= APP_LANG ?>/choixFormule'>Passez à FlowFinder autonome </a>
          </div>
        <?php } ?>


        <div class="navbar">
          <div class="container-fluid">

            <?php if ($ak->statut_paiement > 0) { ?>
              <div class="menu_conversation">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#historique" aria-controls="historique" aria-label="Menu conversation">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M5.75 5.25h12.5a.75.75 0 1 1 0 1.5H5.75a.75.75 0 0 1 0-1.5zm0 6h12.5a.75.75 0 1 1 0 1.5H5.75a.75.75 0 1 1 0-1.5zm0 6h12.5a.75.75 0 1 1 0 1.5H5.75a.75.75 0 1 1 0-1.5z"></path>
                  </svg>
                </button>
              </div>
            <?php } else { ?>
              <a href="/<?= APP_LANG ?>/page">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1"></path>
                </svg>
              </a>
            <?php } ?>

            <a class="navbar-brand" href="/<?= APP_LANG ?>/page/"><img src="/ressources/img/logo_flowfinder.svg" style="height: 2em" alt="flowfinder"></a>

            <div class="d-flex align-items-center">
              <a class="nav-div btn btn-inv btn-primary btn-sm me-2" href="/<?= APP_LANG ?>/accueil/logout" aria-selected="false">
                <?php $ak->traduit('MENU-SEDECONNECTER'); ?>
              </a>
            </div>

          </div>
        </div>
      </nav>
    </header>
  </div>

  <div class="d-flex d-md-none">
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-white">

        <?php if ($ak->pageactuelle_body == "discution_p100") { ?>
          <div class="version_gratuite d-none">
            <a href="/<?= APP_LANG ?>/calendrier/rdv" class="d-inline-flex align-items-center btn btn-sm btn-inverse-primary btn-lg px-4 rounded-pill mb-1 mt-1 ">
              <span class="me-2">Planifier un appel </span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"></path>
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"></path>
              </svg>
            </a>
          </div>
        <?php } else { ?>
          <div class="version_gratuite d-none">
            <a class="nav-div btn btn-inv btn-primary btn-sm me-2" href='/<?= APP_LANG ?>/choixFormule'>Passez au compte autonome en cliquant ici</a>
          </div>
        <?php } ?>


        <div class="container-fluid d-flex justify-content-between">

          <div class="menu_conversation">
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#historique" aria-controls="historique" aria-label="Menu conversation">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" fill-rule="evenodd" d="M5.75 5.25h12.5a.75.75 0 1 1 0 1.5H5.75a.75.75 0 0 1 0-1.5zm0 6h12.5a.75.75 0 1 1 0 1.5H5.75a.75.75 0 1 1 0-1.5zm0 6h12.5a.75.75 0 1 1 0 1.5H5.75a.75.75 0 1 1 0-1.5z"></path>
              </svg>
            </button>
          </div>

          <a class="navbar-brand" href="/<?= APP_LANG ?>/"> <img src="/ressources/img/logo_flowfinder.svg" style="height: 2em"></a>

          <div>
            <a class="nav-div btn btn-inv btn-primary btn-sm me-2" href="/<?= APP_LANG ?>/accueil/logout" aria-selected="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                <path d="M7.5 1v7h1V1z" />
                <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812" />
              </svg>
            </a>
          </div>
        </div>
      </nav>
    </header>
  </div>

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
  <div class="mt-5">
  </div>
  <?php if ($ak->statut_paiement > 0) { ?>
    <?= $contenu ?>
  <?php } else { ?>
    <section id="profil" class="col-12 col-xl-12 mx-auto section mt-5 pb-5">
      <div class="container col-12 col-md-10">
        <div class="row align-items-xl-top gy-5">

          <?php include 'accueil/sections/plansettarifs/section_plansettarifs_top.php'; ?>
        </div>
      </div>
    </section>
  <?php  } ?>

  <div class="d-flex">
    <footer class="footer fixed-bottom text-center">
      <div class="d-flex justify-content-center align-items-center">

        <p class="mt-2 text-white mb-0 d-block d-md-none">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/" class="text-white" target="_blank">&copy; <?php $ak->ecrit(date("Y")) ?> - <?php $ak->ecrit(date("Y") + 1) ?>&nbsp; FlowFinder <?php $ak->traduit('TDBOTTOM-PAR');?> FlowFinder </a>
        </p>

        <p class="mt-2 text-white mb-0 d-none d-md-block">
          <a href="https://flowfinder.org/<?= APP_LANG ?>/page/cgv" class="text-white" target="_blank">&copy; <?php $ak->ecrit(date("Y")) ?> - <?php $ak->ecrit(date("Y") + 1) ?>&nbsp; FlowFinder  — <?php $ak->traduit('TDBOTTOM-TOUSDROITSRESERVES'); ?> </a>
        </p>

        <p class="mt-2 text-white mb-0">&nbsp;|&nbsp;</p>

        <p class="mt-2 text-white mb-0 d-block d-md-none">
          <a href="<?= APP_LANG ?>/page/cgv" class="text-white"><?php $ak->traduit('TDBOTTOM-CGV'); ?></a>
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
  </div>

</body>

</html>