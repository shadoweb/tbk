<?php

/**
 * 产品结构
 * @author auto create
 */
class Product
{
	
	/** 
	 * 产品的非关键属性列表.格式:pid:vid;pid:vid.
	 **/
	public $binds;
	
	/** 
	 * 产品的非关键属性字符串列表.格式同props_str(<strong>注：</strong><font color="red">属性名称中的冒号":"被转换为："#cln#";  
分号";"被转换为："#scln#"
</font>)
	 **/
	public $binds_str;
	
	/** 
	 * 商品类目名称
	 **/
	public $cat_name;
	
	/** 
	 * 商品类目ID.必须是叶子类目ID
	 **/
	public $cid;
	
	/** 
	 * 创建时间.格式:yyyy-mm-dd hh:mm:ss
	 **/
	public $created;
	
	/** 
	 * 用户自定义属性,结构：pid1:value1;pid2:value2 例如：“20000:优衣库”，表示“品牌:优衣库”
	 **/
	public $customer_props;
	
	/** 
	 * 产品的描述.最大25000个字节
	 **/
	public $desc;
	
	/** 
	 * 修改时间.格式:yyyy-mm-dd hh:mm:ss
	 **/
	public $modified;
	
	/** 
	 * 产品名称
	 **/
	public $name;
	
	/** 
	 * 外部产品ID
	 **/
	public $outer_id;
	
	/** 
	 * 产品的主图片地址.(绝对地址,格式:http://host/image_path)
	 **/
	public $pic_url;
	
	/** 
	 * 产品的市场价.单位为元.精确到2位小数;如:200.07
	 **/
	public $price;
	
	/** 
	 * 产品ID
	 **/
	public $product_id;
	
	/** 
	 * 产品的子图片.目前最多支持4张。fields中设置为product_imgs.id、product_imgs.url、product_imgs.position 等形式就会返回相应的字段
	 **/
	public $product_imgs;
	
	/** 
	 * 产品的属性图片.比如说黄色对应的产品图片,绿色对应的产品图片。fields中设置为product_prop_imgs.id、 
product_prop_imgs.props、product_prop_imgs.url、product_prop_imgs.position等形式就会返回相应的字段
	 **/
	public $product_prop_imgs;
	
	/** 
	 * 销售属性值别名。格式为pid1:vid1:alias1;pid1:vid2:alia2。
	 **/
	public $property_alias;
	
	/** 
	 * 产品的关键属性列表.格式：pid:vid;pid:vid
	 **/
	public $props;
	
	/** 
	 * 产品的关键属性字符串列表.比如:品牌:诺基亚;型号:N73(<strong>注：</strong><font color="red">属性名称中的冒号":"被转换为："#cln#";  
分号";"被转换为："#scln#"
</font>)
	 **/
	public $props_str;
	
	/** 
	 * 产品的销售属性列表.格式:pid:vid;pid:vid
	 **/
	public $sale_props;
	
	/** 
	 * 产品的销售属性字符串列表.格式同props_str(<strong>注：</strong><font color="red">属性名称中的冒号":"被转换为："#cln#";  
分号";"被转换为："#scln#"
</font>)
	 **/
	public $sale_props_str;
	
	/** 
	 * 产品卖点描述，长度限制20个汉字
	 **/
	public $sell_pt;
	
	/** 
	 * 当前状态(0 商家确认 1 屏蔽 3 小二确认 2 未确认 -1 删除)
	 **/
	public $status;
	
	/** 
	 * 淘宝标准产品编码
	 **/
	public $tsc;
	
	/** 
	 * 垂直市场,如：3（3C），4（鞋城）
	 **/
	public $vertical_market;	
}
?>