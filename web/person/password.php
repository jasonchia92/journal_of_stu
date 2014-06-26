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
        .required{
            width: 450px;
            height: 25px;
        }

        input{
            border: 1px solid black;
            margin-top: 5px;

        }
        h2{
            font-size: 30px;
            text-align: center;
        }
        #commentForm{
            width: 100%;   
            margin-left: 0px;
            text-align: center;
        }
    </style>
    <script src="../js/jquery.validate.js" type="text/javascript"></script>
    <script src="../js/cmxforms.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#commentForm").validate();
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
	        <div class="span9">
	            <div class="hero-unit">
	                <h2 style="padding:20px;">更改密碼</h2>
	                <?php
	                  $auth_data = get_auth_data();
	                  $id = $auth_data['id'];
	                  if( isset($_COOKIE['jos_user_pms']) ){
	                      $pri = get_user_data( $id );
	                      $pri_name = $pri['name'];
	                      $pri_id = $pri['id'];
	                    /*  echo "<form action='' method='post' id='commentForm'>";
	                      echo "<input type=text name=mid size=30 value=$id >";
	                      echo "新的密碼 : <input type=text name=pri_name size=30  class=required /><br />";
	                      echo "再次輸入 : <input type=password name=ch_pwd size=30 class=required /><br />";
	                      echo "<input type=submit value='送出' style='margin-left:20%;' class='cssbtn' >";
	                      echo "</form>";
	                      */
	                      echo "管理系統";
	                  }
	                  else{
	                    echo "<form action='password2.php' method='post' id='commentForm'>";    
	                    echo "<input type=hidden name=uid value='$id' />";
	                    echo "舊的密碼 : <input type=password name=old_pwd size=30 class=required /><br />";
	                    echo "新的密碼 : <input type=password name=new_pwd size=30 class=required id=ch1 /><br />";
	                    echo "再次輸入 : <input type=password size=30 class=required id=ch2 /><br />";
	                    echo "<button type='submit' style='margin-top:30px;' class='btn' id=ch3 >送出</button>";
	                  //  echo "<a class=cssbtn id=ch3>送出</a>";
	                    echo "</form>";
	                  }
	                  
	                ?>
	            </div>
	        </div>
	    </div>
	  </div>
    <div class="foot">
        <?php include ("../bar/end.php");?>
    </div>
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>
