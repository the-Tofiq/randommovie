<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

define("GENRES",[28=>"action",12=>"adventure",16=>"animation",35=>"comedy",80=>"crime",99=>"documentary",18=>"drama",10751=>"family",14=>"fantasy",36=>"history",27=>"horror",10402=>"music",9648=>"mystery",10749=>"romance",878=>"science fiction",10770=>"tv movie",53=>"thriller",10752=>"war",37=>"western"]);
class MovieController extends Controller
{

    /*public function __construct(Telegram $telegram)
    {

    }*/
    private function tmSetTHook(Telegram $telegram)
    {
        $telegram->setHook();
    }

    public function getButtons()
    {

        $buttons = [
            'resize_keyboard' => true,

            'inline_keyboard'=>[
                [
                    [
                        'text' => '💣 Dava-qırğın',
                        'callback_data' => 28
                    ],
                    [
                        'text' => '🎢 Macəra',
                        'callback_data' => 12
                    ],
                    [
                        'text' => '🧸 Animasiya',
                        'callback_data' => 16
                    ],


                ],
                [
                    [
                        'text' => '😂 Komediya',
                        'callback_data' => 35
                    ],
                    [
                        'text' => '🔪 Kriminal',
                        'callback_data' => 80
                    ],
                    [
                        'text' => '📙 Sənədli film',
                        'callback_data' => 99
                    ],
                ],
                [
                    [
                        'text' => '😭 Drama',
                        'callback_data' => 18
                    ],
                    [
                        'text' => '👪 Ailəvi',
                        'callback_data' => 10751
                    ],
                    [
                        'text' => '🧚 Fentezi',
                        'callback_data' => 14
                    ],

                ],
                [
                    [
                        'text' => '🏺 Tarixi',
                        'callback_data' => 36
                    ],
                    [
                        'text' => '🧛🏼Qorxulu',
                        'callback_data' => 27
                    ],
                    [
                        'text' => '‍🎤 Musiqi',
                        'callback_data' => 10402
                    ],

                ],
                [
                    [
                        'text' => '🔍 Detektiv',
                        'callback_data' => 9648
                    ],
                    [
                        'text' => '💑 Melodrama',
                        'callback_data' => 10749
                    ],
                    [
                        'text' => '🧙🏻 Fantastika',
                        'callback_data' => 878
                    ],

                ],
                [
                    [
                        'text' => '📺 TV Filmlər',
                        'callback_data' => 10770
                    ],
                    [
                        'text' => '🔦 Triller',
                        'callback_data' => 53
                    ],
                    [
                        'text' => '⚔️Müharibə',
                        'callback_data' => 10752
                    ],

                ],
                [
                    [
                        'text' => '🤠 Vestern',
                        'callback_data' => 37
                    ],

                ]
            ],

        ];

        return $buttons;
    }

    // GET MOVIES LIST BY TMDB API AND RETURN RANDOM ONE
    public function getMovie($genre_id)
    {
        $page = rand(1,500);
        $movies_by_genre = 'https://api.themoviedb.org/3/discover/movie?api_key='.config('api_keys.tmdb_key').'&with_genres='.$genre_id.'&page='.$page.'&language=en-US';

        /*$ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_URL,$movies_by_genre);

        $res = curl_exec($ch);
        $res = json_decode($res,true);

        curl_close($ch);
        $rand = array_rand($res['results']);*/

        $res = $response = Http::get($movies_by_genre);
        $res = json_decode($res,true);
        $rand = array_rand($res['results']);

        return $res['results'][$rand];

    }

    public function tmBotAnswers(Telegram $telegram)
    {
        $data = json_decode(file_get_contents("php://input"),1);
        $buttons = $this->getButtons();

        if(isset( $data['callback_query'])) {
           // $user_message = null;
            $user_message = $data['callback_query']['data'];
            $chat_id = $data['callback_query']['message']['chat']['id'];

        }elseif(isset( $data['message'])){
            $user_message = mb_strtolower($data['message']['text']);
            $chat_id = $data['message']['chat']['id'];
            //$callback_data = null;
        }else{
            $user_message = 'start';
            $chat_id = config('api_keys.tm_my_id');
        }
       // Log::debug($data);


        if ($user_message == '/start' || $user_message == 'start' ) {


            $content = 'Filmin janrını seçin';

            $telegram->sendGanres($chat_id,$content,json_encode($buttons));

        } elseif (array_key_exists($user_message,GENRES) || in_array($user_message,GENRES,true)){

            $movie = self::getMovie($user_message);
            $movie_image = 'https://image.tmdb.org/t/p/original'.$movie['poster_path'];
            $tr_link = 'https://www.fullhdfilmizlesene.pw/arama/'.str_replace(" ","%",$movie["title"]);
            $ru_link = 'https://filmix.ac/search/'.str_replace(" ","%",$movie["title"]);
            $content = (string) view('movie_details',compact('movie'));

            $telegram->sendMovie($chat_id,$movie_image,$content,$tr_link,$ru_link);

        }else {

            $telegram->nothingFound($chat_id);

        }



    }

    public function forTest()
    {

       /* $movie = self::getMovie(14);
        echo $movie["title"]; echo '<br>';
        var_dump($movie);*/

        /*$ganres = array_chunk(GENRES,3,true);
        $arraysMerged = [];
        $ds = array_merge_recursive($arraysMerged,$ganres);
        foreach ($ganres as $genre) {
            $arraysMerged += $genre;
        }
        $buttons = [
            'inline_keyboard'=>[
                [
                    [
                        'text' => 'My Button Text',
                        'callback_data' => ''
                    ]
                ],

            ]
        ];*/

        /*$arr_main = [];
        $arr_in = [];

        foreach ($ds as $d){
            foreach ($d as $k => $v){
                $top = ['text' => $v,'callback_data' => $k];
                //array_push($arr_in,$top);
                $arr_in = $top;
            }
            //array_push($arr_main,$arr_in);
            $arr_main = $arr_in;
           // $arr_in = [];
        }
        //var_dump($arr_main);
        echo '<pre>'; print_r($arr_main);echo '<pre>';*/



    }

}
