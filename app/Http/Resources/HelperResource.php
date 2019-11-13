<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class HelperResource extends JsonResource
{
    public static $_firebase;
    private static $response;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * @return object
     */
    public static function initFirebaseObject() {
        return (new Factory)
            ->withServiceAccount(ServiceAccount::fromJsonFile(__DIR__ . '/../../../' . Config::get('constants.firebase')))
            ->withDatabaseUri(Config::get('constants.firebase_database'))
            ->create();
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function firebaseUserByEmail(string $email) {
        self::$_firebase = self::initFirebaseObject();
        try {
            self::$response = self::$_firebase->getAuth()->getUserByEmail($email);
        } catch(UserNotFound $e) {
            self::$response = $e->getMessage();
        }

        return (isset(self::$response->uid)) ? true : false;
    }
}
