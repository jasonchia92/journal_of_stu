<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>校稿::線上投稿系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet">   

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

      h2 {
        margin: 0;
        margin-bottom: 10px;
        text-align: left;
      }

      .hero-unit{
        font-size: 30px;
        padding: 20px;
        text-align: center;
      }
    </style>
    
</head>
  <body class="home">
  <div id="container">
    <div id="wrapper">
       <?php include ("../bar/header_contribute.php");?> 
        <div class="span9">
            <div class="hero-unit">
                <?php
                    $action_1 = ( isset($_FILES['new_upload_file']) ? pfcr_file_uploader(2) : false );
                    $action_2 = ( isset($_FILES['craa']) ? multi_upload($_POST['paper_id'], 'craa', 1, '著作權讓與同意書') : false );

                    if ( $action_1 || $action_2 ) {
                        echo '操作成功';

                        // E-mail notification
                        $auth_data = get_auth_data();
                        $mail_params['pid'] = $_POST['paper_id'];
                        mail_queue_add( $auth_data['account'], 'proofreading_success', $mail_params);
                    } else {
                        echo '操作失敗，請稍後再嘗試；若多次嘗試仍有問題，請聯絡系統管理者。';
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="foot">
       <?php include ("../bar/end.php");?>
    </div>
    </div><!--/.fluid-container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>
