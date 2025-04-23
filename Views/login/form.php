<section id="login" class="col-12 col-xl-12 mx-auto section mt-5 pb-5">
<div class="container-fluid">
    <div class="row">

        <div class="col-sm-1 col-md-3 col-xl-4 d-xl-flex flex-column"></div>
        <div class="col-sm-12 col-md-6 col-xl-4 d-flex flex-column">
            <div class="text-center">
                <a class="navbar-brand" href="/fr/page/">
                    <img src="/ressources/img/logo_flowfinder.svg" style="height: 4em" alt="flowfinder">
                </a>
            </div>
            <div class="card card-plain mb-4 border-0">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center mb-4">
                        <h1 class="text-center">
                        </h1>
                        <h4>Connexion</h4>
                    </div>
                    <form method="POST" action="/<?php echo (APP_LANG); ?>/login/login" id="form_utilisateur">
                        <div class="mb">
                            <label>Identifiant :</label>
                        </div>
                        <div class="mb-3">
                            <input class="flowfinder-form-control w-100" type="text" name="username">
                        </div>
                        <div class="mb">
                            <label>Mot de passe :</label>
                        </div>
                        <div class="mb-3">
                            <input class="flowfinder-form-control w-100" type="password" name="password">
                        </div>

                        <button class="btn btn-primary w-100 mt-2 mb-0" type="submit">Me connecter</button>
                    </form>

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
        <div class="col-sm-1 col-md-3 col-xl-4 d-xl-flex flex-column"></div>
    </div>
</div>
</section>