<?php
if (!defined('IS_GIUEM')) exit();
/**
  * 坑爹的代码迟早被我重构！
  */
class tieba{

	private  $bduss = array();
	private  $un = '';
	private  $setting = '';
	private  $i = '0';
	private  $type = array('at','reply');
	private  $kwdata = array();

	public function __construct(){
		$this->setting = get_setting();
		$this->bduss = get_robot_bduss();
		// 黑名单加入小号，防止重复回复
		$this->setting[3] .= get_robot_name();
		$this->un = self::getun($this->bduss[0]);
		$this->cron();

	}
	private function rand_bduss(){
		$i = rand(0,count($this->bduss)-1);
		$this->i = $i;
	}

	public static function getun($bduss){
		$re = fetch('http://wapp.baidu.com/','BDUSS='.$bduss,null,array('User-Agent: Mozilla/5.0 (Windows NT 6.3; rv:29.0) Gecko/20100101 Firefox/29.0','Connection: Keep-Alive'));
		$name = '';
		if (preg_match('/i?un=(.*?)">/', $re, $match)) {
			$name = urldecode($match[1]);
		}
		return $name;
	}
	public static function islogin($bduss){
		$re=json_decode(fetch('http://tieba.baidu.com/dc/common/tbs','BDUSS='.$bduss),true);
		return $re['is_login'] == 1;
	}
	public static function getpid($bduss,$type){
		$pid = self::getmsg($bduss,$type);
		return $pid[0]['post_id'];
	}
	
	private static function getmsg($bduss,$type){
		$postdata = array (
				'BDUSS='.$bduss,
				'_client_id=wappc_136'.random(10).'_'.random(3),
				'_client_type=2',
				'_client_version=5.0.0',
				'_phone_imei=642b43b58d21b7a5814e1fd41b08e2a6',
				'net_type=3',
				'pn=1'
		);
		$postdata=self::getsign($postdata);
		$header=array('Content-Type: application/x-www-form-urlencoded');
		$re=json_decode(fetch('http://c.tieba.baidu.com/c/u/feed/'.$type.'me',null,$postdata,$header),true);
		return $re[$type.'_list'];
	}
	private function floorpid($tid,$pid){
		$url = 'http://c.tieba.baidu.com/c/f/pb/floor';
		$postdata = array(
				'BDUSS='.$this->bduss[0],
				'_client_id=wappc_136'.random(10).'_'.random(3),
				'_client_type=2',
				'_client_version=5.0.0',
				'_phone_imei=642b43b58d21b7a5814e1fd41b08e2a6',
				'kz='.$tid,
				'net_type=3',
				'spid='.$pid,
				'tbs='.$this->gettbs(0)
		);
		$postdata=self::getsign($postdata);
		$header=array('Content-Type: application/x-www-form-urlencoded');
		$re=json_decode(fetch($url,null,$postdata,$header),true);
		$pid = $re['post']['id'];
		return $pid;
	}

