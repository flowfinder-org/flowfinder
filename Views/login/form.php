
<section id="login" class="col-12 col-xl-12 mx-auto section background-creative mt-5 pb-5">
    <div class="container col-12 col-md-10">
        <div class="row align-items-xl-top gy-5">
            <div class="col-md-3 "></div>

            <div class="col-md-6 bloc_presentation mt-6 pt-2">

                <h4 class="my-0 fw-normal text-center"></i>Connexion</h4>

                <table style="width:400px;">
                    <tbody>
                        <form method="POST" action="/<?php echo(APP_LANG); ?>/login/login">
                        <tr><td> <label for="username">Identifiant :</label> </td><td> <input class="flowfinder-form-control w-100" type="text" name="username"> </td></tr>
                        <tr><td> <label for="password">Mot de passe :</label> </td><td> <input class="flowfinder-form-control w-100" type="password" name="password"> </td></tr>
                        <tr><td></td><td>  <input type="submit" class="flowfinder-btn flowfinder-btn-primary flowfinder-float-end flowfinder-content-sendBtn" value="Valider">  </td></tr>
                        </form>
                    </tbody>
                </table>

<?php if (isset($message_erreur) && $message_erreur != "") { ?>
    <div class="alert alert-danger" role="alert">
        <?php $ak->ecrit($message_erreur); ?>
    </div>
<?php } ?>

            </div>
            <div class="col-md-3 "></div>
        </div>
    </div>
</section>