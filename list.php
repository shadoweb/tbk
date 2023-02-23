<?php
include('inc/data.php');
//https://open.taobao.com/api.htm?docId=35896&docType=2&scopeId=16516
//https://pub.alimama.com/fourth/tool/api/index.htm
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$page = $page > 10 ? 10 : $page;

$q = isset($_GET['q']) && !empty(trim($_GET['q'])) ? trim($_GET['q']) : 'php';

$datas = getShopData($q,$page);

$data = $datas['data'];
$totals = $datas['totals'];

//print_r($data[0]);

$constr = '<ul style="list-style:none;max-width:1200px;margin:0 auto;padding:0;">';
$tmpstr = '';
foreach ($data as $shop){
    $url = !empty($shop['coupon_share_url']) ? $shop['coupon_share_url'] : $shop['url'];
    $pic = $shop['pict_url'];
    $price = $shop['zk_final_price'];
    $oldprice = $shop['reserve_price'];
    $coupon = $shop['coupon_amount'];
    $coupon_info = $shop['coupon_info'];
    $title = $shop['short_title'];
    $store = $shop['nick'];
    $volume = $shop['volume'];
    if(is_numeric($coupon)) $contemp = file_get_contents('template/shop_coupon.html');//有优惠券模板
    else $contemp = file_get_contents('template/shop.html');//无优惠券模板
    $cstr = str_replace('{$url}',$url,$contemp);
    $cstr = str_replace('{$pic}',$pic,$cstr);
    $cstr = str_replace('{$price}',$price,$cstr);
    $cstr = str_replace('{$oldprice}',$oldprice,$cstr);
    $cstr = str_replace('{$coupon}',$coupon,$cstr);
    $cstr = str_replace('{$coupon_info}',$coupon_info,$cstr);
    $cstr = str_replace('{$title}',$title,$cstr);
    $cstr = str_replace('{$store}',$store,$cstr);
    $cstr = str_replace('{$volume}',$volume,$cstr);
    $tmpstr .= $cstr;
}
$constr .= $tmpstr;
$constr .= '</ul>';
$pagestr = '';
$pages = ceil($totals/20) > 10 ? 10 : ceil($totals/20);

$prepage = ($page - 1) > 0 ? $page - 1 : 1;
$nextpage = ($page + 1) < $pages ? $page + 1 : $pages;
$pagestr .= '<a style="margin:5px;padding:3px 10px;border:1px solid #666;" href="?q='.$q.'&page='.$prepage.'">上一页</a>  ';
for($i=1;$i<=$pages;$i++){
    if($i == $page) $pagestr .= '<a style="display: inline-block;margin:5px;padding:3px 10px;border:1px solid #666;background:#666;color:#eee;" href="?q='.$q.'&page='.$i.'">'.$i.'</a>  ';
    else $pagestr .= '<a style="display: inline-block;margin:5px;padding:3px 10px;border:1px solid #666;" href="?q='.$q.'&page='.$i.'">'.$i.'</a>';
}
$pagestr .= '<a style="margin:5px;padding:3px 10px;border:1px solid #666;" href="?q='.$q.'&page='.$nextpage.'">下一页</a>';
$pagestr .= '<a style="margin:5px;padding:3px 10px;border:1px solid #666;background:#666;color:#eee;">共'.$pages.'页</a>';



$temp = file_get_contents('template/list.html');
$str = str_replace('{$q}',$q,$temp);
$str = str_replace('{$list}',$constr,$str);
$str = str_replace('{$pagestr}',$pagestr,$str);
echo $str;


