<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
</p>

### ASN Prototype API Documentation
##### Built with the [Laravel Framework](https://laravel.com) version 5.8.11.
##### Requirements
 - Language ``PHP verion 7.3.9`` 
 - Git
 - Composer
 - MySql
 - .env with MySQL database credentials. ``Get from application owner/head developer``
##### Installation

 > Clone the source code on to your local host and checkout development branch.
````
$ git clone https://github.com/Gerald34/asn-project-api.git
$ git fetch origin
$ git checkout development
````
- Now install all required dependencies.
````
$ composer install
````
- Migrate required database tables.
```
$ php artisan migrate
````
- Request URI and methods

> Base route/Domain: ``http://api.asn.com/public/index.php/``

````
+--------+----------------------------------------+--------------------------------------------------------------------+-----------------------+---------------------------------------------------------------+------------------------------------------------------+
| Domain | Method                                 | URI                                                                | Name                  | Action                                                        | Middleware                                           |
+--------+----------------------------------------+--------------------------------------------------------------------+-----------------------+---------------------------------------------------------------+------------------------------------------------------+
|        | GET|HEAD                               | /                                                                  |                       | Closure                                                       | web                                                  |
|        | GET|HEAD                               | _debugbar/assets/javascript                                        | debugbar.assets.js    | Barryvdh\Debugbar\Controllers\AssetController@js              | Barryvdh\Debugbar\Middleware\DebugbarEnabled,Closure |
|        | GET|HEAD                               | _debugbar/assets/stylesheets                                       | debugbar.assets.css   | Barryvdh\Debugbar\Controllers\AssetController@css             | Barryvdh\Debugbar\Middleware\DebugbarEnabled,Closure |
|        | DELETE                                 | _debugbar/cache/{key}/{tags?}                                      | debugbar.cache.delete | Barryvdh\Debugbar\Controllers\CacheController@delete          | Barryvdh\Debugbar\Middleware\DebugbarEnabled,Closure |
|        | GET|HEAD                               | _debugbar/clockwork/{id}                                           | debugbar.clockwork    | Barryvdh\Debugbar\Controllers\OpenHandlerController@clockwork | Barryvdh\Debugbar\Middleware\DebugbarEnabled,Closure |
|        | GET|HEAD                               | _debugbar/open                                                     | debugbar.openhandler  | Barryvdh\Debugbar\Controllers\OpenHandlerController@handle    | Barryvdh\Debugbar\Middleware\DebugbarEnabled,Closure |
|        | GET|HEAD                               | _debugbar/telescope/{id}                                           | debugbar.telescope    | Barryvdh\Debugbar\Controllers\TelescopeController@show        | Barryvdh\Debugbar\Middleware\DebugbarEnabled,Closure |
|        | POST                                   | api/1.1.0/app/login                                                |                       | App\Http\Controllers\UserLoginController@login                | api                                                  |
|        | POST                                   | api/1.1.0/app/register                                             |                       | App\Http\Controllers\UserRegistrationController@register      | api                                                  |
|        | GET|HEAD                               | api/1.1.0/registered_users/{uid}                                   |                       | App\Http\Controllers\AppEngineController@registeredUsers      | api                                                  |
|        | GET|HEAD                               | api/1.1.0/team/get/{teamID}                                        |                       | App\Http\Controllers\TeamController@getTeam                   | api                                                  |
|        | GET|HEAD                               | api/1.1.0/teams/categories                                         |                       | App\Http\Controllers\TeamController@getCategories             | api                                                  |
|        | POST                                   | api/1.1.0/teams/create                                             |                       | App\Http\Controllers\TeamController@create                    | api                                                  |
|        | GET|HEAD                               | api/1.1.0/teams/fetch                                              |                       | App\Http\Controllers\TeamController@getAllTeams               | api                                                  |
|        | GET|HEAD                               | api/1.1.0/tester/test/{name}                                       |                       | App\Http\Controllers\FilesController@testerMethod             | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/activities                                          |                       | App\Http\Controllers\AppController@activities                 | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/activitypositions/{id}                              |                       | App\Http\Controllers\AppController@getPositionsByActivity     | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/avatar/{avatar}                                     |                       | Closure                                                       | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/getUserInformation/{id}                             |                       | App\Http\Controllers\UserController@getMyInformation          | api                                                  |
|        | GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS | api/1.1.0/user/like                                                |                       | App\Http\Controllers\ProfileController@likePost               | api                                                  |
|        | POST                                   | api/1.1.0/user/profile/activities/create                           |                       | App\Http\Controllers\ActivitiesController@createActivity      | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/activities/delete/{uid}/{activity}/{teamID} |                       | App\Http\Controllers\ActivitiesController@deleteActivity      | api                                                  |
|        | POST                                   | api/1.1.0/user/profile/activities/edit                             |                       | App\Http\Controllers\ActivitiesController@editActivity        | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/activities/get/{uid}/{current_team_id}      |                       | App\Http\Controllers\ActivitiesController@getActivities       | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/avatar/get/{uid}                            |                       | App\Http\Controllers\AvatarController@getAvatarImageFile      | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/avatar/getCurrent/{uid}                     |                       | App\Http\Controllers\AvatarController@getCurrent              | api                                                  |
|        | POST                                   | api/1.1.0/user/profile/avatar/imageProcessor                       |                       | App\Http\Controllers\AvatarController@fileProcessor           | api                                                  |
|        | POST                                   | api/1.1.0/user/profile/avatar/uploadAvatar                         |                       | App\Http\Controllers\AvatarController@saveImage               | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/cover/get/{uid}                             |                       | App\Http\Controllers\AvatarController@getCoverImageFile       | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/information/get/{uid}                       |                       | App\Http\Controllers\UserInformationController@getInformation | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/posts/get/{uid}                             |                       | App\Http\Controllers\FeedPostsController@getUserPosts         | api                                                  |
|        | POST                                   | api/1.1.0/user/profile/posts/post                                  |                       | App\Http\Controllers\FeedPostsController@postUserPosts        | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/profile/posts/postImage/{uid}/{post_id}/{imageName} |                       | App\Http\Controllers\FeedPostsController@getPostImage         | api                                                  |
|        | POST                                   | api/1.1.0/user/team/create                                         |                       | App\Http\Controllers\TeamsController@create                   | api                                                  |
|        | POST                                   | api/1.1.0/user/team/edit                                           |                       | App\Http\Controllers\TeamsController@editTeamByOwnership      | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/team/get/{uid}                                      |                       | App\Http\Controllers\TeamsController@getTeam                  | api                                                  |
|        | POST                                   | api/1.1.0/user/team/remove                                         |                       | App\Http\Controllers\TeamsController@removeExistingTeamByUID  | api                                                  |
|        | GET|HEAD                               | api/1.1.0/user/teams/{id}                                          |                       | App\Http\Controllers\AppController@getAllTeams                | api                                                  |
|        | POST                                   | api/1.1.0/user/updateProfile                                       |                       | App\Http\Controllers\UserController@updateProfile             | api                                                  |
|        | GET|HEAD                               | api/get_venues                                                     |                       | App\Http\Controllers\AuthorizedVenuesController@getAllVenues  | api                                                  |
|        | GET|HEAD                               | api/profile/{uid}                                                  |                       | App\Http\Controllers\UserRegistrationController@profile       | api                                                  |
|        | GET|HEAD                               | api/user                                                           |                       | Closure                                                       | api,auth:api                                         |
|        | GET|HEAD                               | developer                                                          |                       | Closure                                                       | web                                                  |
+--------+----------------------------------------+--------------------------------------------------------------------+-----------------------+---------------------------------------------------------------+------------------------------------------------------+
````

##### Security Vulnerabilities

If you discover a security vulnerability within this api, please send an e-mail to Gerald Mathabela via [code45dev@gmail.com](mailto:code45dev@gmail.com). All security vulnerabilities will be promptly addressed.

##### License

The ASN Prototype API is private software licensed under the [MIT license](https://opensource.org/licenses/MIT).
