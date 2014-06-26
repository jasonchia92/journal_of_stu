<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>系統管理者::帳號管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .pms_block {
            text-align: left;
        }

        .pms_block div {
            display: inline-block;
        }

        .add_user {
            position: relative;
            left: 750px;
        }

        .func_modify {
            display: none;
        }

        #dialog {
            padding: 0;
            margin: 0;
            background-color: #DDEEFF;
        }
    </style>
</head>
    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $('.add_user').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./add_user.php?gid=0";
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=410 height=270 src="' + link + '" ><\/iframe>').dialog({
                    title: "新增審稿委員",
                    autoOpen: true,
                    width: 410,
                    height: 320,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
            });

            $('.edit_data').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./edit_userData.php?gid=0&id=" + $(this).attr('value');
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=410 height=410 src="' + link + '" ><\/iframe>').dialog({
                    title: "修改資料",
                    autoOpen: true,
                    width: 410,
                    height: 460,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
            });

            $('.delete_data').click(function(){
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
                <button class="btn add_user">新增管理員</button>
            <center>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>姓名</th>
                        <th>Email(登入帳號)</th>
                        <th style="width:115px;">功能</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $user_data = get_users_list(0);

                    function check_empty( $str ){
                        return (strcmp( $str, "" )!=0 ? $str : "-");
                    }

                    if ( count($user_data) ) {
                        foreach ( $user_data as $key => $value ) : ?>
                            <tr>
                                <td><?php echo $value['id']; ?></td>
                                <td><?php echo dop($value['name']); ?></td>
                                <td><?php echo dop($value['email']); ?></td>
                                <td class="manage_func">
                                    <button class="btn edit_data" value="<?php echo $value['id']; ?>">修改</button>
                                    <button class="btn delete_data" value="<?php echo $value['id']; ?>">刪除</button>
                                </td>
                            </tr>
                        <?php endforeach;
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
        <div align='center'>
        <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
