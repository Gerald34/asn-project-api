<?php

namespace App\Http\Resources;

use App\FeedPostsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Resources\FirebaseResource as Firebase;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\DatabaseException;

class FeedPostsResource extends JsonResource {
    private static array $response;

    /**
     * Fetch user feed object
     * @param $uid
     * @return array
     */
    public static function fetchPosts(string $uid) {
        $feedData = [];
        $posters = DB::table('posts')
            ->select('uid', 'post_id')
            ->where('uid', $uid)
            ->get();
        foreach ($posters as $poster) {
            $postInformation = [
                // 'avatar' => self::_getUserAvatar($poster->uid),
                'feedData' => self::_getFeedPosts($poster->post_id),
                'userInformation' => self::_getUserInformation($poster->uid)
            ];
            $feedData[] = $postInformation;
        }

        if (empty($feedData)):
            self::$response = ['response' => 204];
        else:
            self::$response = ['response' => 200, 'data' => $feedData];
        endif;
        return self::$response;
    }

    /**
     * @param $uid
     * @return Model|Builder|object|null
     */
    private static function _getUserAvatar(string $uid) {
        return DB::table('avatars')->select('avatar')->where('uid', $uid)->first();
    }

    /**
     * Get user feed posts
     *
     * @param $uid
     * @return array
     */
    private static function _getFeedPosts(string $uid) {
        $posts = DB::table('posts')->where('post_id', $uid)->first();

        return [
            'feed' => $posts,
            'likes' => [
                'count' => count(self::_getPostLikes($posts->post_id)),
                'likeData' => self::_getPostLikes($posts->post_id)
            ],
            'comments' => self::_getPostComments($posts->post_id)
        ];
    }

    /**
     * Get user post like by post id
     *
     * @param $postID
     * @return Collection
     */
    public static function _getPostLikes(string $postID) {
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
     * @return Model|Builder|object|null
     */
    private static function _getUserInformation(string $uid)
    {
        return DB::table('users')->select('uid', 'first_name', 'last_name')->where('uid', $uid)->first();
    }

    /**
     * @param array $data
     * @throws DatabaseException
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
