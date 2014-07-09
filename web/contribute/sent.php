<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>測試頁面</title>
    <!-- Le styles -->
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet">   

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jQuery.appear.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/superfish.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/easypiechart.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/canvas.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/niceScroll.js"></script>

   <script src="../bootstrap/theme/js/jquery.lazyload.js"></script>
   <script src="../bootstrap/theme/js/least.min.js"></script>

  <script type="text/javascript" src="../js/multiple-file-upload/jquery.MultiFile.js"></script>
  <script src="../js/jquery.validate.js" type="text/javascript"></script>
  <script src="../js/cmxforms.js" type="text/javascript"></script>
    <style type="text/css">
        @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }

    #n1,#n2, #n3, #n4{
      display:none;

    }
    #commentForm{
      width: 100%;
      margin: 0px;
    }
    #n0_2{
      display:none;
    }
    input{
      display: inline-block;
    }
    span{
      padding: 5px;
    }

    #keyword,#en_check,#ch_check,#ch_s,#en_s,#primary_author,#other_author,#ch_author, .up_lock, .check0, .check0_2 {
      display:none;
      color:red;
    }

    label.error {
      color: #F00;
      font-size: 12px;
    }

    div.paper_block {
     　border: 0px dashed #888;
      border-top-width: 2px;
      padding-bottom: 10px;
    }

    div.paper_block table th, div.paper_block table td {
      padding: 0px 5px;
    }

    div.authors_block table td.align_left {
      text-align: left;
    }

    table.else_authors {
      border: 0px dotted #000;
      border-top-width: 2px;
    }

    table#current_file_list th, table#current_file_list td {
      border: 1px solid #555;
    }

    table#current_file_list td.file_link {
      cursor: pointer;
      color: #31E;
    }

    table#current_file_list td.file_link:hover {
      color: #39D;
    }

    .span5 input{
      margin-bottom: 0;
      padding: 5px;
    }

    .row{
      margin: 15px;
    }

    .adt_col_left{
      padding: 10px;
    }

    .btn{
      display: inline-block;
    }

    div.agree{
      width: 750px;
      height: 400px;
      overflow:scroll;
      overflow-x:hidden;
      color: black;
      text-align:left;
      line-height:20px;
      background: white;
      border: ridge;
      //  box-shadow: black 1px;     /*陰影for IE*/ 
      padding: 20px;
    }
    .agree p{
      font-weight: bold;

    }

    textarea {
        resize : none;
    }
    span.col-name{
      display: inline-block;
      width: 80px;
      vertical-align: top;
      margin: 0;
      text-align: center;
    }
    span.paper_title{
      display: inline-block;
      text-align: left;
      overflow: hidden;
      word-break: break-all;
    }
    #ptr_1,#ptr_2,#ptr_3,#ptr_4{
      margin-left: -5%;
    }
    span.paper_title textarea{
      width: 500px;
      height: 100px;
      resize: none;
    }
    span.final_view{
      color:black;
      display: inline-block;
      text-align: left;
      overflow: hidden;
      word-break: break-all;
      width:650px;
    }
    .fs{
      font-size: 28px;
    }
    </style>

  <script type="text/javascript">
    $(document).ready(function(){
      $("#btn0").click(function(){
        if($("#n0_ch").attr("checked")){
          $("#n0").slideUp(1000);
          $("#n0_2").slideDown(1000);
        }
        else{
          $('.check0').show();
          return false;
        }
      });
      $("#btn0_2").click(function(){
        if($("#n0_ch_2").attr("checked")){
          $("#n0_2").slideUp(1000);
          $("#n1").slideDown(1000);
        }
        else{
          $('.check0_2').show();
          return false;
        }
      });

      $("#btn1").click(function(){
        if(($('.ch_check').val() != "") && ($('.en_check').val() != "") && ($('.keywords').val() != "")
          && ($('#ch_summary').val() != "") && ($('#en_summar').val() != "")
          ){
          $("#n1").fadeOut(500);
          setTimeout(function(){
            $("#n2").fadeIn(500);
          }, 500);
        }
        else{
          $('#ch_check').show();
          $('#en_check').show();
          $('#keyword').show();
          $('#ch_s').show();
          $('#en_s').show();
        }
      });
      $("#btn2").click(function(){  
        if(($('.lock1').val() != "") && ($('.lock2').val() != "") && ($('.lock3').val() != "") &&
          ($('.lock4').val() != "") && ($('.lock5').val() != "") && ($('.lock6').val() != "")&&
          ($('.lock7').val() != "") && ($('.lock8').val() != "")){
          $("#n2").fadeOut(500);
          setTimeout(function(){
            $("#n3").fadeIn(500);
          }, 500);
        }
        else{
          $('#ch_author').show();
        }
      });

      $("#btn2_b").click(function(){
        $("#n2").fadeOut(500);
        setTimeout(function(){
          $("#n1").fadeIn(500);
        }, 500);
      });

      $("#btn3").click(function(){
        $("#n4_category").html( $("#category option:selected").text() );
        $("#n4_ch_title").html( $("input[name='ch_title']").val() );
        $("#n4_en_title").html( $("input[name='en_title']").val() );
        $("#n4_keyword").html( $("input[name='keywords']").val() );
        $("#n4_ch_summary").html( $("#ch_summary").val() );
        $("#n4_en_summary").html( $("#en_summary").val() );
        if(navigator.userAgent.match("Firefox")){
          var input = $("input:file").val();
        }
        else{
          var input = $("input:file").val().slice(12);
        }
        $("#n4_paper_files").html(input);


      if($('#up_lock').val() != ""){
        var input = $("input:file").val();
        if( (input.match(".rar") != null) | (input.match(".zip") != null) ){
          $("#n3").fadeOut(500);
          setTimeout(function(){
            $("#n4").fadeIn(500);
          }, 500);
        }
        else{
          alert("檔名不符--->名稱需為*.rar或*.zip");
          return false;
        }
      }
      else{
        $('.up_lock').show();
      }
      });


      $("#btn3_b").click(function(){
        $("#n3").fadeOut(500);
        setTimeout(function(){
          $("#n2").fadeIn(500);
        }, 500);
      });

      $("#btn4_b").click(function(){


        $("#n4").fadeOut(500);
        setTimeout(function(){
          $("#n3").fadeIn(500);
        }, 500);
      });

   

    $("submit").click(function(){
      if( ($('#up_lock').val() != "") ){

      }
      else{
        return false;
      }
    });

  /*  $("#commentForm").keypress(function(e) {
      if (e.which == 13) {
      return false;
      }
    });*/   //防止enter送出表單

    /*..................................*/
    function file_open( file_link ){
      window.open("../uploads/"+file_link, "論文檔案", 'width=800,height=600,location=no,toolbar=no,status=no,scrollbars=yes');
    }

    function cancel_edit(){
      window.parent.$('#dialog').dialog('close');
    }

      $("#new_btn").click(function(){
        $("#author_add").append("<div class=\"else_authors\"><div class=\"adt_col_left\"><button class=\"remove_author_block btn\">移除<\/button><\/div><div class=\"adt_col_right\"><table style='margin-left:auto;margin-right:auto;'><tbody><tr><td class=\"table_border_top\" colspan=\"4\"><\/td><\/tr><tr><td>中文姓名<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_ch_name[]\" size=20 required /><\/td><td>英文姓名<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_en_name[]\" size=20 /><\/td><\/tr><tr><td>職稱(中文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_ch_titles[]\" size=20 /><\/td><td>職稱(英文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_en_titles[]\" size=20 /><\/td><\/tr><tr><td>服務單位(中文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_ch_serve_unit[]\" size=20 /><\/td><td>服務單位(英文)<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_en_serve_unit[]\" size=20 /><\/td><\/tr><tr><td>聯絡電話<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_phone[]\" class=\"required number\" size=20 /><\/td><td>E-mail<\/td><td class=\"align_left\"><input type=\"text\" name=\"new_email[]\" class=\"required email\" size=20 /><\/td><\/tr><\/tbody><\/table><\/div><\/div>");
        $('.remove_author_block').bind("click", function(){
          $(this).parent().parent().remove();
        });
      });

      var col_tmp = "";
      $("div.authors_block input").focus(function(){
        col_tmp = $(this).val();
        $(this).blur(function(){
          // 有修改時將其author_id加入#change_authors，送出變更時會update這筆資料 (primary_author與else_authors皆適用)
          if( $(this).val() != col_tmp ) {
            var author_id = $(this).parent().parent().siblings('input').val();
            var change_authors = $("#change_authors").val();
            if ( change_authors.search( author_id+',' ) == -1 )
              $("#change_authors").val( change_authors+author_id+',' );
          }
        });
      });
    });
    </script>
