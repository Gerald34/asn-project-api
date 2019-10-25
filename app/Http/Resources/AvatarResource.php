<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\AvatarModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use App\CoverImageModel;

class AvatarResource extends JsonResource
{
    private static $response;

    /**
     * @param $uid
     * @return array
     */
    public static function getAvatar($uid) {
        $avatar = AvatarModel::where('uid', $uid)->first();
        self::$response = [
            'successCode' => 104,
            'successMessage' => 'Avatar Found',
            'avatar' => $avatar
        ];
        return self::$response;
    }

    /**
     * @param string $uid
     * @param string $fileNameToStore
     */
    public static function addImageDataToDatabase(string $uid, string $fileNameToStore) {
        $avatarData = [
            'avatar' => $fileNameToStore,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        // store $fileNameToStore in the database
        AvatarModel::where('uid', $uid)->update($avatarData);
    }

    /**
     * @param $uid
     * @return \Illuminate\Http\Response|null
     */
    public static function getImage($uid) {
        $avatar = self::_getCurrentUserAvatar($uid);

        if (empty($avatar)) {
            self::$response = null;
        } else {
            $path = storage_path('app/avatars/' . $avatar->avatar);
            if (!File::exists($path)) { abort(404); }
            $file = File::get($path);
            $type = File::mimeType($path);
            self::$response = Response::make($file, 200);
            self::$response->header("Content-Type", $type);
        }

        return self::$response;
    }

    /**
     * @param $uid
     * @return mixed
     */
    public static function _getCurrentUserAvatar($uid) {
        return AvatarModel::select('avatar')->where('uid', $uid)->first();
    }

    /**
     * @param $uid
     * @return \Illuminate\Http\Response|null
     */
    public static function getCoverImage($uid) {
        $cover = self::_getCurrentUserCoverImage($uid);
        if (empty($cover)) {
            self::$response = null;
        } else {
            $path = storage_path('app/covers/' . $cover->cover_image);
            if (!File::exists($path)) { abort(404); }
            $file = File::get($path);
            $type = File::mimeType($path);
            self::$response = Response::make($file, 200);
            self::$response->header("Content-Type", $type);
        }

        return self::$response;
    }

    /**
     * @param $uid
     * @return mixed
     */
    public static function _getCurrentUserCoverImage($uid) {
        return CoverImageModel::select('cover_image')->where('uid', $uid)->first();
    }

    /**
     * @param $image
     * @return \Intervention\Image\Image|string
     */
    public static function processImage($image) {
        return Image::make($image)->mime();
        $resized = Image::make($image)->resize(400, 400);
        dd($resized);
        return $resized;
        $dimensions =  self::imageDimensions($image);
        if ($dimensions['response'] !== true) {
            self::$response = ['304' => 'image diemsions too large'];
        }
    }

    /**
     * @param $image
     * @return array
     */
    private static function imageDimensions($image) {
        $height = Image::make($image)->height();
        $width = Image::make($image)->width();
        return (($height >= 700) && ($width >= 700))
            ? ['response' => true]
            : ['response' => false];
    }

}
