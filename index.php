<?php
include('inc/data.php');
$keywords = getKeywordsDate();
$totals = count($keywords);
$pageno = '150';
$pages = ceil($totals/$pageno);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$page = $page > $pages ? $pages : $page;

$start = 0;

if($page > 1) $start = ($page-1)*$pageno;

$keys = array_slice($keywords,$start,$pageno);

$constr = '<ul style="list-style:none;max-width:1200px;margin:0 auto;padding:0;">';
$contemp = file_get_contents('template/keywords.html');
$tmpstr = '';

foreach ($keys as $keyword){
    $url = './list.php?q='.$keyword;
    $cstr = str_replace('{$url}',$url,$contemp);
    $cstr = str_replace('{$keyword}',$keyword,$cstr);
    $tmpstr .= $cstr;
}

$constr .= $tmpstr;
$constr .= '</ul>';

$pagestr = '';

$prepage = ($page - 1) > 0 ? $page - 1 : 1;
$nextpage = ($page + 1) < $pages ? $page + 1 : $pages;
$pagestr .= '<a style="margin:5px;padding:3px 10px;border:1px solid #666;" href="?page='.$prepage.'">上一页</a>  ';
for($i=1;$i<=$pages;$i++){
    if($i == $page) $pagestr .= '<a style="display: inline-block;margin:5px;padding:3px 10px;border:1px solid #666;background:#666;color:#eee;" href="?page='.$i.'">'.$i.'</a>  ';
    else $pagestr .= '<a style="display: inline-block;margin:5px;padding:3px 10px;border:1px solid #666;" href="?page='.$i.'">'.$i.'</a>';
}
$pagestr .= '<a style="margin:5px;padding:3px 10px;border:1px solid #666;" href="?page='.$nextpage.'">下一页</a>';
$pagestr .= '<a style="margin:5px;padding:3px 10px;border:1px solid #666;background:#666;color:#eee;">共'.$pages.'页</a>';

$temp = file_get_contents('template/index.html');
$str = str_replace('{$list}',$constr,$temp);
$str = str_replace('{$pagestr}',$pagestr,$str);
echo $str;