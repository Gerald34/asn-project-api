<?php

namespace App\Http\Controllers;
use App\Http\Resources\AvatarResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\AvatarModel;
use App\Http\Resources\FirebaseResource;
/**
 * Class AvatarController
 * @package App\Http\Controllers
 */
class AvatarController extends Controller
{
    private $response;

    /**
     * @param $uid
     * @return array
     */
    public function getUserAvatar($uid) {
        $this->response = AvatarResource::getAvatar($uid);
        return $this->response;
    }

    /**
     * @param $avatar
     * @return \Illuminate\Http\Response|null
     */
    public static function avatar($avatar) {
        return AvatarResource::getImage($avatar);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function saveImage(Request $request) {
        $uid = $request->input('uid');
        $fileNameToStore = [];
        $filePath = '';
        if ($request->hasFile('avatar')) {
            // get filename with extension
            $fileNameWithExtension = $request->file('avatar')->getClientOriginalName();
            // get filename without extension
            $filename = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            // get file extension
            $extension = $request->file('avatar')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $uid . '_' .$filename . '.' . $extension;
            // upload File to external server
            Storage::disk('profiles')->put('$uid/' . $fileNameToStore, fopen($request->file('avatar'), 'r+'));
            $filePath = Storage::disk('profiles')->url($fileNameToStore);
            AvatarResource::addImageDataToDatabase($uid, $fileNameToStore);
            FirebaseResource::getUserAvatar($request->hasFile('avatar'));
        }

        return ['status' => 'Images uploaded', 'image_name' => $fileNameToStore, 'path' => $filePath];
    }

    /**
     * @param $uid
     * @return \Illuminate\Http\Response|string|null
     */
    public function getAvatarImageFile($uid) {
        $avatar = AvatarResource::getImage($uid);
        return ($avatar !== null)? $avatar : 'Image not found';
    }

    /**
     * @param $uid
     * @return \Illuminate\Http\Response|string|null
     */
    public function getCoverImageFile($uid) {
        $coverImage = AvatarResource::getCoverImage($uid);
        return ($coverImage !== null)? $coverImage : 'Image not found';
    }

    /**
     * @param Request $request
     * @return array
     */
    public function fileProcessor(Request $request) {
        $uid = $request->input('uid');
        $base64_image = $request->input('avatar');
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $extension = '.png';
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $time = time();
            $fileStorage = $uid . '/' . $time . $extension;
            $fileNameToStore = $time . $extension;
            $data = base64_decode($data);
            Storage::disk('profiles')->put($fileStorage, $data);
            AvatarResource::addImageDataToDatabase($uid, $fileNameToStore);
        }

        return ['status' => 200, 'response' => true];
    }

}
