    <?php require_once('../../loader.php'); check_login_status(); ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>查詢進度</title>
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
        .navbar .nav{
          width: 700px;
        }

        .navbar .container {
          margin-top: 0px ;
        }

        .button-jumbo{
          box-shadow:0px 0px 0px ;
        }

        .container{
          width: 100%;
        }

        
        a:hover{
          color:#1bbc9b;
        }

        .span2 li{
          padding: 10px 20px;
        }
        .table{
          width: 90%;
          margin: 0 auto;
        }
        .table a{
          color: #000;
        }
        .table a:hover{
          color: #16a085;
        }

        td, th {
        padding: 15px;
        border: 1px solid #ccc;
        text-align: center;
        color: #000;
        }

        th {
          background: lightblue;
          border-color: white;
        }

      </style>
    </head>
    <body class="home">
          <div id="container">
                <div id="wrapper">
                 <?php include ("../bar/header_contribute.php");?> 
                     <div class="span10">
                            <div class="hero-unit">
                                <?php
                                $auth_data = get_auth_data();
                                $paper_data = sql_q("SELECT * FROM papers WHERE submit_user=? ORDER BY id DESC ",array($auth_data['id']));
                                $status_arr = array(
                                  "稿件預審",
                                  "審稿中",
                                  "第一階段審稿完成",
                                  "審稿完畢",
                                  "退件",
                                  "等待第二階段審稿",
                                  "第二階段審稿中",
                                  "校稿中",
                                  );
                                $final_result = array("出刊", "退件");

                                if ( count($paper_data) ) {
                                  ?>
                                  <table class="table" id="paper-list">
                                    <thead>
                                      <tr>
                                        <th style="width:115px;">論文ID</th>
                                        <th>論文名稱(中/英)</th>
                                        <th>狀態</th>
                                        <!--    <th style="min-width:50px; max-width:150px;">備註</th>  -->
                                      </tr>
                                    </thead><tbody>
                                    <?php
                                    $col_comment = array(
                                      5 => '請修改論文內容，修改完成後將繼續進行審稿作業。<br/>',
                                      7 => '請修改論文內容，以利後續流程作業。',
                                      );

                                    foreach ( $paper_data as $value ) {
                                      ?>
                                      <tr>
                                        <td>
                                          <a href="check_paper2.php?pid=<?php echo $value['id']; ?>"><?php echo $value['language'].'-'.$value['id']; ?></a>
                                        </td>
                                        <td>
                                          <span class="paper_title"><?php echo dop($value['ch_title']); ?></span><br/>
                                          <span class="paper_title"><?php echo dop($value['en_title']); ?></span><br/>
                                          <?php
                                          if ( $value['smu_edit_enable'] || $value['craa_upload'] ) {
                                            ?>     
                                            ────────────────────────────────────────────<br/>
                                            <div class="tab_inside">                      
                                              <span>請修改論文內容，修改完成後將繼續進行學報各項相關作業程序。</span>
                                              <a href="correction.php?pid=<?php echo $value['id']; ?>">點我修改論文</a>
                                            </div>
                                            <?php
                                          }
                                          ?>
                                        </td>
                                        <td class="paper-status"><?php echo $value['status']==8 ? $final_result[ $value['final_result']-1 ] : $status_arr[ $value['status'] ]; ?></td>
                                        
                                      </tr>
                                      <?php
                                    }
                                    ?>
                                  </tbody></table>
                                  <?php
                                } else {
                                  echo "查無資料";
                                }
                                ?>
                            </div>
                    </div> <!-- span10 -->
              </div><!--  wrapper -->
        </div>
        <div class="foot">
          <?php include ("../bar/end.php");?>
        </div>
        </div><!--/.fluid-container-->
        <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
        <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
    </body>
    </html>
