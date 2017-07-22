<?php


namespace ChatBot;

use GuzzleHttp\Client;
use Models\Setting;

abstract class Base
{
    protected $name = '';
    protected $author = '';
    // markdown support
    protected $describe = '';
    protected $belongTo = '';

    /**
     * setting = [
     *      'key' => [
     *          'name' => (string) setting name show, Required.
     *          'value' => (string) setting default value.
     *          'desc' => (string) short describe for the setting.
     *      ],
     *      ...
     * ]
     * @var array
     */
    protected $settings = [];

    /**
     * call api here
     * @param string $content
     * @return mixed Return <b>string</b> when API requests successfully, <b>false</b> else.
     */
    abstract public function talk($content);

    /**
     * init api here.
     * @return bool
     */
    abstract protected function init(): bool;

    protected $client;

    public final function __construct()
    {
        if (empty($this->name)) {
            $this->name = __CLASS__;
            trigger_error('API name is empty', E_USER_WARNING);
        }

        if (empty($this->belongTo)) {
            $this->belongTo = 'chat_bot_api_' . $this->name;
        }
        // init settings
        foreach ($this->settings as $key => &$setting) {
            $setting['name'] = isset($setting['name']) ? $setting['name'] : $key;
            $setting['value'] = isset($setting['value']) ? $setting['value'] : '';
            $setting['desc'] = isset($setting['desc']) ? $setting['desc'] : '';
        }
        // get settings from database
        $settings = Setting::where('belong_to', $this->belongTo)->get();
        foreach ($settings as $setting) {
            $key = $setting->key;
            if (!isset($this->settings[$key])) continue;
            $this->settings[$key]['name'] = isset($this->settings[$key]['name']) ? $this->settings[$key]['name'] : $key;
            $this->settings[$key]['value'] = isset($setting->value) ? $setting->value : '';
            $this->settings[$key]['desc'] = isset($this->settings[$key]['desc']) ? $this->settings[$key]['desc'] : '';
        }

        $this->client = new Client();

        return $this->init();
    }

    public final function install()
    {
        foreach ($this->settings as $k => $v) {
            $this->setSetting($k, isset($v['value']) ? $v['value'] : '');
        }
    }

    public final function uninstall()
    {
        Setting::where('belong_to', $this->belongTo)->delete();
    }

    public final function getName()
    {
        return $this->name;
    }

    public final function getDescribe()
    {
        return $this->describe;
    }

    public final function getAuthor()
    {
        return $this->author;
    }

    public final function setSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key, 'belong_to' => $this->belongTo],
            ['value' => $value]
        );
        $this->settings[$key]['value'] = $value;
    }

    public final function getSetting($key)
    {
        return $this->settings[$key];
    }

    public final function getSettingValue($key)
    {
        return $this->getSetting($key)['value'];
    }

    /**
     * @param $url
     * @param array $query
     * @param array $cookies
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function get($url, $query = [], $cookies = [], $headers = [])
    {
        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray($cookies);

        return $this->client->get($url, [
            'query' => $query,
            'cookies' => $jar,
            'headers' => $headers
        ]);
    }

    /**
     * @param $url
     * @param array $body
     * @param array $cookies
     * @param array $headers
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function post($url, $body = [], $cookies = [], $headers = [])
    {
        $jar = \GuzzleHttp\Cookie\CookieJar::fromArray($cookies);

        return $this->client->post($url, [
            'form_params' => $body,
            'cookies' => $jar,
            'headers' => $headers
        ]);
    }
}
