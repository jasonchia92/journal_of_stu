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

       .publish_history{
            padding-top: 0;
        }

        .book{
            margin: 10px;
        }

        #footer{
            position: relative;
        }


        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .publish_history {
                padding: 0;
            }
            .bio{
                background-color: #34495e;
                height: 200px;
            }
            #services{
                padding: 100px 0;
            }

            }
          }


       .notice-block:hover {
        color: #1bbc9b; 
    }
    </style>

</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
           <?php include ("bar/header.php");?> 

            <div id="services" >
                <div class="one_half clearfix" style="display:table;">
                    <?php get_sidebarLeft(); ?>
                </div> <!-- end of .one_half -->

                <div class="rightSideBorder">
                    <div class="bio last">
                        <li>
                            <div class="rightSideBorder_title">Announcement</div>
                        </li>
                        <?php
                        $notices = get_notice();
                        foreach ($notices as $key => $value) {
                            ?>
                            <li>
                                <a class="notice-block" href="view_notice.php?notice_id=<?php echo $value['notice_id']; ?>">
                                    <span class="notice-date"><?php echo "[".$value['date']."]" ?></span>

                                    <span class="notice-title">
                                        <?php echo $value['title'] ?>
                                    </span>
                                </a>
                            </li>
                            <?php
                        }                          
                        ?>
                    </div><!-- end of .bio last -->
                </div> <!-- end of .announce -->
            </div> <!-- end of services -->

            <div align='center'>
                <?php include ("bar/end.php");?>
            </div>
        </div><!-- wrapper -->
    </div><!-- container -->


    <script type="text/javascript" src="bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="bootstrap/theme/js/headerWithSlider.js"></script>
   
</body>
</html>


