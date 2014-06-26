<?php require_once('../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>主頁面</title>
    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/slide.css" rel="stylesheet">
    <link href="bootstrap/css/half-slide.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="bar/style.css" rel="stylesheet" type="text/css"/>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/buttons.css">
    <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
    <script src="bootstrap/js/slide.js"></script>
</head>

  <body>
     <?php include ("bar/headerblock.php");?>  

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <li><a href="#">menu</a></li>
                <li><a href="index.php">最新消息</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span10">
            <div class="hero-unit">
                <p>Ya</p>
            </div>
        </div>
    </div>
  </div>
        <div align='center'>
            <?php include ("bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
