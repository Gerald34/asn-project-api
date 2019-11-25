<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AuthorizedVenuesModel;
class AuthorizedVenuesController extends Controller
{
    //

    public function getAllVenues() {
        return AuthorizedVenuesModel::select('venue_id', 'venue_name')->get();
    }
}
