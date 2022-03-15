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

    public function sendGanres($chat_id,$content,$buttons)
    {
       return Http::post(self::TM_URL.$this->tm_api_key.'/sendMessage',[

           'chat_id'=>$chat_id,
           'text' => $content,
           'reply_markup' => $buttons

        ]);
    }

    public function sendMovie($chat_id,$image,$content,$tr_link,$ru_link)
    {
        return Http::post(self::TM_URL.$this->tm_api_key.'/sendPhoto',[

            'chat_id'=>$chat_id,
            'photo' => $image,
            'caption' => $content,
            'reply_markup' => [
                'resize_keyboard' => true,

                'inline_keyboard'=>[
                    [
                        [
                            'text' => 'Türk dilində',
                            'url' => $tr_link
                        ],
                        [
                            'text' => 'Rus dilində',
                            'url' => $ru_link
                        ]

                    ],
                    [
                        [
                            'text' => 'Digər film gəlsin',
                            'callback_data' => 'start'
                        ]

                    ]
                ]
            ],
            'parse_mode'=>'html'


        ]);
    }

    public function nothingFound($chat_id)
    {
        return Http::post(self::TM_URL.$this->tm_api_key.'/sendAnimation',[

            'chat_id'=>$chat_id,
            'animation' => 'https://c.tenor.com/lx2WSGRk8bcAAAAC/pulp-fiction-john-travolta.gif',
            'caption' => 'Bunu yoxla:',
            'reply_markup' => [
                'resize_keyboard' => true,

                'inline_keyboard'=>[
                    [
                        [
                            'text' => '🎥 Janrlar',
                            'callback_data' => '/start'
                        ]

                    ]
                ]
            ]


        ]);
    }


}
