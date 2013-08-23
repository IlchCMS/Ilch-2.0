<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Ilch <?php echo VERSION; ?> - Installation</title>
    <meta name="description" content="Ilch - Installation">
    <link href="<?php echo $this->staticUrl('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <script src="<?php echo $this->staticUrl('js/jquery-1.7.min.js'); ?>"></script>
    <link rel="shortcut icon" href="<?php echo $this->staticUrl('img/logo.png'); ?>">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand" href="#">Ilch CMS <?php echo VERSION; ?></a>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Installations</li>
              <li class="active"><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
            </ul>
          </div>
        </div>
        <div class="span9">
          <div class="hero-unit">
            <h2>Willkommen zur Ilch CMS <?php echo VERSION; ?> - Installation!</h2>
            <p>Bitte führen Sie nun die Installations - Schritte durch.</p>
            
            <?php echo $this->getContent(); ?>
          </div>
        </div>
      </div>
      <hr>
      <footer>
        <p>&copy; Ilch 2013</p>
      </footer>
    </div>
</body>
</html>