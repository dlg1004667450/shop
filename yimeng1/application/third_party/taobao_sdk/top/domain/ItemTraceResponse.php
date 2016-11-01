<?php

/**
 * 商品信息和拼接好埋点信息的无线商品详情地址
 * @author auto create
 */
class ItemTraceResponse
{
	
	/** 
	 * 错误码
	 **/
	public $error_code;
	
	/** 
	 * 错误信息描述
	 **/
	public $error_message;
	
	/** 
	 * 商品id
	 **/
	public $item_id;
	
	/** 
	 * 商品类型，枚举类型,比如天猫商品tmall，淘宝商品taobao，阿里鱼授权商品,alifish，因此返回值为：tmall;alifish
	 **/
	public $item_type;
	
	/** 
	 * 商品原价
	 **/
	public $o_price;
	
	/** 
	 * 商品图片URL，用";"分割
	 **/
	public $pic_urls;
	
	/** 
	 * 商品优惠价
	 **/
	public $prom_price;
	
	/** 
	 * 是否支持手机淘宝无线端购买，极少数商品不支持无线端购买，只支持PC端购买，这部分商品通过这个字段标示
	 **/
	public $supported;
	
	/** 
	 * 商品标题
	 **/
	public $title;
	
	/** 
	 * 是否支持埋点透传到订单，如果商品详情在手机淘宝用h5打开就标示不能将打点存入订单，但是数量比较少，必须标记出来
	 **/
	public $trace_supported;
	
	/** 
	 * 带上打点信息，可以生成二维码的商品详情URL
	 **/
	public $trace_url;	
}
?>