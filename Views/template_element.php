<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>

  <link rel="shortcut icon" href="/<?= APP_LANG ?>/ressources/img/favicon.ico">
  <link rel="stylesheet" href="<?= $ak->asset("ressources/css/ak_bootstrap.css") ?>">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">


  <script src="<?= $ak->asset("ressources/js/ak_bootstrap.js") ?>"></script>

</head>

<body id="body-<?= $ak->idbody ?>" class="vh-100">

  <?= $contenu ?>

</body>


</html>