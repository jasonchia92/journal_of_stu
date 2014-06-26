<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>審稿委員::帳號管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .personal_data .small-col,
        .personal_data .big-col {
            display: inline-block;
            margin-right: 15px;
        }

        .personal_data .small-col {
            width: 200px;
        }

        .personal_data .big-col {
            width: 420px;
        }

        td.manage_func {
            width: 55px;
            vertical-align: middle;
        }

        h2.page-name {
            display: inline-block;
            margin-left:0;
        }

        button.add_user {
            position: relative;
            float: right;
        }

        .hero-unit{
            padding-left: 30px;
        }

        #dialog {
            padding: 0;
            margin: 0;
            background-color: #DDEEFF;
        }
    </style>
</head>
<!--jQuery 1.7.1-->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write('<script src="../js/js_spare/jquery.min.js"><\/script>')</script>
    <!--jQuery UI-->
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $('button.edit_data').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./edit_userData.php?gid=1&id=" + $(this).attr('value');
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=410 height=515 src="' + link + '" ><\/iframe>').dialog({
                    title: "修改資料",
                    autoOpen: true,
                    width: 410,
                    height: 565,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
            });

            $('button.add_user').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./add_user.php?gid=1";
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=410 height=470 src="' + link + '" ><\/iframe>').dialog({
                    title: "新增審稿委員",
                    autoOpen: true,
                    width: 410,
                    height: 520,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
            });

            $('button.delete_data').click(function(){
                if( confirm("確定刪除該使用者?") ){
                    $.ajax({
                        url: '<?php echo ROOT_URL."/web/ajax_request_handler.php"; ?>',
                        type: 'post',
                        data: {
                            'action': 'delete_user',
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
                    <h2 class="page-name">審稿委員</h2>
                    <button class="btn add_user">新增審稿委員</button>
                </div>
                <center>
                    <table class="table" id="data">
                        <tbody>
                            <?php
                                $userData = get_users_list(1);

                                function check_empty( $str ){
                                    return (strcmp( $str, "" )!=0 ? $str : "-");
                                }

                                $categary_name = array("-", "管理", "資訊", "設計", "幼保.外文.性學", "通識教育");

                                if ( count($userData) ) {
                                    foreach ( $userData as $key => $value ) {
                                        ?>
                                        <tr>
                                            <td class="personal_data">
                                                <span class="small-col">姓名：<?php echo dop( check_empty( $value['name'] ) ); ?></span>
                                                <span class="small-col">性別：<?php echo ( $value['sex'] ? "女" : "男" ); ?></span>
                                                <span class="small-col">分派領域：<?php echo $categary_name[ $value['assign_category'] ]; ?></span><br/>
                                                <span class="big-col">E-mail：<?php echo dop( check_empty( $value['email'] ) ); ?></span>
                                                <span class="small-col">電話：<?php echo dop( check_empty( $value['phone'] ) ); ?></span><br/>
                                                <span class="big-col">服務單位：<?php echo dop( check_empty( $value['serve_unit'] ) ); ?></span><br/>
                                                <span class="big-col">地址：<?php echo dop( check_empty( $value['address'] ) ); ?></span>
                                            </td>
                                            <td class="manage_func">
                                                <button class="btn edit_data" value="<?php echo $value['id']; ?>">修改</button><br/>
                                                <button class="btn delete_data" value="<?php echo $value['id']; ?>">刪除</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '查無資料';
                                }
                            ?>
                        </tbody>
                    </table>
                    <div id="res_msg"></div>
                </center>
                <div id="dialog"></div>
            </div>
        </div>
        <div align='center'>
        <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
