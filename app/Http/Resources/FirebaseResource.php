<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class FirebaseResource extends JsonResource
{
    public static $serviceAccount;
    public static $_firebase;
    public static $firebaseUri;
    public static $response;

    public static function signup($registration) {
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

    public static function realtimeDatabase($data) {
        self::$_firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))->create();
        $database = self::$_firebase->getDatabase();
        $database->getReference('users/' . $data->uid)->set(
            [
                'uid' => $data->uid,
                'emails' => $data->email,
                'status' => 'online',
                'personal_information' => [
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                ]
            ]);
    }
}
