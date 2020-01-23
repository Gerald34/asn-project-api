<?php

namespace App\Http\Resources;

// Helper
// use App\Http\Resources\HelperResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use App\Http\Resources\UserRegistrationResource;
use Illuminate\Support\Facades\Hash;
class FirebaseResource extends JsonResource
{
    public static object $_firebase;
    public static array $response;
    public static string $exception;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * @param $data
     */
    public static function realtimeDatabase(array $data): void {
        self::$_firebase = HelperResource::initFirebaseObject();
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
     * @param array $data
     * @return void
     */
    public static function teams(array $data): void
    {
        self::$_firebase = HelperResource::initFirebaseObject();
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
     * @param string $uid
     * @param string $postID
     * @param array $likeCount
     */
    public static function updatePostLikes(string $uid, string $postID, array $likeCount)
    {
        self::$_firebase = HelperResource::initFirebaseObject();
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
    public static function userPosts(array $data)
    {

        self::$_firebase = HelperResource::initFirebaseObject();
        $database = self::$_firebase->getDatabase();
        // $bucket = $database->uplaod();
        $database->getReference('posts/' . $data['uid'] . '/' . $data['post_id'])->set(
            [
                'uid' => $data['uid'],
                'post_id' => $data['post_id'],
                'message' => $data['message'],
                'image' => $data['image'],
                // 'likes' => FeedPostsResource::_getPostLikes($data['post_id']),
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]);
    }

    /**
     * @param $avatar
     */
    public static function getUserAvatar($avatar)
    {
        self::$_firebase = HelperResource::initFirebaseObject();

        $storage = self::$_firebase->getStorage();
        $bucket = $storage->getBucket();
        // Get the default filesystem
        $filesystem = $storage->getFilesystem();
    }

    /**
     * @param array $registration
     * @return array|string
     */
    public static function signup(array $registration)
    {
        self::$_firebase = HelperResource::initFirebaseObject();
        // Check user exists / Fetch user by email
        if (HelperResource::firebaseUserByEmail($registration['email']) == true) {
            // User with email found
            self::$response = [
                'errorCode' => 405,
                'errorMessage' => 'Account with email: ' . $registration['email'] . ' already exists.'
            ];
        } else {
            self::$response = self::_createUserAccount($registration);
            HelperResource::initFirebaseObject()->getAuth()->sendEmailVerificationLink($registration['email']);
        }

        return self::$response;
    }

    /**
     * @param array $userData
     * @return mixed
     */
    protected static function _createUserAccount(array $userData)
    {
        self::$_firebase = HelperResource::initFirebaseObject();
        // Create new user account
        $newFirebaseUser = [
            'email' => $userData['email'],
            'emailVerified' => false,
            'password' => $userData['password'],
            'displayName' => $userData['first_name'] . ' ' . $userData['last_name'],
            'disabled' => false,
        ];
        $newUser = self::$_firebase->getAuth()->createUser($newFirebaseUser);
        $mysqlDatabaseUsers = [
            'uid' => $newUser->uid,
            'email' => $newUser->email,
            'verification' => $newUser->emailVerified,
            'disabled' => $newUser->disabled,
            'created_at' => $newUser->metadata->createdAt,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name']
        ];

        $create = UserRegistrationResource::register($mysqlDatabaseUsers, Hash::make($userData['password']));
        return $create;
        if (isset($create['successCode'])):
            // verify new user email address
            self::$_firebase->getAuth()->sendEmailVerification($newUser->uid);
            ProfileSetupResource::newProfileSetup($newUser->uid);
            self::$response = self::login($userData['email'], $userData['password']);
        else:
            self::$response = $create;
        endif;

        return self::$response;
    }

    /**
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public static function login(string $email, string $password)
    {
        self::$_firebase = HelperResource::initFirebaseObject();

        try {
            $verify = self::$_firebase->getAuth()->verifyPassword($email, $password);
            self::$response = [
                'successCode' => 201,
                'userInformation' => [
                    'uid' => $verify->uid,
                    'email' => $verify->email,
                    'emailVerified' => $verify->emailVerified,
                    'lastLoginAt' => $verify->metadata->lastLoginAt
                ]
            ];
        } catch (InvalidPassword $e) {
            self::$response = ['response' => $e->getMessage()];
        } catch (EmailNotFound $e) {
            self::$response = ['response' => $e->getMessage()];
        } catch (UserNotFound $e) {
            self::$response = ['response' => $e->getMessage()];
        }

        return self::$response;
    }

}
