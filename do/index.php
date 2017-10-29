<?php
header("Content-type:text/html;charset=utf-8");
/**
 *CURL获取链接内容
 */
function curl_file_get_contents($durl)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
    curl_setopt($ch, CURLOPT_REFERER, _REFERER_);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $r = curl_exec($ch);
    $r = mb_convert_encoding($r, 'utf-8', 'GBK,UTF-8,ASCII'); //加上这行
    curl_close($ch);
    return $r;
}

/**
 *文章的标题和内容
 */
function getUrlInfo($url, $div, $array)
{
    $content = curl_file_get_contents($url);
    $content = trim($content);
    $regex4 = "/<div class=\"" . $div . "\".*?>.*?<\/div>.*?<\/div>.*?<\/div>.*?<\/div>/ism";
    preg_match_all($regex4, $content, $matches);
    $nr = $matches[0][0];
    //$nr = str_replace($array, "", $nr);
    return trim($nr);
}


//获取内容
$info = curl_file_get_contents("http://sh.58.com/zhuce/");

//匹配
$regex4 = "/<table class=\"small-tbimg\".*?>.*?<\/table>/ism";
preg_match_all($regex4,$info,$matches);
//正则匹配图片信息
$regPic = "/<td class=\"img\".*?>.*?<\/td>/ism";
preg_match_all($regPic,$info,$pics);
//列表信息
$regList = "/<td class=\"t\".*?>.*?<\/td>/ism";
preg_match_all($regList,$info,$lists);


//获取图片地址
$gitPic = "/lazy_src='(.*?)'/u";
foreach ($pics[0] as $key=>$vo) {
	preg_match($gitPic,$vo,$in);
	$thePic[$key] = $in[1];
}
//获取第一个href
$href = "/href='(.*?)'/u";
$title = "/ class=\"t\">(.*?)<\/a>/ism";
$name = "/nofollow\".*?>(.*?)<\/a>/U";
$item = "/<span .*?>.*?<\/span>/ism";//性质

$leibie = "/<div class=\"su_con\">(.*?)<\/div>/u";//获取类别
$quyu = "/<div class=\"su_con quyuline\".*?>(.*?)<\/div>/ism";//服务区域
foreach ($lists[0] as $key=>$vo) {
	
	preg_match($href,$vo,$im);//链接
	//采集内页获取类型和年限
	preg_match($title,$vo,$t);//标题
	preg_match($name,$vo,$company);//公司名
	if (strstr($im[1],'shtml')) {
		$info = curl_file_get_contents($im[1]);
		preg_match($leibie,$info,$lei);
		preg_match($quyu,$info,$qu);
		$quyu = explode('&nbsp;',substr(str_replace('	', '',trim(strip_tags($qu[1]))),0,-6));
		$theTitle = trim(strip_tags($t[1]));
		$theTitle = explode('	',$theTitle)[0];
		$lei = explode('&nbsp;&nbsp;',$lei[1]);
		$array[] = array('link'=>$im[1],'title'=>strip_tags($theTitle),'com'=>$company[1],'pic'=>$thePic[$key],'leibie'=>$lei,'quyu'=>$quyu);
	}
}
var_dump($array);
?>