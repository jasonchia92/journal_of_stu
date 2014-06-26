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
      body {
        padding-top: 15px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }

      .navbar .nav{
        text-align: center;
        display: table-cell;
        float: none;
      }
      .table_right{
        margin-left: 5%;
      }
      .tab{
        margin-left: -15%;
      }
    </style>
</head>
<body class="home">
     <div id="container">
        <div id="wrapper">
            <?php include ("../bar/header_person.php");?> 
            <div class="span9">
                <div class="hero-unit">
                  <h3 style="margin-left:1%;">更新個人資料</h3>
                  <center>
                    <?php
                        $auth_data = get_auth_data();
                          $result = get_user_data( ( $auth_data['id']) );
                    ?>
                      <form action="update_personal2.php" method="post" class="table_right">
                        <table border="0" class="tab">
                          <tr>
                            <td style="text-align:center;">id：</td>
                            <td style="width:200px;"><?php echo dop($result["id"]);?></td>
                            <?php $id = dop($result['id']); ?>
                            <input type="hidden" name="id" value="$id">
                          </tr>
                          <tr>
                            <td style="text-align:center;">email：</td>
                            <td><?php echo dop($result["email"]); ?></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">性別：</td>
                            <?php 
                              if($result["sex"] == 1)
                                $result["sex"] = "男";                          
                              else
                                $result["sex"] = "女";                        
                            ?>
                            <td><?php echo dop($result["sex"]);?></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">姓名：</td>
                            <td><input type="text" name="name" value="<?php echo dop($result["name"]); ?>"></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">職稱：</td>
                            <td><input type="text" name="titles" value="<?php echo dop($result["titles"]); ?>"></td>
                          <tr>
                            <td style="text-align:center;">服務單位：</td>                        
                            <td><input type="text" name="serve_unit" value="<?php echo dop($result["serve_unit"]); ?>" size="36"></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">電話：</td>
                            <td><input type="text" name="phone" value="<?php echo dop($result["phone"]); ?>"></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">傳真：</td>
                            <td><input type="text" name="fax" value="<?php echo dop($result["fax"]); ?>"></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">地址：</td>
                            <td><input type="text" name="address" value="<?php echo dop($result["address"]); ?>" size="36"></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">郵遞區號：</td>
                            <td><input type="text" name="postcodes" value="<?php echo dop($result["postcodes"]); ?>"></td>
                          </tr>
                          <tr>
                            <td style="text-align:center;">國家：</td>                        
                            <td><input type="text" name="country" value="<?php echo dop($result["country"]); ?>"></td>
                          </tr>
                        </table>
                        <center><button type="submit" class="btn" style="margin-top:20px;">確認</button></center>
                    </form>                    
                  </center>
                </div>
            </div>
          </div>
        </div>
        <div align='center'>
            <?php include ("../bar/end.php");?>
        </div>
    </div><!--/.fluid-container-->
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
  </body>
</html>
