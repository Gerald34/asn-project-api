<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\AvatarModel;
use Carbon\Carbon;

class AvatarResource extends JsonResource
{
    private static $response;

    public static function getAvatar($id) {
        $avatar = AvatarModel::where('user_id', $id)->first();

        if(!empty($avatar)) {
            self::$response = [
                'successCode' => 104,
                'successMessage' => 'Avatar Found',
                'avatar' => $avatar
            ];
        } else {
            self::$response = [
                'errorCode' => 408,
                'errorMessage' => 'no avatar'
            ];
        }

        return self::$response;
    }

    public static function uploadAvatar($upload) {
        $file = $upload['avatar'];

        $path = public_path() . "/avatars/{$upload['user_id']}";
        $file->move($path, $file->getClientOriginalName());
        $storedPath = response()->json(compact('path'));

        self::$response = self::_saveUploads($upload, $file);

        return self::$response;
    }

    private static function _saveUploads($upload, $file) {

        $uploadData = new AvatarModel;

        $uploadData->user_id = $upload['user_id'];
        $uploadData->avatar = public_path() . "/avatars/{$upload['user_id']}/{$file->getClientOriginalName()}";
        $uploadData->uploaded_date = $upload['uploaded_date'];
        $uploadData->created_at = Carbon::now();
        $uploadData->updated_at = Carbon::now();
        $uploadData->save();

        return 'saved';

    }
}
