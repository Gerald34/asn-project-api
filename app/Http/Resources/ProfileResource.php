<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\LikesModel;

class ProfileResource extends JsonResource
{
    public static function likes($ownerID, $uid, $postID)
    {
        $like = LikesModel::create([
            'owner' => $ownerID,
            'uid' => $uid,
            'post_id' => $postID,
            'liked_at' => Carbon::now()
        ]);

        $likeCount = LikesModel::where('post_id', $postID)->get();

        return true;
    }
}
