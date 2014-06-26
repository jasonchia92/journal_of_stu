<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>最新消息::系統設定管理 - 樹德科技大學學報</title>
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

        .create_notice {
            position: relative;
            float: right;
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
          border-top: 1px solid #dddddd;
        }

        .table-striped tbody > tr:nth-child(odd) > td,
        .table-striped tbody > tr:nth-child(odd) > th {
          background-color: #f9f9f9;
        }

        span {
          display : inline-block;
          overflow : hidden;
          text-overflow : ellipsis;
          white-space : nowrap;
          width : 450px;
        }


        .hero-unit{
            padding-left:30px;
            padding-right: 30px;
        }

    </style>
</head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="../js/js_spare/jquery.min.js"></script>
    <!--jQuery UI-->
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
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
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <?php include ("menu_left.php");?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span10">
            <div class="hero-unit">
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
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
