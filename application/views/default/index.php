<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo config_item('website_title');?></title>
        <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta content="telephone=no" name="format-detection">
        <link rel="stylesheet" href="<?php echo base_url(); ?>style/default/skin/css/swiper.min.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>style/default/skin/js/swiper.min.js"></script>
        <style>
            /*引导页start*/
            *{
                margin: 0;
                padding: 0;
            }
            .swiper-container {
                width: 100%;
                margin: 0 auto;
                height: 100%;
            }

            .swiper-slide {
                text-align: center;
            }

            .swiper-container-horizontal .swiper-pagination {
                left: 0;
                width: 100%;
            }

            .swiper-container-horizontal .swiper-pagination .swiper-pagination-bullet {
                margin: 0 5px;
            }

            .swiper-pagination-bullet {
                width: 10px;
                height: 10px;
                display: inline-block;
                border-radius: 100%;
                background: #000;
                opacity: .2;
            }

            .swiper-pagination-bullet-active {
                opacity: 1;
                background: #88d549;
            }
        </style>
    </head>
    <body>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="<?php echo base_url(); ?>style/default/skin/images/index01.png" width="100%">
                </div>
                <!--  <div class="swiper-slide">
                     <img src="/views/default/skin/images/index01.png" width="100%">
                 </div>
                 <div class="swiper-slide">
                     <img src="/views/default/skin/images/index01.png" width="100%">
                 </div> -->
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <script>
            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true,
                autoplay: 1000,
                spaceBetween: 0,
            });

            $(function () {
                setTimeout(function () {
                    location.href = '<?php echo site_url('web/welcome/home');?>';
                }, 4000);
            });
        </script>
    </body>
</html>




