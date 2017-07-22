<?php


namespace ChatBot;


class TuLing extends Base
{
    protected $name = '图灵机器人';
    protected $author = 'Giuem';
    protected $describe = '';

    protected $settings = [
        'api_key' => [
            'name' => 'APIkey',
        ],
    ];

    protected function init(): bool
    {
        return true;
    }

    public function talk($content)
    {
        $url = 'http://www.tuling123.com/openapi/api';
        $json = [
            'key' => $this->getSettingValue('api_key'),
            'info' => $content
        ];
        $res = $this->client->post($url, [
            'json' => $json
        ]);

        $text = $this->handleResponse(json_decode($res->getBody()->getContents(), true));
        return $text;
    }

    private function handleResponse($json)
    {
        if ($json === NULL) return false;
        $text = '';
        switch ($json['code']) {
            case 100000: {
                $text = $json['text'];
                break;
            }
            case 200000: {
                $text = $json['text'] . $json['url'];
                break;
            }
            case 302000: {
                $text = $json['text'] . "\n";
                foreach ($json['list'] as $item) {
                    $text .= "{$item['article']}（{$item['detailurl']}）\n";
                }
                break;
            }
            case 308000: {
                $text = $json['text'] . "\n";
                foreach ($json['list'] as $item) {
                    $text .= "{$item['name']}：{$item['info']}，{$item['detailurl']}\n";
                }
                break;
            }
            default: {
                $text = empty($json['text']) ? false : $json['text'];
            }
        }
        return $text;
    }

}