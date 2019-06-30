<?php

namespace App\Http\Resources;

use App\FeedPostsModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class FeedPostsResource extends JsonResource
{
    private static $response;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        return parent::toArray($request);
    }

    public static function fetchPosts($uid) {

        $posts = DB::table('users')
            ->join('posts', 'users.uid', '=', 'posts.uid')
            ->join('avatars', 'users.uid', '=', 'users.uid')
            ->select('users.*','avatars.avatar','posts.post_id', 'posts.message', 'posts.image', 'posts.created_at')
            ->where('posts.uid', '=', $uid)
            ->get();

//        $posts = DB::table('posts')
//            ->where('uid', $uid)
//            ->get();

        if ($posts->isEmpty()):
            self::$response = ['response' => 204 ];
        else:
            self::$response = ['response' => 200, 'data' => $posts ];
        endif;
        return self::$response;
    }

    public static function postStatusUpdate($uid, $message, $fileNameToStore): void {
        FeedPostsModel::insert(
            [
                'uid' => $uid,
                'post_id' => self::_generateRandomString(),
                'message' => $message,
                'image' => $fileNameToStore,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }

    private static function _generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
