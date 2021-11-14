<?php
header("Content-Type: text/html; charset=UTF-8");
libxml_use_internal_errors(true);
//建议php版本7 开启curl扩展
$typeid =$_GET["t"];
$page = $_GET["pg"];
$ids = $_GET["ids"];
$burl = $_GET["url"];
$wd = $_GET["wd"];

//===============================================基础配置开始===========================================
$web='https://list.iqiyi.com';
//1=开启搜索  0=关闭搜索 默认关闭搜索
$searchable=1;
//1=开启首页推荐  0=关闭首页推荐
$indexable=1;

//====================以下内容可忽略不修改===================

//如不懂可以不填写
$cookie='';

//当影视详情没有影视图片或取图片失败时，返回该指定的图片链接(不设置的话，缺图时历史记录的主图会空白)
$historyimg='https://www.hjunkel.com/images/nopic2.gif';

//模拟ua 如非不要默认即可
$UserAgent='Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36';

//1=开启直链分析  0=关闭直链分析 (直链也是通过本php页面解析) 测试极品关闭直链 大部分能通过webview解析
//该模板的直链代码是针对极品影视的，每个站的直链代码都不同。其他网站请设置为0关闭
$zhilian=0;
//====================以上内容可忽略不修改===================



//===============================================基础配置结束===========================================




//===============================================广告图片配置开始 可以不用修改 默认不开启=======================================
//$adable=1开启广告  $adable=0关闭广告图片  可插入指定图片到每次读取第一页影视列表的开头，默认关闭
$adable=0;
$adpicurl='https://alifei05.cfp.cn/creative/vcg/800/version23/VCG41184086603.jpg';
$adtitle1='我是片名';
$adtitle2='我是更新内容';
//===============================================广告图片配置结束 可以不用修改 默认不开启============================================





//===============================================影视分类相关配置开始===========================
$movietype = '{"class":[{"type_id":"1","type_name":"电 影","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48"},{"type_id":"2","type_name":"连续 剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48"},{"type_id":"3","type_name":"综艺","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=6&data_type=1&mode=24&page_id={pageid}&ret_num=48"},{"type_id":"4","type_name":"动漫","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=4&data_type=1&mode=24&page_id={pageid}&ret_num=48"},{"type_id":"5","type_name":"网络电影","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=27401;must"},{"type_id":"6","type_name":"古装剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=24;must"},{"type_id":"7","type_name":"自制剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=11992;must"},{"type_id":"8","type_name":"武侠剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=23;must"},{"type_id":"9","type_name":"偶像剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=30;must"},{"type_id":"10","type_name":"家庭剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=1654;must"},{"type_id":"11","type_name":"青春剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=1653;must"},{"type_id":"12","type_name":"都市剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=24064;must"},{"type_id":"13","type_name":"喜剧","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=135;must"},{"type_id":"14","type_name":"喜剧片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=8;must"},{"type_id":"15","type_name":"动作片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=11;must"},{"type_id":"16","type_name":"爱情片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=6;must"},{"type_id":"17","type_name":"惊悚片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=128;must"},{"type_id":"18","type_name":"犯罪片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=291;must"},{"type_id":"19","type_name":"悬疑片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=289;must"},{"type_id":"20","type_name":"战争片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=7;must"},{"type_id":"21","type_name":"科幻片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=9;must"},{"type_id":"22","type_name":"动画片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=12;must"},{"type_id":"23","type_name":"恐怖片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=10;must"},{"type_id":"24","type_name":"枪战片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=131;must"},{"type_id":"25","type_name":"奇幻片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=1284;must"},{"type_id":"26","type_name":"魔幻片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=129;must"},{"type_id":"27","type_name":"青春片","catname":"https://pcw-api.iqiyi.com/search/recommend/list?channel_id=1&data_type=1&mode=24&page_id={pageid}&ret_num=48&three_category_id=130;must"}]}';
//===============================================影视分类相关配置结束===========================





//===============================================首页推荐相关配置开始===========================
//读取首页的链接
$indexweb='https://pcw-api.iqiyi.com/search/recommend/list?channel_id=2&data_type=1&mode=24&page_id=1&ret_num=48';

