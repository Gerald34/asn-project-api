<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\AvatarController;
use Illuminate\Support\Facades\Redirect;

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

    //Token
    // Route::get('/token', function() {
    //     return csrf_token();
    // });

Route::group(['prefix' => '1.1.0'], function() {

    Route::get('registered_users/{uid}', 'AppEngineController@registeredUsers');

    Route::group(['prefix' => 'app'], function() {
        Route::post('login', 'UserLoginController@login');
        Route::post('/register', 'UserRegistrationController@register');
    });

    Route::group(['prefix' => 'user'], function() {

        // User Profile
        Route::get('avatar/{avatar}', function($avatar) {
            return AvatarController::avatar($avatar);
        });

        // Teams
        Route::group(['prefix' => 'team'], function() {
            Route::post('create', 'TeamsController@create');
            Route::get('get/{uid}', 'TeamsController@getTeam');
            Route::post('remove', 'TeamsController@removeExistingTeamByUID');
            Route::post('edit', 'TeamsController@editTeamByOwnership');
        });

        // User profile information
        Route::group(['prefix' => 'profile'], function() {

            // Get user information
            Route::group(['prefix' => 'information'], function() {
                Route::get('get/{uid}', 'UserInformationController@getInformation');
            });

            // User avatar
            Route::group(['prefix' => 'avatar'], function() {
                Route::get('getCurrent/{uid}', 'AvatarController@getCurrent');
                Route::post('uploadAvatar', 'AvatarController@saveImage');
                Route::post('imageProcessor', 'AvatarController@fileProcessor');
                Route::get('get/{uid}', 'AvatarController@getAvatarImageFile');
            });

            // user cover image
            Route::group(['prefix' => 'cover'], function() {
                Route::get('get/{uid}', 'AvatarController@getCoverImageFile');
            });

            // Timeline posts
            Route::group(['prefix' => '/posts'], function() {
                Route::get('get/{uid}', 'FeedPostsController@getUserPosts');
                Route::post('post', 'FeedPostsController@postUserPosts');
                Route::get('postImage/{uid}/{post_id}/{imageName}', 'FeedPostsController@getPostImage');
            });
        });

        Route::get('getUserInformation/{id}', 'UserController@getMyInformation');
        Route::post('updateProfile', 'UserController@updateProfile');
        Route::get('/teams/{id}', 'AppController@getAllTeams');
        Route::get('/activities', 'AppController@activities');
        Route::get('/activitypositions/{id}', 'AppController@getPositionsByActivity');
        Route::any('like', 'ProfileController@likePost');
    });

    Route::group(['prefix' => 'team'], function() {
        Route::get('get/{teamID}', 'TeamController@getTeam');
    });

    Route::group(['prefix' => '/tester'], function() {
        Route::get('/test/{name}', 'FilesController@testerMethod');
    });

    Route::group(['prefix' => '/teams'], function() {
       Route::get('/fetch', 'TeamController@getAllTeams');
       Route::get('/categories', 'TeamController@getCategories');
       Route::post('/create', 'TeamController@create');
    });
});


