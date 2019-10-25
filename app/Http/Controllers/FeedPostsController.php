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

    /**
     *
     */
    public function postUserPosts(Request $request) {
        $uid = $request->input('uid');
        $message = $request->input('message');

        //if ($request->hasFile('image')) {
            $files[] = $request->file('images');
            dd($files); exit;
            $images = [];
            foreach($files as $file) {
                // get filename with extension
                $fileNameWithExtension = $file->getClientOriginalName();
                // get filename without extension
                $filename = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                // get file extension
                $extension = $file->getClientOriginalExtension();
                // filename to store
                $fileNameToStore = $filename . '.' . $extension;
                // upload File to external server
                Storage::disk('posts')->put($fileNameToStore, fopen($file, 'r+'));
                $images[] = $fileNameToStore;
            }

            return $images;
            // FeedPostsResource::postStatusUpdate($uid, $message, $fileNameToStore);
        // } else {
            // FeedPostsResource::postStatusUpdate($uid, $message, null);
        // }

        return ['successCode' => 203, 'successMessage' => 'Status updated'];
    }

}