//首页最多读取多少个影片
$indexnum=48;
//===============================================首页推荐相关配置结束===========================







//===============================================影视详情相关配置开始===========================
//影片名称
$vodtitle="//div[@class='head-title']/h2[@class='header-txt']/a[@class='header-link']";
//===============================================影视详情相关配置结束===========================







//===============================================影视搜索相关配置开始===========================
//=========下面把xpath规则的搜索屏蔽了，极品采用json的搜索结果========
$searchtype=1;


//影片搜索 {wd}=搜索文字
//$searchtype=1的网址
$search='https://so.iqiyi.com/so/q_{wd}_ctg__t_0_page_1_p_1_qc_0_rd__site_iqiyi_m_1_bitrate__af_0';

$searchurl1='';

$searchurl2='';

//xpath列表
$searchquery="//div[@class='qy-search-result-item vertical-pic']/div[@class='result-figure']/div/div[@class='qy-mod-link-wrap']/a[@class='qy-mod-link']";

//xpath规则取出影片的标题
$searchtitleAttr="//div[@class='qy-search-result-item vertical-pic']/div[@class='result-figure']/div/div[@class='qy-mod-link-wrap']/a[@class='qy-mod-link']/@title";

//xpath规则取出影片的链接
$searchlinkAttr="//div[@class='qy-search-result-item vertical-pic']/div[@class='result-figure']/div/div[@class='qy-mod-link-wrap']/a[@class='qy-mod-link']/@href";

//xpath规则取影视更新情况 例如：更新至*集
$searchquery2 ="1";


//-----------------------------如非必要，下面4项可以不用修改-------------------------------
//影片标题是否精确匹配  1=精确匹配（必须包含搜索文字）  0为关闭精确匹配，显示所有搜索结果
$titlematch=1;
//搜索访问类型 1=get  2=post 一般默认为1
$datatype=1;
//搜索访问提交数据 当$datatype为2时，需要在此处填写提交数据 关键词用{wd}代替
$searchdata='';
//{wd}提交的编码格式  1=utf-8编码  2=gb2312编码(大部分网站默认为utf-8即可)
$convert=1;
//-----------------------------如非必要，上面4项可以不用修改-------------------------------



//===============================================影视搜索相关配置结束===========================
//==============================================仅需修改以上代码↑=======================================


