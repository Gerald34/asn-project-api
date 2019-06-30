<?php

namespace App\Http\Controllers;
use App\Http\Resources\AvatarResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\AvatarModel;
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
        }

        return ['status' => 'Images uploaded', 'image_name' => $fileNameToStore, 'path' => $filePath];
    }

    public function getAvatarImageFile($uid) {
        $avatar = AvatarResource::getImage($uid);
        return ($avatar !== null)? $avatar : 'Image not found';
    }

}
