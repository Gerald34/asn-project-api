<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GeneratorResource;
use App\UserLoginModel as User;
class ApiTokenResource extends JsonResource
{
    private string $apiToken;

    public static function createSessionToken($uid) {
        $sessionToken = GeneratorResource::generateRandomString(80);
        User::where('uid', $uid)->update(
            ['api_token' => $sessionToken, 'updated_at' => Carbon::now()]
        );
        return $sessionToken;
    }

    public function getUserToken(string $uid) {
        $sessionData = User::select('api_token', 'updated_at')->where('uid', $uid)->get();
    }
}