//==============================================以下内容的代码无需修改↓=======================================
$weburl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
if ($typeid<> null && $page<>null){
//==============================================读取影视列表开始=======================================
$catname ='';
$arr=json_decode($movietype,true);
$arr_q1a=$arr['class'];
$m=count($arr_q1a);
 for($i=0;$i<$m;$i++){
 $type_id = $arr_q1a[$i]["type_id"];
 if($typeid==$type_id){
  $catname =  $arr_q1a[$i]["catname"];
  break;
 }
 }
$catname=str_replace('{pageid}',$page,$catname);
$html ='';
for ($i = 0; $i < 3; $i++) {
$html = curl_get($catname,$cookie,$UserAgent);
if($html<>null){
break;
}
}
$arr=json_decode($html,true);
$arr_q1a=$arr['data']['list'];
$m=count($arr_q1a);
$length=$m;
$guolv='';
if ($adable==1 && $page==1){
$length=$length+1;
}
if ($length<$num)
{
$page2=$page;
}else{
$length=$length+1;
$page2=$page + 1;
}
$result='{"code":1,"page":'.$page.',"pagecount":'. $page2 .',"total":'. $length.',"list":[';
if ($adable==1 && $page==1){
    $result=$result.'{"vod_id":"888888888","vod_name":"'.$adtitle1.'","vod_pic":"'.$adpicurl.'","vod_remarks":"'.$adtitle2.'"},';
}
for ($i = 0; $i < $m; $i++) {
$latestOrder=$arr['data']['list'][$i]['latestOrder'];
$videoCount=$arr['data']['list'][$i]['videoCount'];
if($latestOrder==1 && $videoCount==1){
$text = $arr['data']['list'][$i]['score'].'分';
}else{
if($latestOrder==$videoCount && $latestOrder<>0){
$text = '共'.$latestOrder.'集';
}else{

if($videoCount>0){
$text = '连载至'.$latestOrder.'/'.$videoCount;
}else if($latestOrder>0){
$text = '连载至'.$latestOrder;
}else{
$text ='';
}


}
}
$link = $arr['data']['list'][$i]['playUrl'];
$title =$arr['data']['list'][$i]['name'];
$pic = $arr['data']['list'][$i]['imageUrl'];
$link = str_replace('http:','https:',$link);
   if (substr($pic,0,2)=='//'){
	$pic = 'https:'.$pic;
	}

if($guolv==null){
	    $result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'","vod_pic":"'.$pic.'","vod_remarks":"'.$text.'"},';
    	$guolv=$guolv."{".$link."}";
}else if(strpos($guolv, "{".$link2."}")===false){
	    $result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'","vod_pic":"'.$pic.'","vod_remarks":"'.$text.'"},';
    	$guolv=$guolv."{".$link."}";
	}
 
}

$result=substr($result, 0, strlen($result)-1).']}';
echo $result;
//==============================================读取影视列表结束=======================================
}else if ($ids<> null && strpos($ids, ",")===false && strpos($ids, "%2C")===false){
if($ids=='888888888'){
$result='{"list":[{"vod_id":"888888888",';
$result=$result.'"vod_name":"'.$adtitle1.'",';
$result=$result.'"vod_pic":"'.$adpicurl.'",';
$actor='内详';
$result=$result.'"vod_actor":"'.$actor.'",';
$director='内详';
$result=$result.'"vod_director":"'.$director.'",';
$result=$result.'"vod_content":"'.$adtitle2.'",';
$result= $result.'"vod_play_from":"'."无播放源".'",';
$result= $result.'"vod_play_url":"'."1".'"}]}';
echo $result;
}else{
//==============================================读取影视信息开始=======================================
$html ='';
for ($i = 0; $i < 3; $i++) {
$html = curl_get($ids,$cookie,$UserAgent);
if($html<>null){
break;
}
}
$dom = new DOMDocument();
$html= mb_convert_encoding($html ,'HTML-ENTITIES',"UTF-8");
$dom->loadHTML($html);
$dom->normalize();
$xpath = new DOMXPath($dom);
if($vodtitle<>null){
$texts = $xpath->query($vodtitle);
$text = $texts->item(0)->nodeValue;
$text = replacestr($text);
}

if($text==null){
$texts = $xpath->query("//h1[@class='album-head-title']/a[@class='title-link  J-read-play-record']");
$text =replacestr($texts->item(0)->nodeValue);
}

$texts = $xpath->query("//ul[@class='intro-detail']/li[@class='intro-detail-item'][1]/span[@class='item-content']/span[@class='name-wrap']/a[@class='name-link']");
$director =replacestr($texts->item(0)->nodeValue);
$texts = $xpath->query("//ul[@class='intro-detail']/li[@class='intro-detail-item'][2]/span[@class='item-content']/span[@class='name-wrap']/a[@class='name-link']");
$actor ='';
for ($i = 0; $i < $texts->length; $i++) {
    $actor = $actor.$texts->item($i)->nodeValue.' ';
}
if($actor==null){
$texts = $xpath->query("//div[@class='episodeIntro']/div[@class='episodeIntro-item tag-seo']/div[@class='item-seo'][4]/a[@class='tag-val itemprop=']");
for ($i = 0; $i < $texts->length; $i++) {
    $actor = $actor.$texts->item($i)->nodeValue.' ';
}
}

$texts = $xpath->query("//ul[@class='intro-detail']/li[@class='intro-detail-item'][3]/span[@class='item-content']/span[@class='content-paragraph']");
$vodtext2=replacestr($texts->item(0)->nodeValue);

if($vodtext2==null){
$texts = $xpath->query("//div[@class='info-intro']/div[@class='episodeIntro']/div[@class='episodeIntro-brief']");
$vodtext2=replacestr($texts->item(0)->nodeValue);
}

if($vodtext2==null){
$texts = $xpath->query("//ul[@class='intro-detail']/li[@class='intro-detail-item']/span[@class='item-content']/span[@class='content-paragraph']");
$vodtext2 =replacestr($texts->item(0)->nodeValue);
}
$vodtext2=str_replace('简介：','',$vodtext2);

$texts = $xpath->query("//div[@class='intro-left']/a[@class='intro-img-link disabled']/img[@class='intro-img']/@src");
$img=replacestr($texts->item(0)->nodeValue);

if($img==null){
if(strpos($html,'itemprop="image" content="')>0){
$img= getSubstr($html, 'itemprop="image" content="', '"/');
}
}

if($img==null){
if(strpos($html,'property="og:image" content="')>0){
$img= getSubstr($html, 'property="og:image" content="', '"/');
}
}

if (substr($img,0,2)=='//'){
$img = 'https:'.$img;
}
if($img==null){
$img= $historyimg;
}

$texts = $xpath->query("//div[@class='detail-left']/div[@id='titleRow']/div[@class='intro-mn']/div[@class='intro-mnc']/div[@class='qy-player-tag']/a[@class='tag-item']");
$type =replacestr($texts->item(0)->nodeValue);



if(strpos($html,"param['albumid'] = \"")>0){
$albumid=getSubstr($html, "param['albumid'] = \"", '";');
}

if(strpos($html,"param['tvid'] = \"")>0){
$tvid=getSubstr($html, "param['tvid'] = \"", '";');
}

if($tvid==null){
if(strpos($html,'tvId: "')>0){
$tvid=getSubstr($html, 'tvId: "', '",');
}
}

if($tvid==null){
if(strpos($html,'"tvId":')>0){
$tvid=getSubstr($html, '"tvId":', ',"');
}
}

if($albumid==null){
if(strpos($html,'"albumId":"')>0){
$albumid=getSubstr($html, '"albumId":"', '",');
}
}

if($albumid==null){
if(strpos($html,'"albumId":')>0){
$albumid=getSubstr($html, '"albumId":', ',"');
}
}

$result='{"list":[{"vod_id":"'.$ids.'",';
if($text==null){
$text='片名获取失败';
}
$result=$result.'"vod_name":"'.$text.'",';
if($img<>null){
$result=$result.'"vod_pic":"'.$img.'",';
}
if($type<>null){
$result=$result.'"type_name":"'.$type.'",';
}
if($year<>null){
$result=$result.'"vod_year":"'.$year.'",';
}
if($actor==null){
$actor='内详';
}
$result=$result.'"vod_actor":"'.$actor.'",';
if($director==null){
$director='内详';
}
$result=$result.'"vod_director":"'.$director.'",';
if($area<>null){
$result=$result.'"vod_area":"'.$area.'",';
}
if($vodtext2<>null){
$vodtext2=str_replace('"','\"',$vodtext2);
$result=$result.'"vod_content":"'.$vodtext2.'",';
}

$yuan = 'iqiyi';
if($albumid=='0'){
$result= $result.'"vod_play_from":"'."iqiyi".'",';
$result= $result.'"vod_play_url":"'.'高清$'.$ids.'"}]}';
}else{
$ids2='https://pcw-api.iqiyi.com/albums/album/avlistinfo?aid='.$albumid.'&page=1&size=40';
$html ='';
for ($i = 0; $i < 3; $i++) {
$html = curl_get($ids2,$cookie,$UserAgent);
if($html<>null){
break;
}
}
$arr=json_decode($html,true);
$arr_page=$arr['data']['page'];
$arr_q1a=$arr['data']['epsodelist'];
$m=count($arr_q1a);
$dizhi='';
if($m<=1){
if($tvid<>null && $albumid<>null){
$ids2='https://pcw-api.iqiyi.com/album/source/listByNumber/'.$albumid.'?include=true&number=100&seq=true&tvId='.$tvid;
$html = curl_get($ids2,$cookie,$UserAgent);
$arr=json_decode($html,true);
$arr_q1a=$arr['data'];
$m=count($arr_q1a);
for($i=0;$i<$m;$i++) 
{ 
$name=$arr['data'][$i]['shortTitle'];
$name=str_replace($text,"",$name);
$dizhi2=$arr['data'][$i]['playUrl'];
$dizhi=$dizhi.$name.'$'.$dizhi2.'#';
}

}else{
$texts1 = $xpath->query("//ul[@class='qy-play-list']/li[@class='play-list-item ']/div[@class='mod-right']/div[@class='vertical-center-box']/h3[@class='main-title']/a[@class='title-link']");
$texts2 = $xpath->query("//ul[@class='qy-play-list']/li[@class='play-list-item ']/div[@class='mod-right']/div[@class='vertical-center-box']/h3[@class='main-title']/a[@class='title-link']/@href");
for ($i = 0; $i < $texts1->length; $i++) {
    $event1 = $texts1->item($i);
    $event2 = $texts2->item($i);
    $dizhi=$dizhi.$event1->nodeValue.'$'.$event2->nodeValue.'#'; 
}
}



}else{
for($i=0;$i<$m;$i++) 
{ 
$name=$arr['data']['epsodelist'][$i]['name'];
$name=str_replace($text,"",$name);
$dizhi2=$arr['data']['epsodelist'][$i]['playUrl'];
$dizhi=$dizhi.$name.'$'.$dizhi2.'#';
}
if($arr_page>1){
$arr_page2=$arr_page-1;
for ($i = 0; $i < $arr_page2; $i++) {
$i2=$i+2;
$ids2='https://pcw-api.iqiyi.com/albums/album/avlistinfo?aid='.$albumid.'&page='.$i2.'&size=40';
$html = curl_get($ids2,$cookie,$UserAgent);
$arr=json_decode($html,true);
$arr_q1a=$arr['data']['epsodelist'];
$m=count($arr_q1a);
for($i3=0;$i3<$m;$i3++) 
{ 
$name=$arr['data']['epsodelist'][$i3]['name'];
$name=str_replace($text,"",$name);
$dizhi2=$arr['data']['epsodelist'][$i3]['playUrl'];
$dizhi=$dizhi.$name.'$'.$dizhi2.'#';
}
}
}
}
$dizhi=substr($dizhi, 0, strlen($dizhi)-1);
$result= $result.'"vod_play_from":"'.$yuan.'",';
$result= $result.'"vod_play_url":"'.$dizhi.'"}]}';
}
echo $result;
//==============================================读取影视信息结束=======================================
}

}else  if ($burl<> null){

//=============================以下是直链分析代码=======================================================
$html = curl_get($burl,$cookie,$UserAgent);
$content=getSubstr($html,'var player','</script>');
$content=getSubstr($content,'"url":"','",');
$content=urldecode(str_replace("\/","/",$content));
if(strpos($content,'.m3u8')>0 or strpos($content,'.mp4')>0){
echo  '<iframe src="'.$content.'" class="iframeStyle" id="myiframe" ></iframe>';
}else{
$from=getSubstr($html,'"from":"','",');
$from=urldecode(str_replace("\/","/",$from));
$playerconfig=$web.'/static/js/playerconfig.js';
$playerhtml = curl_get($playerconfig,$cookie,$UserAgent);
if(strpos($playerhtml,'player_list=')>0){
$content2=getSubstr($playerhtml,'player_list=',',Mac');
$arr=json_decode($content2,true);
$show=$arr[$from]['show'];
$parse=$arr[$from]['parse'];
if (substr($parse,0,4)<>'http'){
$parse=$web.$parse;
}
$parse=str_replace("\/","/",$parse);
echo  '<iframe src="'.$parse.$content.'" class="iframeStyle" id="myiframe" ></iframe>';
}else{
echo  '<iframe src="'.$burl.'" class="iframeStyle" id="myiframe" ></iframe>';
}
}
//==============================以上是直链分析代码=======================================================



}else  if ($wd<> null){
//=============================以下是搜索代码=======================================================
if($searchable==0){
echo 'php未开启搜索';
exit;
}
if($page==null){
$page=1;
}
if($convert==1){
$key=urlencode($wd);
}else{
$key=urlencode(mb_convert_encoding($wd, 'gb2312', 'utf-8'));
}
$geturl =str_replace("{wd}",$key,$search);
$geturl =str_replace("{page}",$page,$geturl);
if ($datatype==1){
$html = curl_get($geturl,$cookie,$UserAgent);
}else{
$getdata=str_replace("{wd}",$key,$searchdata);
$getdata=str_replace("{page}",$page,$getdata);
$html = curl_post($geturl,$getdata,$cookie,$UserAgent);
}

if($searchtype==1){
$dom = new DOMDocument();
$html= mb_convert_encoding($html ,'HTML-ENTITIES',"UTF-8");
$dom->loadHTML($html);
$dom->normalize();
$xpath = new DOMXPath($dom);
if($searchquery2<>null){
$texts = $xpath->query($searchquery2);
}
$events = $xpath->query($searchquery);
$titleevents= $xpath->query($searchtitleAttr);
$linkevents= $xpath->query($searchlinkAttr);
$length=$events->length;
$result='{"code":1,"page":'.$page.',"pagecount":'. $page.',"total":'. $length.',"list":[';
for ($i = 0; $i < $events->length; $i++) {
    $event = $events->item($i);
    if($searchquery2<>null){
$text = $texts->item($i)->nodeValue;
    }
    $link = $linkevents->item($i)->nodeValue;
       if (substr($link,0,2)=='//'){
	$link = 'https:'.$link;
	}
    $title = $titleevents->item($i)->nodeValue;
    $title=replacestr($title);
    if($searchurl1<>null){
        $link2 =getSubstr($link,$searchurl1,$searchurl2);
    }else{
    $link2 =$link;
    }
 
 if($link<>null){
 
 if(strpos($link,'/lib/m_')>0){
 $lib=getSubstr($link,'/lib/m_','.html');
  if($lib<>null){
 $pos=strpos($html,'entity_id:"'.$lib.'"');
 if($pos>0){
$html2=substr($html,$pos);
if(strpos($html2,'g_img_link:"')>0){
$link2=getSubstr($html2,'g_img_link:"','"');
$link2=str_replace('\u002F','/',$link2);
if (substr($link2,0,2)=='//'){
$link2 = 'https:'.$link2;
}
}
}
}
}





if(strpos($link2,'book')===false){
    if($titlematch==0){
    $result=$result.'{"vod_id":"'.$link2.'","vod_name":"'.$title.'","vod_remarks":"'.$text.'"},';
    }else if($titlematch==1 and strpos($title,$wd)===false){
    }else{
    $result=$result.'{"vod_id":"'.$link2.'","vod_name":"'.$title.'","vod_remarks":"'.$text.'"},';
    }
    }
    
}
}

$result=substr($result, 0, strlen($result)-1).']}';
echo $result;
}else{
$arr=json_decode($html,true);
$arr_q1a=$arr[$searchquery];
$m=count($arr_q1a);
$result='{"code":1,"page":'.$page.',"pagecount":'. $page.',"total":'. $m.',"list":[';
 for($i=0;$i<$m;$i++){
 $title = $arr_q1a[$i][$searchtitleAttr];
$link =  $arr_q1a[$i][$searchlinkAttr];
if($url1==null && is_numeric($link)==true && $searchurl1<>null){
$link =$searchurl1.$link.$searchurl2;
}
if($searchquery2<>null){
$text = $arr_q1a[$i][$searchquery2];

if($titlematch==0){
$result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'","vod_remarks":"'.$text.'"},';
}elseif($titlematch==1 and strpos($title,$wd)===false){
}else{
$result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'","vod_remarks":"'.$text.'"},';
}

}else{
if($titlematch==0){
$result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'"},';
}else if($titlematch==1 and strpos($title,$wd)===false){
}else{
$result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'"},';
}

}
 }
 $result=substr($result, 0, strlen($result)-1).']}';
echo $result;
}
//==============================以上是搜索代码=======================================================
}else{
if($indexable==0){
echo $movietype;
}else{
$html = curl_get($indexweb,$cookie,$UserAgent);
$arr=json_decode($html,true);
$arr_q1a=$arr['data']['list'];
$m=count($arr_q1a);
$guolv='';
$m2=0;
$result=',"list": [';
for ($i = 0; $i < $m; $i++) {
$latestOrder=$arr['data']['list'][$i]['latestOrder'];
$videoCount=$arr['data']['list'][$i]['videoCount'];
if($latestOrder==1 && $videoCount==1){
$text = $arr['data']['list'][$i]['score'].'分';
}else{
if($latestOrder==$videoCount && $latestOrder<>0){
$text = '共'.$latestOrder.'集';
}else{

if($videoCount>0){
$text = '连载至'.$latestOrder.'/'.$videoCount;
}else if($latestOrder>0){
$text = '连载至'.$latestOrder;
}else{
$text ='';
}
}
}
$link = $arr['data']['list'][$i]['playUrl'];
$title =$arr['data']['list'][$i]['name'];
$pic = $arr['data']['list'][$i]['imageUrl'];
$link = str_replace('http:','https:',$link);
   if (substr($pic,0,2)=='//'){
	$pic = 'https:'.$pic;
	}

	if($title<>null && $link<>null){
if($guolv==null){
	    $result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'","vod_pic":"'.$pic.'","vod_remarks":"'.$text.'"},';
    	$guolv=$guolv."{".$link."}";
    	$m2=$m2+1;
}else if(strpos($guolv, "{".$link."}")===false){
	    $result=$result.'{"vod_id":"'.$link.'","vod_name":"'.$title.'","vod_pic":"'.$pic.'","vod_remarks":"'.$text.'"},';
    	$guolv=$guolv."{".$link."}";
    	$m2=$m2+1;
	}
	}
	if($m2>=$indexnum){
	break;
	}
}

if($m2==0){
echo $movietype;
}else{
$result=substr($result, 0, strlen($result)-1).']}';
echo substr($movietype, 0, strlen($movietype)-1).$result;
}
}
}

