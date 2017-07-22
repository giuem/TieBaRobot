<?php


namespace Tieba;


use GuzzleHttp\Cookie\CookieJar;

class Client
{

    protected $bduss = '';
    protected $userInfo = [
        'id' => 0,
        'name' => ''
    ];
    protected $tbs = '';

    private $client;
    private $jar;

    const commonHeaders = [
        'User-Agent' => 'BaiduTieba for Android 6.5.2',
        'Content-Type' => 'application/x-www-form-urlencoded'
    ];

    function __construct($bduss)
    {
        $this->bduss = $bduss;

        $this->jar = CookieJar::fromArray([
            'BDUSS' => $bduss,
        ], '.baidu.com');

        $this->client = new \GuzzleHttp\Client();

        $this->reLogin();

        return true;
    }

    public function getTbs(): string
    {
        return $this->tbs;
    }

    public function getUserID(): string
    {
        return $this->userInfo['id'];
    }

    public function getUserName(): string
    {
        return $this->userInfo['name'];
    }

    public function getForumID($forum) : string
    {
        static $forums = [];
        if(!array_key_exists($forum, $forums)) {
            $forums[$forum] = $this->getForumData($forum)['forum_id'];
        }
        return $forums[$forum];
    }

    private function reLogin()
    {
        $url = 'http://c.tieba.baidu.com/c/s/login';
        $body = [
            'bdusstoken' => $this->bduss
        ];

        $json = $this->fetch($url, $body, false, false);

        $this->userInfo['id'] = $json['user']['id'];
        $this->userInfo['name'] = $json['user']['name'];
        $this->tbs = $json['anti']['tbs'];
    }


    /**
     * Get latest msg
     * @param string $type reply|at
     * @param string $pid post_id
     * @return array
     */
    public function getMsg($type, $pid = '0'): array
    {
        $url = "http://c.tieba.baidu.com/c/u/feed/{$type}me";
        $body = [
            'net_type' => '3',
            'pn' => '1'
        ];
        $json = $this->fetch($url, $body);

        $msgs = [];
        foreach ($json["{$type}_list"] as $item) {
            if ($item['post_id'] == $pid) break;
            array_push($msgs, [
                'forum' => $item['fname'],
                'thread_id' => $item['thread_id'],
                'post_id' => $item['post_id'],
                'is_floor' => $item['is_floor'],
                'msg_type' => $type,
                'author' => $item['replyer']['name_show'],
                'content' => preg_replace("/@{$this->getUserName()}\s*?|回复(\s|@)*?{$this->getUserName()}\s*?(:|：)/i", '', $item['content'])
            ]);
        }
        return $msgs;
    }

    /**
     * Get forum data.
     * NOTE: this api can perform better if using client side api,
     *       but will cost much bandwidth
     * @param $forum
     * @return array
     */
    public function getForumData($forum)
    {
        $res = $this->client->get("http://tieba.baidu.com/sign/info?kw={$forum}&ie=utf-8", [
            'cookies' => $this->jar
        ]);
        $json = json_decode($res->getBody()->getContents(), true);

        return [
            'forum_id' => $json['data']['forum_info']['forum_info']['forum_id'],
            'is_sign' => $json['data']['user_info']['is_sign_in'],
        ];
    }

    public function createPost($content, $forum, $tid, $pid)
    {
        $url = 'http://c.tieba.baidu.com/c/c/post/add';
        $body = [
            'content' => $content,
            'kw' => $forum,
            'fid' => $this->getForumID($forum),
            'tid' => $tid,
            'quote_id' => $pid,
            'tbs' => $this->tbs,
            'is_ad' => '0',
            'new_vcode' => '1',
            'anonymous' => '1',
            'vcode_tag' => '11'
        ];
        $json = $this->fetch($url, $body);

        // TODO
    }


    /**
     * create a client request
     * @param $url
     * @param $body
     * @param bool $withBduss
     * @param bool $withHeader
     * @return mixed
     */
    protected function fetch($url, $body, $withBduss = true, $withHeader = true)
    {
        $postData = $this->genClientForm($body, $withBduss, $withHeader);
//        var_dump($postData);
        $res = $this->client->post($url, [
            'headers' => self::commonHeaders,
            'form_params' => $postData
        ]);

        $json = json_decode($res->getBody()->getContents(), true);
        if (!$json) {
            throw new \Exception('Request error, check your network', -1);
        }
        if ($json['error_code'] !== '0') {
            throw new \Exception(<<<EOT
                Tieba error! 
                url: "{$url}"
                error_code: "{$json['error_code']}"
                error_msg: "{$json['error_msg']}"
EOT
                , -10);
        }
        return $json;

    }

    /**
     * Return standard post form for tieba bootstrap.
     * @param $body
     * @param bool $withBduss
     * @param bool $withHead
     * @return array
     */
    protected function genClientForm($body, $withBduss = true, $withHead = true)
    {
        $postData = [];
        $head = [
            '_client_id' => 'wappc_136' . self::random(10) . '_' . self::random(3),
            '_client_type' => '2',
            '_client_version' => '5.0.0',
            '_phone_imei' => md5($this->bduss),
            'cuid' => strtoupper(md5(self::random(16))) . '|' . self::random(15, TRUE),
            'model' => 'M1',
        ];
        $tail = [
//            'from' => 'baidu_appstore',
            'stErrorNums' => '0',
            'stMethod' => '1',
            'stMode' => '1',
            'stSize' => rand(50, 2000),
            'stTime' => rand(50, 500),
            'stTimesNum' => '0',
            'timestamp' => time() . self::random(3)
        ];
        if ($withHead) $postData += $head;
        $postData += $body + $tail;
        //ksort($postData);
        if ($withBduss) {
            $postData = [
                    'BDUSS' => $this->bduss
                ] + $postData;
        }
        $str = '';
        foreach ($postData as $k => $v) {
            $str .= "$k=$v";
        }
        $postData['sign'] = md5($str . 'tiebaclient!!!');
        return $postData;
    }

    /**
     * random a length of numeric
     * @param int $length
     * @return string
     */
    public static function random($length = 0)
    {
        $re = '';
        for ($i = 0; $i < $length; $i++) {
            $re .= rand(0, 9);
        }
        return $re;
    }


}