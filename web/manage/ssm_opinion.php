<?php
require_once('../../loader.php');
check_login_status();

// Delete
if ( isset($_POST['submit']) ) {
    $msg = '';
    if ( delete_opinion($_POST['data_del']) )
        $msg = "操作成功";
    else
        $msg = "操作失敗，請稍後再嘗試。若多次嘗試仍顯示此訊息，請聯絡系統管理員。";

    function alert_msg( $msg ) { ?>
        <script type="text/javascript">alert('<?=$msg;?>');</script>
    <?php }

    func_queue_add('alert_msg', $msg);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>意見回應管理::系統設定管理 - 樹德科技大學學報</title>
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

        .hero-unit{
            padding-left:30px;
            padding-right: 30px;
        }

        .table th,
        .table tbody tr td {
            text-align: center;
        }

        /* table column - keynote */
        .table tbody tr td:nth-child(3) {
            text-align: left;
        }

        .func_selectAll {
            font-size: 14px;
        }

        .hyper-link {
            text-decoration: none;
            color: #494949;
            padding: 1px 0px;
            margin-bottom: -1px;
        }

        .hyper-link:hover {
            color: #0000FF;
            cursor: pointer;
        }
    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="../js/js_spare/jquery.min.js"></script>
    <!--jQuery UI-->
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $('form[name="del_data"]').submit(function(){
                var checked_sum = 0;
                $('.chk_del').each(function(){
                    if ( $(this).prop('checked') )
                        checked_sum++;
                });

                if ( checked_sum <= 0 ) {
                    alert('未選取任何項目.');
                    return false;
                } else if ( !confirm('刪除後的資料無法復原，確定刪除?') )
                    return false;
            });

            $('.func_sa_control').click(function(){
                var action = $(this).html()=='全選';
                $('.chk_del').each(function(){
                    $(this).prop('checked', action);
                });
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
                <div class="span2">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <?php include ("menu_left.php");?>
                        </ul>
                    </div><!--/.well -->
                </div><!--/span2-->
                <div class="span10">
                    <div class="hero-unit">
                        <div><span style="font-size:30px; font-weight:bold;">意見回應管理</span></div>
                        <form name="del_data" action="" method="post">
                            <input type="submit" class="btn" value="刪除選取項目" />
                            <span class="func_selectAll">(
                                <span class="func_sa_control hyper-link">全選</span>/
                                <span class="func_sa_control hyper-link">全不選</span>
                            )</span>
                            <?php $opinion_list = get_opinion_list(); ?>
                            <?php if ( $opinion_list !== false ): ?>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:16px;">x</th>
                                            <th style="width:100px;text-align:center;">編號</th>
                                            <th>主旨</th>
                                            <th style="width:100px;text-align:center;">日期</th>
                                            <th style="width:60px;">已回覆</th>
                                        <tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach( $opinion_list->data as $key => $value ): ?>
                                        <tr>
                                            <td><input type="checkbox" name="data_del[]" class="chk_del" value="<?=$value['opinion_id'];?>"></td>
                                            <td><?=$value['opinion_id'];?></td>
                                            <td><a href="view_opinion.php?opinion_id=<?=$value['opinion_id'];?>"><?=dop($value['keynote']);?></a></td>
                                            <td><?=$value['date'];?></td>
                                            <td><?=($value['replied'] == 1 ? '是' : '否')?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>目前系統上尚任何發佈的公告，欲發佈公告請點選「新增公告」。</p>
                            <?php endif; ?>
                            <div class="operation_block">
                                <div class="data_operation">
                                    <input type="submit" class="btn" name="submit" value="刪除選取項目" />
                                </div> <!-- end data_operation -->
                            </div> <!-- end operation_block -->
                        </form>
                        <div id="res_msg"></div>
                    </div><!--/hero-unit-->
                    <div class="page_numbers_block"><?php if ( $opinion_list !== false ) $opinion_list->action_show_page_numbers(); ?></div>
                </div><!--/span10-->
            </div><!--row-fluid-->
        </div><!--/.container-fluid-->
        <div id="dialog"></div>
        <div style="text-align:center;"><?php include ("../bar/end.php");?></div>
    </div><!--container-->
</body>
</html>
