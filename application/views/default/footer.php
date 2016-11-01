<footer>
    <div class="blank"></div>
    <div class="navbar">
        <div class="navwrap  flexbox">
            <a href="<?php site_url('web/activity') ?>" class="navbar_a  <{if get_segment(2)=='activity'}>navbarF<{/if}>"  >
                <div class="navbar_imgbox">
                    <img src="<?php echo base_url() ?>style/default/skin/images/foot0<?php if (get_segment(2) == 'activity'): ?>0<?php endif; ?>1.png" >
                </div>
                <p class="navbar_label">活动</p>
            </a>
            <a href="<?php echo site_url('web/welcome/home') ?>" class="navbar_a <?php if (get_segment(2) == '' || get_segment(2) == 'welcome' || get_segment(2) == 'goods'): ?>navbarF<?php endif; ?>" >
                <div class="navbar_imgbox">
                    <img src="<?php echo base_url() ?>style/default/skin/images/foot0<?php if (get_segment(2) == '' || get_segment(2) == 'welcome' || get_segment(2) == 'goods'): ?>0<?php endif; ?>2.png" >
                </div>
                <p class="navbar_label">商城</p>
            </a>
            <a href="<?php echo site_url('web/cart') ?>" class="navbar_a  <?php if (get_segment(2) == 'cart'): ?>navbarF<?php endif; ?>">
                <div class="navbar_imgbox">
                    <img src="<?php echo base_url() ?>style/default/skin/images/foot0<?php if (get_segment(2) == 'cart'): ?>0<?php endif; ?>3.png" >
                    <div class="dot"  id="my_cart_count">0</div>
                </div>
                <p class="navbar_label">购物车</p>
            </a>
            <a href="<?php echo site_url('web/member') ?>"  class="navbar_a <?php if (get_segment(2) == 'member'): ?>navbarF<?php endif; ?>">
                <div class="navbar_imgbox">
                    <img src="<?php echo base_url() ?>style/default/skin/images/foot0<?php if (get_segment(2) == 'member'): ?>0<?php endif; ?>4.png">
                </div>
                <p class="navbar_label">个人中心</p>
            </a>
        </div>
    </div>
</footer>
<script language="JavaScript">
    $(function () {
        //购物车基本信息展示
        $.ajax({
            type: "POST",
            url: "/api/cart/cart_count",
            data: "",
            dataType: "json",
            success: function (data) {
                if (data.status == 'y') {
                    if (data.result.sku_count > 0) {
                        $('#my_cart_count').text(data.result.sku_count);
                        $('#my_cart_count').show();
                    } else {
                        $('#my_cart_count').hide();
                        $('#top_my_cart_count').hide();
                    }
                }
            }
        });
    })
</script>