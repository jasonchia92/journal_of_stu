<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>校稿::線上投稿系統 - 樹德科技大學學報</title>
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
    <style type="text/css">
      
    .sidebar-nav {
        padding: 9px 0;
    }

    .hero-unit{
        margin: 3%;
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

h2 {
    margin: 0;
    margin-bottom: 10px;
    text-align: left;
    font-size: 35px;
    padding-bottom: 10px;
}
</style>

</head>
<body class="home">
  <div id="container">
    <div id="wrapper">
       <?php include ("../bar/header_contribute.php");?> 
       <div class="span9">
            <div class="hero-unit">
                <h2 style="border-bottom:2px solid red;">校稿</h2>
                <?php $paper = get_paper_inf( $_GET['pid'] ); ?>
                <?php if ( $paper['smu_edit_enable'] ): ?>
                    <span class="label label-important" style="font-size:15px">先前上傳之檔案</span>
                    <?php print_pfcr( $_GET['pid'], 3 ); ?>
                    <br/>
                <?php endif; ?>
                <form action="correction_2.php?pid=<?=dop($_GET['pid']);?>" method="post" enctype="multipart/form-data">
                    <?php if ( $paper['smu_edit_enable'] ): ?>
                        <div class="file_upload_block">
                            <div class="block_title">
                                <span class="label label-important" style="font-size:15px">上傳更正版論文壓縮檔</span>
                                <input type="checkbox" name="bln_uf" class="enable_file_upload" style="display:none;" checked />
                            </div>
                            <div class="block_main func_disabled">
                                選擇檔案：
                                <input name="new_upload_file" type="file" class="select_upload_file" style="display:inline-block;" /><br/>
                                <span style="font-size:12px; color:#888;">(檔案格式限制：*.rar、*.zip)</span><br/>
                                <textarea class="file_description" name="file_description" style="width:600px; height:100px; text-align:left; resize:none;">(在此寫下檔案備註)</textarea><br/>
                                <span class="tip" style="font-size:12px;">※上傳檔案後，系統會將檔案重新命名(命名格式：論文ID_年月日_時分秒.副檔名).</span><br/>
                            </div>
                        </div><br/>
                    <?php endif; ?>
                    <?php if ( $paper['craa_upload'] == 1 ): ?>
                        <div class="file_upload_block">
                            <div class="block_title">
                                <span class="label label-important" style="font-size:15px">上傳著作權讓與同意書</span>
                                <input type="checkbox" name="bln_uf" class="enable_file_upload" style="display:none;" checked />
                            </div>
                            <div class="block_main func_disabled">
                                選擇檔案：
                                <input name="craa" type="file" class="select_upload_file" style="display:inline-block;"/><br/>
                                <span style="font-size:12px; color:#888;">(檔案格式限制：*.pdf、*.doc、*.docx)</span><br/>
                                <span style="font-size:12px; color:#00F;">
                                    <a class="hyper_link" target="_blank" href="<?=ROOT_URL.'/web/file/附件1-著作權讓與同意書.doc';?>">(下載著作權讓與同意書)</a>
                                </span><br/>
                                <br/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <script type="text/javascript" src="<?=ROOT_URL.'/web/js/file_upload_check.js'; ?>"></script>
                    <input type="hidden" name="paper_id" value="<?=dop($_GET['pid']);?>" />
                    <input type="submit" value="送出" class="btn cssbtn" style="margin-left:40%;" />
                </form>
            </div>
        </div>
    </div>
    <div class="foot">
       <?php include ("../bar/end.php");?>
    </div>
  </div>
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>
