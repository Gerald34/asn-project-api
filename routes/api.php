<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/app'], function() {
    Route::post('/login', 'UserLoginController@login');
    Route::post('/register', 'UserRegistrationController@register');
});

Route::group(['prefix' => '/user'], function() {
    // Teams
    Route::group(['prefix' => '/team'], function() {
       Route::post('create', 'TeamsController@create');
       Route::post('get', 'TeamsController@getTeam');
       Route::post('remove', 'TeamsController@removeExistingTeamByUID');
       Route::post('edit', 'TeamsController@editTeamByOwnership');
    });

    Route::get('getPosts/{id}', 'FeedPostsController@getUserPosts');
    Route::get('getUserInformation/{id}', 'UserController@getMyInformation');
    Route::get('getAvatar/{id}', 'AvatarController@getUserAvatar');
    Route::post('uploadAvatar', 'AvatarController@uploadUserAvatar');
    Route::post('updateProfile', 'UserController@updateProfile');
    Route::get('/teams/{id}', 'AppController@getAllTeams');
    Route::get('/activities', 'AppController@activities');
    Route::get('/activitypositions/{id}', 'AppController@getPositionsByActivity');
});

