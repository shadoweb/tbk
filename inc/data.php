<?php
/*
淘宝客信息说明
需要 APP_KEY和SECRET
需要广告位ID setAdzoneId
需要申请API接口权限 店铺搜索 taobao.tbk.shop.get
需要申请API接口权限 物料搜索 taobao.tbk.dg.material.optional
*/
$filepath = __DIR__. DIRECTORY_SEPARATOR;
$dopath = dirname($_SERVER["SCRIPT_FILENAME"]) . DIRECTORY_SEPARATOR;

define('TOP_AUTOLOADER_PATH',$filepath.'sdk');
define('TOP_SDK_WORK_DIR',$filepath.'sdk');
define('APP_KEY','33**22');//阿里妈妈APP_KEY
define('SECRET','3333****223');//阿里妈妈SECRET

require_once($filepath.'function.php');
require_once($filepath.'sdk/Autoloader.php');
//https://open.taobao.com/api.htm?docId=35896&docType=2&scopeId=16516
//https://pub.alimama.com/fourth/tool/api/index.htm

function getShopData($q,$page){
    $filepath = dirname($_SERVER["SCRIPT_FILENAME"]) . DIRECTORY_SEPARATOR;
    $json = 'shop'.md5($q.$page).'.json';
    $jsonpath = $filepath.'json/'.$json;
    $bool = true;
    if(file_exists($jsonpath)){
      //用函数判断文件是否存在，存在则判断时间，过期则重新生成。
      $bool = false;
      $json_file = fopen($jsonpath,'r');
      $nres = json_decode(fgets($json_file), true);//获取数据转成数组
      $date = $nres['date'];
      $old = new DateTime($date);
      $now = new DateTime(date('Y-m-d'));
      $diff = $now->diff($old)->format("%a");
      if($diff > 7) $bool = true;//缓存数据3天有效期
      fclose($json_file);
    }
    if($bool){
        $c = new TopClient;
        $c->appkey = APP_KEY;
        $c->secretKey = SECRET;
        $c->gatewayUrl = 'http://gw.api.taobao.com/router/rest';
        $c->method = 'taobao.tbk.dg.material.optional';
        $c->format = 'json';
        $page = $page > 10 ? 10 : $page;//只取前10页数据
        if(!is_numeric($page)) $page = 1 ;
        $req = new TbkDgMaterialOptionalRequest;
        $req->setQ($q);//搜索词
        $req->setMaterialId("6707");//千人千面
        $req->setAdzoneId("114*****72");//广告位ID
        $req->setSort('total_sales');//按销量排序
        $req->setHasCoupon("true");//只获取有优惠券的商品
        $req->setPageSize("20");//每页显示20个
        $req->setPageNo($page);
        $resp = $c->execute($req);
        $res = json_encode($resp,true);
        $res = json_decode($res,true);
        $datas = $res['result_list']['map_data'];
        $totals = $res['total_results'];
        $shop = array();
        $i = 0;
        foreach ($datas as $data){
            $shop[$i]['url'] = $data['url'];
            $shop[$i]['pict_url'] = $data['pict_url'];
            $shop[$i]['zk_final_price'] = $data['zk_final_price'];
            $shop[$i]['reserve_price'] = $data['reserve_price'];
            $shop[$i]['coupon_amount'] = $data['coupon_amount'];
            $shop[$i]['coupon_info'] = $data['coupon_info'];
            $shop[$i]['coupon_share_url'] = $data['coupon_share_url'];
            $shop[$i]['short_title'] = $data['short_title'];
            $shop[$i]['nick'] = $data['nick'];
            $shop[$i]['volume'] = $data['volume'];
            $i++;
        }
        $nres['data'] = $shop;
        $nres['totals'] = $totals;
        $nres['date'] = date('Y-m-d');
        fopen($jsonpath,'w');
        file_put_contents($jsonpath,json_encode($nres));
    }
    return $nres;
}

function getStoreData($q,$page){
    $c = new TopClient;
    $c->appkey = APP_KEY;
    $c->secretKey = SECRET;
    $c->gatewayUrl = 'http://gw.api.taobao.com/router/rest';
    $c->method = 'taobao.tbk.shop.get';
    $c->format = 'json';
    $page = $page > 10 ? 10 : $page;//只取前10页数据
    if(!is_numeric($page)) $page = 1 ;//默认取第一页数据
    $req = new TbkShopGetRequest;
    $req->setQ($q);
    $req->setFields("user_id,shop_title,shop_type,seller_nick,pict_url,shop_url");
    $req->setSort('total_auction');
    $req->setPageSize("20");
    $req->setPageNo($page);
    $resp = $c->execute($req);
    $res = json_encode($resp,true);
    $res = json_decode($res,true);
    $data = $res['results']['n_tbk_shop'];
    //print_r($data[0]);
    $totals = $res['total_results'];
    $nres['data'] = $data;
    $nres['totals'] = $totals;
    return $nres;
}

function getKeywordsDate(){
    $filepath = dirname($_SERVER["SCRIPT_FILENAME"]) . DIRECTORY_SEPARATOR;
    if(ii_isMobileAgent()) $file = $filepath.'data/m.txt';
    else $file = $filepath.'data/pc.txt';
    $keywords = array();
    if(file_exists($file)){
        $keylist = file_get_contents($file);
        $keywords=explode("\r\n", trim($keylist));
    }
    return $keywords;
}

function getSearchDate(){
    $filepath = dirname($_SERVER["SCRIPT_FILENAME"]) . DIRECTORY_SEPARATOR;
    $searchpath = $filepath.'data/search.json';
    $keywords = array();
    if(file_exists($searchpath)){
        $keylist = file_get_contents($searchpath);
        if(!empty($keylist)){
            $keywords = json_decode($keylist,true);
        }
    }
    return $keywords;
}

function saveSearchData($q){
    $filepath = dirname($_SERVER["SCRIPT_FILENAME"]) . DIRECTORY_SEPARATOR;
    $searchpath = $filepath.'data/search.json';
    if(file_exists($searchpath)){
       $data = getSearchDate();
       if(is_array($data) && count($data) > 0){
           $bool = true;
           for($i=1;$i<count($data) + 1;$i++){
               if($data[$i]['key'] == $q){
                   $data[$i]['hot'] = $data[$i]['hot'] + 1;
                   $bool = false;
                   break;
               }
           }
           if($bool){
               $length = is_numeric(count($data)) ? count($data) + 1 : 1;
               $data[$length]['key'] = $q;
               $data[$length]['hot'] = 1;
               $bool = false;
           }
       }else{
            $data = array();
            $data[1]['key'] = $q;
            $data[1]['hot'] = 1;
        }
        $res = json_encode($data,true);
        $data = array();
    }
    fopen($searchpath,'w');
    file_put_contents($searchpath,$res);
}


