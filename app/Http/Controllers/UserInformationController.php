<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserInformationResource;
class UserInformationController extends Controller
{
    public function getInformation($uid) {
        return UserInformationResource::getInformation($uid);
    }
}
