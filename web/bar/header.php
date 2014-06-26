<header id="main_header">
                <!--MENU-->
                <div id="menu" class="clearfix">
                    <a href="#" id="mobile_nav" class="closed">Navigation Menu</a>
                    <nav id="main-menu">
                        <ul id="menu-main_menu" class="nav">
                        <div class="btn-group">
                            <li><a href="index.php">首頁</a></li>
                            <li><a href="#">編審委員</a></li>
                            <li><a href="callforpaper.php">徵稿辦法</a></li>
                            <li><a href="publishs_history.php">卷期查詢</a></li>
                            <li><a href="login.php">線上投稿</a></li>
                            <li><a href="opinion.php">意見回應</a></li>
                                 <?php if ( !check_login_status(false) ): ?>
                                <li><a href="signup.php">申請帳號</a></li>
                                <?php endif ?>
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
                                        <img src="bootstrap/theme/images/slider/header_1.jpg" alt='img' />
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
                                        <img src="bootstrap/theme/images/slider/header_2.jpg" alt='img' />
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
                                        <img src="bootstrap/theme/images/slider/header_3.jpg" alt='img' />
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