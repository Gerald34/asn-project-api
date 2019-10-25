<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AppEngineResource;
class AppEngineController extends Controller
{
    private $response;

    public function registeredUsers($uid) {
        $users = DB::table('users')->get();
        $this->response = AppEngineResource::processRegisteredUserInformation($users);
        return $this->response;
    }
}