function unicode_decode($unistr, $encoding = 'utf-8', $prefix = '&#', $postfix = ';') {
 $arruni = explode($prefix, $unistr);
 $unistr = '';
 for ($i = 1, $len = count($arruni); $i < $len; $i++) {
 if (strlen($postfix) > 0) {
  $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
 }
 $temp = intval($arruni[$i]);
 $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
 }
 return iconv('UCS-2', $encoding, $unistr);
}


function curl_get($url,$cookie2,$UserAgent2){
  $header = array(
       'Accept: */*',
       'Accept-Language: zh-cn',
       'Referer: '.$url,
       'User-Agent: '.$UserAgent2,
       'Content-Type: application/x-www-form-urlencoded'
    );
        $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt ($curl, CURLOPT_HTTPHEADER , $header);
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent2);
    if($cookie2<>null){
    curl_setopt($curl, CURLOPT_COOKIE, $cookie2);
    }
    $data = curl_exec($curl);
    if (curl_error($curl)) {
        return "Error: ".curl_error($curl);
    } else {
	curl_close($curl);
		return $data;
	}
	}
    
function curl_post($url,$postdata,$cookie2,$UserAgent2){
  $header = array(
       'Accept: */*',
       'Accept-Language: zh-cn',
       'Referer: '.$url,
       'User-Agent: '.$UserAgent2,
       'Content-Type: application/x-www-form-urlencoded'
    );
        $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt ($curl, CURLOPT_HTTPHEADER , $header);
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent2);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    if($cookie2<>null){
    curl_setopt($curl, CURLOPT_COOKIE, $cookie2);
    }
    $data = curl_exec($curl);
    if (curl_error($curl)) {
        return "Error: ".curl_error($curl);
    } else {
	curl_close($curl);
		return $data;
	}
	}


function getSubstr($str, $leftStr, $rightStr) 
{
if($leftStr<>null && $rightStr<>null){
$left = strpos($str, $leftStr);
$right = strpos($str, $rightStr,$left+strlen($leftStr));
if($left < 0 or $right < $left){
return '';
}
return substr($str, $left + strlen($leftStr),$right-$left-strlen($leftStr));
}else{
$str2=$str;
if($leftStr<>null){
$str2=str_replace($leftStr,'',$str2);
}
if($rightStr<>null){
$str2=str_replace($rightStr,'',$str2);
}
return $str2;
}
}

function replacestr($str2){
$test2=$str2;
$test2=str_replace("	","",$test2);
$test2=str_replace(" ","",$test2);
$test2 = preg_replace('/\s*/', '', $test2);
return $test2;
}
?>