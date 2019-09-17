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
            Storage::disk('avatar')->put($fileNameToStore, fopen($request->file('avatar'), 'r+'));
            $filePath = Storage::disk('avatar')->url($fileNameToStore);
            AvatarResource::addImageDataToDatabase($uid, $fileNameToStore);
            FirebaseResource::getUserAvatar($request->hasFile('avatar'));
        }

        return ['status' => 'Images uploaded', 'image_name' => $fileNameToStore, 'path' => $filePath];
    }

    public function getAvatarImageFile($uid) {
        $avatar = AvatarResource::getImage($uid);
        return ($avatar !== null)? $avatar : 'Image not found';
    }

    public function fileProcessor(Request $request) {
        $uid = $request->input('uid');
        $base64_image = $request->input('avatar');
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $fileNameToStore = 'current_' . $uid . '.png';
            $data = base64_decode($data);
            Storage::disk('avatar')->put($fileNameToStore, $data);
            AvatarResource::addImageDataToDatabase($uid, $fileNameToStore);
        }
        return self::getUserAvatar($uid);
    }

}
