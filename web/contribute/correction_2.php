<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>校稿::線上投稿系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
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

      h2 {
        margin: 0;
        margin-bottom: 10px;
        text-align: left;
      }
    </style>
    <script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
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
                <?php require_once("../bar/menu_contribute.php"); ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
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
  </div>
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
