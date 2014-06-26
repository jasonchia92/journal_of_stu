<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>更新個人資料</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet">   

    <script type="text/javascript" src="../bootstrap/theme/js/jquery-1.10.2.min.js"></script>
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
      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }

      h2{
        font-size: 30px;
        padding: 10px;
      }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#ch3').click(function(){
                var ch1 = $('#ch1').val()
                var ch2 = $('#ch2').val()
                if(ch1 == ch2){
                    $('#ch3').submit();
                }
                else{
                    alert('新密碼再次輸入有誤!!');
                    return false;
                }
            });
        });
    </script>
</head>
   <body class="home">
     <div id="container">
        <div id="wrapper">
            <?php include ("../bar/header_person.php");?> 
	        <div class="span9" style="text-align:center;">
	            <div class="hero-unit">
	                <h2 style="padding-bottom:30px;">更改密碼</h2>
	               <?php
						if ( check_login_status() ) {
							$id = $_POST['uid'];
							$old_pwd = $_POST['old_pwd'];
							$new_pwd = $_POST['new_pwd'];
							$result = change_pwd( $id, $old_pwd, $new_pwd );
							
							if($result){							
								echo '密碼更改成功';
							}
							else{
								echo '輸入資料有誤 , 請仔細輸入';
							}
						}
					?>
	            </div>
	        </div>
	    </div>
	   <div class="foot">
            <?php include ("../bar/end.php");?>
    </div>
    </div><!--/.fluid-container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>
