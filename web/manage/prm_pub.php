<!-- Paper Review Manage - Paper publishs -->
<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>可出刊的論文::論文審查管理系統 - 樹德科技大學學報</title>
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

        #data {
            margin-top: 20px;
        }

        #data table {
            width: 850px;
            margin: 0 auto;
        }

        .table th,
        .table .pid,
        .table .category {
            text-align: center
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

        .view_paper_data {
            cursor: pointer;
            color: #00C;
        }

        .view_paper_data:hover {
            color: #FF9000;
        }

        #dialog {
            display: none;
        }

        .table{
            width:880px;
        }

        .paper_title {
            display: inline-block;
            width: 460px;
            text-align: left;
            overflow : hidden;
            text-overflow : ellipsis;
            white-space : nowrap;
        }

        #category_select{
            margin-top: 50px;
            text-align: center;
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
<script type="text/javascript">
    $('document').ready(function(){
        $('.view_paper_data').click(function(e) {
            e.preventDefault();
            var $this = $(this);
            var link = "./paper_data.php?id=" + $(this).attr('title');
            $('#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=800 height=600 src="' + link + '" ><\/iframe>').dialog({
                title: "論文資料",
                autoOpen: true,
                width: 860,
                height: 'auto',
                modal: true,
                resizable: false,
                autoResize: false,
            });
        });
    });
</script>
<body class="home">
        <div id="container">
            <div id="wrapper">
            <?php include ("header_manage.php");?> 
            <div class="span10">
                    <div class="hero-unit">
                        <div id="category_select">
                            請選擇您要查詢的類別<a href="<?php echo basename(__FILE__); ?>" style="margin-left:50px; color:blue;">顯示全部</a>
                            <form action="" method="get">
                                <input type="checkbox" name="category[]" value="1" />&nbsp;管理&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="category[]" value="2" />&nbsp;資訊&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="category[]" value="3" />&nbsp;設計&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="category[]" value="4" />&nbsp;幼保.性學.外文&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" name="category[]" value="5" />&nbsp;通識教育&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="submit" class="btn" style="display:inline;width:100px;font-size:15px;padding:5px;" />查詢</button>
                                <button type="reset" class="btn" style="display:inline;width:100px;font-size:15px;padding:5px;" />重設</button>
                            </form>
                        </div>
                        <div id="data">
                            <?php
                                $sql = "SELECT
                                            a.*,
                                            b.ch_name
                                        FROM papers AS a LEFT JOIN authors_data AS b ON
                                            a.id=b.paper_id
                                        WHERE
                                            a.status='8'
                                            AND a.final_result='1'
                                            AND b.author_type='1' ";

                                if ( isset($_GET['category']) ) {
                                    # array data convert to string
                                    $category_str = acts($_GET['category'], 1);
                                    $sql .= "AND a.category IN (".$category_str.") ";
                                }

                                $result = sql_q_adv( $sql );
                                $data = $result->data;

                                if ( count($data) > 0 ) {
                                    ?>
                                    <table class="table">
                                        <thead><tr>
                                            <th style="width:115px;">ID</th>
                                            <th style="width:120px;">類別</th>
                                            <th>標題</th>
                                        </tr></thead>
                                        <tbody>
                                    <?php

                                    $categoryName_array = array(
                                        "未分類",
                                        "管理",
                                        "資訊",
                                        "設計",
                                        "幼保.性學.外文",
                                        "通識教育"
                                    );

                                    foreach ($data as $key => $value) {
                                        ?>
                                        <tr>
                                            <td class="pid"><span class="view_paper_data" title="<?php echo get_sys_pid($value['id'], 2); ?>"><?php echo $value['language'].'-'.$value['id']; ?></span></td>
                                            <td class="category"><?php echo $categoryName_array[ $value['category'] ]; ?></td>
                                            <td>
                                                <span class="paper_title">
                                                    <?php echo dop( $value['ch_title'] ); ?><br/>
                                                    <?php echo dop( $value['en_title'] ); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    echo "</tbody></table>";
                                } else {
                                    echo "<div style='font-size:20px;text-align:center;'>查無資料</div>";
                                }
                            ?>
                        </div>
                        <?php $result->action_show_page_numbers(); ?>
                        <div id="dialog"></div>
                    </div><!-- end hero-unit -->
                </div><!-- end span10 -->
            </div><!-- end row-fluid -->
            <div align='center'>
                <?php include ("../bar/end.php");?>
            </div>
        </div><!-- end container-fluid -->
    </div>
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>
