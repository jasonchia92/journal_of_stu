<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>瀏覽維護論文資料::論文管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
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
            width: 850px;
        }

        .table th,
        .table .pid,
        .table .category {
            text-align: center
        }

        .paper_title {
            display: inline-block;
            width: 520px;
            text-align: left;
            overflow : hidden;
            text-overflow : ellipsis;
            white-space : nowrap;
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
    </style>
</head>
    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $('.edit_data').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./paper_data_edit.php?pid=" + $(this).html();
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=830 height=530 src="' + link + '" ><\/iframe>').dialog({
                    title: "修改論文資料",
                    autoOpen: true,
                    width: 860,
                    height: 590,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
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
                    <table id="data" class="table">
                        <thead>
                            <tr>
                                <th style="width:115px;">ID</th>
                                <th style="width:120px;">類別</th>
                                <th>標題</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql = "SELECT
                                    a.*,
                                    b.ch_name,
                                    b.ch_serve_unit
                                FROM papers AS a LEFT JOIN authors_data AS b ON
                                    a.id=b.paper_id
                                WHERE
                                    a.status<7
                                    AND b.author_type=1
                                ORDER BY a.id DESC ";

                            $result = sql_q_adv( $sql );
                            $paper_data = $result->data;

                            $category = array("未分類", "管理", "資訊", "設計", "幼保.性學.外文", "通識教育");
                            foreach( $paper_data as $key => $value ){
                                $sys_id = $value['id'];
                                ?>
                                <tr>
                                    <td class="pid"><span class="edit_data hyper-link"><?php echo $value['language'].'-'.$value['id']; ?></span></td>
                                    <td class="category"><?php echo $category[ $value['category'] ]; ?></td>
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
                        </tbody>
                    </table>
                    <?php $result->action_show_page_numbers(); ?>
                    <div id="dialog"></div>
                </div>
            </div>
        </div>
  </div>
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
  </body>
</html>