</head>
   <body class="home">
          <div id="container">
                <div id="wrapper">
                 <?php include ("../bar/header_contribute.php");?> 
        <div class="span10">
            <div class="hero-unit">
                <form  method="post" action="sent2.php" enctype="multipart/form-data" id="commentForm">
                      <div id="n0">
                        <center>
                        學報發行辦法
                        <div class="agree">
                          <?php
                            $jpp_content = get_option('journal_publish_provition');
                            echo str_replace('\"', '"', $jpp_content['option_value']);
                          ?>
                        </div>
                        <input type="checkbox" id="n0_ch" name="n0_ch" /> 我同意以上條件<br />
                        <div class="check0">勾選同意後才可進行下一步</div>
                        <button type="button" id="btn0" class="btn"  />下一步</button>
                        </center>
                      </div>
                      <div id="n0_2">
                        <center>
                        個人資料保護法
                        <div class="agree">
                          <?php
                            $pipa_content = get_option('personal_information_protection_act');
                            echo str_replace('\"', '"', $pipa_content['option_value']);
                          ?>
                        </div>
                        <input type="checkbox" id="n0_ch_2" name="n0_ch_2" /> 我同意以上條件<br />
                        <div class="check0_2">勾選同意後才可進行下一步</div>
                        <button type="button" id="btn0_2" class="btn"  />下一步</button>
                        </center>
                      </div>
                      <div id="n1">
                        <div style="text-align:center;"><img id="ptr_1" src="../bar/process1.png" border="0" ></img></div><br/>
                        <div class='row' style='margin-top:10px;'>
                          <div class='span5'>
                          <span class="col-name">語言 : </span>
                              <div style="margin-left:90px;"> 
                              <input type=radio name="language" value="1" Checked />中文 
                              <input type=radio name="language" value="2" />英文 
                              <input type=radio name="language" value="3" />其他(須附上中文譯本) 
                              </div>
                          </div>
                        </div>
                        <div class='row'>
                            <div class='span5'>
                            <span class="col-name">類別 : </span>
                                <select name="category" id="category" style="margin:0;">
                                  <option value="1">管理 </option>
                                  <option value="2">資訊 </option>
                                  <option value="3">設計 </option>
                                  <option value="4">幼保.外文.性學 </option>
                                  <option value="5">通識教育 </option>
                                </select>
                            </div>
                        </div>
                        <input type='hidden' name='c_name' value='$c_name' />
                        <div class='row'>
                            <div>
                            <span class="col-name">中文標題 : </span>
                            <input type=text name='ch_title' size=13 class='ch_check'/><span id="ch_check">*必填欄位</span>
                            </div>
                        </div>

                        <div class='row'>
                            <div>
                              <span class="col-name">英文標題：</span>
                              <input type=text name='en_title' size=26 class='en_check' /><span id="en_check">*必填欄位</span>
                            </div>
                        </div>
                        <div class='row'>
                            <div>
                                <span class="col-name">關鍵字：</span>
                                <input type=text name='keywords' size=26 class='keywords' /><span id="keyword">*必填欄位</span>
                            </div>
                        </div>
                        <div class='row'>
                            <div>
                            <span class="col-name">中文摘要：</span>
                            <span class="paper_title">
                                <textarea name='ch_summary' id='ch_summary' ></textarea><span id="ch_s">*必填欄位</span>
                              </span>
                            </div>
                        </div>
                        <div class='row'>
                            <div>
                              <span class="col-name">英文摘要：</span>
                              <span class="paper_title">
                                <textarea name='en_summary' id='en_summary' ></textarea><span id="en_s">*必填欄位</span></p>
                              </span>
                            </div>
                        </div>
                        <center><button type='button' id="btn1" class='btn'>下一步</button></center>
                      </div><!--n1-->
                      <div id="n2" style="text-align:center;">
                    <div class="paper_block authors_block">
                        <div style="text-align:center;"><img id="ptr_1" src="../bar/process2.png" border="0" ></img></div><br/>
                        <!--主作者-->
                      <div><div class="fs" style="margin-top:5%;">[主要作者]</div><br/>
                        <table style="margin-left:auto;margin-right:auto;"><tbody>
                          <tr>
                            <td>中文姓名：</td><td class="align_left"><input type="text" name="pa_ch_name" size="20" class="lock1" /></td><span id="ch_author">*作者欄位皆為必填</span>
                            <td>英文姓名：</td><td class="align_left"><input type="text" name="pa_en_name" size="20" class="lock2" /></td>
                          </tr><tr>
                            <td>職稱(中文)：</td><td class="align_left"><input type="text" name="pa_ch_titles" size="20" class="lock3" /></td>
                            <td>職稱(英文)：</td><td class="align_left"><input type="text" name="pa_en_titles" size="20" class="lock4" /></td>
                          </tr><tr>
                            <td>服務單位(中文)：</td><td class="align_left"><input type="text" name="pa_ch_serve_unit" size="20" class="lock5" /></td>
                            <td>服務單位(英文)：</td><td class="align_left"><input type="text" name="pa_en_serve_unit" size="20" class="lock6" /></td>
                          </tr><tr>
                            <td>聯絡電話：</td><td class="align_left"><input type="text" name="pa_phone" size="20" class="lock7" /></td>
                            <td>E-mail：</td><td class="align_left"><input type="text" name="pa_email" size="20" class="lock8" /></td>
                          </tr>
                        </tbody></table>
                      </div><br/>
                        <!--其他作者-->
                      <div><div class="fs">[其他作者]</div>
                        <div id="author_add"></div>
                       
                      </div>
                      <div>
                        <button type="button" id="new_btn" class='btn' style="margin-top:3%;display:inline-block;" />新增一欄</button>
                        <button type="button" id="btn2_b" class='btn' style="display:inline-block;">上一步</button>
                        <button type="button" id="btn2" class='btn' style="display:inline-block;">下一步</button>
                      </div>
                    </div>
                      </div><!--n2-->

                      <div id="n3" style="text-align:center;">
                        <img id="ptr_3" src="../bar/process3.png" border="0" /><br/>                        
                        <h4 style="margin-top:5%;">1.把所有要上傳的資料壓縮成一個檔案</h4>
                        <h4>2.上傳壓縮檔</h4>                    
                        <?php
                          echo '
                            <div class="file_upload_block">
                              <div style="margin-top:5px;">
                                選擇檔案：
                                <input name="new_upload_file" type="file" class="select_upload_file" id="up_lock" />
                                <br/><div class="up_lock">*請選擇上傳檔案</div>
                                <span style="font-size:16px; color:blue;">(檔案格式限制：*.rar、*.zip)<br/> 
                                p.s 論文檔案須為word檔
                                </span><br/>
                                <span class="tip" style="font-size:16px;color:blue;">※上傳檔案後，系統會將檔案重新命名(命名格式：論文ID_年月日_時分秒.副檔名).</span>
                                <br/>
                              </div>
                            </div>
                            <script type="text/javascript" src="'.ROOT_URL.'/web/js/file_upload_check.js'.'""></script>
                          ';
                        ?>
                        <center style='margin-top:20%'>
                          <button type="button" id="btn3_b" class='btn'>上一步</button>
                          <button type="button" id="btn3" class='btn'>下一步</button>
                        </center> 
                      </div><!--n3-->
                      <div id="n4" style="text-align:center;">
                        <img id="ptr_4" src="../bar/process4.png" border="0" style='margin-bottom:10px;' ></img>
                        <div class="row">
                          <span class="col-name" >類別：</span>
                          <span class="final_view"><span id="n4_category"></span></span>
                        </div>
                        <div class="row">                          
                            <span class="col-name">中文標題：</span>                                                     
                            <span class="final_view"><span id="n4_ch_title"></span></span>                          
                        </div>
                        <div class="row">
                          <span class="col-name">英文標題：</span>
                          <span class="final_view"><span id="n4_en_title"></span></span>
                        </div>
                        <div class="row">
                          <span class="col-name">關鍵字：</span>
                          <span class="final_view"><span id="n4_keyword"></span></span>
                        </div>
                        <div class="row">
                          <span class="col-name">中文摘要：</span>
                          <span class='final_view'><span id="n4_ch_summary"></span></span>
                        </div>
                        <div class="row">
                          <span class="col-name">英文摘要：</span><span class='final_view'><span id="n4_en_summary"></span></span>
                        </div>
                        <div class="row">
                          <span class="col-name">上傳檔案：</span>
                          <span class="final_view"><span id="n4_paper_files"></span></span>
                        </div>
                        <input type="hidden" name="ae_flag" id="ae_flag" value="0" />
                        <center style='margin-left:-10%;margin-top:10%;'>
                          <button type="button" id="btn4_b" class='btn'>返回修改</button>
                          <button type='submit' value="確認送出" class="btn"/>確認送出</button>
                        </center>
                      </div><!--n4-->
                    </form>
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
