<?php
/**
 * TOP API: taobao.baichuan.microservice.iteminfo request
 * 
 * @author auto create
 * @since 1.0, 2016.05.19
 */
class BaichuanMicroserviceIteminfoRequest
{
	/** 
	 * 扩展信息，特殊业务可以，可以不用传
	 **/
	private $extra;
	
	/** 
	 * 商品id
	 **/
	private $itemId;
	
	/** 
	 * PC端的商品详情URL
	 **/
	private $itemUrl;
	
	/** 
	 * 埋点接入APP的appKey值
	 **/
	private $traceAppKey;
	
	/** 
	 * 埋点信息，这个埋点可以直接打入到手机淘宝创建的订单中
	 **/
	private $tracer;
	
	private $apiParas = array();
	
	public function setExtra($extra)
	{
		$this->extra = $extra;
		$this->apiParas["extra"] = $extra;
	}

	public function getExtra()
	{
		return $this->extra;
	}

	public function setItemId($itemId)
	{
		$this->itemId = $itemId;
		$this->apiParas["item_id"] = $itemId;
	}

	public function getItemId()
	{
		return $this->itemId;
	}

	public function setItemUrl($itemUrl)
	{
		$this->itemUrl = $itemUrl;
		$this->apiParas["item_url"] = $itemUrl;
	}

	public function getItemUrl()
	{
		return $this->itemUrl;
	}

	public function setTraceAppKey($traceAppKey)
	{
		$this->traceAppKey = $traceAppKey;
		$this->apiParas["trace_app_key"] = $traceAppKey;
	}

	public function getTraceAppKey()
	{
		return $this->traceAppKey;
	}

	public function setTracer($tracer)
	{
		$this->tracer = $tracer;
		$this->apiParas["tracer"] = $tracer;
	}

	public function getTracer()
	{
		return $this->tracer;
	}

	public function getApiMethodName()
	{
		return "taobao.baichuan.microservice.iteminfo";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
