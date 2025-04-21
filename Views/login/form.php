<h1>Login please</h1>
<form method="POST" action="/<?php echo(APP_LANG); ?>/login/login">
    <label for="username">Username: </label><input type="text" name="username">
    <label for="username">Username: </label><input type="password" name="password">
    <input type="submit" value="Sign-In">
</form>
<?php if (isset($message_erreur) && $message_erreur != "") { ?>
    <div class="alert alert-danger" role="alert">
        <?php $ak->ecrit($message_erreur); ?>
    </div>
<?php } ?>