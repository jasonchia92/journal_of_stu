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
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="../js/js_spare/jquery.min.js"></script>
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

        input{
            display: inline;
        }

        td, th {
        padding: 15px;
        border: 1px solid #ccc;
        text-align: center;
        color: #000;
        }

        th {
          background: lightblue;
          border-color: white;
        }
    </style>
    
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
<body class="home">
    <div id="container">
        <div id="wrapper">
        <?php include ("header_manage.php");?> 
                <div class="span10" style="padding-top:50px;">
                    <div class="hero-unit">
                        <div><span style="font-size:30px; font-weight:bold;">意見回應管理</span></div>
                        <form name="del_data" action="" method="post">
                            <input type="submit" class="btn" value="刪除選取項目" style="font-size:15px;padding:5px;" />
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
                                    <input type="submit" class="btn" name="submit" value="刪除選取項目" style="font-size:15px;padding:5px;"/>
                                </div> <!-- end data_operation -->
                            </div> <!-- end operation_block -->
                        </form>
                        <div id="res_msg"></div>
                    </div><!--/hero-unit-->
                    <div class="page_numbers_block"><?php if ( $opinion_list !== false ) $opinion_list->action_show_page_numbers(); ?></div>
                </div><!--/span10-->
            </div><!--row-fluid-->
        <div id="dialog"></div>
        <div style="text-align:center;"><?php include ("../bar/end.php");?></div>
    </div><!--container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>
