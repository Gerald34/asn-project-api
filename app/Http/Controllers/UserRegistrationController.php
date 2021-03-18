<?php

namespace App\Http\Controllers;

use App\Http\Resources\FirebaseResource;
use App\Http\Resources\ProfileSetupResource;
use App\Http\Resources\UserRegistrationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Exception\FirebaseException;

/**
 * Class UserRegistrationController
 * @package App\Http\Controllers
 */
class UserRegistrationController extends Controller {
    private array $response;
    private FirebaseResource $firebaseResource;
    private UserRegistrationResource $registrationResource;
    private ProfileSetupResource $profileSetupResource;

    /**
     * UserRegistrationController constructor.
     * @param FirebaseResource $firebaseResource
     * @param UserRegistrationResource $registrationResource
     * @param ProfileSetupResource $profileSetupResource
     */
    public function __construct(
        FirebaseResource $firebaseResource,
        UserRegistrationResource $registrationResource,
        ProfileSetupResource $profileSetupResource
    ) {
        $this->firebaseResource = $firebaseResource;
        $this->registrationResource = $registrationResource;
        $this->profileSetupResource = $profileSetupResource;
    }

    /**
     * @param Request $request
     * @return JsonResponse|object
     * @throws DatabaseException
     * @throws AuthException
     * @throws FirebaseException
     */
    public function register(Request $request): object {
        if ($request->input('password') === $request->input('confirmPassword')) {
            $registration = [
                'email' => trim(strip_tags($request->input('email'))),
                'password' => trim(strip_tags($request->input('password'))),
                'first_name' => trim(strip_tags($request->input('first_name'))),
                'last_name' => trim(strip_tags($request->input('last_name')))
            ];
        } else {
            return response()->json([
                'error' => true,
                'success' => false,
                'message' => 'Passwords do not match...'
            ], 401);
        }

        $this->response = $this->firebaseResource::signup($registration);

        return response()
            ->json($this->response, 200)
            ->header('Content-Type', 'application/json');
    }

    /**
     * @param $uid
     */
    public function profile($uid) {
        $this->profileSetupResource::newProfileSetup($uid);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function activateAccount(Request $request): array {
        $uid = trim(strip_tags($request->input('uid')));
        $onetimePIN = trim(strip_tags($request->input('verification_code')));
        $activation = $this->registrationResource::oneTimePinConfirmation($uid, $onetimePIN);
        return ($activation) ?
            [
                'success' => true,
                'error' => false,
                'message' => 'Email account verified'
            ] :
            [
                'success' => false,
                'error' => true,
                'message' => 'One time PIN seems incorrect.'
            ];
    }

}
