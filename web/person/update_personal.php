<?php
  require_once('../../loader.php');
  check_login_status();

  require_once( ROOT_DIR . '/web/person/update_personal_process.php' );
?>
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
      .tab{
        margin-left: -15%;
      }
      input{
        margin: 0;
      }
      .table_right tr{
        line-height: 30px;
      }
      h3{
        font-size: 30px;
      }
    </style>
</head>
<body class="home">
     <div id="container">
        <div id="wrapper">
            <?php include ("../bar/header_person.php");?> 
            <div class="span9">
            <div class="hero-unit">
              <h3 style="text-align:center;padding:20px;">更新個人資料</h3>
              <center>
                <?php
                    $auth_data = get_auth_data();
                      $result = get_user_data( ( $auth_data['id']) );
                      echo "<form action='update_personal.php' method='post' class='table_right'>";
                      echo "<table border=0 class='tab'>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>id：</td>";
                      echo "<td style='width:200px;'>".dop($result["id"])."</td>";
                        $id = dop($result['id']);
                      echo "<input type='hidden' name='id' value='$id'>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>email：</td>";
                      echo "<td>".dop($result["email"])."</td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>性別：</td>";
                        if($result["sex"] == 0){
                          $result["sex"] = "男";
                        }
                        else{
                          $result["sex"] = "女";
                        }
                      echo "<td>".dop($result["sex"])."</td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>姓名：</td>";
                        $na = dop($result["name"]);
                      echo "<td><input type='text' name='name' value='$na'></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>職稱：</td>";
                        $ti = dop($result["titles"]);
                      echo "<td><input type='text' name='titles' value='$ti'></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>服務單位：</td>";
                        $serve_unit = dop($result["serve_unit"]);
                      echo "<td><input type='text' name='serve_unit' value='$serve_unit' size='36' ></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>電話：</td>";
                        $phone = dop($result["phone"]);
                      echo "<td><input type='text' name='phone' value='$phone' ></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>傳真：</td>";
                        $fax = dop($result["fax"]);
                      echo "<td><input type='text' name='fax' value='$fax' ></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>地址：</td>";
                        $address = dop($result["address"]);
                      echo "<td><input type='text' name='address' value='$address' size='36'></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>郵遞區號：</td>";
                        $postcodes = dop($result["postcodes"]);
                      echo "<td><input type='text' name='postcodes' value='$postcodes' ></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td style='text-align:center;'>國家：</td>";
                        $country = dop($result["country"]);
                      echo "<td><input type='text' name='country' value='$country' ></td>";
                      echo "</tr>";
                      echo "<tr>";
                      echo "<td></td>";
                      echo "</tr>";
                      echo "</table>";
                      echo "<center><button type='submit' class='btn' style='margin-top:20px;'>確認</button></center>";                      
                      
                      echo "</form>";
                    ?>
                  </center>
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
