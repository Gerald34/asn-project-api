<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FilesResource;

class FilesController extends Controller
{

    /**
     * @param $avatar
     * @return array|\Illuminate\Http\Response
     */
    public function getAvatarImageFile($avatar) {
        return FilesResource::retrieveFile($avatar, 'app/avatars/');
    }
}
