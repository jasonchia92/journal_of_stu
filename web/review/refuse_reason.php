<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>所有論文</title>
  <!-- Le styles -->
  <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
  <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="../bar/style.css" rel="stylesheet" type="text/css" />
  <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />
  <style type="text/css">
    body {
      padding-top: 15px;
      padding-bottom: 40px;
    }
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
  <body>
  	<div class="container">
        <div align='center'>
            <?php include ("../bar/header.php");?>
        </div>
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
              <?php include ("../bar/menu_top.php");?>
            </div>
          </div>
        </div><!-- /.navbar -->
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <?php require_once ("../bar/menu_review.php");?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit">
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
  </body>
</html>