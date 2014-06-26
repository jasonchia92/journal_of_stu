<?php require_once('../loader.php'); if ( check_login_status(false ) )  ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>意見回應::樹德科技大學學報</title>
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
    

</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
           <?php include ("bar/header.php");?> 
            <div id="fr_contact_form">
                <div class="sizers clearfix">
                    <div class="section_description">
                        <h3 class="section_name">意見調查</h3><!--NAME OF SECTION-->
                    </div> <!-- end of .section_description -->
                    <form action="opinion2.php" method="POST" id="commentForm">
                        <div class="one_half">
                            <p class="contact_page name"><input type="text" name="keynote" class="required" placeholder="主旨"/></p>
                            <p class="contact_page name"><input type="text" name="name" class="required" placeholder="姓名"/></p>
                            <p class="contact_page name"><input type="text" name="email" class="required" placeholder="電子郵件"/></p>
                            <p class="contact_page name"><input type="text" name="number" class="required" placeholder="稿件序號"/> </p>
                            <p style="font-size:12px; color:#FF0000;">注意 : 若您針對某一篇文章，請包含稿件序號或篇名</p>
                            <span class="col-name" style="font-size:14px;">身分：</span>
                            <span class="col-content">
                                <ul>
                                    <li><input type="radio" name="identity" value="0" >作者</li>
                                    <li><input type="radio" name="identity" value="1">評閱者</li>
                                    <li><input type="radio" name="identity" value="2">編輯委員</li>
                                    <li><input type="radio" name="identity" value="3">其它</li>
                                </ul>
                            </span><br/>
                        </div>

                        <div class="one_half last">
                            <textarea class="input" id="contact_message" name="content" placeholder="意見"></textarea>
                        </div>
                        
                        <p class="clearfix"><input type="reset" id="contact_reset" value="Reset" />
                        <input id="contact_submit" class="contact_submit submit" type="submit" value="Send"/></p>
                    </form>
                </div> <!-- end of .sizers -->
            </div>

            <div align='center'>
                <?php include ("bar/end.php");?>
            </div>
        </div><!--  end of wrapper -->
    </div> <!-- end of container -->
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