<?php

namespace App\Http\Controllers;
use App\Http\Resources\AvatarResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class AvatarController
 * @package App\Http\Controllers
 */
class AvatarController extends Controller
{
    private $response;

    /**
     * @param $id
     * @return array
     */
    public function getUserAvatar($id) {
        $this->response = AvatarResource::getAvatar($id);

        return $this->response;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function uploadUserAvatar(Request $request) {

        $upload = [
            'user_id' => $request->input('user_id'),
            'avatar' => $request->file('avatar'),
            'uploaded_date' => Carbon::now()
        ];

        return AvatarResource::uploadAvatar($upload);
    }

}