	private function reply($tid,$pid,$kw,$content){
		$postdata = array (
			'BDUSS='.$this->bduss[$this->i],
    		'_client_id='.'wappc_136'.random(10).'_'.random(3),
    		'_client_type='. 2,
    		'_client_version=' . '6.5.2',
    		'_phone_imei=' . md5($this->bduss[$this->i]),
			'anonymous=' . 1,
			'content=' . $content,
			'fid=' . $this->kwdata['forum_info']['forum_info']['forum_id'],
			'from=' . '1382d',
			'is_ad=' . '0',
			'kw=' . $kw,
			'model=' .'SCH-I959',
			'new_vcode=' .'1',
			'quote_id=' . $pid,
			'tbs='. $this->gettbs($this->i),
			'tid='.$tid,
			'vcode_tag='.'11'
		);
		$postdata=self::getsign($postdata);
		$re = fetch('http://c.tieba.baidu.com/c/c/post/add',null,$postdata,array(
        	'User-Agent: bdtb for Android 6.5.2',
            'Content-Type: application/x-www-form-urlencoded',
        ));
		$re = json_decode($re,true);
		if($re['error_code'] == 0){
			return '回帖成功';
		}else {
			return '回帖失败，错误代码：'.$re['error_code'].' '.$re['error_msg'];
		}
		return ;
	}
	private function getkwdata($kw){
		$re=json_decode(fetch("http://tieba.baidu.com/sign/info?kw={$kw}&ie=utf-8",'BDUSS='.$this->bduss[$this->i]),true);
		$this->kwdata = $re['data'];
	}
	private function likekw($kw){
		$postdata = 'fid='.$this->kwdata['forum_info']['forum_info']['forum_id'].'&fname='.urlencode($kw).'&uid='.urlencode(self::getun($this->bduss[$this->i])).'&ie=gbk&tbs='.$this->gettbs($this->i);
		$re = json_decode((fetch('http://tieba.baidu.com/f/like/commit/add','BDUSS='.$this->bduss[$this->i],$postdata)));
	}
	private function signkw($kw){
		$postdata = array(
				'BDUSS='.$this->bduss[$this->i],
				'_client_id=03-00-DA-59-05-00-72-96-06-00-01-00-04-00-4C-43-01-00-34-F4-02-00-BC-25-09-00-4E-36',
				'_client_type=4',
				'_client_version=1.2.1.17',
				'_phone_imei=540b43b59d21b7a4824e1fd31b08e9a6',
				'fid='.$this->kwdata['forum_info']['forum_info']['forum_id'],
				'kw='.$kw,
				'net_type=3',
				'tbs='.$this->gettbs($this->i),
		);
		$postdata = self::getsign($postdata);
		$re = json_decode(fetch('http://c.tieba.baidu.com/c/c/forum/sign',null,$postdata,array('Content-Type: application/x-www-form-urlencoded')),1);
		if ($re['user_info'])
			return '在'.$kw.'吧签到成功，经验值上升'.$re['sign_bonus_point'];
		else
			return '在'.$kw.'吧签到失败，错误代码：'.$re['error_code'].' '.$re['error_msg'];
	}
	private function gettbs($i){
		$re=json_decode(fetch('http://tieba.baidu.com/dc/common/tbs','BDUSS='.$this->bduss[$i]),true);
		return $re['tbs'];
	}
	private static function getsign($postdata){
		$postdata=implode("&", $postdata)."&sign=".md5(implode('', $postdata).'tiebaclient!!!');
		return $postdata;
	}

	private function cron_type($type){
		$msg = self::getmsg($this->bduss[0],$type);
		$db_pid = get_pid($type);
		set_pid($msg[0]['post_id'], $type);
		foreach ($msg as $k){
			$content = '';
			$this->rand_bduss();
			$this->getkwdata($k['fname']);
			if ($db_pid == $k['post_id']) break;
			if (@stristr($this->setting[3],$k['replyer']['name_show'])===false && @stristr($this->setting[4],$k['fname'])===false){
				if ($this->setting[5]==1){
					if($this->kwdata['user_info']['is_sign_in']==0){
						$this->likekw($k['fname']);
						set_log($this->signkw($k['fname']));
					}
				}
				if($k['is_floor'] == 1){
					$pid = $this->floorpid($k['thread_id'], $k['post_id']);
					$content = "回复 {$k['replyer']['name_show']} :";
				}else{
					$pid = $k['post_id'];
				}

				$content .= talk($k['content'],$this->un,$this->setting[1],$this->setting[2]).$this->setting[6];
				$res = $this->reply($k['thread_id'], $pid, $k['fname'], $content);
				echo "在{$k['fname']}吧贴号{$k['thread_id']}{$res}";
				set_log("在{$k['fname']}吧贴号{$k['thread_id']}{$res}");
				// 回帖间隔
				sleep(3);
			}
		}
	}
	private function cron(){
		for ($i=0;$i<2;$i++){
			$this->cron_type($this->type[$i]);
		}
	}
}
/*PHP END*/