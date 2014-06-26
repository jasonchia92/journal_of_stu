<?php
/*
sql_q( $cmd );
    資料查詢
傳入參數：
    $cmd：SQL指令，string
傳出參數：
    SQL result array：SQL query集合fetch()後的陣列
*/
function sql_q( $sql, $sql_array ){
    global $db;
    /*
    $sql_query = $db->query( $cmd );
    $sql_query->setFetchMode(PDO::FETCH_ASSOC);
    $result = $sql_query->fetchAll();
    */
    $sth = $db -> prepare($sql);
    $sth -> execute($sql_array);
    if( $sth -> errorCode() != '00000' ){
        $error = $sth -> errorInfo();
        echo "Error : ".$error[2];
        return false;
    }
    $sth->setFetchMode(PDO::FETCH_ASSOC);

    $result = filter_var_array($sth -> fetchAll(), FILTER_SANITIZE_MAGIC_QUOTES);

    /*
    echo count($result);
    print_r($result);
    exit;
    */

    if( count($result) == 0 )
        $result = array();

    return $result;
}


/*
sql_e( $cmd );
    資料寫入、更新與刪除
傳入參數：
    $cmd：SQL指令，string
傳出參數：
    無
*/
function sql_e( $sql, $sql_array ){
    global $db;

    //$sql_exec = $db->exec( $cmd );

    $sth = $db -> prepare($sql);
    $sth -> execute($sql_array);
    if( $sth -> errorCode() != '00000' ){
        $error = $sth -> errorInfo();
        echo "Error : ".$error[2];
        return false;
    }
    return true;
}


/*
sql_q_adv()
    資料查詢 (進階)
Params:
    $sql
    $params : SQL語法參數 (Default: none)
    $view_rows : 每頁顯示資料數 (Default: 10)
Returns
    Object
        -> data : 資料
        -> max_page : 最大頁數
        -> paged : 當前頁碼
        -> action_show_page_numbers() : 顯示頁數選擇區塊
*/
function sql_q_adv( $sql, $params=array(), $view_rows=10 ) {
    class obj {
        public $data = array();
        public $max_page = 1;
        public $paged = 1;
        public $view_rows = 10;

        function action_show_page_numbers() {
            ?>
            <table style="width:100%;"><tbody>
                <td  style="border:none;"><?php show_page_numbers( $this->max_page ); ?>
                <td style="width:185px;border:none;">
                    <form style="padding-top:20px;">
                        資料顯示筆數：
                        <input type="text" name="view_rows" value="<?php echo $this->view_rows; ?>" style="width:40px; text-align:center; margin:0;display:inline-block;" />
                        <input type="submit" style="display: none;" />
                        <input type="hidden" name="paged" value="1" />
                    </form>
                </td>
            </tbody></table>
            <?php
        }
        
        function __construct( $data, $max_page, $paged, $view_rows ) {
            $this->data = $data;
            $this->max_page = $max_page;
            $this->paged = $paged;
            $this->view_rows = $view_rows;
        }
    }

    if ( !isset($_GET['paged']) )
        $_GET['paged'] = 1;
    if ( isset($_GET['view_rows']) ) {
        if ( $_GET['view_rows'] > 0 )
            $view_rows = $_GET['view_rows'];
        else
            $_GET['view_rows'] = $view_rows;
    }

    $start = ($_GET['paged']-1) * $view_rows;
    $sql_queryData = $sql." LIMIT ".$start.", ".$view_rows;
    $data = sql_q( $sql_queryData, $params );

    # Max page
    $sql = substr( $sql, strpos($sql, 'FROM'), strlen($sql) );
    $sql = "SELECT COUNT(*) AS total_rows ".$sql;
    $result = sql_q( $sql, $params );

    # global $max_page;
    $max_page = ceil( $result[0]['total_rows']/$view_rows );

    return new obj( $data, $max_page, $_GET['paged'], $view_rows );
}


function show_page_numbers( $set_max_page ){
    if ( $set_max_page <= 1 ) return true;
    ?>
        <link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL.'/plugin/wp-page-numbers/classic/wp-page-numbers.css'; ?>"/ >
    <?php
    global $max_page;
    $max_page = $set_max_page;

    wp_page_numbers();
}


function get_total_rows( $sql, $colName, $array=array() ){
    $sql = substr( $sql, strpos($sql, 'FROM'), strlen($sql) );
    $sql = "SELECT COUNT('".$colName."') AS total_rows ".$sql;

    $result = sql_q( $sql, $array );

    return $result[0]['total_rows'];
}


/*
acts( $array, $quotes );
    一維陣列資料轉換為SQL指令列(欄位與資料部分) - Array Convert To String
傳入參數：
    $array：一維陣列資料
    $quotes：是否需要加上單引號(參數：0、1、false、true)
傳出參數：
    陣列轉換後的字串，可直接加進SQL指令內
*/
function acts( $array, $quotes ){
    if ( !is_array($array) )
        $array = array($array);

    $cmd_string = "";
    if( $quotes ){
        foreach( $array as $key => $value )
            $cmd_string .= "'{$value}',";
    } else {
        foreach( $array as $key => $value )
            $cmd_string .= $value.",";
    }

    $cmd_string = substr( $cmd_string, 0, strlen($cmd_string)-1 );

    return $cmd_string;
}


