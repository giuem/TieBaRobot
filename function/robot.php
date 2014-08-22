<?php
/* 
 * By Giuem.
 * 自定义机器人函数
 */
//主函数
function talk($content) {
	global $name;
	// 对内容进行处理
	$content = str_ireplace("@$name",'',$content);
	$content = str_ireplace("回复 $name :",'',$content);
	// 有时候无法显示 回复的内容，就要有一个默认回复
	if($content == ''){
		return '你好';
	}
	
	$re = xiaoji($content);
	// 有时候返回内容为空 就要有一个默认回复
	if($re == ''){
		return '你好';
	}else {
		return $re;
	}
}
//和dun的小红鸡一样，可调教
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


//第三方小i机器人接口，不怎么好，可以查天气什么的
function xiaoi3($content){
    $re=json_decode(file_get_contents("http://rmbz.net/Api/AiTalk.aspx?key=rmbznet&talk=xiaoi&word=".$content),true);
    $re = $re['content'];
    return $re;
}
?>