<section id="login" class="vh-100 d-flex align-items-center background-creative">
  <div class="container">
    <div class="row justify-content-center w-100">
      <div class="col-sm-12 col-md-6 col-xl-4 d-flex flex-column justify-content-center">

        <!-- Logo -->
        <div class="text-center mb-4">
          <a class="navbar-brand" href="/fr/page/">
            <img src="/ressources/img/logo_flowfinder_blanc.svg" style="height: 4em" alt="flowfinder">
          </a>
        </div>

        <!-- Card -->
        <div class="card card-plain mb-4 border-0">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center mb-4">
              <h1 class="text-center"></h1>
              <h4>Connexion</h4>
            </div>

            <!-- Formulaire -->
            <form method="POST" action="/<?php echo (APP_LANG); ?>/login/login" id="form_utilisateur">
              <div class="mb">
                <label>Identifiant :</label>
              </div>
              <div class="mb-3">
                <input class="flowfinder-form-control form-control w-100" type="text" name="username">
              </div>
              <div class="mb">
                <label>Mot de passe :</label>
              </div>
              <div class="mb-3">
                <input class="flowfinder-form-control form-control w-100" type="password" name="password">
              </div>
              <button class="btn btn-primary w-100 mt-2 mb-0" type="submit">Me connecter</button>
            </form>

            <!-- Message erreur -->
            <p class="mt-4 text-center">
              <?php if (isset($message_erreur) && $message_erreur != "") { ?>
              <div class="alert alert-danger" role="alert">
                <?php $ak->ecrit($message_erreur); ?>
              </div>
              <?php } ?>
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
<script>
    document.querySelectorAll(".navbar").forEach(function(element) {
  element.style.display = "none";
  document.body.style.paddingTop = "0";
});
</script>