/*
sqh( $str );
    特殊引號處理(用於SQL IN函式) - Special Quotes Handle 
傳入參數：
    $str：
傳出參數：
    陣列轉換後的字串，可直接加進SQL指令內
*/
function sqh( $str ){
    $cmd_string = str_replace(",", "','", $str);
    //remove first char and last char
    if( strpos($cmd_string, "'") == true )
        $cmd_string = substr( $cmd_string, 0, strlen($cmd_string)-1 );

    return $cmd_string;
}


/* Data Output
dop( $string );
    資料輸出(預防XSS攻擊)
傳入參數：
    $string
*/
function dop( $string ){
    return htmlentities($string, ENT_QUOTES, "UTF-8");
}


/* ------------------------------- Templates ------------------------------- */

/*
首頁左側區塊內容
*/
function get_sidebarLeft() {
?>
            <?php
                $publish_data = get_newest_publish();
                if ( $publish_data != false ) {
                    $img_path = ROOT_DIR.'/web/publish/'.$publish_data['file_cover'];
                    if ( strcmp(PHP_OS, 'WINNT') == 0 )
                        $img_path = iconv('utf-8', 'big5//ignore', $img_path);

                    if ( !file_exists($img_path) )
                        $img_path = 'images/'.urlencode('image-not-found.jpg');
                    else
                        $img_path = 'publish/'.urlencode($publish_data['file_cover']);
                    ?>

                        <div class="book">
                       
                        <a target="_blank" class="publish-block" href="publish/<?php echo urlencode($publish_data['file_pdf']);?>">
                            <img src="<?php echo $img_path; ?>" style="float:left;padding:10px;"/>
                        </a>

                        <div class="book_title">
                        最新期刊：<br>
                        <?php echo dop($publish_data['title']);?><br>
                        發行日期：<?php echo $publish_data['publish_date']; ?></div>
                        
                    <?php

                } else { ?>
                    <span style="font-size:14px;">未登錄任何期刊資料於系統</span>
                <?php } // end if
            ?>
        <div class="publish_history"><a href="publishs_history.php">檢視歷年期刊→</a></div>
        </div>
<?php
}


/*
取得CSS
Params:
    css檔名(不含副檔名)，讀取多檔則傳入多個參數。
    EX: 取得3個CSS檔案， get_css('css1', 'css2', 'css3');
Returns:
    直接輸出
*/
function get_css(){
    if ( func_num_args() > 0 ) {
        for ( $i=0; $i<func_num_args(); $i++ ) {
        ?>
            <link rel="stylesheet" type="text/css" href="<?php echo CSS_URL.'/'.func_get_arg($i); ?>.css" />
        <?php
        }
    }
}



/* ------------------------------- Access Control ------------------------------- */
function check_page_pms() {
    // If session were expired. Job of redirect do not anything.
    if ( check_login_status(false) == false )
        return true;

    $auth_data = get_auth_data();
    $gid = $auth_data['gid'];

    // 審稿召集人
    if ( ($gid{2} == 1) && ($gid{1} == 0) )
        $gid{1} == 1;

    $gid = $gid{0}.$gid{1}.$gid{3};

    // Check current use child system permission
    $system_arr = array(
        'manage',
        'review',
        'contribute'
    );

    $current_use_system = -1;
    $main_pattern = DIR_NAME.'/web/';
    $main_pattern = str_replace('.', '\.', $main_pattern);
    $main_pattern = str_replace('/', '\/', $main_pattern);    
    $main_pattern = str_replace(':', '\:', $main_pattern);
    for ( $i=0; $i<=2; $i++ ) { 
        $pattern = $main_pattern.$system_arr[$i];
        $pattern = '/'.$pattern.'/';
        if ( preg_match($pattern, $_SERVER['REQUEST_URI']) ) {
            $current_use_system = $i;
            break;
        }
    }

    if ( $current_use_system > -1 ) {
        // Check user group
        if ( $gid{$current_use_system} == 0 ) {
            permission_denied();
        } else {
            switch ( $system_arr[$current_use_system] ) {
                case 'manage':
                    // Manager can use any function at manage system.
                break;

                case 'review':
                    $pid = '';
                    if ( isset($_GET['pid']) )
                        $pid = $_GET['pid'];
                    else
                        return false;

                    $sql = "SELECT id FROM review_record WHERE
                            paper_id=? AND
                            rid=? ";
                    $array = array( get_sys_pid($pid), $auth_data['id'] );
                    $result = sql_q( $sql, $array );
                    
                    if ( count($result) == 0 )
                        permission_denied();
                break;

                case 'contribute':
                    $pid = '';
                    if ( isset($_GET['pid']) )
                        $pid = $_GET['pid'];
                    else
                        return false;

                    $sql = "SELECT id FROM papers WHERE
                            id=? AND
                            submit_user=? ";
                    $array = array( get_sys_pid($pid), $auth_data['id']);
                    $result = sql_q( $sql, $array );
                    
                    if ( count($result) == 0 )
                        permission_denied();
                break;
                
                default:
                break;
            }
        }

    } else {
        // Current apply check permission function to manage, review and contribute system only.
    }
}



function permission_denied() {
    $location = get_login_redirect_url();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Access Denied - 樹德科技大學學報</title>
    <script type="text/javascript">
        setTimeout( function(){
            window.location.href = '<?php echo $location; ?>';
        }, 5000 );
    </script>
</head>
<body>
    You not have permission for access this page. The browser redirect page after 5 second.<br/>
    <a href="<?php echo $location; ?>">Click here to be redirect now.</a>
</body>
</html>
<?php
    exit;
}