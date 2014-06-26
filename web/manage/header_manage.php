<header id="main_header">
                <!--MENU-->
                <div id="menu" class="clearfix">
                    <a href="#" id="mobile_nav" class="closed">Navigation Menu</a>
                    <nav id="main-menu">
                        <ul id="menu-main_menu" class="nav">
                        <div class="btn-group">
                            <li><a href="<?php echo ROOT_URL;?>/web/">學報系統首頁</a></li>
                            <li><a href="<?php echo ROOT_URL;?>/web/person/index.php">個人資料</a></li>
                                <?php
                                $auth_data = get_auth_data();
                                if( $auth_data ){
                                    $pms = $auth_data['gid'];
                                    echo $pms{3} ? '<li><a href="'.ROOT_URL.'/web/contribute/check_paper.php" >線上投稿系統</a></li>' : '';
                                    echo ($pms{1} | $pms{2}) ? '<li><a href="'.ROOT_URL.'/web/review/all_paper.php" >線上審稿系統</a></li>' : '';
                                    echo $pms{0} ? '<li><a href="'.ROOT_URL.'/web/manage/index.php">學報管理系統</a></li>': '';
                                }
                                echo '<li><a href="'.ROOT_URL.'/web/logout.php">登出</a></li>';
                            ?>
                        </div>
                        </ul>   
                    </nav>
                </div> <!-- end #menu -->
                <!--MENU-->
                
                <!--SLIDER-->
                <div id="header_featured" class="flexslider clearfix">
                    <ul class="slides">
                    
                    <!--IMAGE SLIDE-->
                        <li class="slide fr_slide_image">
                            <div class="slide_wrap">
                                <div class="featured_box">
                                    <a href="#">                            
                                        <img src="../bootstrap/theme/images/slider/header_1.jpg" alt='img' />
                                    </a>
                                    <div class="fr_image_description">
                                        <div class="fr_inner_description">
                                            <h2 class="title">樹德科技大學學報</h2>
                                            <p>Journal of STU</p>
                                        </div> <!-- end .fr_inner_description -->
                                    </div>
                                </div> <!-- end .featured_box -->
                            </div> <!-- end .slide_wrap -->
                        </li>
                        
                        <li class="slide fr_slide_image" >
                            <div class="slide_wrap">
                                <div class="featured_box">
                                    <a href="#">                            
                                        <img src="../bootstrap/theme/images/slider/header_2.jpg" alt='img' />
                                    </a>
                                    <div class="fr_image_description">
                                        <div class="fr_inner_description">
                                            <h2 class="title">樹德科技大學學報</h2>
                                            <p>Journal of STU</p>
                                        </div> <!-- end .fr_inner_description -->
                                    </div>
                                </div> <!-- end .featured_box -->
                            </div> <!-- end .slide_wrap -->
                        </li>
                        
                        <li class="slide fr_slide_image" >
                            <div class="slide_wrap">
                                <div class="featured_box">
                                    <a href="#">                            
                                        <img src="../bootstrap/theme/images/slider/header_3.jpg" alt='img' />
                                    </a>
                                    <div class="fr_image_description">
                                        <div class="fr_inner_description">
                                           <h2 class="title">樹德科技大學學報</h2>
                                            <p>Journal of STU</p>
                                        </div> <!-- end .fr_inner_description -->
                                    </div>
                                </div> <!-- end .featured_box -->
                            </div> <!-- end .slide_wrap -->
                        </li>                       
                    </ul>
                </div>  <!-- end #header_featured -->
                <!--SLIDER-->
            </header> <!-- end #main-header -->

            <?php include("menu_left.php"); ?>
            

            