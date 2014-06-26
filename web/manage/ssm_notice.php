<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>最新消息::系統設定管理 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <link href="../../plugin/page_number_creater/style/normal.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
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

        .create_notice {
            position: relative;
            float: right;
            margin-top: -5px;
        }

        .table {
          width: 100%;
          margin-bottom: 20px;
        }

        .table th,
        .table td {
          padding: 8px;
          line-height: 20px;
          text-align: left;
          vertical-align: top;
          text-align: center;
          border-top: 1px solid #dddddd;
        }

        .table-striped tbody > tr:nth-child(odd) > td,
        .table-striped tbody > tr:nth-child(odd) > th {
          background-color: #f9f9f9;
        }

        .hero-unit{
            padding-left:30px;
            padding-right: 30px;
        }

    </style>
</head>
    
    <script type="text/javascript">
        $('document').ready(function(){
            $('.delete_notice').click(function(){
                if( confirm("確定刪除該公告?") ){
                    $.ajax({
                        url: '<?php echo ROOT_URL."/web/ajax_request_handler.php"; ?>',
                        type: 'post',
                        data: {
                            'action': 'notice_delete',
                            'id': $(this).attr('value'),
                        },
                        success: function(){
                            window.location.reload();
                        },
                        error: function(){
                            alert("Operating fail.");
                        }
                    });
                }
            });
        });
    </script>

  <body class="home">
    <div id="container">
        <div id="wrapper">
        <?php include ("header_manage.php");?> 

        <div class="span10">
            <div class="hero-unit" style="padding-top:70px;">
                <div>
                    <span style="font-size:30px; font-weight:bold;">最新消息管理</span>
                    <button class="btn create_notice" onclick=" window.location='add_notice.php'; ">新增公告</button>
                </div>
                <center>
                    <?php
                        $sql = "SELECT
                                a.*,
                                b.name AS user_name
                            FROM notice AS a LEFT JOIN users AS b ON
                                a.post_by=b.id
                            ORDER BY a.notice_id DESC ";

                        $notices = sql_q( $sql, array() );

                        if ( count($notices) ) {
                            ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>發佈人</th>
                                        <th>發佈時間</th>
                                        <th>功能</th>
                                    <tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach( $notices as $key => $value ){
                                        ?>
                                            <td><span><?php echo dop($value['title']); ?></span></td>
                                            <td><?php echo dop($value['user_name']); ?></td>
                                            <td><?php echo $value['date']; ?></td>
                                            <td>
                                                <button class="btn" onClick=" window.location.href='edit_notice.php?notice_id=<?php echo $value['notice_id']; ?>' " >修改</button>
                                                <button class="btn delete_notice" value="<?php echo $value['notice_id']; ?>">刪除</button></td>
                                            <tr>
                                        <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            echo '目前系統上尚任何發佈的公告，欲發佈公告請點選「新增公告」。';
                        }
                    ?>
                    <div id="res_msg"></div>
                </center>
                <div id="dialog"></div>
            </div>
            </div>
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
        </div>
    </div><!--/.fluid-container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>

  </body>
</html>
