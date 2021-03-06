<!-- Paper Review Manage - Paper Proofreading -->
<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>論文校稿::學報管理系統 - 樹德科技大學學報</title>
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


    
    <script type="text/javascript">
        $('document').ready(function(){
            $('.edit_data').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./paper_proofreading.php?pid=" + $(this).html();
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=830 height=530 src="' + link + '" ><\/iframe>').dialog({
                    title: "論文校稿",
                    autoOpen: true,
                    width: 850,
                    height: 584,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
            });

            $('button.delete_data').click(function(){
                if( confirm("確定刪除?") ){
                    $.ajax({
                        url: '<?php echo ROOT_URL."/web/ajax_request_handler.php"; ?>',
                        type: 'post',
                        data: {
                            'action': 'paper_delete',
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

        table#data {
            width: 880px;
        }

        .abc {
            width: 100px;
        }

        div.paper_summary {
            text-align: left;
        }

        span.file_link {
            cursor: pointer;
            color: #31E;
            font-size: 12px;
        }

        span.file_link:hover {
            color: #39D;
        }

        .button {
            font:bold 16px Century Gothic, sans-serif;
            font-style:normal;
            color:#eeeeee;
            background:#9ea86b;
            border:1px solid #d5dbb6;
            text-shadow:0px -1px 1px #084d0c;
            box-shadow:0px 0px 3px #252b01;
            -moz-box-shadow:0px 0px 7px #252b01;
            -webkit-box-shadow:0px 0px 7px #252b01;
            border-radius:10px;
            -moz-border-radius:5px;
            -webkit-border-radius:20px;
            width:60px;
            padding:1px 0px;
            margin: 3px 0px;
            cursor:pointer;
        }

        .button:hover {
            background: #d5dbb6;
        }

        .button:active {
            cursor:pointer;
            position:relative;
            top:2px;
        }

        .hyper-link {
            cursor: pointer;
            color: #31E;
        }

        .hyper-link:hover {
            color: #39D;
        }

        .paper_title {
            display: inline-block;
            width: 460px;
            text-align: left;
            overflow : hidden;
            text-overflow : ellipsis;
            white-space : nowrap;
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
  <body class="home">
        <div id="container">
            <div id="wrapper">
            <?php include ("header_manage.php");?> 
            <div class="span10">
                <div class="hero-unit" style="padding-top:50px;">
                   <center>
                    <?php
                        $sql = "SELECT
                                id,
                                language,
                                ch_title,
                                en_title,
                                category
                            FROM papers WHERE
                                status IN ('3', '7')
                            ORDER BY id DESC ";
                        
                        $result = sql_q_adv( $sql );
                        $paper_data = $result->data;

                        if ( count($paper_data) ){
                            ?>
                            <table class="table" id="data">
                                <thead>
                                    <tr>
                                        <th style="width:115px;">論文ID</th>
                                        <th style="width:120px;">類別</th>
                                        <th>論文標題</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            $categoryName_array = array("未分類", "管理", "資訊", "設計", "幼保.性學.外文", "通識教育");
                            foreach( $paper_data as $key => $value ){
                                $sys_id = get_sys_pid($value['id']);
                                ?>
                                    <tr>
                                        <td><span class="edit_data hyper-link"><?php echo $value['language'].'-'.$value['id']; ?></span></td>
                                        <td><?php echo $categoryName_array[ $value['category'] ]; ?></td>
                                        <td>
                                            <span class="paper_title">
                                                <?php echo dop( $value['ch_title'] ); ?><br/>
                                                <?php echo dop( $value['en_title'] ); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php
                            }
                            ?>
                                </tbody></table>
                            <?php
                                $result->action_show_page_numbers();
                        } else {
                            echo "<div style='font-size:20px;text-align:center;'>目前尚無論文需要校稿</div>";
                        }
                    ?>
                    <div id="dialog"></div>
                    </center>
                </div>
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
