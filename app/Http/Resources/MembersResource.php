<?php

namespace App\Http\Resources;

use App\MembersModel;
use Illuminate\Http\Resources\Json\JsonResource;

class MembersResource extends JsonResource
{
    /**
     * @param string $uid
     * @return object
     */
    public static function verifyUserMembership(string $uid): object {
        $member = MembersModel::select('team_id')->where(['uid' => $uid, 'active' => 1])->first();
        return (!empty($member)) ? $member : response()->json(['error' => 204, 'message' => 'Looks like you\'re not part of the team']);
    }
}
