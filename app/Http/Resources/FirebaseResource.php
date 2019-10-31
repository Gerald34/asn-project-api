<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Illuminate\Http\Request;
class FirebaseResource extends JsonResource
{
    public static $serviceAccount;
    public static $_firebase;
    public static $firebaseUri;
    public static $response;

    /**
     * @param $registration
     * @return array|\Kreait\Firebase\Auth\UserRecord|string
     */
    public static function signup($registration)
    {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();
        // Check if user exists;
        try {
            $user = self::$_firebase->getAuth()->getUserByEmail($registration['email']);
        } catch (UserNotFound $e) {
            self::$response = $e->getMessage();
        }

        if (isset($user->uid)) {
            self::$response = [
                'errorCode' => 405,
                'errorMessage' => 'User with email: ' . $registration['email'] . ' already exists.'
            ];
        } else {
            self::$response = self::$_firebase->getAuth()
                ->createUserWithEmailAndPassword($registration['email'], $registration['password']);
        }


        return self::$response;
    }

    /**
     * @param $data
     */
    public static function realtimeDatabase($data)
    {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();
        $database = self::$_firebase->getDatabase();
        $database->getReference('users/' . $data->uid)->set(
            [
                'uid' => $data->uid,
                'emails' => $data->email,
                'status' => 1,
                'personal_information' => [
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                ]
            ]);
    }

    /**
     * @param $data
     */
    public static function teams($data)
    {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();
        $database = self::$_firebase->getDatabase();
        $database->getReference('teams/' . $data['team_id'])->set(
            [
                'uid' => $data['uid'],
                'owner' => $data['uid'],
                'team_id' => $data['team_id'],
                'team_name' => $data['team_name'],
                'team_slug' => $data['team_slug'],
                'sports_category' => $data['sports_category'],
                'active' => $data['active'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]);
    }

    /**
     * @param $email
     * @return \Kreait\Firebase\Auth\UserRecord
     */
    public static function login($email)
    {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();

        try {
            self::$response = self::$_firebase->getAuth()->getUserByEmail($email);
        } catch (UserNotFound $e) {
            self::$response = $e->getMessage();
        }

        return self::$response;
    }

    /**
     * @param $uid
     * @param $postID
     * @param $likeCount
     */
    public static function updatePostLikes($uid, $postID, $likeCount)
    {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();
        $database = self::$_firebase->getDatabase();
        $database->getReference('posts/' . $postID)->set(
            [
                'uid' => $uid,
                'likeCount' => count($likeCount),
            ]);
    }

    /**
     * @param array $data
     */
    public static function userPosts(array $data, $request)
    {

        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();
        $database = self::$_firebase->getDatabase();
        // $bucket = $database->uplaod();
        $database->getReference('posts/' . $data['uid'] . '/' . $data['post_id'])->set(
            [
                'uid' => $data['uid'],
                'post_id' => $data['post_id'],
                'message' => $data['message'],
                'image' => $data['image'],
                // 'likes' => FeedPostsResource::_getPostLikes($data['post_id']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
    }

    /**
     * @param $avatar
     */
    public static function getUserAvatar($avatar)
    {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();


        $storage = self::$_firebase->getStorage();
        $bucket = $storage->getBucket();
        // Get the default filesystem
        $filesystem = $storage->getFilesystem();
    }

}
