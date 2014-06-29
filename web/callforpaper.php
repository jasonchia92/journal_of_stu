<?php require_once('../loader.php'); ?>
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

    <style type="text/css">
        .rightSideBorder {
           background-color: #ccad3b;
       }

       @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .publish_history {
                padding: 0;
            }
            .bio{
                background-color: #ccad3b;
                height: auto;
                padding: 10px;
            }

            .rightSideBorder{
                
                height: auto;
            }
            #services{
                padding: 100px 0;
            }

            }
          }

    }
    </style>

</head>

</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
           <?php include ("bar/header.php");?> 
                <div id="services" >
                    <div class="one_half clearfix">
                        <?php get_sidebarLeft(); ?>
                    </div> <!-- end of .one_half -->

                    <div class="rightSideBorder">
                        <div class="bio last">
                            <div class="rightSideBorder_title rightSideBorder">徵稿辦法與規定文件</div>
                            <div class="rulesAndFiles rightSideBorder">
                                <a target="_blank" href="file/附件1-著作權讓與同意書.doc">1.著作權讓與同意書</a><br/>
                                <a target="_blank" href="file/附件2-學報投稿資料表.doc">2.學報投稿資料表</a><br/>
                                <a target="_blank" href="file/附件3-學報投稿稿件回條資料表.doc">3.學報投稿稿件回條資料表</a><br/>
                                <a target="_blank" href="file/附件4-樹德科技大學學報稿件格式.pdf">4.樹德科技大學學報稿件格式</a><br/>
                                <a target="_blank" href="file/稿件校正表.doc">5.稿件校正表</a><br/>
                                <a target="_blank" href="file/學報發行辦法.pdf">6.學報發行辦法</a><br/>
                            </div>
                        </div>
                    </div>
                </div><!-- end of .services -->
        </div><!-- end of .wrapper -->


        <div align='center'>
            <?php include ("bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->

    <script type="text/javascript" src="bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/headerWithSlider.js"></script>
    <script>
        $(document).ready(function(){
            $('#gallery').least({
                random: 0,
            });
        });

        $(function() {
            $('.chart').easyPieChart({
                barColor: 'rgba(255,255,255,0.8)',
                trackColor: 'rgba(0,0,0,0.5)',
                scaleColor: false,
                lineWidth: 5,
                size: 80
            });
        });
    </script>
</body>
</html>