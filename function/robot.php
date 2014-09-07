<?php
/* 
 * By Giuem.
 * 自定义机器人函数
 */
//主函数
function talk($content,$api) {
	global $name;
	// 对内容进行处理
	$content = str_ireplace("@$name",'',$content);
	$content = str_ireplace("回复 $name :",'',$content);
	// 如果只是单单@的话，会被过滤为空。api无法识别，所以要设置默认回复
	if($content == ''){
		return '您好，贴吧小冰很高兴为您服务';
	}
	switch ($api){
		case 'xiaoji':
			$re = xiaoji($content);
			break;
		case 'xiaoi3':
			$re = xiaoi3($content);
			break;
		case 'tuling':
			$key = '';//填写key
			$re = tuling($content,$key);
			break;
		case 'simsimi3':
			$re = simsimi3($content);
			break;
		case 'simsimi':
			$re = simsimi($content);
			break;
		default:
			$re = xiaoji($content);
			
	}
	
	
	
	
	// 有时候返回内容为空 就要有一个默认回复
	if($re == ''){
		return 'Hi!';
	}else {
		return $re;
	}
}

/*
 * 和dun的小红鸡一样的接口，可调教
 */
function xiaoji($content){
	//正则匹配 调教
	if(preg_match('/\[问：(.*?) 答：(.*?)\]/',$content,$teach) == 1){
		$re = teach($teach[1],$teach[2]);
		return $re;
	}else {
		$re = json_decode(HTTPclient('http://lover.zhenyao.net/chat-g.json','','type=3&msg='.urlencode($content).'&score=true&botid=12056'),true);
		return $re['messages'][0]['message'];
	}
}
//调教 方式   [问：xxxxx 答：xxxxx]
function teach($q,$a){
	$re = json_decode(HTTPclient('http://lover.zhenyao.net/robot/teach.json','','answer='.urlencode($a).'&botid=12056&question='.urlencode($q)),true);
	if($re['status'] == 1){
		return '调教成功！';
	}else {
		return '调教失败！';
	}
}
/*
 * 图灵机器人官网地址： http://www.tuling123.com/
 */
function tuling($content,$key){
	$re = json_decode(HTTPclient('http://www.tuling123.com/openapi/api?key='.$key.'&info='.$content),true);
	$code = $re['code'];
	switch ($code){
		//文本类数据
		case 100000:
			$content = $re['text'];
			break;
		//网址类数据
		case 200000:
			$content = $re['text'].$re['url'];
			break;
		//新闻
		case 302000:
			$list = $re['list'];
			$i = rand(0,count($list)-1);
			$list = $list[$i];
			$content = $re['text'].'：'.$list['article'];
			break;
		//火车
		case 305000:
			$list = $re['list'];
			$i = rand(0,count($list)-1);
			$list = $list[$i];
			$content = '起始站：'.$list['start'].'，到达站：'.$list['terminal'].'，开车时间：'.$list['starttime'].'，到达时间：'.$list['endtime'].'。亲，更多信息请上网查询哦！';
			break;
		//飞机
		case 306000:
			$list = $re['list'];
			$i = rand(0,count($list)-1);
			$list = $list[$i];
			$content = '航班：'.$list['flight'].'，航班路线：'.$list['route'].'，起飞时间：'.$list['starttime'].'，到达时间：'.$list['endtime'].'。亲，更多信息请上网查询哦！';
			break;
		//彩票
		case 310000:
			$list = $re['list'];
			$i = rand(0,count($list)-1);
			$list = $list[$i];
			$content = $list['info'].'中奖号码：'.$list['number'].'。亲，小赌怡情，大赌伤身哦！';
			break;
		//次数用完或者不能用了
		case 40004:
			$content = '今天累了，明天再聊吧';
			break;
		default:
			//$content = xiaoji($content);
			$content = $re['text'];
	}
	return $content;
}




/*
 * 第三方小i机器人接口，不怎么好，可以查天气什么的
 */
function xiaoi3($content){
    $re=json_decode(file_get_contents("http://rmbz.net/Api/AiTalk.aspx?key=rmbznet&talk=xiaoi&word=".$content),true);
    $re = $re['content'];
    return $re;
}
/*
 * 第三方小黄鸡接口
 */
function simsimi3($content){
	$re = HTTPClient('http://api.mrtimo.com/Simsimi.ashx?parm='.$content);
	return $re;
}
/*
 * 盗用官方的api
 */
function simsimi($content){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,'http://www.simsimi.com/talk.htm');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER,1);
	curl_setopt($ch, CURLOPT_NOBODY, false);
	$rs = curl_exec($ch);
	preg_match_all('/Set-Cookie: (.+)=(.+)$/m', $rs, $regs);
	foreach($regs[1] as $i=>$k);
	$cc=str_replace(' Path','' ,$k);
	$cc='simsimi_uid=507454034223;'.$cc;
	$re = HTTPClient('http://www.simsimi.com/func/reqN?lc=ch&ft=1.0&req='.$content.'&fl=http%3A%2F%2Fwww.simsimi.com%2Ftalk.htm',$cc);
	$re = json_decode($re,true);
	return $re['sentence_resp'];
}
?>