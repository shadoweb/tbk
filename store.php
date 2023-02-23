<?php
include('inc/data.php');
//https://open.taobao.com/api.htm?docId=35896&docType=2&scopeId=16516
//https://pub.alimama.com/fourth/tool/api/index.htm
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$page = $page > 10 ? 10 : $page;

$q = $_GET['q'] ? $_GET['q'] : '女装';

$datas = getStoreData($q,$page);

$data = $datas['data'];
$totals = $datas['totals'];

//print_r($data[0]);

$constr = '<ul style="list-style:none;max-width:1200px;margin:0 auto;padding:0;">';
$contemp = file_get_contents('template/store.html');
$tmpstr = '';
foreach ($data as $shop){
    $url = $shop['shop_url'];
    $pic = $shop['pict_url'];
    $title = $shop['shop_title'];
    $cstr = str_replace('{$url}',$url,$contemp);
    $cstr = str_replace('{$pic}',$pic,$cstr);
    $cstr = str_replace('{$title}',$title,$cstr);
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
$str = str_replace('{$q}',$q,$temp);
$str = str_replace('{$list}',$constr,$str);
$str = str_replace('{$pagestr}',$pagestr,$str);
echo $str;


