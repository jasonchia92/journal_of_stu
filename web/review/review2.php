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
      .refuse,.up_lock{
        display:none;
        color:red;
      }
      table#current_file_list th, table#current_file_list td {
        border: 1px solid #555;
      }

      table#current_file_list td.file_link {
        cursor: pointer;
        color: #31E;
      }

      table#current_file_list td.file_link:hover {
        color: #39D;
      }
      .warm{
        color:red;
        display: none;
      }
      .file_link:hover{
        color: blue;
        cursor: pointer;
      }
      .upload_font{
        color:blue;
      }
      .ww{
        color:red;
      }
      .parallel{
        -moz-column-count:2;  /* Firefox */
        -webkit-column-count:2; /* Safari 和 Chrome */
        column-count:2;
        width:600px;
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
</head>
  <body class="home">
     <div id="container">
        <div id="wrapper">
            <?php include ("../bar/header_review.php");?> 
            <div class="span9">
                <div class="hero-unit" style="text-align:center;font-size:30px;padding:20px;">
                    <?php
                    // 接收檔案
                    pfcr_file_uploader(1);

                    // 產生score_option
                    $score_option = '000000000000';
                    if ( isset($_POST['score_option']) )
                      foreach ( $_POST['score_option'] as $key => $value )
                        $score_option{$value} = '1';

                    /*
                    # 二階段審稿，判斷最終結果(推薦or不推薦)，避免再次進入複審
                    if ( isset($_POST['result']) && $_POST['status']==6 )
                      $_POST['result'] = (int)( ($_POST['result']+1)/2 );
                    */

                    // Update record
                    $exec_result = execute_review(
                      $_POST['id'],
                      $score_option,
                      $_POST['opinion'],
                      ( isset($_POST['result']) ? $_POST['result'] : 0 )
                    );

                    if ( $exec_result && ($_POST['update_type'] == 1) ) {
                      $exec_result = record_lock( $_POST['id'] );
                      // E-mail notification
                      $auth_data = get_auth_data();
                      $mail_params['pid'] = $_POST['paper_id'];
                      mail_queue_add( $auth_data['account'], 'review_finished', $mail_params);
                    }

                    if ( $exec_result ) {
                      echo '操作成功';
                    } else {
                      echo '操作失敗，請稍候再嘗試；若嘗試多次仍未成功，請聯絡系統管理者。';
                      // make_log('Execute review has been error (' . $_POST['id'] . ')');
                      throw new Exception('Execute review has been error (' . $_POST['id'] . ')');
                    }
                    ?>
                </div>
            </div>
          </div>
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
      </div><!--/.fluid-container-->
  <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
  <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>
