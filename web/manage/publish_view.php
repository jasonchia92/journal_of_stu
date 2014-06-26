<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>學報管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../bar/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../js/js_spare/jquery-ui.css" media="all" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <style type="text/css">
        body {
            padding-top: 15px;
            padding-bottom: 40px;
            color:black;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#next').click(function(){
                var input = $("input:file").val();

                if( ($('#title').val() != "") & ($('#file').val() != "") &(input.match(".pdf") != null) )
                    $('#pfrom').submit();
                else if($('#title').val() == "")
                    $('.warm').show();       
                else if( $('#file').val() == "" )
                    $('.warm2').show();
                else if( (input.match(".pdf") == null) )
                    $('.warm3').show();        

            });

            $('button#del').click(function(){
                if( confirm("確定要刪除該檔案?") ){
                    $.ajax({
                        url: 'publish_delete.php',
                        type: 'post',
                        data: {
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

            $('button#modify').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./publish_modify.php?id=" + $(this).attr('value');
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=450 height=400 src="' + link + '" ><\/iframe>').dialog({
                    title: "修改資料",
                    autoOpen: true,
                    width: 450,
                    height: 400,
                    modal: true,
                    resizable: false,
                    autoResize: false,
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
                <div class="span3">
                    <div class="well sidebar-nav">
                        <ul class="nav nav-list">
                            <?php include ("menu_left.php");?>
                        </ul>
                    </div><!--/.well -->
                </div><!--/span-->
                <div class="span9">
                    <div class="hero-unit">
                        <h3>卷期查詢管理</h3>
                        <?php
                            $key = sql_q("SELECT * FROM publish",array());
                            if(count($key) > 0){
                                echo "<table class='table table-striped'>";
                                echo "<thead><td>卷期標題</td><td>功能</td></thead>";
                                foreach ($key as $value) {
                                    echo "<tr><td>"."<a href='../file/".$value['upload_file']."''>".$value['title']."</a>"."</td>";
                                    echo "<td><button id='modify' class='btn btn-primary' value='{$value['id']}'>修改</button>";
                                    echo "<button id='del' class='btn btn-danger' value='{$value['id']}'>刪除</button></td></tr>";
                                }
                                echo "</table>";
                            }
                            else
                                echo "目前暫無檔案";
                        ?>
                        <div id="dialog"></div>
                    </div> <!-- .hero-unit -->
                </div><!-- .span9 -->
            </div>
        </div><!-- .container-fluid -->
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.container-->
</body>
</html>
