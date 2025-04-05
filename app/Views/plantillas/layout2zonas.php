<!doctype html>
<html lang="en">
  <head>
  	<title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('css/styleBars.css'); ?>" >
  </head>
  <body>

    <div class="wrapper d-flex align-items-stretch">

        <?= $this->include("plantillas/vSideBar"); ?>
        
        <?= $this->include("plantillas/barraNav"); ?>

        <!-- contenido que va variando -->
        <?= $this->renderSection("principal"); ?>

        </div>   <!--  El cierre del div de barraNav -->

    </div>

    <script src="<?= base_url('js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('js/popper.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('js/main.js'); ?>"></script>
    
  </body>
</html>