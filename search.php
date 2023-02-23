<?php
include('inc/data.php');
//https://open.taobao.com/api.htm?docId=35896&docType=2&scopeId=16516
//https://pub.alimama.com/fourth/tool/api/index.htm
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$page = $page > 10 ? 10 : $page;

$q = isset($_GET['q']) && !empty(trim($_GET['q'])) ? trim($_GET['q']) : 'nosearchkeyword';

if($q != 'nosearchkeyword'){

    $datas = getShopData($q,$page);
    
    $data = $datas['data'];
    $totals = $datas['totals'];
    
    if($totals > 0) saveSearchData($q);
    
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
    $page_num = ceil($totals/20) > 10 ? 10 : ceil($totals/20);
    for($i=1;$i<=$page_num;$i++){
        $pagestr .= '<a href="?q='.$q.'&page='.$i.'">'.$i.'</a> | ';
    }
    
    $temp = file_get_contents('template/list.html');

}else{
    
    $keywords = getSearchDate();
    $hot = array_column($keywords,'hot');
    array_multisort($hot,SORT_DESC,$keywords);
    $totals = count($keywords);
    $pageno = '150';
    $pages = ceil($totals/$pageno);
    
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
    $page = $page > 100 ? 100 : $page;
    
    $start = 0;
    
    if($page > 1) $start = ($page-1)*$pageno;
    
    $keys = array_slice($keywords,$start,$pageno);
    
    $constr = '<ul style="list-style:none;max-width:1200px;margin:0 auto;padding:0;">';
    $contemp = file_get_contents('template/keywords.html');
    $tmpstr = '';
    
    foreach ($keys as $keyword){
        $url = './search.php?q='.$keyword['key'];
        $cstr = str_replace('{$url}',$url,$contemp);
        $cstr = str_replace('{$keyword}',$keyword['key'],$cstr);
        $tmpstr .= $cstr;
    }
    
    $constr .= $tmpstr;
    $constr .= '</ul>';
    
    $pagestr = '';
    
    for($i=1;$i<=$pages;$i++){
        $pagestr .= '<a href="?page='.$i.'">'.$i.'</a> | ';
    }

    
    
    $temp = file_get_contents('template/search.html');
}
    $str = str_replace('{$q}',$q,$temp);
    $str = str_replace('{$list}',$constr,$str);
    $str = str_replace('{$pagestr}',$pagestr,$str);
    echo $str;


