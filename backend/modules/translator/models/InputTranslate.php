<?php
/**
 * Created by PhpStorm.
 * User: Start
 * Date: 08.11.2018
 * Time: 0:02
 */

namespace backend\modules\translator\models;


class InputTranslate
{
    protected
        $url,
        $str,
        $settings;

    public function __construct(string $str, string $from='ru', string $to='en')
    {
        $this->settings = include dirname(__DIR__) .DS .'config' .DS .'main-local.php';
        $this->str = urlencode(
            preg_replace('~[^\w ]~u', '', $str)
        );
        //$url = "http://mymemory.translated.net/api/get?q={$this->str}&langpair={$from}|{$to}";
        $this->url = "https://translate.yandex.net/api/v1.5/tr.json/translate";
        $this->url .= "?key={$this->settings['yandexKey']}&text={$this->str}&lang={$from}-{$to}";

        return $this;
    }

    /**
     * @return string
     * @throws \ErrorException
     */
    public function getEn(): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->settings['userAgent']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $result = curl_exec($ch);
        $code =(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($code!=200 && $code!=204) {
            throw new \ErrorException('Request error!', 400);
        }

        return strtolower(
            json_decode($result)->text[0]
        );

    }
}