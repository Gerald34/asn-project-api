<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvatarResource;
use Illuminate\Http\Request;
use App\FeedPostsModel;
use App\Http\Resources\FeedPostsResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class FeedPostsController
 * @package App\Http\Controllers
 */
class FeedPostsController extends Controller
{
    private $response;
    /**
     * @param $uid
     * @return mixed
     */
    public function getUserPosts($uid) {
        return FeedPostsResource::fetchPosts($uid);
    }

    public function postUserPosts(Request $request) {
        $uid = $request->input('uid');
        $message = $request->input('message');

        if ($request->hasFile('image')) {
            // get filename with extension
            $fileNameWithExtension = $request->file('image')->getClientOriginalName();
            // get filename without extension
            $filename = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            // get file extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename . '.' . $extension;
            // upload File to external server
            Storage::disk('posts')->put($fileNameToStore, fopen($request->file('image'), 'r+'));
            FeedPostsResource::postStatusUpdate($uid, $message, $fileNameToStore);
        } else {
            FeedPostsResource::postStatusUpdate($uid, $message, null);
        }

        return ['successCode' => 203, 'successMessage' => 'Status updated'];
    }

}
