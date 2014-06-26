<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>所有論文</title>
  <!-- Le styles -->
  <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
  <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
  <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
  <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
  <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
  <style type="text/css">
   
    .sidebar-nav {
      padding: 9px 0;
    }

    @media (max-width: 980px) {
      /* Enable use of floated navbar text */
      .navbar-text.pull-right {
        float: none;
        padding-left: 5px;
        padding-right: 5px;
      }
    }

    .navbar .nav{
      text-align: center;
      display: table-cell;
      float: none;
    }

    #main{
      color:black;
    }
  </style>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/jQuery.appear.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/superfish.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/jquery.flexslider-min.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/jquery.easing.1.3.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/easypiechart.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/canvas.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/niceScroll.js"></script>

  <script src="../bootstrap/theme/js/jquery.lazyload.js"></script>
  <script src="../bootstrap/theme/js/least.min.js"></script>

  <script type="text/javascript" src="<?php echo JS_URL; ?>/jquery.validate.js"></script>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/cmxforms.js"></script>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?php echo JS_URL; ?>/tab_page.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#cr").click(function(){
        $(".refuse").show();
      });
    });
  </script>
</head>
  <body class="home">
     <div id="container">
      <div id="wrapper">
        <?php include ("../bar/header_review.php");?> 
        <div class="span9">
          <div class="hero-unit" style="text-align:center;font-size:20px;padding:20px;">
    <?php
      require_once("../../loader.php");
      $result = update_request( $_POST['id'], 0, $_POST['reason'] );
      
      if ( $result ) {
        echo '已拒絕審查此論文。';
        // E-mail notification
        $auth_data = get_auth_data();
        $mail_params['pid'] = $_GET['pid'];
        mail_queue_add( $auth_data['account'], 'review_invite_deny', $mail_params);
      } else {
        echo '發生錯誤 , 請通知負責人員';
      }
    ?>

  </div>
    </div><!--/.fluid-container-->
    <div align='center'>
        <?php include ("../bar/end.php");?>
    </div>
  <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>