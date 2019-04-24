<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
class ProfileController extends Controller
{
    public function likePost(Request $request) {
        $owner = trim(strip_tags($request->input('owner')));
        $uid = trim(strip_tags($request->input('uid')));
        $postID = trim(strip_tags($request->input('postID')));
        return ProfileResource::likes($uid, $postID, $owner);
    }
}
