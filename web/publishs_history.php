<?php require_once('../loader.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>卷期查詢::樹德科技大學學報</title>
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
    <style type="text/css">
        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            #recent-work{
                padding-top: 50px;
            }

          }

    </style>
</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
           <?php include ("bar/header.php");?> 
            <section id="recent-work">
            <ul id="gallery">
                        <?php
                            $get_publishs = get_publishs();
                            if ( $get_publishs != false ) {
                                $publish_data = $get_publishs->data;
                                foreach ($publish_data as $value) {
                                    $img_path = ROOT_DIR.'/web/publish/'.$value['file_cover'];
                                    if ( strcmp(PHP_OS, 'WINNT') == 0 )
                                        $img_path = iconv('utf-8', 'big5//ignore', $img_path);

                                    if ( !file_exists($img_path) )
                                        $img_path = 'images/'.urlencode('image-not-found.jpg');
                                    else
                                        $img_path = 'publish/'.urlencode($value['file_cover']);
                                    ?>
                                    <li>
                                        <a href="publish/<?php echo urlencode($value['file_pdf']);?>" target="_blank"></a>
                                        <img src="<?php echo $img_path; ?>" alt="aticle-image" width="200" height="250" style="display: inline">
                                        <div class="overLayer"></div>
                                        <div class="infoLayer">

                                            <ul>
                                                <li>
                                                    <h2>
                                                        <?php echo dop($value['title']); ?>
                                                    </h2>
                                                </li>
                                                <li>
                                                    <p>
                                                        View Files
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>
                                    </li><!-- end li -->
                                    <?php
                                } // end foreach
                            } else { ?>
                                <span style="font-size:14px;">未登錄任何期刊資料於系統</span>
                            <?php } // end if
                        ?>
                    <div class="page_numbers_block"><?php $get_publishs->action_show_page_numbers(); ?></div>
                </div>
            </ul>
        </section>
        </div> <!-- end of wrapper -->


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

