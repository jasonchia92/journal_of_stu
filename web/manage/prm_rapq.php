<?php require_once('../../loader.php'); check_login_status(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>論文分派狀態::論文分派系統 - 樹德科技大學學報</title>
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
        table .category_name {
            width: 120px;
        }

        table .category_name a {
            text-decoration: none;
        }

        #summary td {
            width: 120px;
            text-align: center;
        }

        #summary a{
            color: blue;
        }

        #data {
            padding: 20px;
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



        #data table th, #data table td,{
            padding: 0px;
        }
    </style>
</head>
<body class="home">
    <div id="container">
        <div id="wrapper">
        <?php include ("header_manage.php");?> 
                <div class="span10" style="padding-top:50px;font-size:15px;">
                    <div class="hero-unit">
                       <?php
                        $query = sql_q("SELECT assign_category FROM users WHERE rid!=0", array());
                        $summary = array(0, 0, 0, 0, 0, 0);
                        
                        foreach ($query as $key => $value)
                            $summary[ $value['assign_category'] ]++;
                        
                        unset($summary[0]);
                        ?>
                        <center>
                            <div id="category_table">
                                點選類別名稱檢視負責評閱該類別的審稿委員被分派論文數
                                <a href="<?php echo basename(__FILE__); ?>" style="margin-left:30px; color:blue;">顯示全部</a>
                                <table id="summary" summary="">
                                    <tbody>
                                        <tr>
                                            <th>類別名稱</th>
                                            <td><a href="?category=1">管理</a></td>
                                            <td><a href="?category=2">資訊</a></td>
                                            <td><a href="?category=3">設計</a></td>
                                            <td><a href="?category=4">幼保.性學.外文</a></td>
                                            <td><a href="?category=5">通識教育</a></td>
                                        </tr><tr>
                                            <td>審稿委員數</td>
                                            <?php
                                                foreach ($summary as $key => $value)
                                                    echo "<td>".$value."</td>";
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="data">
                                <?php
                                    

                                    $sql = "SELECT rid, name FROM users WHERE assign_category";
                                    $array = array();

                                    if ( isset($_GET['category']) && $_GET['category']>0 ){
                                        $sql .= "=? ";
                                        $array[] = $_GET['category'];
                                    } else {
                                        $sql .= "!='0' "; # All reviewer
                                    }

                                    $data = sql_q( $sql, $array );
                                    $reviewer = array();

                                    foreach ($data as $key => $value) {
                                        $rid = $value['rid'];
                                        //assigned quantity
                                        $asg_qnt = sql_q("SELECT COUNT('quantity') FROM review_record WHERE rid=?", array($rid) );
                                        $reviewer[$rid] = $value;
                                        $reviewer[$rid]['asg_qnt'] = $asg_qnt[0]["COUNT('quantity')"];
                                    }

                                    if ( count($reviewer) ) {
                                        function print_link( $rid, $name, $asg_qnt ){
                                            if ( $asg_qnt > 0 )
                                                return "<a href=\"prm_pal.php?search_condition=1&amp;search_str=".$rid."\">".dop( $name )."</a>";
                                            else
                                                return dop( $name );
                                        }

                                        ?>
                                            <table id="summary">
                                                <thead><tr>
                                                    <th>審稿委員ID</th>
                                                    <th>審稿委員姓名</th>
                                                    <th>分派篇數</th>
                                                </tr></thead>
                                                <tbody>
                                                <?php
                                                    foreach( $reviewer as $key => $value ){
                                                        $bln = $asg_qnt > 0 ? 1 : 0;
                                                        echo "<tr>
                                                            <td>".$value['rid']."</td>
                                                            <td>".print_link( $value['rid'], $value['name'], $value['asg_qnt'] )."</td>
                                                            <td>".$value['asg_qnt']."</td>
                                                        </tr>";
                                                    }
                                                ?>
                                            </tbody></table>
                                        <?php
                                    } else {
                                        echo "目前無審稿委員屬於此分派類別.";
                                    }
                                ?>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div><!-- end container-fluid -->
    <div align='center'>
        <?php include ("../bar/end.php");?>
    <script type="text/javascript" src="../bootstrap/theme/js/custom.js"></script>
    <script type="text/javascript" src="../bootstrap/theme/js/headerWithSlider.js"></script>
</body>
</html>