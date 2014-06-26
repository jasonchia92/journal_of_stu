<?php require_once('../loader.php'); if ( check_login_status( false ) ) redirect(); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>線上投稿審稿系統-登入::樹德科技大學學報</title>
<!-- Le styles -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="bootstrap/css/slide.css" rel="stylesheet">
<link href="bootstrap/css/half-slide.css" rel="stylesheet">
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="bar/style.css" rel="stylesheet" type="text/css"/>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="bootstrap/css/buttons.css">
<link rel="stylesheet" href="bootstrap/theme/css/style.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo JS_URL; ?>/js_spare/jquery.min.js"></script>
</head>

<body class="home" style="background: url(bootstrap/theme/images/slider/header_1.jpg) no-repeat;background-size: cover; padding:0;">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $('#login input[name="account"]').focus();
        });
    </script>

    <div class="container" style="width: 100%">
        <div class="login_title">系統登入</div>
        <div class="login">
            <form action="login2.php" method="post">
                <div class="loginblock">
                    <input type="text" name="account" placeholder="    賬號" class="id" style="color:#000">
                    <p>
                    <input type="password" name="password" placeholder="   密碼" class="password"> 
                </div>

                 <?php if(isset($_GET['error'])){ ?>
                    <div class="row">                   
                        <div class="error_wram">*帳號或密碼錯誤</div> 
                    </div>
                <?php } ?>

                <div>
                    <input type="submit" class="button button-rounded button-flat-primary2" value="確認">
                </div>
            </form>
        </div> 

    </div>

    <div style="background-color:#fff;text-align:center;margin-top:5%;"> 
    <?php include ("bar/end.php");?>           
    </div>
<!--/.fluid-container-->
</body>
</html>