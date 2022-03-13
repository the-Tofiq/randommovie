<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

define("GENRES",'[["id"=>28,"name"=>"Action"],["id"=>12,"name"=>"Adventure"],["id"=>16,"name"=>"Animation"],["id"=>35,"name"=>"Comedy"],["id"=>80,"name"=>"Crime"],["id"=>99,"name"=>"Documentary"],["id"=>18,"name"=>"Drama"],["id"=>10751,"name"=>"Family"],["id"=>14,"name"=>"Fantasy"],["id"=>36,"name"=>"History"],["id"=>27,"name"=>"Horror"],["id"=>10402,"name"=>"Music"],["id"=>9648,"name"=>"Mystery"],["id"=>10749,"name"=>"Romance"],["id"=>878,"name"=>"Science Fiction"],["id"=>10770,"name"=>"TV Movie"],["id"=>53,"name"=>"Thriller"],["id"=>10752,"name"=>"War"],["id"=>37,"name"=>"Western"]]');
class MovieController extends Controller
{

    /*public function __construct(Telegram $telegram)
    {

    }*/
    private function tmSetTHook(Telegram $telegram)
    {
        $telegram->setHook();
    }

    // GET MOVIES LIST BY TMDB API AND RETURN RANDOM ONE
    public function getFilm($genre_id = null)
    {

        $movies_by_genre = 'https://api.themoviedb.org/3/discover/movie?api_key='.config('api_keys.tmdb_key').'&with_genres=27&language=en-US';

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

    public function tmBotAnswers(Telegram $telegram,Request $request)
    {
        //Log::debug($request->all());
        $data = json_decode(file_get_contents('php://input'),1);
        $user_message = mb_strtolower($data['message']['text']);

        if ($user_message == 27 || $user_message == 'horror'){
            $method = 'sendMessage';
            $content = 'ujas';
        }else {
            $method = 'sendMessage';
            $content = 'ni-ce-qo';
        }
        $chat_id = $data['message']['chat']['id'];
        $telegram->sendGanres($chat_id,$content);

    }

    public function forTest()
    {
        echo GENRES[0]['id'];
    }

}
