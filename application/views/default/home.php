<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo config_item('website_title') ?></title>
        <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta content="black" name="apple-mobile-web-app-status-bar-style">
        <meta content="telephone=no" name="format-detection">
        <link rel="stylesheet" href="<?php echo base_url(); ?>style/default/skin/css/swiper.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>style/default/skin/css/style.css">
    </head>
    <body>
        <!-- banner start -->
<!--        <section class="banner">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($adv_list as $value):?>
                    <div class="swiper-slide">
                        <?php echo $value;?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>-->
        <!-- banner end -->
        <section class="gridsitem">
            <!--顶部分类 start-->
            <?php foreach ($cate_list as $key): ?>
                <a href="<?php echo site_url('web/category?cate_id=' . $key["id"]) ?>" class="gridsitem_a1">
                    <div class="gridsitem_icon2">
                        <img src="<?php echo base_url($key['image']) ?>">
                    </div>
                    <p class="grids_label cat_color_<?php echo $key['id'] ?>"><?php echo $key['name'] ?></p>
                </a>
            <?php endforeach; ?>
            <!--顶部分类 end-->
            <a href="<?php echo site_url('web/category'); ?>" class="gridsitem_a1">
                <div class="gridsitem_icon2">
                    <img src="<?php echo base_url(); ?>style/default/skin/images/icon11.png">
                </div>
                <p class="grids_label">更多</p>
            </a>
        </section>
        <!--gridsitem end  -->
        <section class="content">
            <div class="midline">
                <img src="<?php echo base_url(); ?>style/default/skin/images/icon05.png">
            </div>

            <section class="sortwrap">
                <!--单个分类模板start-->
                <?php foreach ($cate_list as $val): ?>
                    <section class="sortbox">
                        <!-- 分类标题 start-->
                        <div class="borderbox" onclick="window.location.href = '<?php echo site_url("web/goods/goods_list?cat_id=" . $key['id']) ?>'">
                            <div class="borderboxL">
                                <img src="<?php echo base_url(); ?>style/default/skin/images/icon12.png">
                            </div>
                            <div class="borderboxM">
                                <?php echo $val['name'] ?>
                            </div>
                            <div class="borderboxR">
                            </div>
                        </div>
                        <!-- 分类标题 end -->
                        <!-- 分类内容 start -->
                        <?php foreach ($goods_list[$val['id']] as $value):?>
                        <div class="sortitembox">
                            <a href="<?php echo site_url("web/goods/product/".$value['id'])?>" class="gridsitem_a2">
                                <div class="sortImgbox">
                                    <img src="<?php echo base_url().$value['image']?>">
                                </div>
                                <p class="pro_name"><?php echo $value['name']?></p>
                                <p class="pro_price">￥<?php echo $value['sell_price']?></p>
                            </a>
                        </div>
                        <?php endforeach;?>
                        <!-- 分类内容 end-->
                    </section>
                <?php endforeach; ?>
            </section>
        </section>


        <script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>public/js/artTemplate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>style/default/skin/js/swiper.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>style/default/skin/js/public.js"></script>
        <?php $this->load->view('default/footer'); ?>
        <script>
                        //图片滑动
                        var swiper = new Swiper('.swiper-container', {
                            pagination: '.swiper-pagination',
                            paginationClickable: true,
                            autoplay: 1000,
                            spaceBetween: 0,
                        });
        </script>
    </body>
</html>




