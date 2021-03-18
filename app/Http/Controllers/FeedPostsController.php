<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvatarResource;
use Illuminate\Http\Request;
use App\FeedPostsModel;
use App\Http\Resources\FeedPostsResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Kreait\Firebase\Exception\DatabaseException;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

/**
 * Class FeedPostsController
 * @package App\Http\Controllers
 */
class FeedPostsController extends Controller {
    private object $response;
    private FeedPostsResource $feedPostsResource;

    public function __construct(FeedPostsResource $feedPostsResource) {
        $this->feedPostsResource = $feedPostsResource;
    }

    /**
     * @param $uid
     * @return mixed
     */
    public function getUserPosts($uid)
    {
        return $this->feedPostsResource::fetchPosts($uid);
    }

    /**
     * @param $uid
     * @param $post_id
     * @param $imageName
     * @return \Illuminate\Http\Response
     */
    public function getPostImage($uid, $post_id,  $imageName): object {
        $path = storage_path('app/posts/' . $uid . '/' . $post_id . '/' . $imageName);
        if (!File::exists($path)) { abort(404); }
        $file = File::get($path);
        $type = File::mimeType($path);
        $this->response = Response::make($file, 200);
        $this->response->header("Content-Type", $type);

        return $this->response;
    }

    /**
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function postUserPosts(Request $request)
    {
        $uid = $request->input('uid');
        $message = $request->input('message');
        $postID = $this->feedPostsResource::_generateRandomString();

        if ($request->hasFile('images') === true) {
            $files[] = $request->file('images');
            $images = [];
            $fileNameToStore = '';
            foreach ($files as $file) {

                // get filename with extension
                $fileNameWithExtension = $file->getClientOriginalName();

                // get filename without extension
                $filename = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);

                // get file extension
                $extension = $file->getClientOriginalExtension();

                // filename to store
                $fileNameToStore = Carbon::now() . '_' . $uid . '.' . $extension;

                // ImageOptimizer::optimize($fileNameToStore);

                // upload File to external server
                Storage::disk('posts')
                    ->put($uid . '/' . $postID . '/' . $fileNameToStore, fopen($file, 'r+'));
                $images[] = $fileNameToStore;
            }

            $postData = [
                'uid' => $uid,
                'post_id' => $postID,
                'message' => $message,
                'image' => $fileNameToStore,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            $this->response = $this->feedPostsResource::postStatusUpdate($postData);
        } else {
            // FeedPostsResource::postStatusUpdate($uid, $message, null);
        }

        return $this->response;

        return ['successCode' => 203, 'successMessage' => 'Status updated'];
    }

}
