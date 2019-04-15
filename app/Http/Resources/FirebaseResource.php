<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\UserNotFound;

class FirebaseResource extends JsonResource
{
    public static $serviceAccount;
    private static $firebaseUri;
    private static $response;

    public function __construct($resource)
    {
        parent::__construct($resource);
        self::$serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/asn-sports-firebase-adminsdk-hjyvg-f95e677461.json');
        self::$firebaseUri = 'https://asn-sports.firebaseio.com';
    }

    public static function signup($registration) {

        $firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/asn-sports-firebase-adminsdk-hjyvg-f95e677461.json'))
            ->withDatabaseUri('https://asn-sports.firebaseio.com')->create();

        // Check if user exists;
        try {
            $user = $firebase->getAuth()->getUserByEmail($registration['email']);
        } catch (UserNotFound $e) {
            self::$response = $e->getMessage();
        }

        if (isset($user->uid)) {
            self::$response = [
                'errorCode' => 405,
                'errorMessage' => 'User with email: ' . $registration['email'] . ' already exists.'
            ];
        } else {
            self::$response = $firebase->getAuth()
                ->createUserWithEmailAndPassword($registration['email'], $registration['password']);
        }


        return self::$response;
    }

    public static function realtimeDatabase($data) {
        $firebase = (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/asn-sports-firebase-adminsdk-hjyvg-f95e677461.json'))
            ->withDatabaseUri('https://asn-sports.firebaseio.com')->create();

        $database = $firebase->getDatabase();
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
