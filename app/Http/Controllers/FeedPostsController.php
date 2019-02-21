<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FeedPostsModel;

/**
 * Class FeedPostsController
 * @package App\Http\Controllers
 */
class FeedPostsController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function getUserPosts($id) {
        $post = FeedPostsModel::where('user_id', $id)->get();
        return $post;
    }

}
