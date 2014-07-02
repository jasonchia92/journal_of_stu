<?php require_once('../loader.php'); if ( check_login_status( false ) ) header('Location: home.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>申請帳號::樹德科技大學學報</title>
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
    <script type="text/javascript" src="bootstrap/js/buttons.js"></script>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script src="js/cmxforms.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('document').ready(function(){
            $("#commentForm").validate();
            $('#commentForm').submit(function(){
                var pwd_1 = $("#pwd_1").val();
                var pwd_2 = $("#pwd_2").val();
                if ( pwd_1 != pwd_2 ){
                    alert('兩次輸入密碼不一致，請重新輸入!');
                    return false;
                }
            });
        });
    </script>

    <style type="text/css">
        #commentForm {
           width: 100%;
           margin-left: 0;
       }
       #footer{
        background: #fff;
        position :  absolute; 
    }
    </style>
</head>


<body class="home" style="background: url(bootstrap/theme/images/slider/header_2.jpg) no-repeat;background-size: cover; padding:0;">
    <div class="container" style="width: 100%">
        <div class="signup_title">註冊帳號</div>
        <div class="login">
            <form method="post" action="signup_2.php" id="commentForm" class="cmxform">
               <div class="signupblock">
                    <input type="text" name="email" class="required id" placeholder="   電子郵件（即賬號）" style="color:#000;padding:2px;" /></span><br/>
                    <input type="password" name="pwd" id="pwd_1" class="required id" placeholder="   密碼" /></span><br/>
                    <input type="password" name="check_pwd" id="pwd_2" class="required password" placeholder="   重新輸入密碼" /></span><br/>
                </div>
                <input type="submit" class="button button-rounded button-flat-primary2" value="確認">
            </form>
        </div>
    </div><!--/.fluid-container-->


    <div style="background-color:#fff;text-align:center;margin-top:5%;"> 
    <?php include ("bar/end.php");?>           
    </div>
   
</body>
</html>