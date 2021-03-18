<?php

namespace App\Http\Resources;

// Helper
use Exception;
use Illuminate\Support\Facades\Config;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\EmailNotFound;
use Illuminate\Support\Facades\Hash;

use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;
use App\VerificationModel;
use Kreait\Firebase\Auth\UserRecord;

class FirebaseResource {
    private static object $factory;
    private static array $response;
    private static object $database;
    private static object $auth;
    private static object $fireStore;
    private static object $emailResource;
    private static object $profileSetupResource;
    private static object $generatorResource;
    private static object $verificationModel;
    private static UserRecord $userRecord;

    /**
     * FirebaseResource constructor.
     * @param EmailResource $emailResource
     * @param ProfileSetupResource $profileSetupResource
     * @param GeneratorResource $generatorResource
     * @param VerificationModel $verificationModel
     */
    public function __construct(
        EmailResource $emailResource,
        ProfileSetupResource $profileSetupResource,
        GeneratorResource $generatorResource,
        VerificationModel $verificationModel
    ) {
        self::$factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../../' . Config::get('constants.firebase'))
            ->withDatabaseUri(Config::get('constants.firebase_database'));
        self::$auth = self::$factory->createAuth();
        self::$database = self::$factory->createDatabase();
        self::$fireStore = self::$factory->createStorage();
        self::$emailResource = $emailResource;
        self::$profileSetupResource = $profileSetupResource;
        self::$verificationModel = $verificationModel;
        self::$generatorResource = $generatorResource;
    }

    /**
     * @param array $registration
     * @return array|string
     * @throws AuthException
     * @throws DatabaseException
     * @throws FirebaseException
     */
    public static function signup(array $registration): array {
        $exists = self::firebaseUserByEmail($registration['email']);
        if ($exists) {
            self::$response = [
                'error' => true,
                'success' => false,
                'message' => 'Account with email: ' . $registration['email'] . ' already exists.'
            ];
        } else {
            self::$response = self::_createUserAccount($registration);
        }
        return self::$response;
    }

    /**
     * @param array $userData
     * @return mixed
     * @throws AuthException
     * @throws DatabaseException
     * @throws FirebaseException
     */
    protected static function _createUserAccount(array $userData): array {
        $newFirebaseUser = [
            'email' => $userData['email'],
            'emailVerified' => false,
            'password' => $userData['password'],
            'displayName' => $userData['first_name'] . ' ' . $userData['last_name'],
            'disabled' => false,
        ];

        try {
            self::$userRecord = self::$auth->createUser($newFirebaseUser);
        } catch (AuthException $e) {
            self::$response = ['error' => $e->getMessage()];
        } catch (FirebaseException $e) {
            self::$response = ['error' => $e->getMessage()];
        }

        $mysqlDatabaseUsers = [
            'uid' => self::$userRecord->uid,
            'email' => self::$userRecord->email,
            'verification' => self::$userRecord->emailVerified,
            'disabled' => self::$userRecord->disabled,
            'created_at' => self::$userRecord->metadata->createdAt,
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name']
        ];

        $create = UserRegistrationResource::register(
            $mysqlDatabaseUsers, Hash::make($userData['password'])
        );

        if (isset($create['successCode'])):
            // verify new user email address
            $verificationCode = self::$generatorResource::generateOneTimeVerifier();
            self::$verificationModel::create([
               'uid' =>  self::$userRecord->uid, 'verification_code' => $verificationCode
            ]);
            self::$emailResource->sendVerificationEmail(
                self::$userRecord->email, $userData['first_name'], $verificationCode
            );
            self::$profileSetupResource::newProfileSetup(self::$userRecord->uid);
            self::$response = self::fetchUserInformation($userData['email'], $userData['password']);
        else:
            return $create;
        endif;

        return self::$response;
    }

    private function setUserAvatar($uid) {

    }


    /**
     * @param object $data
     * @param string $path
     * @throws DatabaseException
     */
    public static function realtimeDatabase(object $data, string $path): void {
        $reference = self::$database->getReference($path);
        $reference->set(
            [
                'uid' => $data->uid,
                'emails' => $data->email,
                'status' => 0,
                'personal_information' => [
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                ]
            ]);
    }

    /**
     * @param array $data
     * @return void
     * @throws DatabaseException
     */
    public static function teams(array $data): void {
        self::$database->getReference('teams/' . $data['team_id'])->set(
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
     * @throws DatabaseException
     */
    public static function updatePostLikes(string $uid, string $postID, array $likeCount)
    {
        $reference = self::$database->getReference('posts/' . $postID);
        $reference->set(
            [
                'uid' => $uid,
                'likeCount' => count($likeCount),
            ]);
    }

    /**
     * @param array $data
     * @throws DatabaseException
     */
    public static function userPosts(array $data) {
        // $bucket = $database->uplaod();
        self::$database->getReference('posts/' . $data['uid'] . '/' . $data['post_id'])->set(
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
    public function getUserAvatar($avatar) {
        $storage = self::$fireStore->getStorage();
        $bucket = self::$fireStore->getBucket();
        // Get the default filesystem
        $filesystem = self::$fireStore->getFilesystem();
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws AuthException
     * @throws FirebaseException
     */
    public static function fetchUserInformation(string $email, string $password): array {
        try {
            $verify = self::$auth->getUserByEmail($email);
            self::$response = [
                'successCode' => 201,
                'userInformation' => [
                    'uid' => $verify->uid,
                    'email' => $verify->email,
                    'name' => $verify->displayName,
                    'emailVerified' => $verify->emailVerified,
                    'avatar' => $verify->photoUrl,
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

    /**
     * @param $teamID
     * @return array|mixed
     * @throws Exception
     */
    public static function getFlag($teamID) {
        try {
            $data = self::$database->getReference('teams/' . $teamID)->getSnapshot();
            $object = $data->getValue();
            return self::cloudStorage($object['flag']);
        } catch (DatabaseException $e) {
            return ['exception' => $e->getMessage()];
        }
    }

    /**
     * @param $url
     * @return mixed
     * @throws Exception
     */
    private static function cloudStorage($url) {
        $object = self::$fireStore->bucket('gs://asn-sports.appspot.com')->object($url);
        $file = self::$fireStore->downloadToFile($object);
        var_dump($file);

        $expiresAt = new \DateTime('tomorrow');
        return self::$fireStore->bucket()->object($url)->signedURL($expiresAt);
    }

    /**
     * @param string $email
     * @return bool
     * @throws FirebaseException
     */
    private static function firebaseUserByEmail(string $email) {
        try {
            $user = self::$auth->getUserByEmail($email);
            return (isset($user->uid)) ? true : false;
        } catch(AuthException $e) {
            return false;
        }
    }

    /**
     * @param string $uid
     * @return bool|string
     */
    public static function activate(string $uid) {
        try {
            self::$verificationModel::where('uid', $uid)->delete();
            self::$auth->updateUser($uid, ['emailVerified' => true]);
            return true;
        } catch (AuthException $e) {
            return $e->getMessage();
        } catch (FirebaseException $e) {
            return $e->getMessage();
        }
    }
}
