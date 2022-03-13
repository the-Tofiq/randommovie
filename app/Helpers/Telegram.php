<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Telegram
{
    const TM_URL = 'https://api.telegram.org/bot';

    protected $tm_api_key;

    public function setHook()
    {
        $site_url = route('tmBotAnswers');

        $return = Http::get(self::TM_URL.$this->tm_api_key.'/setWebhook?url='.$site_url);
        dump(json_decode($return->body()));
        exit();
    }

    public function __construct($tm_api_key)
    {
        $this->tm_api_key = $tm_api_key;

    }

    public function sendGanres($chat_id,$content)
    {
       return Http::post(self::TM_URL.$this->tm_api_key.'/sendMessage',['chat_id'=>$chat_id,

        'text' => $content,
        'parse_mode' => 'html'

        ]);
    }


}
