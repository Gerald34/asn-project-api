<?php

namespace App\Http\Resources;

use App\FeedPostsModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Resources\FirebaseResource as Firebase;
use Illuminate\Http\Request;

class FeedPostsResource extends JsonResource
{
    private static $response;

    /**
     * Fetch user feed object
     * @param $uid
     * @return array
     */
    public static function fetchPosts($uid)
    {
        $feedData = [];
        // Fetch user feed posts
        $posters = DB::table('posts')->select('uid', 'post_id')->where('uid', $uid)->get();
        // Get each user id
        foreach ($posters as $poster) {
            $postInformation = [
                // 'avatar' => self::_getUserAvatar($poster->uid),
                'feedData' => self::_getFeedPosts($poster->post_id),
                'userInformation' => self::_getUserInformation($poster->uid)
            ];
            // Feed object push
            $feedData[] = $postInformation;
        }

        if (empty($feedData)):
            // Return empty
            self::$response = ['response' => 204];
        else:
            // Return successful
            self::$response = ['response' => 200, 'data' => $feedData];
        endif;
        return self::$response;
    }

    /**
     * @param $uid
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    private static function _getUserAvatar($uid)
    {
        return DB::table('avatars')->select('avatar')->where('uid', $uid)->first();
    }

    /**
     * Get user feed posts
     *
     * @param $uid
     * @return array
     */
    private static function _getFeedPosts($uid)
    {
        $posts = DB::table('posts')->where('post_id', $uid)->first();
        // $feedObject = [];
        $feedObject = [
            'feed' => $posts,
            'likes' => [
                'count' => count(self::_getPostLikes($posts->post_id)),
                'likeData' => self::_getPostLikes($posts->post_id)
            ],
            'comments' => self::_getPostComments($posts->post_id)
        ];

        return $feedObject;
    }

    /**
     * Get user post like by post id
     *
     * @param $postID
     * @return \Illuminate\Support\Collection
     */
    public static function _getPostLikes($postID)
    {
        return DB::table('post_likes')->where('post_id', $postID)->get();
    }

    /**
     * @param $postID
     */
    private static function _getPostComments($postID)
    {

    }

    /**
     * Get post user information by $uid
     * @param $uid
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    private static function _getUserInformation($uid)
    {
        return DB::table('users')->select('uid', 'first_name', 'last_name')->where('uid', $uid)->first();
    }

    /**
     * @param array $data
     * @param Request $request
     */
    public static function postStatusUpdate(array $data)
    {
        FeedPostsModel::insert(
            [
                'uid' => $data['uid'],
                'post_id' => $data['postID'],
                'message' => $data['message'],
                'image' => $data['fileNameToStore'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]
        );
       FeedPostsModel::where('post_id', $data['postID'])->get();

        return Firebase::userPosts($data);
    }

    /**
     * @param int $length
     * @return string
     */
    public static function _generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }


}
