<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use App\AvatarModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

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

    public static function addImageDataToDatabase(string $uid, string $fileNameToStore) {
        $avatarData = [
            'avatar' => $fileNameToStore,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];

        // store $fileNameToStore in the database
        AvatarModel::where('uid', $uid)->update($avatarData);
    }

    public static function getImage($avatar) {

        $path = storage_path('app/avatars/' . $avatar);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public static function getCurrentUserAvatar($uid) {
        return AvatarModel::select('avatar')->where('uid', $uid)->first();
    }

}
