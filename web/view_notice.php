<?php require_once("../loader.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>樹德科技大學學報</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet">   

    <script type="text/javascript" src="bootstrap/theme/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/jQuery.appear.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/superfish.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/easypiechart.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/canvas.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/niceScroll.js"></script>
    
    <script src="bootstrap/theme/js/jquery.lazyload.js"></script>
    <script src="bootstrap/theme/js/least.min.js"></script>

    <script type="text/javascript">
        document.documentElement.className = 'js';
    </script>
    <style type="text/css">
         a.notice-block {
           display: block;
           background-color: #34495e;
           cursor: pointer;
           color: #FFFFFF;
           font-size: 15px;
           padding: 3px 30px;
           text-decoration: none;
       }

       .notice-block:hover {
        color: #1bbc9b; 
        }

        .hero-unit {
            padding: 20px 60px 60px 40px;
            background-color: #FFF;
            max-height: 550px;
            border-radius: 15px;
            font-size: 20px;
        }

        .hero-unit h2{
            font-size: 35px;
            border-bottom: 1px solid black;
            padding-bottom: 15px;
        }

        .notice-title{
            font-size: 35px;
            padding: 15px;
            text-indent: 20px;

        }

        .notice-content{
            font-size: 20px;
            padding: 0;
            text-indent: 80px;
        }
    </style>
</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
           <?php include ("bar/header.php");?> 
                <div class="span9">
                    <div class="hero-unit">
                        <h2 style="margin-bottom:10px;">最新消息</h2>
                        <a href="index.php" style="position:relative; float:right; top:-45px;">←返回</a>
                        <?php $notice = get_notice( $_GET['notice_id'] ); ?>
                        <div>
                            <div class="notice-title"><?php echo dop( $notice['title'] ); ?></div>
                            <div class="notice-content"><?php echo cke_output($notice['content']); ?></div>
                        </div>
                        <a href="#" style="float:right; top:-70px; right:40px;font-size:15px;">↑Go to top</a>
                    </div>
                </div>
            </div><!--/.span9 -->
             <div align='center'>
            <?php include ("bar/end.php");?>
        </div>
        </div><!--/.container-fluid -->
    <script type="text/javascript" src="bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>


