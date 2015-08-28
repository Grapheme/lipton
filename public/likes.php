<?php
// ini_set('display_errors', 1);

$url = !empty($_REQUEST['url']) ? $_REQUEST['url'] : 'http://grapheme.ru';
//$url = urlencode($url);

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

//vkontakte
$vk_query_share = 'http://vk.com/share.php?act=count&index=1&url='.$url;
$vk_query_likes = 'http://vk.com/widget_like.php?app=5042647&type=mini&url='.urlencode($url);
// $vk_query2 = 'https://vk.com/widget_like.php?app=3122703&type=mini&url=http://newyork-bar.ru/afisha';
//facebook
$fb_query = 'http://graph.facebook.com/?ids='.$url;
//Одноклассники
$od_query = 'http://www.odnoklassniki.ru/dk?st.cmd=extLike&uid=odklcnt0&ref='.$url;

curl_setopt($ch, CURLOPT_URL, $vk_query_share);
$vk = curl_exec ($ch);

curl_setopt($ch, CURLOPT_URL, $vk_query_likes);
$vk2 = curl_exec ($ch);


curl_setopt($ch, CURLOPT_URL, $fb_query);
$fb = curl_exec ($ch);

curl_setopt($ch, CURLOPT_URL, $od_query);
$od = curl_exec ($ch);

function vk_parse($vk){
	$vk = str_replace('VK.Share.count(1, ', '', $vk);
	$vk = str_replace(');', '', $vk);
	return (int)$vk;
}

function vk_parse_id($vk) {
	if (preg_match("/counter\s=\s(\d+)/", $vk, $matches)) {
		return (int)$matches[1];
	} else {
		return 0;
	}
}

function fb_parse($fb){
	//string(73) "{"http:\/\/www.starhit.ru":{"id":"http:\/\/www.starhit.ru","shares":300}}"
	global $url;
	$fb = json_decode($fb);
	settype($fb, 'array');
	$fb = $fb[$url];
	settype($fb, 'array');
	return !empty($fb['shares']) ? (int)$fb['shares'] : 0;
}

function od_parse($od){
	//string(35) "ODKL.updateCount('odklcnt0','458');"
	$od = str_replace("ODKL.updateCount('odklcnt0','", "", $od);
	$od = str_replace("');", "", $od);
	return (int)$od;
}
$vk_likes = (int) vk_parse($vk);
$vks_likes = (int) vk_parse_id($vk2);
$fb_likes = (int) fb_parse($fb);
$ok_likes = (int) od_parse($od);
$result['total'] = $vk_likes + $vks_likes + $fb_likes + $ok_likes;
$result['extend'] = 'Всего: '.$result['total'].', Facebook: '.$fb_likes.', Вконтакте: '.($vk_likes + $vks_likes).', Одноклассники: '.$ok_likes;
echo json_encode($result);
?>