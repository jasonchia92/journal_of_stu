<?php
require_once('../../loader.php');
check_login_status();

// Add publish
$have_upload = false;
$upload_result = "";
if ( isset($_POST['submit']) ) {
    $have_upload = true;
    if ( isset($_POST['title'])
        && isset($_POST['publish_date'])
        && isset($_FILES['file_pdf'])
        && isset($_FILES['file_cover'])
        && add_publish( $_POST['title'], $_POST['publish_date'] )
        ) {
        $upload_result = "上傳成功";
    } else {
        $upload_result = "上傳失敗，請稍後再嘗試。若多次嘗試仍顯示此訊息，請聯絡系統管理員。";
    }
}


$have_delete = false;
$delete_result = "";
// Delete publish
if ( isset($_POST['data_del']) ) {
    $conv_to_sql = acts($_POST['data_del'], 1);
    $sql = "SELECT * FROM publish WHERE id IN (".$conv_to_sql.") ";
    $get_db_record = sql_q($sql, array());

    // Remove files
    if ( count($get_db_record) > 0 ) {
        foreach ($get_db_record as $value) {
            $pdf_file_path = PUBLISH_DIR.'/'.$value['file_pdf'];
            $cover_file_path = PUBLISH_DIR.'/'.$value['file_cover'];
            if ( OS_IS_WINDOWS == true ) {
                $pdf_file_path = iconv('utf-8', 'big5//ignore', $pdf_file_path);
                $cover_file_path = iconv('utf-8', 'big5//ignore', $cover_file_path);
            }

            if ( file_exists($pdf_file_path) ) unlink($pdf_file_path);
            if ( file_exists($cover_file_path) ) unlink($cover_file_path);
        }
    }

    $sql = "DELETE FROM publish WHERE id IN (".$conv_to_sql.") ";

    $have_delete = true;
    if ( sql_e($sql, array()) )
        $delete_result = "操作成功";
    else
        $delete_result = "操作失敗，請稍後再嘗試。若多次嘗試仍顯示此訊息，請聯絡系統管理員。";

    unset($_POST['data_del']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>學報管理系統 - 樹德科技大學學報</title>
    <!-- Le styles -->
    
    <link rel="stylesheet" href="../bootstrap/theme/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/headerWithSlider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="../bootstrap/theme/css/lightbox.html" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../bootstrap/theme/css/font-awesome.min.css" media="screen" />
    <link href="../bootstrap/theme/css/least.min.css" rel="stylesheet">
    <link href="../bootstrap/theme/css/jquery.easy-pie-chart.css" rel="stylesheet"> 
    <link href="../bar/tab_page.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="../js/js_spare/jquery.min.js"></script>
    <script type="text/javascript" src="../js/js_spare/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jQuery.appear.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/superfish.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/easypiechart.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/canvas.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/niceScroll.js"></script>

    <script src="../bootstrap/theme/js/jquery.lazyload.js"></script>
    <script src="../bootstrap/theme/js/least.min.js"></script>

    <script type="text/javascript" src="../js/tab_page.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <style type="text/css">
        .warm {
            font-size: 12px;
            color: #FF0000;
            display: none;
        }

        .prompt {
            color:blue;
            font-size: 16px;
        }

        .tab_content {
            color:black;
        }

        #pform div {
            margin-top: 8px;
        }

        .table th, .table td {
            text-align: center;
        }

        .table td {
            vertical-align:middle;
        }

        .func_selectAll {
            font-size: 14px;
        }

        .up_img {
            max-width: 80%;
            max-width: 90px;
            max-height: 120px;
        }

        .hyper-link {
            text-decoration: none;
            color: #494949;
            padding: 1px 0px;
            margin-bottom: -1px;
        }

        .hyper-link:hover {
            color: #0000FF;
            cursor: pointer;
        }

        input{
            display: inline;
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
    <script type="text/javascript">
        function clear_form( formDomAddr ) {
            $(formDomAddr+" :input").each(function(){
                switch($(this).attr('type')){
                    case 'radio':
                      $(this).attr("checked", false);
                    case 'checkbox':
                      $(this).attr("checked", false);
                    break;
                    case 'select-one':
                      $(this)[0].selectedIndex = 0;
                    break;
                    case 'text':
                      $(this).attr("value", "");
                    break;
                    case 'password':
                      $(this).attr("value", "");
                    //case 'hidden':
                    case 'textarea':
                      $(this).attr("value", "");
                    break; 
                }
            });
        }

        $(document).ready(function(){
            <?php if ( $have_upload ): ?> alert("<?php echo $upload_result; ?>"); <?php endif ?>
            <?php if ( $have_delete ): ?> alert("<?php echo $delete_result; ?>"); <?php endif ?>

            $('#pform').submit(function(){
                $('.warm').hide();

                var title           = $('#pform input[name="title"]');
                var file_pdf        = $('#pform input[name="file_pdf"]');
                var file_cover      = $('#pform input[name="file_cover"]');
                var publish_date    = $('#pform input[name="publish_date"]');
                var cancel_submit   = false;

                if ( title.val() == '' ) {
                    title.siblings('.warm').html('必填欄位.').show();
                    cancel_submit = true;
                }

                if ( file_pdf.val() == '' ) {
                    file_pdf.siblings('.warm').html('必須選擇期刊檔案.').show();
                    cancel_submit = true;
                } else if ( file_pdf.val().match('.pdf') == null ) {
                    file_pdf.siblings('.warm').html('期刊檔案格式必須為pdf檔.').show();
                    cancel_submit = true;
                }

                if ( file_cover.val() == '' ) {
                    file_cover.siblings('.warm').html('必須選擇封面檔案').show();
                    cancel_submit = true;
                } else if ( (file_cover.val().match(".png") == null) && (file_cover.val().match(".jpg") == null) ) {
                    file_cover.siblings('.warm').html('封面圖片格式必須為png或jpg檔.').show();
                    cancel_submit = true;
                }

                if ( publish_date.val() == '' ) {
                    publish_date.siblings('.warm').html('必填欄位.').show();
                    cancel_submit = true;
                }

                if ( cancel_submit == true )
                    return false;
            });

            $('form[name="del_data"]').submit(function(){
                var checked_sum = 0;
                $('.chk_del').each(function(){
                    if ( $(this).prop('checked') )
                        checked_sum++;
                });

                if ( checked_sum <= 0 ) {
                    alert('未選取任何項目.');
                    return false;
                } else if ( !confirm('刪除後的資料無法復原，確定刪除?') )
                    return false;
            });

            $('.select_date').datepicker({
                dateFormat: 'yy-mm-dd',
                showOn: 'both'
            });

            $('.func_sa_control').click(function(){
                var action = $(this).html()=='全選';
                $('.chk_del').each(function(){
                    $(this).prop('checked', action);
                });
            });

            $('.modify').click(function(e) {
                e.preventDefault();
                var $this = $(this);
                var link = "./publish_modify.php?id=" + $(this).attr('value');
                $('div#dialog').html('<iframe id="externalSite" class="externalSite" frameborder=0 width=450 height=380 src="' + link + '" ><\/iframe>').dialog({
                    title: "修改資料",
                    autoOpen: true,
                    width: 450,
                    height: 430,
                    modal: true,
                    resizable: false,
                    autoResize: false,
                });
            });
        });
    </script>
</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
        <?php include ("header_manage.php");?> 
                <div class="span10">
                    <div class="hero-unit" style="padding:70px;">
                        <div class="abgne_tab">
                            <ul class="tabs">
                                <li><a href="#tab1">檢視期刊</a></li>      
                                <li><a href="#tab2">發佈期刊</a></li>
                            </ul>
                            <div class="tab_container">
                                <div id="tab1" class="tab_content">
                                    <?php
                                    $publish_data = get_publishs();

                                    if ( $publish_data !== false ) {
                                        $key = $publish_data->data; ?>
                                        <form name="del_data" action="ssm_publish.php" method="post">
                                            <span class="func_selectAll">(
                                                <span class="func_sa_control hyper-link">全選</span>/
                                                <span class="func_sa_control hyper-link">全不選</span>
                                            )</span>
                                            <table class="table" style="width:100%;">
                                                <thead>
                                                    <th style="width:16px;">x</th>
                                                    <th style="width:140px;text-align:center;">封面縮圖</th>
                                                    <th>卷期標題</th>
                                                    <th style="width:55px;text-align:center;">功能</th>
                                                </thead>
                                                <tbody> <?php
                                                    foreach ($key as $value) {
                                                        $img_path = ROOT_DIR.'/web/publish/'.$value['file_cover'];
                                                        if ( strcmp(PHP_OS, 'WINNT') == 0 )
                                                            $img_path = iconv('utf-8', 'big5//ignore', $img_path);

                                                        if ( !file_exists($img_path) )
                                                            $img_path = '../images/'.urlencode('image-not-found.jpg');
                                                        else
                                                            $img_path = '../publish/'.urlencode($value['file_cover']);
                                                        ?>
                                                            <tr>
                                                                <td><input type="checkbox" name="data_del[]" class="chk_del" value="<?php echo $value['id']; ?>"></td>
                                                                <td>
                                                                    <a target="_blank" href="<?php echo $img_path; ?>">
                                                                        <img class="up_img" src="<?php echo $img_path; ?>" />
                                                                    </a>
                                                                </td>
                                                                <td style="text-align:left;">
                                                                    <a target="_blank" href="../publish/<?php echo urlencode($value['file_pdf']); ?>">
                                                                        <?php echo $value['title']; ?>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <button class="modify btn" value="<?php echo $value['id']; ?>" style="font-size:15px;padding:5px;">修改</button>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?> </tbody>
                                            </table>
                                            <div class="operation_block">
                                                <div class="data_operation" style="padding-top:10px;">
                                                    <input type="submit" class="btn" value="刪除選取項目" />
                                                </div> <!-- end data_operation -->
                                            </div> <!-- end operation_block -->
                                        </form>
                                        <div class="page_numbers_block"><?php if ( $publish_data !== false ) $publish_data->action_show_page_numbers(); ?></div>
                                        <div id="dialog"></div>
                                        <?php
                                    } else {
                                        echo "無任何期刊登錄於系統上，請點選上方「發布期刊」以登錄期刊資料。";
                                    } ?>
                                </div><!--tab1-->
                                <div id="tab2" class="tab_content">
                                    <form action="ssm_publish.php" method="post" enctype="multipart/form-data" id="pform">
                                        <div class="title">
                                            期刊標題：<input type="text" name="title" />
                                            <span class="warm"></span>
                                        </div>
                                        <div class="file-pdf">
                                            期刊檔案：<input type="file" name="file_pdf" />
                                            <span class="warm"></span>
                                        </div>
                                        <div class="file-cover">
                                            封面檔案：<input type="file" name="file_cover" />
                                            <span class="warm"></span>
                                        </div>
                                        <div class="publish-date">
                                            <span class="col-name">出刊日期：</span>
                                            <span>
                                                <input type="text" name="publish_date" class="select_date required" />
                                                <span class="warm"></span>
                                            </span>
                                        </div>
                                        <input type="submit" name="submit" value="新增" class="btn" style="font-size:15px;padding:5px;"/>
                                    </form>
                                </div><!--tab2-->
                            </div><!--tab_container-->
                        </div><!--abgne_tab-->
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
