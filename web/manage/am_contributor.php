<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>投稿者::帳號管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <link href="general.css" rel="stylesheet" type="text/css" />
    <link href="../../plugin/page_number_creater/style/normal.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript">window.jQuery || document.write('<script src="../js/js_spare/jquery.min.js"><\/script>')</script>
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
        td.personal_data {
            text-align: left;
            padding: 10px;
        }

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

        #data{
            width: 90%;
            margin: 0 auto;
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
</head>
<!--jQuery 1.7.1-->
<script type="text/javascript">
    $('document').ready(function(){
        $('.edit_data').click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var link = "./edit_userData.php?gid=3&id=" + $(this).attr('value');
            $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0  width=410 height=515 src="' + link + '" ><\/iframe>').dialog({
                title: "修改資料",
                autoOpen: true,
                width: 410,
                height: 565,
                modal: true,
                resizable: false,
                autoResize: false,
            });
        });

        $('.add_user').click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var link = "./add_user.php?gid=3";
            $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=410 height=440 src="' + link + '" ><\/iframe>').dialog({
                title: "新增投稿者",
                autoOpen: true,
                width: 410,
                height: 490,
                modal: true,
                resizable: false,
                autoResize: false,
            });
        });

        $('.delete_data').click(function(){
            if( confirm("確定刪除該投稿者?") ){
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
<body class="home">
    <div id="container">
        <div id="wrapper">
        <?php include ("header_manage.php");?> 
                <div class="span10">
                    <div class="hero-unit" style="padding-top:50px;">
                        <div style="width:90%;margin:0 auto;">
                            <h2 class="page-name">投稿者</h2>
                            <button class="btn add_user">新增投稿者</button>
                        </div>
                        <center>
                            <table class="table" id="data">
                                <tbody>
                                <?php
                                    $userData = get_users_list(3);

                                    function check_empty( $str ){
                                        return (strcmp( $str, "" )!=0 ? $str : "-");
                                    }

                                    if ( count($userData) ) {
                                        foreach ( $userData as $key => $value ) { ?>
                                            <tr>
                                                <td class="personal_data">
                                                    <span class="small-col">姓名：<?php echo dop( check_empty( $value['name'] ) ); ?></span>
                                                    <span class="small-col">性別：<?php echo ( $value['sex'] ? "女" : "男" ); ?></span><br/>
                                                    <span class="big-col">E-mail：<?php echo dop( check_empty( $value['email'] ) ); ?></span>
                                                    <span class="small-col">電話：<?php echo dop( check_empty( $value['phone'] ) ); ?></span><br/>
                                                    <span class="big-col">服務單位：<?php echo dop( check_empty( $value['serve_unit'] ) ); ?></span><br/>
                                                    <span class="big-col">地址：<?php echo dop( check_empty( $value['address'] ) ); ?></span>
                                                </td>
                                                <td class="manage_func">
                                                    <button class="btn edit_data" value="<?php echo $value['id']; ?>">修改</button>
                                                    <button class="btn delete_data" value="<?php echo $value['id']; ?>">刪除</button>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else {
                                        echo '查無資料';
                                    }
                                ?>
                                </tbody>
                            </table>
                            <div id="res_msg"></div>
                        </center>
                        <div id="dialog"></div>
                    </div> <!-- .hero-unit -->
                </div> <!-- .span10 -->
                <div align='center'><?php include("../bar/end.php"); ?></div>
            </div><!-- .row-container -->
        </div><!-- .container-fluid -->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>

</body>
</html>
